<?php




$db='mysql:host=localhost;dbname=shop';
$user='root';
$pass='';
$option=array(
PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try{
	$con = new PDO($db, $user, $pass, $option);
	$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// echo "You are connect";
}
catch(PDOException $e){
	echo 'Failed to  connected' . $e->getMessage();
}
