<?php
	require_once("config.php");
function Connect()
{
	$con = mysqli_connect($GLOBALS['dbHost'],$GLOBALS['dbUser'],$GLOBALS['dbPass'],$GLOBALS['dbName']);
	$con->set_charset('utf8');
	$con->query("SET collation_connection = utf8_czech_ci");
	return $con;
}

function Disconnect($con)
{
	mysqli_close($con);
}

?>