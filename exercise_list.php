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
		<title>Lista esercizi</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
				<?php
					if($status != AUTH_LOGGED && $user[type]=='2'){
						header("Refresh: 5;URL=panel".$user[type].".php");
						echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                    else{
                    	echo(" 
                        
                        	<div id='function_bar'>");
                            	echo("<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>");
                            	if($user[type]==2){
									if($_GET[mode]=='assigned')echo("<div id='function_button'><a href='show_users.php?mode=trainer' id='function_text'>Scegli istruttore</a></div>");
                                    echo("<div id='red_function_button'><a href='logout.php' id='function_text'>Logout</a></div>");
                                }
                                else if($user[type]==1){
                                	switch($_GET[mode]){
                                    	case'assigned_trainer':
                                        	echo("<div id='function_button'><a href='user_detail.php?id=".$_GET[id]."' id='function_text'>Scheda utente</a></div>
                                        	<div id='function_button'><a href='exercise_list.php?id=".$_GET[id]."&mode=to_assign' id='function_text'>Aggiungi un esercizio</a></div>
											<div id='red_function_button'><a href='execute_function.php?id=".$_GET[id]."&mode=delete_plan' id='function_text'>Elimina tutti gli esercizi</a></div>");
                                        	break;
                                        case'to_assign':
                                        	echo("<div id='function_button'><a href='user_detail.php?id=".$_GET[id]."' id='function_text'>Scheda utente</a></div>");
                                            break;
                                        case'to_modify':
                                        	echo("<div id='function_button'><a href='new_exercise.php' id='function_text'>Aggiungi esercizio</a></div>");
                                            break;
                                    }	
                                }
                               //
                            echo("</div>
                            <div id='contenuto'>");
                            	switch($_GET[mode]){
                                  	case'executed':
									list($list, $max) = executed_ex($user[id]);
                                    if($max!=0)echo("<div id=avviso_elenco><a id=name>Esercizi eseguiti</a></div>");
                                    else echo("<div id=avviso_elenco><a id=name>Ancora nessun esercizio e' stato eseguito</a></div>");
									for($i=0;$i<$max;$i++){
                						echo"<div id=exercise_list>
                                        		<a href='exercise_detail.php?id=".$list[$i][id]."' id=name> ".$list[$i][name]."</a><br/>
                                        		<div id=list_block>
                                                	<a id=bold_text> Ripetizioni</a>
                                                    <a id=basic_text> ".$list[$i][repetition]."</a>
                                                </div>
                                            	<div id=list_block>
                                                	<a id=bold_text> Data</a>
                                                    <a id=basic_text> ".date('d M Y', $list[$i]['date'])."</a>
                                                </div>
                                            </div>
										";
                                    }
              						break; 
                                
                                	case'assigned':
                    				list($list, $max) = assigned_ex($user[id]);
                                    if($max!=0)echo("<div id=avviso_elenco><a id=name>Esercizi assegnati</a></div>");
                                    else echo("<div id=avviso_elenco><a id=name>Nessun esercizio assegnato</a></div>");
									for($i=0;$i<$max;$i++){
                						echo"<div id=exercise_list>
                                        		<a href='exercise_detail.php?id=".$list[$i][id]."' id=name> ".$list[$i][name]."</a><br/>
                                        		<div id=list_block>
                                                	<a id=bold_text> Ripetizioni</a>
                                                    <a id=basic_text> ".$list[$i][repetition]."</a>
                                                </div>
                                            	<div id=list_block>
                                                	<a id=bold_text> Priorita'</a>
                                                    <a id=basic_text> ".$list[$i][priority]."</a>
                                                </div>
                                                <div id=list_button>
                                                	<a href='execute_function.php?id_ex=".$list[$i][id]."&id_us=".$user[id]."&repetition=".$list[$i][repetition]."&mode=execute_ex' id='list_button_text'>Eseguito</a>
                                                </div>
                                            </div>										";
              						}
                                    break;
                                    
                                    case'assigned_trainer':
                    				list($list, $max) = assigned_ex($_GET[id]);
                                    if($max!=0)echo("<div id=avviso_elenco><a id=name>Esercizi assegnati</a></div>");
                                    else echo("<div id=avviso_elenco><a id=name>Nessun esercizio assegnato</a></div>");
									for($i=0;$i<$max;$i++){
                						echo"<div id=exercise_list>
                                        		<a href='exercise_detail.php?id=".$list[$i][id]."' id=name> ".$list[$i][name]."</a><br/>
                                        		<div id=list_block>
                                                	<a id=bold_text> Ripetizioni</a>
                                                    <a id=basic_text> ".$list[$i][repetition]."</a>
                                                </div>
                                            	<div id=list_block>
                                                	<a id=bold_text> Priorita'</a>
                                                    <a id=basic_text> ".$list[$i][priority]."</a>
                                                </div>
                                                <div id=red_list_button>
                                                	<a href='execute_function.php?id_ex=".$list[$i][id]."&id_us=".$_GET[id]."&mode=delete_ex_plan' id='list_button_text'>Elimina</a>
                                                </div>
                                            </div>";
              						}
                                    break;
                                    
                                    case'to_assign':
                    				list($list, $max) = exercise_list();
                                    if($max!=0)echo("<div id=avviso_elenco><a id=name>Esercizi</a></div>");
                                    else echo("<div id=avviso_elenco><a id=name>Nessun esercizio creato</a></div>");
									for($i=0;$i<$max;$i++){
                						echo"<div id=exercise_list>
                                        		<form action='execute_function.php' method='post'>
                                        		<a href='exercise_detail.php?id=".$list[$i][id]."' id=name> ".$list[$i][name]."</a><br/>
                                                
                                        		<div id=list_block>
                                                	<a id=bold_text> Ripetizioni</a>
                                                    <input type='number' name='repetition' min='0' id='list_input'>
                                                </div>
                                            	<div id=list_block>
                                                	<a id=bold_text> Priorita'</a>
                                                    <input type='number' name='priority' min='0' id='list_input'>
                                                </div>
                                                <input type='hidden' name='id' value='".$_GET[id]."'>
                                                <input type='hidden' name='id_ex' value='".$list[$i][id]."'>
                                                <input type='hidden' name='mode' value='add_ex'>
                                                <input type='submit' name='action' value='Invia' id='list_input_button'>
                                                </form>
                                            </div>";
              						}
                                    break;
                                    
                                    case'to_modify':
                    				list($list, $max) = exercise_list();
									for($i=0;$i<$max;$i++){
                						echo"<div id=exercise_list><a href='exercise_detail.php?id=".$list[$i][id]."' id=name> ".$list[$i][name]."</a></div>
										";
              						}
                                    
                                    break;
                                 	default:
                                    	break;
                                 }
                                
                       			 echo("
                          	</div>");
                        
                    }
				?>
        	
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>





