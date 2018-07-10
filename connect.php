<?php

$dsn='mysql:host=localhost;dbname=shop';
$user='root';
$pass='';
$options=array(
			PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8',
	);



try {
	$conn = new PDO($dsn,$user,$pass,$options);

	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


	// echo "You Are Connected Welcome To Database";


} catch (PDOException $e) {
	// echo "failed connection".$e->getmessage() ;
}



?>