<?php
include_once("include/config.php");
include_once("include/auth.lib.php");
include_once("include/function.php");



list($status, $user) = auth_get_status();

if($status == AUTH_LOGGED & auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else	$link = '';
$es=get_exercise_detail($_GET['id']);
?>

<html>
	<head>
    	<?php
        echo("<link type='text/css' rel='stylesheet' href='style.css'>
		<title>".$es[name]."</title>");
        ?>
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
                            echo("</div>
                            
                            
                            <div id='contenuto'>
                            	<div id=titolo_scheda>
                                	<a id='name'>".$es[name]."</a>");
                                echo("</div>
                                <div id=contenuto_scheda>
                                	".$es[description]."
                                </div>
                                ");                              
                          	echo("</div>");
                        
                    }
				?>
        	
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>




