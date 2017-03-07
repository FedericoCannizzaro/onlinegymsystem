<?php

//registrazione nuovo user
function reg_register($data){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    
	$data['username']=$data['mail'];
    $data['password']="".$data['name'].".".$data['surname']."";
    
	$id = reg_get_unique_id();
    if($data[type]==2) $query=("
		INSERT INTO ".$_CONFIG['user']."
		(name, surname, mail, username, password, address, telephone, age, height, o_weight, a_weight, type, reg_date, reg_id, activated)
		VALUES
		('".$data['name']."', '".$data['surname']."', '".$data['mail']."', '".$data['username']."', MD5('".$data['password']."'), '".$data['address']."', '".$data['telephone']."', '".$data['age']."', '".$data['height']."', '".$data['weight']."', '".$data['weight']."', ".$data['type'].", ".time().", '".$id."', '0')" );
   	else $query=("
	INSERT INTO ".$_CONFIG['user']."
	(name, surname, mail, username, password, type, reg_date, reg_id, activated)
	VALUES
	('".$data['name']."', '".$data['surname']."', '".$data['mail']."', '".$data['username']."', MD5('".$data['password']."'), ".$data['type'].", ".time().", '".$id."', '0')" );

	/*//<mysql>
	mysql_query($query);
	if(mysql_insert_id()){
    *///</mysql>
    
    ///<mysqli>
	if($conn->query($query)){
    ///</mysqli>
    
    	if($data[type]=='2'){
        	$query = ("
            SELECT id
            FROM ".$_CONFIG['user']."
            WHERE reg_id = '".$id."'
            ");
            /*//<mysql>
            $result=mysql_query($query);
            $uid=mysql_fetch_row($result);
            *///</mysql>
            
            ///<mysql>
            if ($result = $conn->query($query)){
            	$uid=$result->fetch_array();
                $result->close();
            }
            ///</mysql>
            
            client_first_var($uid[id],$data[weight]);
        }
        return reg_send_confirmation_mail($data['mail'], $_CONFIG['mail'], $id, $data['username'], $data['password']);
	}else return REG_FAILED;
}

function client_first_var($id, $w){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	    $query=("
	INSERT INTO ".$_CONFIG['var_weight']."
	(id, date, variation, weight)
	VALUES
	('".$id."', ".time().", '0', '".$w."')" );
    
    /*//<mysql>
    mysql_query($query);
	return(mysql_insert_id())?true : false;
    *///</mysql>
    
    ///<mysqli>
	return($conn->query($query))?true : false;
    ///</mysqli>
}

//Email di conferma
function reg_send_confirmation_mail($to, $from, $id, $username, $password){
	$msg = "Per confermare l'avvenuta registrazione, clicckate il link seguente:
	http://x81000201.altervista.org/confirm.php?id=".$id."
    I dati di accesso sono
    Username: ".$username."
    Password: ".$password."
	";
	return (mail($to, "Conferma la registrazione", $msg, "From: ".$from)) ? REG_SUCCESS : REG_FAILED;
}

//elimina user non confermati
function reg_clean_expired(){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
	
	$query = ("
	DELETE FROM ".$_CONFIG['user']."
	WHERE (reg_date + ".($_CONFIG['regexpire'] * 60 * 60).") <= ".time()." and activated='0'");
    
    /*//<mysql>
    mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
    $conn->query($query);
    ///</mysqli>
}

//restituisce un ID univoco per gestire la registrazione
function reg_get_unique_id(){
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	return md5(uniqid(mt_rand(), true));
}


//Controlla la validità dei dati
function reg_check_data(&$data){
	global $_CONFIG;
    
	$errors = array();
	
	foreach($data as $field_name => $value){
		$func = $_CONFIG['check_table'][$field_name];
		if(!is_null($func)){
			$ret = $func($value);
			if($ret !== true)
				$errors[] = array($field_name, $ret);
		}
	}
	
	return count($errors) > 0 ? $errors : true;
}

function reg_confirm($id){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
	$query =("
	UPDATE ".$_CONFIG['user']."
	SET activated='1'
	WHERE reg_id='".$id."'");
	
    /*//<mysql>
    mysql_query($query);
	return (mysql_affected_rows () != 0) ? REG_SUCCESS : REG_FAILED;
    *///</mysql>
    
    ///<mysqli>
    $conn_query($query);
	return ($conn->affected_rows () != 0) ? REG_SUCCESS : REG_FAILED;
    ///</mysqli>
}
?>