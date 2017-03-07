<?php
include_once("include/config.php");
include_once("include/auth.lib.php");

list($status, $user) = auth_get_status();

if($status == AUTH_NOT_LOGGED){
	$uname = $_POST['uname'];
	$passw = $_POST['passw'];
	if($uname == "" or $passw == ""){
		$status = AUTH_INVALID_PARAMS;
        }
    else{
		list($status, $user) = auth_login($uname, $passw);
		if(!is_null($user)){
			list($s, $uid) = auth_register_session($user);
		}
	}
}
?>
<html>
	<head>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title>Login</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
<?php
switch($status){
	case AUTH_LOGGED:
		header("Refresh: 5;URL=panel".$user[type].".php");
		echo("<div id='message'>Lei e' gia connesso ... attenda il reindirizzamento</div>");
	break;
	case AUTH_INVALID_PARAMS:
		header("Refresh: 5;URL=index.php");
		echo ("<div id='red_message'>Ha inserito dati non corretti ... attenda il reindirizzamento</div>");
	break;
	case AUTH_LOGEDD_IN:
		switch(auth_get_option("TRANSICTION METHOD")){
			case AUTH_USE_LINK:
				header("Refresh: 5;URL=panel".$user['type'].".php?uid=".$uid);
			break;
			case AUTH_USE_COOKIE:
				header("Refresh: 5;URL=panel".$user['type'].".php");
				setcookie('uid', $uid, time()+3600*365);
			break;
			case AUTH_USE_SESSION:
				header("Refresh: 5;URL=panel".$user['type'].".php");
				$_SESSION['uid'] = $uid;
			break;
		}
		echo ("<div id='message'>Salve ".$user['name']." ".$user['surname']." ... attenda il reindirizzamento</div>");
	break;
	case AUTH_FAILED:
		header("Refresh: 5;URL=index.php");
		echo ("<div id='red_message'>Fallimento durante il tentativo di connessione ... attenda il reindirizzamento</div>");
	break;
}
?>
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>

