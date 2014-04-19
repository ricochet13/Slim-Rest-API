<?php

require_once '../vendor/slim/slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('url'=>'/index.php'));
$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'myappsecret')));
$db=new PDO('mysql:host=localhost;dbname=test','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_LOCAL_INFILE=>true));

$auth = function ($app) {
    return function () use ($app){
        if (!isset($_SESSION['user'])){
				$_SESSION['urlRedirect']=$app->config('url').$app->request()->getPathInfo();				
            $app->flash('error', 'Login required');
            $app->redirect($app->config('url').'/login');
        }
    };
};

$app->hook('slim.before.dispatch', function() use ($app) {$user = null;
   if (isset($_SESSION['user']))$user = $_SESSION['user'];$app->view()->setData('user', $user);
});

$app->get("/", $auth($app), function () use ($app) {
    $app->render('index.php');
});

$app->get("/logout", function () use ($app,$db) {
   unset($_SESSION['user']);$app->view()->setData('user', null);$db=null;
   $app->render('logout.php');
});

$app->get("/login", function () use ($app) {
   $flash = $app->view()->getData('flash');

   $error = '';$urlRedirect = '/';   
   
   if (isset($flash['error']))$error = $flash['error'];   

   if ($app->request()->get('r') && $app->request()->get('r') != '/logout' && $app->request()->get('r') != '/login') {
      $_SESSION['urlRedirect'] = $app->request()->get('r');
   }

   if(isset($_SESSION['urlRedirect']))$urlRedirect = $_SESSION['urlRedirect'];

   $app->render('login.php', array('url'=>$app->config('url'),'error'=>$error, 'urlRedirect'=>$urlRedirect));
});

$app->post("/login", function () use ($app,$db) {
    $uname = $app->request()->post('uname');
    $pwd = $app->request()->post('password');

    $errors = array();
	
	$res = $db->prepare('select count(id) from users where uname=? and password=?'); 	
	$res->execute(array($uname,hash('sha256',$pwd))); 
	$rowcount = $res->fetchColumn(); 

    if ($rowcount==0) {
        $errors="Invalid UserName/Password!!";
		$app->flash('error', $errors);
		$app->redirect($app->config('url').'/login');		
    }

    $_SESSION['user'] = $uname;$_SESSION['url'] = $app->config('url');	

    if (isset($_SESSION['urlRedirect'])) {
       $tmp = $_SESSION['urlRedirect'];unset($_SESSION['urlRedirect']);$app->redirect($tmp);
    }
	
    $app->redirect($app->config('url').'/users');
});

$app->get("/users", $auth($app), function () use ($app) {
   $app->render('users.php',array('url'=>$app->config('url')));
});

$app->get("/gusers/:p/:r",$auth($app), function ($p,$r) use ($app,$db) {
	header('Content-type: application/json');
	
	if($r==0)$st=$db->query("SELECT id,uname,fullname FROM users order by fullname");
	else{
	$count=$db->query("SELECT count(id) FROM users")->fetchColumn();
	$page=(int)$p<=0?1:(int)$p;
	$recordsPerPage = (int)$r;
	$start = ($page-1) * $recordsPerPage;
	$lastpage = ceil($count/$recordsPerPage);
	$st=$db->prepare("SELECT id,uname,fullname,$lastpage as lp FROM users order by fullname limit ?,?");
   $st->execute(array($start,$recordsPerPage));
	}
	
	$res=$st->fetchAll(PDO::FETCH_ASSOC);
   echo json_encode($res);
});

$app->delete("/deluser/:id", $auth($app), function ($id) use ($app,$db) {
   $st=$db->prepare("delete FROM users where id = ?");
   $st->execute(array(intval($id)));
});

$app->post("/adduser", $auth($app), function () use ($app,$db) {
   $jv=json_decode(file_get_contents("php://input")); 
   
   $st=$db->prepare("insert into users(fullname,uname,password) values(?,?,?)");
   $st->execute(array($jv->f,$jv->u,hash('sha256',$jv->p)));
   echo json_encode(array("id"=>$db->lastInsertId(),"uname"=>stripslashes($jv->u),"fullname"=>stripslashes($jv->f)));      
});

$app->post("/edituser", $auth($app), function () use ($app,$db) {
	$jv=json_decode(file_get_contents("php://input"));	
	if(empty($jv->p) || $jv->p==null || $jv->p==""){
   $st=$db->prepare("update users set fullname=? where id=?");
   $st->execute(array($jv->f,intval($jv->uid)));
   }else{
   $st=$db->prepare("update users set fullname=?,password=? where id=?");
   $st->execute(array($jv->f,hash('sha256',$jv->p),intval($jv->uid)));}
});

$app->post("/chgpwd", $auth($app), function () use ($app,$db) {
   $op=$_POST['op'];$np=$_POST['np'];$u=$_SESSION['user'];
   $st=$db->prepare("select count(id) from users where uname=? and password=?");
   $st->execute(array($u,hash('sha256',$op)));     
   
   $rc = $st->fetchColumn(); 
   if($rc==0)throw new Exception("hi");
   else{
   $st=$db->prepare("update users set password=? where uname=?");
   $st->execute(array(hash('sha256',$np),$u));}
   });
   
$app->run();
