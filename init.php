<?php
//Error Reporting
ini_set('display_errors','On');

error_reporting(E_ALL);
$sessionUser='';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

include 'admin_panel/connect.php';
//Routes

$tmpl ='includes/templates/';
$lang ='includes/languages/';
$func ='includes/functions/';
$css  ='layout/css/'; //Css dirictory
$js   ='layout/js/'; //js dirictory

include $func.'function.php';
include $lang.'english.php';
include $tmpl.'header.php';


