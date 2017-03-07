<?php
include_once("include/config.php");
include_once("include/auth.lib.php");
include_once("include/function.php");

list($status, $user) = auth_get_status();

if($status == AUTH_LOGGED & auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else	$link = '';

?>



<html>
	<head>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title>Modifica dati</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
            <?php
				if(!($status == AUTH_LOGGED)){
					header("Refresh: 5;URL=panel".$user[type].".php");
					echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                else{
                	echo("                        	
                    	<div id='function_bar'>
                       		<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>");                              
							if($_GET[mode]!='data' && $user[type]==2)echo("<div id='function_button'><a href='alter_user.php?mode=data' id='function_text'>Modifica dati</a></div>");                                
                       		if($_GET[mode]!='password')echo("<div id='function_button'><a href='alter_user.php?mode=password' id='function_text'>Modifica password</a></div>"); 
                       		if($_GET[mode]!='weight' && $user[type]==2)echo("<div id='function_button'><a href='alter_user.php?mode=weight' id='function_text'>Aggiorna peso</a></div>");                                
							echo("<div id='red_function_button'><a href='logout.php' id='function_text'>Logout</a></div>
						</div>"
                        );
                	$us=get_user_detail($user[id]);?>
					<div id=contenuto>
                    	<form action="execute_function.php" method="post">
							<table cellspacing="2" id='input_table'>
                            	<?php if($_GET[mode]=='password'){
                            	echo("<tr>
									<td id='input_text'>Password:</td>
									<td><input type='password' name='password' id='input'></td>
								</tr>");
                                }
                                else if($_GET[mode]=='data'){ 
								echo("<tr>
									<td id='input_text'>Indirizzo:</td>
									<td><input type='text' name='address' id='input' value='".$us[address]."'></td>
								</tr>
								<tr>
									<td id='input_text'>Telefono:</td>
									<td><input type='text' name='telephone' id='input' value='".$us[telephone]."'></td>
								</tr>
								<tr>
									<td id='input_text'>Eta':</td>
									<td><input type='number' name='age' min='14' max='90' id='input' value='".$us[age]."'></td>
								</tr>
								<tr>
									<td id='input_text'>Altezza (cm):</td>
									<td><input type='number' name='height' min='100' max='250' id='input' value='".$us[height]."'></td>
								</tr>							
                                ");
                                }
                                else if($_GET[mode]=='weight'){
                                	echo("<tr>
									<td id='input_text'>Peso:</td>
									<td><input type='text' name='weight' id='input' value='".$us[a_weight]."'></td>
									</tr>");
                                }
                                
                                if(isset($_GET[mode])){
                                echo("
                                <input type='hidden' name='mode' value='alter_".$_GET[mode]."'>
								<input type='hidden' name='id' value='".$user[id]."'>                                <tr>
									<td colspan='2' align='center'><input type='submit' name='action' value='Invia' id='input_button'></td>
								</tr>"
                                ); 
                                }?>
    						</table>
                        </form>
					</div>
					
					<?php 
            	}?>
    		</div>
            <?php  include'bottom.php';?>
		</body>
</html>