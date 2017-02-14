<?PHP
/**/if(isset($_POST)){
	print_r($_POST);
	print_r($_SESSION);
}/**/
if(isset($_POST['accept'])){
	$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	$accept = acceptfriend($con, $_SESSION['user'], DB_FRIENDTABLE,$_POST['accept']);
	unset($_POST['accept']);
	echo $accept;
}
if(isset($_POST['decline'])){
	$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	$decline = declinefriend($con, $_SESSION['user'], DB_FRIENDTABLE,$_POST['decline']);
	unset($_POST['decline']);
	echo $decline;
}
if(isset($_POST['addfriend'])){
	$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	$addfriend = addfriend($con,$_SESSION['user'],DB_FRIENDTABLE,$_POST['addfriend']);
	echo $addfriend;
}
$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
$friends = friends($con, $_SESSION['user'],DB_FRIENDTABLE);
$explode = explode("/", $friends);
?>

<form action="" method="POST">
<h3 style="float:left;">Friends</h3>
<div class="row">
 <div class="col-lg-6"style="float:right; padding: 11px;">
    <div class="input-group">
	<input  type="text" name="addfriend" class="form-control"/>
	<span class="input-group-btn">
		<input type="submit" name="submit" class="btn btn-default" value="Add Friend"/>
	</span>
	  </div>
  </div>
</div>
</form>
<hr>
<?PHP
if($explode[0] == " "){
	echo "No Friends Added At This Time<hr>";
}else{
	foreach(explode(',',$explode[0]) as $key){
		if(strpos($key, '=') !== false){
			$key = str_replace("=","&nbsp;:&nbsp;",$key);
			echo $key . '<hr>';
		}else{
			?>
			<form action="" method="POST">
			<?PHP echo $key; ?><button type="submit" class="btn btn-default" style="float: right; padding-bottom: 2px; padding-top:2px;" name="chat" value="<?PHP echo $key; ?>" id="textbox">Chat</button><hr>
			</form>
		<?PHP
		}
	}
}
?>
<h3>Requests</h3>

<hr>
<?PHP
if($explode[1] == " "){
	echo "No Requests At This Time<hr>";
}else{
	foreach(explode(',',$explode[1]) as $keys){
		$key = str_replace(" ","",$keys);
	?>
	<div><?PHP echo $key; ?>
	<a style="float: right" href="#" onclick="document.getElementById('<?PHP echo $key; ?>decline').submit();">Decline</a>
	<a style="float: right; padding-right:10px;" href="#" onclick="document.getElementById('<?PHP echo $key; ?>accept').submit();">Accept</a>
	<form action="" method="POST" id="<?PHP echo $key; ?>accept">
		<input hidden type="text" value="<?PHP echo $key; ?>" name="accept"/>
	</form>
	<form action="" method="POST" id="<?PHP echo $key; ?>decline">
		<input hidden type="text" value="<?PHP echo $key; ?>" name="decline"/>
	</form>
	</div>
	<hr>
	<?PHP
	}
}
?>