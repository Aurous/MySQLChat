<?PHP
require 'database.php';
require 'config.php';
if(!isset($_SESSION['pages'])){
	$_SESSION['pages'] = 'login';
}
if($_SESSION['pages'] == 'login'){
		require 'pages/login.php';
	}elseif($_SESSION['pages'] == 'main'){
		require 'pages/main.php';
	}elseif($_SESSION['pages'] == 'register'){
		require 'pages/register.php';
	}
?>