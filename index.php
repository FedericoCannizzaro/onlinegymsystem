<?php
include_once("include/config.php");
include_once("include/auth.lib.php");

list($status, $user) = auth_get_status();

if($status == AUTH_LOGGED & auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else	$link = '';
?>
<html>
	<head>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title>Home Page</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
		<?php
		switch($status){
			case AUTH_LOGGED:
            header("Refresh: 5;URL=panel".$user[type].".php");
			?>
		<div id='message'><b>Sei loggato con il nome di <?=$user["name"];?> <a href="logout.php<?=$link?>">Logout</a></b></div>
			<?php
			break;
			case AUTH_NOT_LOGGED:
			?>
            <div  align='center'>
			<form action="login.php<?=$link?>" method="post">
				<table cellspacing="2" id='input_table'>
					<tr>
						<td id="input_text">Nome Utente:</td>
						<td><input type="text" name="uname" id="input"></td>
					</tr>
					<tr>
						<td id="input_text">Password:</td>
						<td><input type="password" name="passw" id="input"></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="action" value="login" id="input_button"></td>
					</tr>
				</table>
			</form>
            </div>
			<?php
			break;
		}
		?>
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>
