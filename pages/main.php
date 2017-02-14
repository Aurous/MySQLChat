<?PHP
if(isset($_POST['logout'])){
	logout();
	
}
if(!isset($_SESSION['user'])){
	$_SESSION['pages'] = 'login';
	require 'pages/login.php';
	exit();
}
?>
<html>
<head>
	<?PHP require 'parts/strap.php'; ?>
</head>
<body>
<?PHP require 'parts/navbar.php';?>
<div class="container-fluid">
	<div class="col-lg-4" style="border-right: solid 1px #cccccc;  height: 89%;">
		<?PHP require 'parts/friends.php'; ?>
	</div>
	<div class="col-lg-8">
		<?PHP require 'parts/chat.php'; ?>
	</div>
</body>
</html>