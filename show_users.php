<?php
include_once("include/config.php");
include_once("include/auth.lib.php");
include_once("include/function.php");



list($status, $user) = auth_get_status();

if($status == AUTH_LOGGED & auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else	$link = '';

/*
	$_GET[mode]:
    	- activated : solo per gli admin
        - deactivated : solo per gli admin
        - admin : solo per gli admin
        - trainer : per client e admin
        - client : per trainer e admin
*/

?>

<html>
	<head>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title><?php 
        	switch($_GET[mode]){
            	case 'activated':
                	echo("Lista utenti");
                    break;
                case 'deactivated':
                	echo("Lista account non attivi");
                    break;
                case 'admin':
                	echo("Lista amministratori");
                    break;
                case 'trainer':
                	echo("Lista istruttori");
                    break;
                case 'client':
                	echo("Lista utenti");
                    break;
               	default:
                    break;
            }
        ?></title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
			
				<?php
					if(
                    	!(
                        	$status == AUTH_LOGGED
                        )
                        ||
                        (
                    		!(
                            	(
                                	(
                                    	$_GET[mode]=='activated'
                                    )
                                    ||
                                    (
                                    	$_GET[mode]=='deactivated'
                                    )
                                    ||
                                    (
                                    	$_GET[mode]=='admin'
                                    )
                         		)
                                &&
                                $user[type]==0
                      		)
                            &&
                        	!(
                            	(
                                	$_GET[mode]=='trainer'
                                )
                                &&
                                (
                                	(
                                    	$user[type]==0
                                    )
                                    ||
                                    (
                                    	$user[type]==2
                                    )
                               	)
                           	)
                            &&
                            !(
                            	(
                                	$_GET[mode]=='client'
                                )
                                &&
                                (
                                	(
                                    	$user[type]==0
                                    )
                                    ||
                                    (
                                    	$user[type]==1
                                    )
                               	)
                          	)
                       	)
                   	)
                    {
						header("Refresh: 5;URL=panel".$user[type].".php");
						echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                    else{
                    	echo(" 
                        
                        	<div id='function_bar'>                        		
                            	<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>");
								
                        		if((!($_GET[mode]=='activated'))&&$user[type]==0)echo("<div id='function_button'><a href='show_users.php?mode=activated' id='function_text'>Lista utenti</a></div>");
                                if((($_GET[mode]=='activated')||($_GET[mode]=='trainer')||($_GET[mode]=='admin'))&&$user[type]==0)echo("<div id='function_button'><a href='reg_user.php?mode=0' id='function_text'>Nuovo amministratore o istruttore</a></div>");
                            	if(($_GET[mode]=='activated')||($_GET[mode]=='client')&&$user[type]==0)echo("<div id='function_button'><a href='reg_user.php?mode=1' id='function_text'>   Nuovo cliente</a></div>");
                        		if((!($_GET[mode]=='deactivated'))&&$user[type]==0)echo("<div id='function_button'><a href='show_users.php?mode=deactivated' id='function_text'>Lista account non attivati</a></div>");
                        		if((!($_GET[mode]=='admin'))&&$user[type]==0)echo("<div id='function_button'><a href='show_users.php?mode=admin' id='function_text'>Lista amministratori</a></div>");
                                if((!($_GET[mode]=='trainer'))&&(($user[type]==0)||($user[type]==2)))echo("<div id='function_button'><a href='show_users.php?mode=trainer' id='function_text'>Lista istruttori</a></div>");
                                if((!($_GET[mode]=='client'))&&(($user[type]==0)||($user[type]==1)))echo("<div id='function_button'><a href='show_users.php?mode=client' id='function_text'>Lista clienti</a></div>");

                               
                               echo("<div id='red_function_button'><a href='logout.php' id='function_text'>Logout</a></div>
                            </div>
                            <div id='contenuto'>");
                            	switch($_GET[mode]){
                                	case 'activated':
                                    	echo("<div id=avviso_elenco><a id=name> Lista utenti</a></div>");
										break;
                                    case 'deactivated':
                                    	echo("<div id=avviso_elenco><a id=name> Lista account non ancora attivati</a></div>");
										break;
                                    case 'admin':
                                    	echo("<div id=avviso_elenco><a id=name> Lista amministratori</a></div>");
										break;
                                    case 'trainer':
                                    	echo("<div id=avviso_elenco><a id=name> Lista istruttori</a></div>");
                                        if($user[type]==2){
                            				$id_trainer=get_trainer($user[id]);
                             	   			if(isset($id_trainer)){
                                				$trainer=get_user_detail($id_trainer);
                            					echo("<div id=avviso_elenco><a href='user_detail.php?id=".$trainer[id]."' id=name>Istruttore attuale: ".$trainer['name']." ".$trainer['surname']."</a></div>");
                            	    			}
                        	        		else{
						          				echo("<div id=avviso_elenco><a id=name> Nessun istruttore e' stato ancora scelto</a></div>");
                                			}
                                		}
										break;
                                   case 'client':
                                    	echo("<div id=avviso_elenco><a id=name> Lista allievi</a></div>");
										break;
                                    
                                }
                            
                    			list($us, $max) = show_user($_GET[mode], $user);

                        		
                				for($i=0;$i<$max;$i++){
                					echo"<div id=titolo_scheda><a href='user_detail.php?id=".$us[$i][id]."' id=name> ".$us[$i]['name']." ".$us[$i]['surname']."</a></div>
									";
              					} 		
                       			 echo("
                          	</div>");
                        
                    }
				?>
        	
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>



