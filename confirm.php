<?php
include_once("include/config.php");
include_once("include/reg.lib.php");

if(isset($_GET['id']) and strlen($_GET['id']) == 32){
	reg_clean_expired();
	$status = reg_confirm($_GET['id']);
	
	switch($status){
		case REG_SUCCESS:
			echo "La tua registrazione è stata confermata; ora puoi effettuare il login.";
		break;
		case REG_FAILED:
			echo "La registrazione non può essere confermata, probabilemente poichè è scaduta.";
		break;
	}
}
?>