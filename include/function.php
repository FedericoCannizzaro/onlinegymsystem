<?php
//User
function switch_function($data){
	//echo($data[mode]);
	switch($data[mode]){
    	case 'activate_u':
        	return activate_user($data[id]);
        	break;
        case 'delete_u':
        	return delete_user($data[id]);
        	break;
    	case 'alter_password':
        	return alter_password($data);
        	break;
        case 'alter_data':
        	return alter_data($data);
        	break;
        case 'alter_weight':
        	return alter_weight($data);
        	break;
        case 'reg_p':
        	return reg_payment($data);
        	break;
        case 'send_notice':
        	return send_notice($data[id], $data[deadline]);
        	break;   
        case 'confirm_p':
        	return confirm_payment($data[id], $data[deadline]);
        	break;
       	case 'set_trainer':
        	return set_trainer($data[id_user], $data[id_trainer]);
        	break;
        case 'execute_ex':
        	return execute_ex($data[id_ex], $data[id_us], $data[repetition]);
        	break;
        case 'delete_ex_plan':
        	return delete_ex_plan($data[id_ex], $data[id_us]);
        	break;
        case 'delete_plan':
        	return delete_plan($data[id]);
        	break;
        case 'add_ex':
        	return add_ex($data[id], $data[id_ex], $data[repetition], $data[priority]);
        	break;
        case 'new_ex':
        	return new_ex($data[name], $data[description]);
            break;
    }
}
/*
    	- activated : solo per gli admin
        - deactivated : solo per gli admin
        - admin : solo per gli admin
        - trainer : per client e admin
        - client : per trainer e admin
*/
function show_user($mode, $user){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $r=array();
	$c=0;
    if(($mode=='client')&&($user[type]==1)){
    	$query=("SELECT U.id as id, U.name as name, U.surname as surname FROM ".$_CONFIG['user']." as U, ".$_CONFIG['trainer']." as T WHERE U.id=T.id_user AND T.id_trainer=".$user[id]." ORDER BY reg_date DESC;");
    }
    else{
    $w='';
    	switch($mode){
    		case 'activated':
    	    	$w='WHERE activated=1';
        		break;
      	  case 'deactivated':
   		     	$w='WHERE activated=0';
        		break;
			case 'admin':
        		$w='WHERE activated=1 AND type=0';
     		   	break;   
    		case 'trainer':
        		$w='WHERE activated=1 AND type=1';
        		break;
           	case 'client':
        		$w='WHERE activated=1 AND type=2';
        		break;
            default:
            	break;
    	}
        $query=("SELECT id, name, surname FROM ".$_CONFIG['user']." ".$w." ORDER BY reg_date DESC;");
    }
    //echo($query);
   	
    /*//<mysql>
	$result=mysql_query($query);
	while($riga=mysql_fetch_array($result)){				
		$r[$c]=$riga;
        $c++;}
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
    while($riga = $result->fetch_array()){				
		$r[$c]=$riga;
        $c++;}
    ///</mysqli>
     
    return array ($r, $c);
}

function get_user_detail($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $query = ("select id, name, surname, mail, type, reg_date, activated, address, telephone, age, height, o_weight, a_weight from ".$_CONFIG['user']." WHERE id = $id;");
	
    /*//<mysql>
    $result = mysql_query($query);
	$riga=mysql_fetch_array($result);
    *///</mysql>
    
    ///<mysqli>
    if($stmt = $conn->prepare($query)){
    	$stmt->execute();
    	$stmt->store_result();
		if($stmt->num_rows == 1){
        	$result = $conn->query($query);
			$riga = $result->fetch_array();;
        	$result->free();
		}
        $stmt->close();
    }
    ///</mysqli>
    
    return $riga;
}

function delete_user($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    $query = ("select type, name, surname from ".$_CONFIG['user']." WHERE id = ".$id.";");
    
    /*//<mysql>
	$result = mysql_query($query);			
	$riga=mysql_fetch_array($query);
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
	$riga = $result->fetch_array();
    $result->free();
    ///</mysqli>
    
    if($riga[type]==0){
    	$msg="Non e' possibile eliminare un amministratore...";
    	return array (0, $msg);
    }
    
    /*//<mysql>
	if($riga[type]==2){
    	mysql_query("DELETE from ".$_CONFIG['assigned_ex']." WHERE id = $id;");
        mysql_query("DELETE from ".$_CONFIG['executed_ex']." WHERE id = $id;");
        mysql_query("DELETE from ".$_CONFIG['payments']." WHERE id = $id;");
        mysql_query("DELETE from ".$_CONFIG['trainer']." WHERE id_user = $id;");
        mysql_query("DELETE from ".$_CONFIG['var_weight']." WHERE id = $id;");
    }
    mysql_query("DELETE from ".$_CONFIG['user']." WHERE id = ".$id.";");
    *///</mysql>
    
    ///<mysqli>
	if($riga[type]==2){
    	$conn->query("DELETE from ".$_CONFIG['assigned_ex']." WHERE id = $id;");
        $conn->query("DELETE from ".$_CONFIG['executed_ex']." WHERE id = $id;");
        $conn->query("DELETE from ".$_CONFIG['payments']." WHERE id = $id;");
        $conn->query("DELETE from ".$_CONFIG['trainer']." WHERE id_user = $id;");
        $conn->query("DELETE from ".$_CONFIG['var_weight']." WHERE id = $id;");
    }
    $conn->query("DELETE from ".$_CONFIG['user']." WHERE id = ".$id.";");
    //</mysqli>
    
    $msg="Elimino ".$riga[name]." ".$riga[surname]."...";
    $page="";//panel
    return array ($page, 1, $msg);
}

function activate_user($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $query = ("select name, surname from ".$_CONFIG['user']." WHERE id = ".$id.";");
    
    /*//<mysql>
	$result=mysql_query($query);			
	$riga=mysql_fetch_array($result);			
    mysql_query("UPDATE ".$_CONFIG['user']." SET activated='1' WHERE id = ".$id.";");
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
	$riga = $result->fetch_array();
    $result->free();
    $conn->query("UPDATE ".$_CONFIG['user']." SET activated='1' WHERE id = ".$id.";");
    ///</mysqli>
    
    
    $msg="Attivo utente ".$riga[name]." ".$riga[surname]."...";
    $page="user_detail.php?id=".$id."";
    return array ($page, 1, $msg);
}

//Payments
function reg_payment($data){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $data[deadline]=str_replace('/', '-', $data[deadline]);
    $data[deadline]=strtotime($data[deadline]);
    $query = ("INSERT INTO ".$_CONFIG['payments']."
	(id, deadline, amount, note, payed)
	VALUES
	('".$data['id']."', '".$data['deadline']."', '".$data['amount']."', '".$data['note']."', '0')");
    
    /*//<mysql>
	mysql_query($query);	
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);	
    ///</mysqli>
    
    $msg="Registro pagamento...";
    $page="payment_detail.php?id=".$data['id']."&deadline=".$data['deadline']."";
    return array ($page, 1, $msg);
}

function payments($id,$state,$deadline){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $r=array();
	$c=0;
    
    $w="";
    $a1="";
    $u="";
    $s="";
    $a2="";
    $d="";
    if($id!=-1 || $state!=-1 || $deadline!=-1){
    	$w=" WHERE";
        if(($id!=-1 && $state!=-1) || ($id!=-1 && $deadline!=-1)) $a1=" AND";
        if($state!=-1 && $deadline!=-1) $a2=" AND";
        if($id!=-1)$u=" id=".$id."";
        if($state!=-1)$s=" payed=".$state."";
        if($deadline!=-1)$d=" deadline <".$deadline."";
    }
    $query="select*from ".$_CONFIG['payments']."".$w."".$u."".$a1."".$s."".$a2."".$d." ORDER BY deadline";
	//echo($query);
    
    /*//<mysql>
    $result=mysql_query($query);
	while($riga=mysql_fetch_array($result)){				
		$r[$c]=$riga;
        $c++;}
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
    while($riga = $result->fetch_array()){				
		$r[$c]=$riga;
        $c++;}
    ///</mysqli>
    
    return array ($r, $c);
}

function get_payment_detail($id, $deadline){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $state=1;
    $query="select * from ".$_CONFIG['payments']." WHERE id = ".$id." AND deadline = ".$deadline.";";
    //echo($query);
    
    /*//<mysql>
   	$result=mysql_query($query);
	$riga=mysql_fetch_array($result);
    *///</mysql>
    
    ///<mysqli>
    if($stmt = $conn->prepare($query)){
    	$stmt->execute();
    	$stmt->store_result();
		if($stmt->num_rows == 1){
        	$result = $conn->query($query);
			$riga = $result->fetch_array();;
        	$result->free();
		}
        $stmt->close();
    }
    ///</mysqli>
    
    if($deadline<time())$state=0;
    return array ($riga, $state);
}

function confirm_payment($id, $deadline){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query="UPDATE ".$_CONFIG['payments']." SET payed='1' WHERE id = $id AND deadline=".$deadline.";";
    //echo($query);
    
    /*//<mysql>
    mysql_query($query);
    *///<mysql>
	
    ///<mysqli>
    $conn->query($query);
    ///<mysqli>

    $msg="Confermo pagamento...";
    $page="payment_detail.php?id=".$id."&deadline=".$deadline."";
    return array ($page, 1, $msg);
}

function send_notice($id, $deadline){
	$us=get_user_detail($id);
    $to="".$us[mail]."";
    $title="Notifica di scadenza";
    $deadline=date('d M Y',$deadline);
    $msg="La avvisiamo che non ha ancora pagato un canone gia' scaduto il".$deadline."";
    //echo("Destinatario: ".$to."<br/> Titolo: ".$title."<br/>".$msg."");
	return send_mail($id, $to, $title, $msg);
}

function send_mail($id, $to, $title, $msg){
	global $_CONFIG;
    
	mail($to, $title, $msg, "From: ".$_CONFIG['mail']);
    $msg="Invio avviso...";
    $page="user_detail.php?id=".$id."";
    return array ($page, 1, $msg);
}

function alter_password($data){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query ="UPDATE ".$_CONFIG['user']." SET password=MD5('".$data[password]."') WHERE id = ".$data[id].";";

	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $msg="Cambio password...";
    $page="";//panel
    return array ($page, 1, $msg);
}

function alter_data($data){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query ="UPDATE ".$_CONFIG['user']." SET address='".$data[address]."', telephone='".$data[telephone]."', age='".$data[age]."', height='".$data[height]."' WHERE id = ".$data[id].";";

	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $msg="Modifico dati...";
    $page="";//panel
    return array ($page, 1, $msg);
}

function alter_weight($data){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $us=get_user_detail($data[id]);
	$query ="UPDATE ".$_CONFIG['user']." SET a_weight=".$data[weight]." WHERE id = ".$data[id].";";
    //echo($query);
    
	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $v=$data[weight]-$us[a_weight];
    if($v!=0){
		$query="INSERT INTO ".$_CONFIG['var_weight']." (id, date, variation, weight)
    	VALUES ('".$data[id]."', ".time().", '".$v."', '".$data[weight]."')";
		//echo($query);
		
		/*//<mysql>
		mysql_query($query);
    	*///</mysql>
    
    	///<mysqli>
		$conn->query($query);
    	///</mysqli>

		$msg="Aggiorno peso...";
        $page="";
    	return array ($page, 1, $msg);
    }
    $msg="Peso invariato...";
    $page="";//panel
    return array ($page, 0, $msg);
}

function executed_ex($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $r=array();
	$c=0;
    $query="select E.id as id, E.name as name, A.repetition as repetition, A.date as date from ".$_CONFIG['executed_ex']." as A, ".$_CONFIG['exercise']." as E WHERE A.id = ".$id." AND A.id_ex=E.id ORDER BY date DESC;";
    //echo($query);
    
    /*//<mysql>
	$result=mysql_query($query);
	while($riga=mysql_fetch_array($result)){				
		$r[$c]=$riga;
        $c++;}
    mysql_free_result($query);
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
    while($riga = $result->fetch_array()){				
		$r[$c]=$riga;
        $c++;}
    ///</mysqli>
    
    return array ($r, $c);
}
function assigned_ex($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $r=array();
	$c=0;
    $query="select E.id as id, E.name as name, A.repetition as repetition, A.priority as priority from ".$_CONFIG['assigned_ex']." as A, ".$_CONFIG['exercise']." as E WHERE A.id = ".$id." AND A.id_ex=E.id ORDER BY A.priority;";
    
    /*//<mysql>
	$result=mysql_query($query);
	while($riga=mysql_fetch_array($result)){				
		$r[$c]=$riga;
        $c++;}
    mysql_free_result($query);
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
    while($riga = $result->fetch_array()){				
		$r[$c]=$riga;
        $c++;}
    ///</mysqli>
    
    return array ($r, $c);
}

function exercise_list(){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $r=array();
	$c=0;
    $query="select * from ".$_CONFIG['exercise']." ORDER BY name;";
    
    /*//<mysql>
	$result=mysql_query($query);
	while($riga=mysql_fetch_array($result)){				
		$r[$c]=$riga;
        $c++;}
    mysql_free_result($query);
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
    while($riga = $result->fetch_array()){				
		$r[$c]=$riga;
        $c++;}
    ///</mysqli>
    
    return array ($r, $c);
}

function get_trainer($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query="SELECT id_trainer FROM ".$_CONFIG['trainer']." WHERE id_user = ".$id.";";
    //echo($query);
    
    /*//<mysql>
    $result=mysql_query($query);
    $trainer=mysql_fetch_array($result);
    mysql_free_result($result);
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
	$trainer = $result->fetch_array();
    $result->free();
    ///</mysqli>
    
    return $trainer[id_trainer];

}

function set_trainer($user, $trainer){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $query="SELECT * FROM ".$_CONFIG['trainer']." WHERE id_user = ".$user.";";
    
    /*//<mysql>
    $result=mysql_query($query);
	if(mysql_num_rows($result) == 1){
    	$query=("UPDATE ".$_CONFIG['trainer']." SET id_trainer=".$trainer." WHERE id_user=".$user.";");
    }
    else{$query=("INSERT INTO ".$_CONFIG['trainer']."(id_user, id_trainer) VALUES ('".$user."','".$trainer."');");}
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
    if($stmt = $conn->prepare($query)){
    	$stmt->execute();
    	$stmt->store_result();
		if($stmt->num_rows == 1){
    		$query=("UPDATE ".$_CONFIG['trainer']." SET id_trainer=".$trainer." WHERE id_user=".$user.";");
    	}
    	else{
        	$query=("INSERT INTO ".$_CONFIG['trainer']."(id_user, id_trainer) VALUES ('".$user."','".$trainer."');");
       	}
        $stmt->close();
    }
	$conn->query($query);
    ///</mysqli>
    
    $msg="Seleziono istruttore...";
    $page="exercise_list.php?mode=assigned";
    return array ($page, 1, $msg);
}

function get_exercise_detail($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $query="select * from ".$_CONFIG['exercise']." WHERE id = ".$id.";";

	/*//<mysql>
    $result=mysql_query($query);
    $riga=mysql_fetch_array($result);
    mysql_free_result($result);
    *///</mysql>
    
    ///<mysqli>
    $result = $conn->query($query);
	$riga = $result->fetch_array();
    $result->free();
    ///</mysqli>
    
    return $riga;
}

function execute_ex($exercise, $user, $repetition){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query=("INSERT INTO ".$_CONFIG['executed_ex']."(id, id_ex, repetition, date) VALUES ('".$user."','".$exercise."','".$repetition."',".time().");");

	    
	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $msg="Eseguo...";
    $page="exercise_list.php?mode=assigned";
    return array ($page, 1, $msg);
    
}

function  delete_ex_plan($id_ex, $id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query=("DELETE from ".$_CONFIG['assigned_ex']." WHERE id = ".$id." AND id_ex=".$id_ex.";");

    
	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $msg="Rimuovo esercizio dal piano...";
    $page="exercise_list.php?id=".$id."&mode=assigned_trainer";
    return array ($page, 1, $msg);
}

function  delete_plan($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query=("DELETE from ".$_CONFIG['assigned_ex']." WHERE id = ".$id.";");

	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $msg="Rimuovo tutti gli esercizi dal piano...";
    $page="exercise_list.php?id=".$id."&mode=assigned_trainer";
    return array ($page, 1, $msg);
}

function add_ex($id, $id_ex, $repetition, $priority){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
   	$query=("SELECT * FROM ".$_CONFIG['assigned_ex']." WHERE id=".$id." AND id_ex=".$id_ex.";");
    $page="exercise_list.php?id=".$id."&mode=assigned_trainer";
    
    /*//<mysql>
    $result=mysql_query($query);
	if(mysql_num_rows($result) == 0){
   		$query=("INSERT INTO ".$_CONFIG['assigned_ex']."(id, id_ex, repetition, priority) VALUES ('".$id."','".$id_ex."','".$repetition."','".$priority."');");
		//echo($query);
    	mysql_query($query);
        $msg="Aggiungo esercizio...";
        return array ($page, 1, $msg);
    }
    *///</mysql>
    
    ///<mysqli>
    if($stmt = $conn->prepare($query)){
    	$stmt->execute();
    	$stmt->store_result();
		if($stmt->num_rows == o){
   			$query=("INSERT INTO ".$_CONFIG['assigned_ex']."(id, id_ex, repetition, priority) VALUES ('".$id."','".$id_ex."','".$repetition."','".$priority."');");
			//echo($query);
    		$conn->query($query);
        	$msg="Aggiungo esercizio...";
        	return array ($page, 1, $msg);
    	}
        $stmt->close();
    }
    ///</mysqli>
    
    else{
    	$msg="Esercizio gia' presente...";
    return array ($page, 0, $msg);
    }
	
}

function new_ex($name,$description){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $query=("INSERT INTO ".$_CONFIG['exercise']."(name, description) VALUES ('".$name."','".$description."');");
    
	/*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
    $msg="Aggiungo esercizio...";
    $page="exercise_list.php?mode=to_modify";
    return array ($page, 1, $msg);
}
?>


