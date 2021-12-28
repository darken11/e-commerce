<?php

include 'connect.php';
//Routes

$tmpl ='includes/templates/';
$lang ='includes/languages/';
$func ='includes/functions/';
$css  ='layout/css/'; //Css dirictory
$js   ='layout/js/'; //js dirictory

include $func.'function.php';
include $lang.'english.php';
include $tmpl.'header.php';

//Include navbar in all pages excepte the one with nonavbar variable
if(!isset($noNavBar)){include $tmpl.'navbar.php';}
