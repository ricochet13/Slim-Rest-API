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

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->	 
  </head>

  <body>

	<br/><br/><br/>
	 <div class="container" style="width:600px">
	 
		<div class="row">
			<div class="col-md-6"><br/><br/>
			</div>
			<div class="col-md-6">

			<? if(!empty($error)): ?>
			<div class="alert alert-danger alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong><?=$error?></strong>				
			</div>
			<? endif; ?>

				<div class="jumbotron">
					<form class="form-signin" role="form" action="<?=$url?>/login" method="POST">				  
					  <input type="text" name="uname" class="form-control" placeholder="UserName" required autofocus>
					  <input type="password" name="password" class="form-control" placeholder="Password" required>
					  <label class="checkbox">
						 <input type="checkbox" value="remember-me"> Remember me
					  </label><br/>
					  <button class="btn btn-md btn-primary btn-block" type="submit">Sign in</button>
					</form>
				</div>
			</div>
		</div>

    </div> <!-- /container -->
	
	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>	
  </body>
</html>
