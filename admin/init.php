<?php

include 'connect.php';




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



//include navbar on all pages expect the one with $nonavbar variable
if (!isset($nonavbar)){
	 include $tpl."navbar.php";
}












