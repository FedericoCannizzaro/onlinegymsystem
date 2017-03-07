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
		<title>Nuovo pagamento</title>
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
                    	<div id='function_button'><a href='payments_list.php?id=".$_GET[id]."&state=-1&deadline=-1' id='function_text'>Lista pagamenti</a></div> 
                      	<div id='function_button'><a href='user_detail.php?id=".$_GET[id]."' id='function_text'>Scheda utente</a></div> 
					</div>"); ?>
					<form action="execute_function.php" method="post">
						<div id=contenuto>
							<table cellspacing="2" id='input_table'>
                            	<tr>
									<td id="input_text">Scadenza</td>
									<td><input type="date" name="deadline" id="input" value=gg/mm/aaaa></td>
								</tr>
								<tr>
									<td id="input_text">Importo:</td>
									<td><input type="number" name="amount" id="input" min=0></td>
								</tr>
								<tr>
									<td id="input_text">Note:</td>
									<td><input type="text" name="note" id="input"></td>
								</tr>
								
								<tr>
    								<?php echo(" <input type='hidden' name='id' value='".$_GET[id]."'>
                                    			<input type='hidden' name='mode' value='reg_p'>");?>
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