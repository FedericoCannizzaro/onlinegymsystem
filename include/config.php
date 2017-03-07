 <?php
//Database data
$_CONFIG['http'] = (" http://x81000201.altervista.org");
$_CONFIG['host'] = " localhost";
$_CONFIG['user'] = "x81000201";
$_CONFIG['pass'] = "";
$_CONFIG['dbname'] = "my_x81000201";

//Table
$_CONFIG['user_session'] = "user_session";
$_CONFIG['user'] = "user";
$_CONFIG['payments'] = "payments";
$_CONFIG['var_weight'] = "var_weight";
$_CONFIG['executed_ex'] = "executed_ex";
$_CONFIG['assigned_ex'] = "assigned_ex";
$_CONFIG['exercise'] = "exercise";
$_CONFIG['trainer'] = "trainer";



//Session limit
$_CONFIG['expire'] = 6000;

//reg_time limit
$_CONFIG['regexpire'] = 24; //in ore

$_CONFIG['mail'] = 'fefecnn@gmail.com'; //in ore



$_CONFIG['check_table'] = array(
	"mail" => "check_username",
	"password" => "check_global",
	"name" => "check_global",
	"surname" => "check_global",
	"address" => "check_global",
    "telephone" => "check_global",
    "age" => "check_global",
    "height" => "check_global",
    "weight" => "check_global"
);

function check_telephone($phone){
	if(!preg_match("/^[0-9]{10}$/", $phone)) {
   		return "Il numero di telefono non è valido";
	}
}

function check_username($value){
	global $_CONFIG;
    
    ///<mysqli>
	$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
	if ($conn->connect_errno) {
 	   printf("Connessione fallita: %s\n", $conn->connect_error);
 	   exit();
	}
    ///</mysqli>
	
	$value = trim($value);
	if($value == "")
		return "Il campo non può essere lasciato vuoto";
	$query = ("
	SELECT id
	FROM ".$_CONFIG['table_utenti']."
	WHERE username='".$value."'");
    
    /*//<mysql>
    $result=mysql_query($query);
	if(mysql_num_rows($result) != 0)
		return "Un utente si è già registrato con questa mail";
	*///</mysql>
    
	///<mysqli>
    if ($stmt = $conn->prepare($query)) {
	    $stmt->execute();
	    $stmt->store_result();
        if($stmt->num_rows != 0)
		return "Un utente si è già registrato con questa mail";
		$stmt->close();
    }
	///</mysqli>
    
	return true;
}

function check_global($value){
	global $_CONFIG;
	
	$value = trim($value);
	if($value == "")
		return "Il campo non può essere lasciato vuoto";
	
	return true;
}


//--------------
define('AUTH_LOGGED', 99);
define('AUTH_NOT_LOGGED', 100);

define('AUTH_USE_COOKIE', 101);
define('AUTH_USE_LINK', 103);
define('AUTH_INVALID_PARAMS', 104);
define('AUTH_LOGEDD_IN', 105);
define('AUTH_FAILED', 106);

define('REG_ERRORS', 107);
define('REG_SUCCESS', 108);
define('REG_FAILED', 109);

/*//<mysql>
$conn = mysql_connect($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass']) or die('Impossibile stabilire una connessione');
mysql_select_db($_CONFIG['dbname']);
*///</mysql>
?>
