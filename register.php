<?php
include_once("include/config.php");
include_once("include/auth.lib.php");
include_once("include/reg.lib.php");



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
		<title>Registrazione</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
<?php
if(isset($_POST['action']) and $_POST['action'] == 'Invia'){
	$ret = reg_check_data($_POST);
	$status = ($ret === true) ? reg_register($_POST) : REG_ERRORS;
	
	switch($status){
		case REG_ERRORS:
			?>
			<span class="style1">Sono stati rilevati i seguenti errori:</span><br>
			<?php
			foreach($ret as $error)
				printf("<b>%s</b>: %s<br>", $error[0], $error[1]);
			?>
			<br>Premere "indietro" per modificare i dati
			<?php
		break;
		case REG_FAILED:
        	header("Refresh: 5;URL=panel".$user['type'].".php");
			echo("<div id='red_message'>Registrazione Fallita a causa di un errore interno.</div>");
		break;
		case REG_SUCCESS:
            header("Refresh: 5;URL=panel".$user['type'].".php");
        	echo("<div id='message'>Registrazione avvenuta con successo.<br>
			E' stata inviata una email al nuovo utente contente le istruzioni per confermare la registrazione.</div>");
		break;
	}
}
?>
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>