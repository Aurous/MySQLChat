<?PHP
if(isset($_POST['user']) AND isset($_POST['pass']) AND isset($_POST['submit'])){
	if(!empty($_POST['user']) AND !empty($_POST['pass'])){
		$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		$login = login($con, DB_LOGINTABLE, $_POST['user'], md5($_POST['pass']));
		if($login == '1'){
			$_SESSION['pages'] = 'main';
			require 'pages/main.php';
			exit();
		}
	}
}
if(isset($_POST['register'])){
	$_SESSION['pages'] = 'register';
	require 'pages/register.php';
	exit();
}
?>

<html>
<head>
<?PHP require 'parts/strap.php'; ?>
</head>
<body>
<?PHP require 'parts/navbar.php'; ?>
<div class="container-fluid">
<h1>Login</h1>
<p><?PHP if(isset($login)){echo $login;}?></p>
<form method="POST" action="">
	<div class=" col-lg-4 col-md-6 col-sm-9 col-xs-12">
		<div class="form-group">
			<label>Username:</label> 
			<input type="text" class="form-control" name="user" id="textbox"/><br />
		</div>
		<div>
			<label>Password:</label>
			<input type="password" class="form-control" name="pass" id="textbox"/><br />
		</div>
		<div class="form-group">
			<input type="submit" name="submit" value="Submit" class="btn btn-default"/>
		</div>
	</div>
</form>
</div>
</body>
</html>