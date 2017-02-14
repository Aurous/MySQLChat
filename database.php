<?PHP
session_start();
function connect($server, $user, $password, $database){
	$db = mysqli_connect($server,$user,$password,$database);
	if(!$db){
		return "Error:". mysqli_error($db);
	}else{
		return $db;
	}
}
function login($con, $table, $user, $pass)
{
	$username = mysqli_real_escape_string($con,$user);
	$password = mysqli_real_escape_string($con,$pass); 
	$sql = "SELECT * FROM `$table` WHERE `user` = '$username' and `pass` = '$password'";
    $result = mysqli_query($con,$sql);
	if(!$result){
		return "Error:". mysqli_error($con);
	}else{
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$count = mysqli_num_rows($result);
		if( $count == 1 && $row['pass']==$pass) {
			$_SESSION['user'] = $row['user'];
			$_SESSION['userid'] = $row['userid'];
			return true;
		} else {
			return "Incorrect Credentials, Try again..." . $username;
		}
	}
}
function logout()
{
	$_SESSION = [];
	session_destroy();	
}
function register($con, $table, $user, $email, $pass)
{
	$username = htmlspecialchars($pass);
	$username = mysqli_real_escape_string($con,$user);
	$email = mysqli_real_escape_string($con,$email);
	$pass = mysqli_real_escape_string($con,$pass);
	$sql = "SELECT * FROM `$table` WHERE `email` = '$email'";
	$result = mysqli_query($con, $sql);
	if(!$result){
		return "Error:". mysqli_error($con);
	}else{
		$count = mysqli_num_rows($result);
		if($count != 0){
			return False;
		}else{
			$sql = "SELECT * FROM `$table` WHERE `user` = '$user'";
			$result = mysqli_query($con, $sql);
			if(!$result){
				return "Error:". mysqli_error($con);
			}else{
				$count = mysqli_num_rows($result);
				if($count != 0){
					return False;
				}else{
					$sql = "INSERT INTO `$table` (`user`, `pass`, `email`, `userid`) VALUES ('$user', '$pass', '$email', NULL)";
					$result = mysqli_query($con, $sql);
					if(!$result){
						return "Error:". mysqli_error($con);
					}else{
						return true;
					}				
				}
			}
		}
	}
}
function registerchat($con, $table, $user){
	$username = mysqli_real_escape_string($con,$user);
	$sql = "SELECT * FROM `$table` WHERE `user` = '$username'";
	$result = mysqli_query($con, $sql);
	if(!$result){
		return "Error:". mysqli_error($con);
	}else{
		if(mysqli_num_rows($result) != 0){
			return false;
		}else{
			$sql = "INSERT INTO `$table` (`user`, `friends`, `requested`) VALUES ('$username', '', '')";
			$result = mysqli_query($con,$sql);
			if(!$result){
				return "Error:". mysqli_error($con);
			}else{
				return True;
			}
		}
	}
}
function friends($con, $user, $table)
{
	$username = mysqli_real_escape_string($con,$user);
	$sql = "SELECT * FROM `$table` WHERE `user` = '$username'";
	$result = mysqli_query($con,$sql);
	if(!$result){
		return "Error:". mysqli_error($con);
	}else{
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$friends = $row['friends'];
		$requested = $row['requested'];
		return $friends . ' / ' . $requested;
	}
}
function addfriend($con, $user, $table, $friend){
	$user = mysqli_real_escape_string($con,$user);
	$friend = mysqli_real_escape_string($con,$friend);
	if($user != $friend){
		$sql = "SELECT * FROM `$table` WHERE `user` = '$friend'";
		$result = mysqli_query($con,$sql);
		$sql2 = "SELECT * FROM `$table` WHERE `user` = '$user'";
		$result2 = mysqli_query($con,$sql);
		if(!$result and !$result2){
			return "Error:". mysqli_error($con);
		}else{
			if(mysqli_num_rows($result) == 1 and mysqli_num_rows($result2) == 1){
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC); //get info for row
				$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC); //user data
				if(empty($row['requested'])){
					$friends = array();
					$friendslist = explode(",",$row['friends']);
				}else{
					$friends = explode(",",$row['requested']);
					$friendslist = explode(",",$row['friends']);
				}
				if(empty($row2['friends'])){
					$userlist = array();
				}else{
					$userlist = explode(",",$row2['friends']);
				};
				if(in_array($user,$friends) or in_array($user,$friendslist)){
					return "Friend Request Already Sent";
				}else{
					$userlist[] = $friend . "=Request Sent";
					$friends[] = $user;
					$friends = implode(",",$friends);
					$sql = "UPDATE `$table` SET `requested`='$friends' WHERE `user` = '$friend'";
					$result = mysqli_query($con,$sql);
					$userlist = implode(",",$userlist);
					$sql2 = "UPDATE `$table` SET `friends`='$userlist' WHERE `user` = '$user'";
					$result2 = mysqli_query($con,$sql2);
					if(!$result and !$result2){
						return "Error:". mysqli_error($con);
					}else{
						return "Friend Request Sent Or Accepted";
					}
				}
			}else{
				return "Friend Not Found";
			}
		}
	}else{
		echo "You Cannot Add Yourself";
	}
}
function acceptfriend($con, $user, $table, $friend){
	$user = mysqli_real_escape_string($con,$user); //escape
	$friend = mysqli_real_escape_string($con,$friend); //escape
	$sql = "SELECT * FROM `$table` WHERE `user` = '$user'"; //user sql
	$sql2 = "SELECT * FROM `$table` WHERE `user` = '$friend'"; //friend sql
	$result = mysqli_query($con,$sql);
	$result2 = mysqli_query($con,$sql2);
	if(!$result or !$result2){
		return "Error:". mysqli_error($con);
	}else{
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$row2=mysqli_fetch_array($result2, MYSQLI_ASSOC);
		if(empty($row['requested'])){ //$friend
			$users = array(); 
			$userslist = array();
		}else{
			$users = explode(",",$row['requested']);
			$userslist = explode(",",$row['friends']);
		}
		$friendslist = $row2['friends'];
		if(in_array($friend,$users) and !in_array($friend,$userslist)){
			$list = implode(",",array_diff($users, array($friend)));
			$userkey = $user . "=Request Sent";
			$friendslist = str_replace($userkey,$user,$friendslist);
			$list = str_replace(" ","",$list);
			if(count($userslist) != 0){
				$userslist = $friend;
			}else{
				$userslist[] = $friend;
				$userslist = implode(",",$userslist);
				$userslist = str_replace(" ","",$userslist);
			}
			$sql = "UPDATE `$table` SET `friends`='$userslist',`requested`='$list' WHERE `user` = '$user'";
			$sql2 = "UPDATE `$table` SET `friends`='$friendslist' WHERE `user` = '$friend'";
			$result = mysqli_query($con,$sql);
			$result2 = mysqli_query($con,$sql2);
			if(!$result or !$result2){
				return "Error:". mysqli_error($con);
			}else{
				return "Friend Accepted";
			}
		}else{
			return "Friend Already Accepted";
		}
	}
}
function declinefriend($con, $user, $table, $friend){
	$user = mysqli_real_escape_string($con,$user);
	$friend = mysqli_real_escape_string($con,$friend);
	$sql = "SELECT * FROM `$table` WHERE `user` = '$user'";
	$result = mysqli_query($con,$sql);
	$sql2 = "SELECT * FROM `$table` WHERE `user` = '$friend'";
	$result2 = mysqli_query($con,$sql2);
	if(!$result or !$result2){
		return "Error:". mysqli_error($con);
	}else{
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$friends = explode(",",$row['requested']);
		$row2=mysqli_fetch_array($result2, MYSQLI_ASSOC);
		$friendslist = explode(",",$row2['friends']);
		$userrequest = $user . "=Request Sent";
		print_r($friendslist);
		if(in_array($friend,$friends) and in_array($userrequest,$friendslist)){
			$list = implode(",",array_diff($friends, array($friend)));
			$friendlist = implode(",",array_diff($friendslist,array($userrequest)));
			$sql = "UPDATE `$table` SET `requested`='$list' WHERE `user` = '$user'";
			$sql2 = "UPDATE `$table` SET `friends`='$friendlist' WHERE `user` = '$friend'";
			$result = mysqli_query($con,$sql);
			$result2 = mysqli_query($con,$sql2);
			if(!$result or !$result2){
				return "Error:". mysqli_error($con);
			}else{
				return "Request Declined";
			}
		}else{
			return "Request Already Declined";
		}
	}
}
function chat($con, $user, $table, $friend){
	$user = mysqli_real_escape_string($con,$user);
	$friend = mysqli_real_escape_string($con,$friend);
	$messages = array();
	$sql = "SELECT  * FROM `$table` WHERE `user` = '$user' and `receiver` = '$friend'";
	$sql2 = "SELECT  * FROM `$table` WHERE `user` = '$friend' and `receiver` = '$user'";
	$result = mysqli_query($con,$sql);
	$result2 = mysqli_query($con,$sql2);
	if(!$result or !$result2){
		return "Error:". mysqli_error($con);
	}else{
		if(mysqli_num_rows($result) != 0 OR mysqli_num_rows($result2) != 0){
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
				$messages[] = array('sender'=>$row['user'],'receiver'=>$row['receiver'],'message'=>$row['message'],'time'=>$row['time']);
			}
			while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
				$messages[] = array('sender'=>$row2['user'],'receiver'=>$row2['receiver'],'message'=>$row2['message'],'time'=>$row2['time']);
			}
			foreach ($messages as $key => $node) {
				$timestamps[$key]    = $node['time'];
			}
			array_multisort($timestamps, SORT_ASC, $messages);
			return $messages;
		}else{
			return "No Messages Sent";
		}
	}
}
function sendmessage($con,$user,$table,$friend,$message){
	$user = mysqli_real_escape_string($con,$user);
	$friend = mysqli_real_escape_string($con,$friend);
	$message= mysqli_real_escape_string($con,$message);
	if(!empty($message)){
		$sql = "INSERT INTO `$table` (`receiver`, `time`, `message`, `user`) VALUES ('$friend', CURRENT_TIMESTAMP, '$message', '$user')";
		$result = mysqli_query($con,$sql);
		if(!$result){
			return "Error:".mysqli_error($con);
		}else{
			return "Message Sent";
		}
	}
}
?>
