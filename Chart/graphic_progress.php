<?php
include_once("../include/config.php");
include_once("../include/auth.lib.php");
include_once("../include/function.php");



list($status, $user) = auth_get_status();

if($status == AUTH_LOGGED & auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else	$link = '';

?>

<html>
	<head>
    	<?php $us=get_user_detail($user[id]); ?>
    	<link type="text/css" rel="stylesheet" href="../style.css">
		<title><?php echo("".$us[name]." ".$us[surname]."") ?></title>
	</head>
	<body>
	   	<div id="titolo">
        	<a href="index.php"><img src="../img/logo.png"  id=logo></a>
		</div>
		
        
    	<div id="corpo">
			
				<?php
					if(!($status == AUTH_LOGGED && $user[type]=='2')){
						header("Refresh: 5;URL=../panel".$user[type].".php");
						echo '<div id="red_message">Non hai i diritti per visualizzare la pagina'.$user[type].'</div>';
					}
                    else{ ?>
                        	<div id='function_bar'>
                            	<?php
                                	echo("<div id='function_button'><a href='../panel".$user[type].".php' id='function_text'>Pannello di controllo</a></div>");
                                    if($_GET[mode]=='c')echo("<div id='function_button'><a href='../exercise_list.php?mode=executed' id='function_text'>Esercizi svolti</a></div>");                                
									echo("<div id='red_function_button'><a href='../logout.php' id='function_text'>Logout</a></div>");
                                ?>


                            </div>
                            <div id='contenuto'>
                            	<div id=titolo_scheda>
                                	<a id='name'><?php echo("$us[name] $us[surname]"); ?></a><a id = type>CLIENTE</a>
                                </div>
                                <div id=contenuto_scheda>
                                <?php
                                	if($_GET[mode]=='w'){
                                		echo"<div>";
                                        	include("var_weight.html");
                                        echo"</div>
                                   		<div>";
                                        	include("weight.html");
                                        echo"</div>";
                                    }?>
								</div>
                       		</div>
						</div>
             		<?php } ?> 
        	
		</div>
	<?php include'../bottom.php'; ?>
	</body>
</html>