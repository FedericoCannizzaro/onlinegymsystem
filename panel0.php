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
    	<?php $us=get_user_detail($user[id]); ?>
    	<link type="text/css" rel="stylesheet" href="style.css">
		<title><?php echo("Pannello amministratore: ".$us[name]." ".$us[surname]."") ?></title>
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
                    	echo(" 
                        	<div id='function_bar'>
                        		<div id='function_button'><a href='show_users.php?mode=activated' id='function_text'>Lista utenti</a></div>
                        		<div id='function_button'><a href='payments_list.php?id=-1&state=-1&deadline=-1' id='function_text'>Lista pagamenti</a></div> 
                        		<div id='function_button'><a href='alter_user.php?mode=password' id='function_text'>Modifica password</a></div> 
								<div id='red_function_button'><a href='logout.php' id='function_text'>Logout</a></div>


                            </div>
                            <div id='contenuto'>
                            	<div id=titolo_scheda>
                                	<a id='name'>".$us[name]." ".$us[surname]."		</a>
                                    <a id = type>AMMINISTRATORE</a></div>
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

