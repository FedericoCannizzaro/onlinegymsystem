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
		<title>Pagamenti</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
				<?php
					if($status != AUTH_LOGGED || ($user[type]=='2' && $_GET[id]==-1)){
						header("Refresh: 5;URL=panel".$user[type].".php");
						echo '<div id="red_message">Non hai i diritti per visualizzare la pagina</div>';
					}
                    else{
                    	echo(" 
                        
                        	<div id='function_bar'>                        		
                            	<div id='function_button'><a href='panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>");
								if($_GET[id]!=-1 && $user[type]==0){
                                echo("<div id='function_button'><a href='user_detail.php?id=".$_GET[id]."' id='function_text'>Dettagli utente</a></div>");
                                }                                
                            	echo("<div id='function_button'><a href='payments_list.php?id=".$_GET[id]."&state=-1&deadline=-1' id='function_text'>Tutti i pagamenti</a></div>                                
                            	<div id='function_button'><a href='payments_list.php?id=".$_GET[id]."&state=1&deadline=-1' id='function_text'>Pagamenti effettuati</a></div>
                            	<div id='function_button'><a href='payments_list.php?id=".$_GET[id]."&state=0&deadline=-1' id='function_text'>Pagamenti non ancora effettuati</a></div>
                            	<div id='red_function_button'><a href='payments_list.php?id=".$_GET[id]."&state=0&deadline=".time()."' id='function_text'>Pagamenti scaduti</a></div>
								<div id='red_function_button'><a href='logout.php' id='function_text'>Logout</a></div>
                            </div>
                            <div id='contenuto'>");
                    			list($list, $max) = payments($_GET[id], $_GET[state], $_GET[deadline]);
									echo("<div id=avviso_elenco><a id=name>Lista pagamenti</a></div>");                        		
                				for($i=0;$i<$max;$i++){
                					echo"<div id=titolo_scheda><a href='payment_detail.php?id=".$list[$i][id]."&deadline=".$list[$i][deadline]."' id=name> ".date('d M Y', $list[$i]['deadline'])."</a></div>
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



