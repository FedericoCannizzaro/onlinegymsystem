<?php
include_once("include/config.php");
include_once("include/auth.lib.php");

list($status, $user) = auth_get_status();
header("Refresh: 5;URL=index.php");
?>
<html>
	<head>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title>Logout</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">

<?php
if($status == AUTH_LOGGED){
	if(auth_logout()){
		echo ("<div id='message'>Disconnessione effettuata ... attenda il reindirizzamento</div>");
	}else{
		echo ("<div id='red_message'>Errore durante la disconnessione ... attenda il reindirizzamento</div>");
	}
}else{
	echo("<div id='red_message'>Lei non e' connesso ... attenda il reindirizzamento</div>");
}
?>
</div>
	<?php include'bottom.php'; ?>
	</body>
</html>

