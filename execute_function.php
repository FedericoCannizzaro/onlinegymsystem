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
		<title>Eseguo operazione</title>
	</head>
	<body>
	<?php include'top.php'; ?>
    	<div id="corpo">
			<?php
            	$data=array();
                //echo("POST[mode]: ".$_POST[mode]."<br/>GET[mode]: ".$_GET[mode]."");
            	if(isset($_POST[mode]) || isset($_GET[mode])){
                	if(isset($_POST[mode]))$data=$_POST;
            		else $data=$_GET;
                    list($page, $result, $msg)=switch_function($data);
                    //echo("<br/>data[mode]: ".$data[mode].", ".$data[id].", ".$data[deadline]."");
                    if($page=="")$page="panel".$user['type'].".php";
                    //echo($page);
                	header("Refresh: 5;URL=".$page."");
             		if($result==1)echo("<div id='message'>".$msg."</div>");
                    else echo("<div id='red_message'>".$msg."</div>");
                }
            ?>
		</div>
	<?php include'bottom.php'; ?>
	</body>
</html>





