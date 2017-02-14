<?PHP
if(isset($_POST['user']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['retype_password']) and isset($_POST['submit'])){
	if(!empty($_POST['user']) and !empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['retype_password'])){
		if($_POST['password'] == $_POST['retype_password']){
			$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
			$register = register($con, DB_LOGINTABLE, $_POST['user'], $_POST['email'], md5($_POST['password']));
			$registerchat = registerchat($con, DB_FRIENDTABLE, $_POST['user']); 
			if($register == '1' and $registerchat == '1'){
				$_SESSION['pages'] = 'login';
				require 'pages/login.php';
				exit();
			}else{
				if(isset($register)){
					echo $register;
				}
				if(isset($registerchat)){
					echo $registerchat;
				}
			}
		}else{
			$message = "Error";
		}
	}
}
if(isset($_POST['login'])){
	$_SESSION['pages'] = 'login';
	require 'pages/login.php';
	exit();
}
?>
<html>
<head>
<?PHP require 'parts/strap.php';?>
</head>
<body>
<?PHP require 'parts/navbar.php';?>
<div class="container-fluid">
<h1>Register</h1>
	<p><?PHP if(isset($register)){echo $register;} ?></p>
	<form action="" method="POST" >
	<div class=" col-lg-4 col-md-6 col-sm-9 col-xs-12">
		<div class="form-group">
			<label>Username:</label> 
			<input type="text" class="form-control " name="user" id="textbox"/>
		</div>
		<div class="form-group">
			<label>Email:</label>  	
			<input type="text" class="form-control" name="email" id="textbox"/>
		</div>
		<div class="form-group">
			<label>Password:</label> 
			<input type="password" class="form-control" name="password" id="textbox"/>
		</div>
		<div class="form-group">
			<label>Retype Password:</label> 
			<input type="password" class="form-control" name="retype_password" id="textbox"/>
		</div>
		<div class="form-group">
			<input type="submit" name="submit" value="Submit" class="btn btn-default"/>
		</div>
	</div>
	</form>
</div>
</body>
</html>