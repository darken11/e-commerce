<?php
session_start();
//this variable needed for '$noNavBar' know if we need to add navbar for this page of no.some pages are not need to navbar like login or logout
$noNavBar = '';

$pageTitle = 'Login';
if (isset($_SESSION['Username'])) {
	header('Location:dashboard.php'); //redirect to dashboard page
}
include 'init.php';


//check if user coming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username  = $_POST['user'];
	$password  = $_POST['pass'];
	$hashedPass = sha1($password);
	//check if the user exist in db
	$stmt = $con->prepare("SELECT 
		                        User_ID, Username, Password 
		                   FROM 
		                         users 
		                   WHERE 
		                         Username=? 
		                   AND 
		                         Password=? 
		                   AND 
		                         GroupId=1
		                    Limit 1");
	$stmt->execute(array($username, $hashedPass));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();

	//if count >0 this mean the database contain record about this username
	if ($count > 0) {
		$_SESSION['Username'] = $username;
		$_SESSION['Id'] = $row['User_ID']; //register sesion id
		header('Location:dashboard.php');
		exit();
	}
}
?>
<div class="container">
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<h4 class="text-center">Admin Login</h4>
	<input class="form-control" type="text" name="user" placeholder="User " autocomplete="off">
	<input class="form-control" type="password" name="pass" placeholder="Password " autocomplete="new-password">
	<input class="btn btn-primary" type="submit" value="Login">
</form>
</div>




<?php
include $tmpl . 'footer.php';
?>