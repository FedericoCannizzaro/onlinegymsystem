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
		<title>Nuovo user</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
            <?php
				if(!($status == AUTH_LOGGED && $user[type]=='0')){
					header("Refresh: 5;URL=panel".$user[type].".php");
					echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                else{
                	echo("<div id='function_bar'>                        		
                            	<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>
								<div id='function_button'><a href='show_users.php?mode=activated' id='function_text'>Lista utenti</a></div>
                                <div id='red_function_button'><a href='logout.php' id='function_text'>Logout</a></div>
                            </div>");
                	?>
                	
                	<div id=contenuto>
						<form action="register.php" method="post">
							<table cellspacing="2" id='input_table'>
                            	<tr>
									<td id="input_text">Nome:</td>
									<td><input type="text" name="name" id="input"></td>
								</tr>
								<tr>
									<td id="input_text">Cognome:</td>
									<td><input type="text" name="surname" id="input"></td>
								</tr>
								<tr>
									<td id="input_text">Mail:</td>
									<td><input type="text" name="mail" id="input"></td>
								</tr>
                                <?php if($_GET[mode]==1){ 
								echo("<tr>
									<td id='input_text'>Indirizzo:</td>
									<td><input type='text' name='address' id='input'></td>
								</tr>
								<tr>
									<td id='input_text'>Telefono:</td>
									<td><input type='text' name='telephone' id='input'></td>
								</tr>
								<tr>
									<td id='input_text'>Eta':</td>
									<td><input type='number' name='age' min='14' max='90' id='input'></td>
								</tr>
								<tr>
									<td id='input_text'>Altezza (cm):</td>
									<td><input type='number' name='height' min='100' max='250' id='input'></td>
								</tr>
   								<tr>
									<td id='input_text'>Peso (Kg):</td>
									<td><input type='number' name='weight' min='40' max='150' id='input'></td>
								</tr>
								
    								<input type='hidden' name='type' value='2'>
                                ");}
                                else{ echo("
                                	<tr>
										<td id='input_text'><input type='radio' name='type' value='0' checked>Amministratore<br></td>
										<td id='input_text'><input type='radio' name='type' value='1'>Istruttore<br></td>
									</tr>
                                ");}?>
                                <tr>
									<td colspan="2" align="center"><input type="submit" name="action" value="Invia" id="input_button"></td>
								</tr>
    						</table>
						</form>
                    </div>
					<?php  
            	}?>
    		</div>
            <?php include'bottom.php'; ?>
		</body>
</html>