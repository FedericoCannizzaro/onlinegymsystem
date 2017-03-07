<?php

$_AUTH = array(
	"TRANSICTION METHOD" => AUTH_USE_COOKIE
);

function auth_set_option($opt_name, $opt_value){
	global $_AUTH;
	
	$_AUTH[$opt_name] = $opt_value;
}

function auth_get_option($opt_name){
	global $_AUTH;
	
	return is_null($_AUTH[$opt_name])
		? NULL
		: $_AUTH[$opt_name];
}

function auth_clean_expired(){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    $query = ("SELECT creation_date FROM ".$_CONFIG['user_session']." WHERE uid='".auth_get_uid()."'");
	
    /*//<mysql>
	$result = mysql_query($query);
	if($result){
		$data = mysql_fetch_array($result);
    *///</mysql>
    
    ///<mysqli>
	if($result = $conn->query($query)){
		$data = $result->fetch_array(MYSQLI_NUM);;
        $result->free();
    ///</mysqli>
    
    
		if($data['creation_date']){
			if($data['creation_date'] + $_CONFIG['expire'] <= time()){
				switch(auth_get_option("TRANSICTION METHOD")){
					case AUTH_USE_COOKIE:
						setcookie('uid');
					break;
					case AUTH_USE_LINK:
						global $_GET;
						$_GET['uid'] = NULL;
					break;
				}
			}
		}
	}
	
    $query = ("
	DELETE FROM ".$_CONFIG['user_session']."
	WHERE creation_date + ".$_CONFIG['expire']." <= ".time());
    
    /*//<mysql>
	mysql_query($query);
    *///</mysql>
    
    ///<mysqli>
	$conn->query($query);
    ///</mysqli>
    
}

function auth_get_uid(){
	
	$uid = NULL;

	switch(auth_get_option("TRANSICTION METHOD")){
		case AUTH_USE_COOKIE:
			global $_COOKIE;
			$uid = $_COOKIE['uid'];
		break;
		case AUTH_USE_LINK:
			global $_GET;
			$uid = $_GET['uid'];
		break;
	}

	return $uid ? $uid : NULL;
}

function auth_get_status($uid, $pass){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
    
    
	auth_clean_expired();
    //echo($uid);
	if(pass!=9896)$uid = auth_get_uid();
	if(is_null($uid))
		return array(100, NULL);
	
	$query = ("SELECT U.id as id, U.name as name, U.surname as surname, U.username as username, U.type as type
	FROM ".$_CONFIG['user_session']." S,".$_CONFIG['user']." U
	WHERE S.user_id = U.id and S.uid = '".$uid."'");
    
    /*//<mysql>
    $result = mysql_query($query);
	if(mysql_num_rows($result) != 1)
		return array(100, NULL);
	else{
		$user_data = mysql_fetch_assoc(mysql_query($result));
		return array(99, array_merge($user_data, array('uid' => $uid)));
	}
    *///</mysql>
    
    ///<mysqli>
    if($stmt = $conn->prepare($query)){
    	$stmt->execute();
    	$stmt->store_result();
		if($stmt->num_rows != 1)
			return array(100, NULL);
		else{
        	$result = $conn->query($query);
			$user_data = $result->fetch_array();;
        	$result->free();
			return array(99, array_merge($user_data, array('uid' => $uid)));
		}
        $stmt->close();
    }
    ///</mysqli>
}

function auth_login($uname, $passw){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>

	$query = ("
	SELECT *
	FROM ".$_CONFIG['user']."
	WHERE username='".$uname."' and password=MD5('".$passw."') AND activated=1"
	);
    
    /*//<mysql>
	$result = mysql_query($query);
	if(mysql_num_rows($result) != 1){
		return array(AUTH_INVALID_PARAMS, NULL);
	}else{
		$data = mysql_fetch_array($result);
		return array(AUTH_LOGEDD_IN, $data);
	}
    *///</mysql>
    
    ///<mysqli>
	if($stmt = $conn->prepare($query)){
    	$stmt->execute();
    	$stmt->store_result();
		if($stmt->num_rows != 1){
			return array(AUTH_INVALID_PARAMS, NULL);
		}
        else{
        $result = $conn->query($query);
		$data = $result->fetch_array();;
        $result->free();
		return array(AUTH_LOGEDD_IN, $data);
		}
        $stmt->close();
    }
    ///</mysqli>
}

function auth_generate_uid(){
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	return md5(uniqid(mt_rand(), true));
}

function auth_register_session($udata){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
	
	$uid = auth_generate_uid();
	$query = ("
	INSERT INTO ".$_CONFIG['user_session']."
	(uid, user_id, creation_date)
	VALUES
	('".$uid."', '".$udata['id']."', ".time().")
	"
	);
    
    /*//<mysql>
	mysql_query($query);
	if(!mysql_insert_id()){
		return array(AUTH_LOGEDD_IN, $uid);
	}else{
		return array(AUTH_FAILED, NULL);
	}
    *///</mysql>
   
   
    ///<mysqli>
    $conn->query($query);
	if(($conn->affected_rows)>0){
		return array(AUTH_LOGEDD_IN, $uid);
	}else{
		return array(AUTH_FAILED, NULL);
	}
    ///</mysqli>
}

function auth_logout(){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>

	$uid = auth_get_uid();
	
	if(is_null($uid)){
		return false;
	}else{
    	$query = ("
		DELETE FROM ".$_CONFIG['user_session']."
		WHERE uid = '".$uid."'"
		);
        ///<mysql>
		mysql_query($query);
        ///</mysql>
        ///<mysql>
		$conn->query($query);
        ///</mysql>
		return true;
	}
}
?>
