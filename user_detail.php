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
    	<?php $us=get_user_detail($_GET[id]); ?>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title><?php echo("".$us[name]." ".$us[surname]."") ?></title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
			
				<?php
					if(!($status == AUTH_LOGGED)){
						header("Refresh: 5;URL=panel".$user[type].".php");
						echo '<div align="center">Non hai i diritti per visualizzare la pagina</div>';
					}
                    else{
                    	echo(" 
                        
                        	<div id='function_bar'>
                           		<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>");
                            if($user[type]==0){
                            	
                                if($us[activated]==0){
                                	echo("<div id='function_button'><a href='show_users.php?mode=deactivated' id='function_text'>Lista account non attivati</a></div>");
                                	echo("<div id='function_button'><a href='execute_function.php?id=".$us['id']."&mode=activate_u' id='function_text'>Attiva utente</a></div>");
                                }
                               	
                                else{
                                	echo("<div id='function_button'><a href='show_users.php?mode=activated' id='function_text'>Lista utenti</a></div>");
                                    if($us[type]==2){
                                    	echo("<div id='function_button'><a href='payments_list.php?id=".$us[id]."&state=-1&deadline=-1' id='function_text'>Lista pagamenti</a></div>                                   
                                    	<div id='function_button'><a href='new_payment.php?id=".$us[id]."' id='function_text'>Notifica pagamento</a></div>");
                                    }
                                }
                            	if($us[type]!=0)echo("<div id='red_function_button'><a href='execute_function.php?id=".$us['id']."&mode=delete_u' id='function_text'>Elimina utente</a></div>");
                            }
                            else if($user[type]==2)echo("<div id='function_button'><a href='execute_function.php?id_trainer=".$us['id']."&id_user=".$user['id']."&mode=set_trainer' id='function_text'>Scegli come istruttore</a></div>");
                            else if($user[type]==1)echo("<div id='function_button'><a href='exercise_list.php?id=".$us['id']."&mode=assigned_trainer' id='function_text'>Visualizza piano d'allenamento</a></div>");
							echo("</div>
                            <div id='contenuto'>
                            	<div id=titolo_scheda>
                                	<a id='name'>".$us[name]." ".$us[surname]."		</a>");
                                    if($us[type]==0)echo("<a id = type>AMMINISTRATORE</a>");
                                    else if($us[type]==1)echo("<a id = type>ISTRUTTORE</a>");
                                    else echo("<a id = type>CLIENTE</a>");
                                echo("</div>
                                <div id=contenuto_scheda>
                                	<table id=detail_table>
                                    	<tr>
                                        	<th align=left>Nome</th>
                                            <td>".$us[name]."
                                        </tr>
                                        <tr>
                                        	<th align=left>Cognome</th>
                                            <td>".$us[surname]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>E-mail</th>
                                            <td>".$us[mail]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Data di registrazione</th>
                                            <td>".date('d M Y',$us[reg_date])."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Attivazione</th>");
                                            if($us[activated]==1)echo("<td>Effettuata</td>");
                                            else echo("<td>Non effettuata</td>");
                                        echo("</tr>");
                                        
                                        if($us[type]==2){
                                        	echo("
                                        <tr>
                                        	<th align=left>Indirizzo</th>
                                            <td>".$us[address]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Telefono</th>
                                            <td>".$us[telephone]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Eta'</th>
                                            <td>".$us[age]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Altezza</th>
                                            <td>".$us[height]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Peso al momento dell'iscrizione</th>
                                            <td>".$us[o_weight]."</td>
                                        </tr>
                                        <tr>
                                        	<th align=left>Peso attuale</th>
                                            <td>".$us[a_weight]."</td>
                                        </tr>
                                            ");
                                            
                                        }
                                    echo("</table>
                                </div>
                                ");                              
                          	echo("</div>");
                        
                    }
				?>
        	
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>



