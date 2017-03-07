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
    	<?php
        	list($p, $p_state)=get_payment_detail($_GET[id], $_GET[deadline]);
        	$us=get_user_detail($_GET[id]);
        ?>
        
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title><?php echo("Pagamento di ".$us[name]." ".$us[surname]."") ?></title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
			
				<?php
					if($status != AUTH_LOGGED || $user[type]=='1'){
						header("Refresh: 5;URL=panel".$user[type].".php");
						echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                    else{
                    	echo(" 
                        
                        	<div id='function_bar'>                        		
                            	<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>
                                <div id='function_button'><a href='payments_list.php?id=".$us[id]."&state=-1&deadline=-1' id='function_text'>Tutti i pagamenti</a></div>");
                                if($user[type]==0){
                                	if($p[payed]==0)echo("<div id='function_button'><a href='execute_function.php?id=".$p[id]."&deadline=".$p[deadline]."&mode=confirm_p' id='function_text'>Conferma pagamento</a></div>");                                   
									if($p_state==0 && $p[payed]==0)echo("<div id='red_function_button'><a href='execute_function.php?id=".$p[id]."&deadline=".$p[deadline]."&mode=send_notice' id='function_text'>Manda un avviso di scadenza</a></div>");  
                                }
							echo("</div>
                            <div id='contenuto'>
                            	<div id=titolo_scheda>");
                                	if($user[type]==0)echo("<a href='user_detail.php?id=".$us[id]."' id='name'>Debitore   ".$us[name]." ".$us[surname]."		</a>");
                                    else echo("<a id='name'>Debitore   ".$us[name]." ".$us[surname]."		</a>");
                                   echo(" <a id = type>".date('d M Y',$p[deadline])."</a>
                                </div>
                                <div id=contenuto_scheda>
                                	<table id=detail_table>
                                    	<tr>
                                        	<th align=left>Stato</th>");
                                    	if($p[payed]==1)echo("<td>PAGATO</td>");
                                        else echo("<td>NON PAGATO</td>");
                                    	echo("
                                        </tr>
                                        <tr>
                                        	<th></th>
                                            ");
                                        	if($p_state==0)echo("<td>SCADUTO</td>");
                                        echo("
                                        <tr>
                                        	<th align=left>Note</th>
                                            <td>".$p[note]."</td>
                                        </tr>
									</table>
                                </div>
                                ");                              
                          	echo("</div>");
                        
                    }
				?>
        	
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>




