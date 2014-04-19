<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>
    
	 <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	 
	 <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
	 <script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	 <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>	 
	 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular.min.js"></script>  

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	 
  </head>

  <body>  
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
		  <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>		
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
			      <li><a href="users">Users</a></li>
			      <li><a data-toggle="modal" data-target="#modalCP" href="#">Change Password</a></li>			
            <li><a href="logout">Logout</a></li>
          </ul>
		  <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link"><?=$_SESSION['user']?></a></p>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	
	<div class="modal fade" id="modalCP" tabindex="-1" role="dialog" aria-labelledby="modalCPLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalCPLabel">Change Password</h4>
      </div>
      <div class="modal-body">
		<input type="password" placeholder="Old Password" id="opwd" value="">
		<input type="password" placeholder="New Password" id="npwd" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="cps">Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$("#cps").click(function() {
if($('#opwd').val()!="" && $('#npwd').val()!=""){
p1=$("#opwd").val();p2=$("#npwd").val();
 $.post('<?=$_SESSION['url'].'/chgpwd'?>',{op:p1,np:p2},function(result){alert('Password changed!');})
  .fail(function(){alert("Old password not found!!");})
  .always(function(){$("#opwd").val('');$("#npwd").val('');});
}
});
	
</script>
