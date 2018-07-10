<?php

//Error Reporting
ini_set('display_errors','On');
error_reporting(E_ALL);

include 'connect.php';

$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}



//routes

$tpl='includes/templets/';   	//templet directory
$css='layout/css/';				//css directory
$js='layout/js/';				//js directory 
$lang ='includes/languages/';	//language directory
$func='includes/functions/';	//functions directory

//include the important files

include $func.'functions.php';
include $lang.'english.php';  //must invoke at first
 include $tpl."header.php";













