<?php

include_once("../../include/config.php");
include_once("../../include/auth.lib.php");
include_once("../../include/function.php");


global $_CONFIG;

/*//<mysql>
$conn = mysql_connect($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass']) or die('Impossibile stabilire una connessione');
mysql_select_db($_CONFIG['dbname']);
*///</mysql>

///<mysqli>
$conn = new mysqli($_CONFIG['host'], $_CONFIG['user'], $_CONFIG['pass'], $_CONFIG['dbname']);
if ($conn->connect_errno) {
	printf("Connessione fallita: %s\n", $conn->connect_error);
 	exit();
}
///</mysqli>

list($status, $user) = auth_get_status();

if($status == AUTH_LOGGED & auth_get_option("TRANSICTION METHOD") == AUTH_USE_LINK){
	$link = "?uid=".$_GET['uid'];
}else	$link = '';

header('Content-Type: application/json');    

$count_query =("SELECT count(*) as c FROM ".$_CONFIG['var_weight']." WHERE id=".$user[id]."");

/*//<mysql>
$count=mysql_fetch_array(mysql_query($count_query));
*///</mysql>

///<mysqli>
$result_count = $conn->query($count_query);
$count = $result_count->fetch_array();
$result_count->free();
///</mysqli>

$count=$count[c];
$count-=10;
if($count<0)$count=0;

$query =("SELECT date, variation, weight FROM ".$_CONFIG['var_weight']." WHERE id=".$user[id]." ORDER BY date LIMIT ".$count.", 10");

/*//<mysql>
$result = mysql_query($query);
$data = array();
while($riga=mysql_fetch_array($result)){
		$riga[date]=date('d M',$riga[date]);
		$data[]=$riga;
}
*///</mysql>

///<mysqli>
    $result = $conn->query($query);
    while($riga = $result->fetch_array()){				
		$riga[date]=date('d M',$riga[date]);
        $riga[0]=date('d M',$riga[0]);
		$data[]=$riga;
    }
///</mysqli>
print json_encode($data);