<?PHP
if(isset($_POST['sendmessage'])){
	$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	$sendmessage = sendmessage($con,$_SESSION['user'],DB_CHATTABLE,$_SESSION['chat'],$_POST['sendmessage']);
}
if(isset($_POST['chat'])){
	$_SESSION['chat'] = $_POST['chat'];
}
if(isset($_SESSION['chat'])){
	$con =  connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	$chat = chat($con, $_SESSION['user'], DB_CHATTABLE, $_SESSION['chat']);
	if(is_array($chat)){
	foreach($chat as $messages){
		?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<?PHP echo "<b>" . $messages['sender'] . "</b>&nbsp;&nbsp;&nbsp;" . $messages['time']; ?>
			</div>
			<div class="panel-body">
				<?PHP echo $messages['message']; ?>
			</div>
		</div>
		<?PHP
	}
	}else{
		echo $chat;
	}
	?>
	<form action="" method="POST">
	<div class="row">
		<div class="col-lg-10"style="float:right; padding: 11px;">
			<div class="input-group">
				<textarea name="sendmessage" class="form-control"></textarea>
				<span class="input-group-btn">
					<input type="submit" name="submit" class="btn btn-default" value="Send Message"/>
				</span>
			</div>
		</div>
	</div>
	</form>
	<?PHP
}else{
	echo 'Please Choose a Chat';
}