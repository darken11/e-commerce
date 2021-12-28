<?php
$do=isset($_GET['do']) ? $_GET['do'] : 'Manage';

if($do =='Manage'){
	echo "You are in Manage page";
	echo"<a href='pages.php?do=Add'>Add new Cat+</a>";
}elseif ($do == 'Add') {

	echo "You are in Add page";
}else{
	echo "it's no page with this name";
}
?>