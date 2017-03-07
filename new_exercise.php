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
		<title>Nuovo esercizio</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
            <?php
				if(!($status == AUTH_LOGGED && $user[type]=='1')){
					header("Refresh: 5;URL=panel".$user[type].".php");
					echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                else{?>
                	<div id='function_bar'>
                       	<?php	echo("<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>
                        <div id='function_button'><a href='exercise_list.php?mode=to_modify' id='function_text'>Lista esercizi</a></div>

"); ?>                              
					</div>
					<form action="execute_function.php" method="post">
						<div id=contenuto>
							<table cellspacing="2" id='input_table'>
                            	<tr>
									<td id="input_text">Nome:</td>
									<td><textarea name="name" id="input"> </textarea></td>
								</tr>
								<tr>
									<td id="input_text">Descrizione:</td>
									<td><textarea cols="40" rows="5" maxlength="200" name="description" id="input_large"></textarea></td>
								</tr>
								<tr>
                                    <input type='hidden' name='mode' value='new_ex'>
									<td colspan="2" align="center"><input type="submit" name="action" value="Invia" id="input_button"></td>
								</tr>
    						</table>
						</div>
					</form>
					<?php 
            	}?>
    		</div>
            <?php include'bottom.php';  ?>
		</body>
</html>