<?php

/*
==========================================================
==  Manage Members Page
== You can Add | Edit | Delete Members from here
==========================================================
*/


session_start();
$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {



	include 'init.php';

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

	//start Manage page

	if ($do == 'Manage') {   //manage Members page  
		$query="";
		if(isset($_GET['page']) && $_GET['page'] = 'Pending') {
			$query = 'AND RegStatus = 0';
		}
         $rows=getAllFrom("*","users", "WHERE GroupId !=1", $query,"User_ID");
        if(!empty($rows)){
?>
		<h1 class="text-center">Manage Members</h1>
		<div class="container">
			<div class="table-responsive">

				<table class="main-table text-center table table-bordered">
					<tr>
						<td>#ID</td>
						<td>Avatar</td>
						<td>Username</td>
						<td>Email</td>
						<td>Full Name</td>
						<td>Ragiterd Date</td>
						<td>Controle</td>
					</tr>
					<?php
					foreach ($rows as $row) {

						echo "<tr>";
						echo "<td>" . $row['User_ID'] . "</td>";
						echo '<td>';
						if(empty($row['Avatar'])){
                           echo'<img class="avatar-pic" src="./layout/images/no-avatar-300x300.png"/>';
						}else{
							echo'<img class="avatar-pic" src="uploads\avatars\\' . $row['Avatar'] .'"/>';

						}
						echo '</td>';
						echo "<td>" . $row['Username'] . "</td>";
						echo "<td>" . $row['Email'] . "</td>";
						echo "<td>" . $row['FullName'] . "</td>";
						echo "<td>" . $row['Date'] . "</td>";
						
						echo '<td>
              <a href="members.php?do=Edit&userId=' . $row['User_ID'] . '" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
              	<a href="members.php?do=Delete&userId=' . $row['User_ID'] . '" class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';
                   if($row['RegStatus']==0 ){
					echo '<a href="members.php?do=Activate&userId=' . $row['User_ID'] . '" class="btn btn-info activate"><i class="fas fa-user-check"></i> Activate</a>';

				   }
					  echo  '</td>';
						echo "</tr>";
					}

					?>


				</table>


			</div>
			<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>

		</div>




	<?php	}else{
					echo'<div class="container">';
						echo'<div  class="nice-alert"> There\'s No Data To Show';

						echo'</div>';
						echo'<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
			
					echo'</div>';
				}




} elseif ($do == 'Add') { ?>



		<h1 class="text-center"> Add New Member</h1>

		<div class="container">
			<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

				<!-- start username field -->
				<div class="mb-3 row">
					<label for="username" class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10 col-md-6 controlStar">
						<input type="text" name="username" id="username" class="form-control" autocomplete="off" required="required" placeholder=" Username To Login Shop." />
					</div>

				</div>
				<!-- End username field -->
				<!-- start password field -->
				<div class="mb-3 row">
					<label class="col-sm-2 control-label">Password</label>
					<div class="col-sm-10 col-md-6 controlStar">
						<input type="password" name="pass" class="password form-control" autocomplete="new-password" placeholder="Must Be Hard nd Complex." required="required" />
						<i class="show-pass far fa-eye"></i>
					</div>

				</div>
				<!-- End password field -->
				<!-- start Email field -->
				<div class="mb-3 row">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10 col-md-6 controlStar">
						<input type="email" name="email" class="form-control" autocomplete="off" required="required" placeholder="Email For Regitration." />
					</div>

				</div>
				<!-- End Email field -->
				<!-- start FullName field -->
				<div class="mb-3 row">
					<label class="col-sm-2 control-label">FullName</label>
					<div class="col-sm-10 col-md-6 controlStar">
						<input type="text" name="fullName" class="form-control" autocomplete="off" placeholder="Full Name Show In Your page Member." required="required"/>
					</div>

				</div>
				<!-- End FullName field -->
				<!-- start Avatar field -->
				<div class="mb-3 row">
					<label class="col-sm-2 control-label" for="avatar">Avatar</label>
					<div class="col-sm-10 col-md-6 ontrolStar">
						<input type="file" name="avatar" class="form-control"  placeholder="Add Your Image Here." required="required"/>
					</div>

				</div>
				<!-- End Avatar field -->
				<!-- start Button field -->
				<div class="my-3 row">

					<!-- <div class="col-sm-offset-2 col-sm-10"> -->
						<div class="col-sm-offset-4 col-sm-8">
						<button type="submit" class="btn btn-primary btn-lg">Add New Member</button>
					</div>

				</div>
				<!-- End Buuton field -->
			</form>

		</div>




		<?php } elseif ($do == 'Insert') {



		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			echo "<h1 class='text-center'> Update Member</h1>";
			echo " <div class='container'>";

			 $avatarName= $_FILES['avatar']['name'];
			 $avatarSize= $_FILES['avatar']['size'];
			 $avatarTmp= $_FILES['avatar']['tmp_name'];
			 $avatarType= $_FILES['avatar']['type'];

			 //List Of Allowed File Type To Upload
			 $avatarAllowedExtention= array("jpeg","jpg","png","gif");

			 //Get Avatar Extention
			 $splitAvatarextention=explode(".",$avatarName);
			 //Must separate the code like so, the explode return aan array and we cant render 
			 //the value direct in an other function. we get err of refference.
			 $avatarExtention=strtolower(end($splitAvatarextention));
			 //Get Variable From The Form

			$username = $_POST['username'];

			$password = $_POST['pass'];

			$email = $_POST['email'];

			$fullName = $_POST['fullName'];
			$hashPassword = sha1($_POST['pass']);

			//password trick
			//	$pass=empty($_POST['newpass'])?$_POST['oldpass']:sha1($_POST['newpass']);
			//validate form
			$formErrors = array();
			if (strlen($username) < 4) {
				$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong> ';
			}

			if (empty($username)) {
				$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
			}
			if (empty($password)) {
				$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
			}
			if (empty($fullName)) {
				$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
			}
			if (empty($email)) {

				$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
			}
			if(empty($avatarName) ){
				$formErrors[] = 'The Avatar Is  <strong>Required</strong>';
			}
			if(!empty($avatarName) && !in_array($avatarExtention,$avatarAllowedExtention)){
				$formErrors[] = 'This Extention Is Not <strong>Allowed</strong>';
			}
			if($avatarSize>4194304){
				$formErrors[] = 'This Size Of The Picture Cant Be Larger Than <strong>4MB</strong>';
			}
			foreach ($formErrors as $error) {
				$theMsg= '<div class="alert alert-danger">' . $error . '</div>';
				redirectHome($theMsg,'back');
			}
			// check if there is no errors , proceed to update operation
			if (empty($formErrors)) {
                  $avatar=rand(0, 100000).'_'.$avatarName;
                 move_uploaded_file($avatarTmp,"uploads\avatars\\".$avatar);
				$checkAlready = checkItem("Username", "users", $username);
				if ($checkAlready == 1) {
					$theMsg= "<div class='alert alert-danger'>Sorry This User Already Existe In DATABASE.</div>";
				    redirectHome($theMsg, 'back');
				} else {
					//Insert user data
					$stmt = $con->prepare("INSERT  into users  (Username,Password,Email,FullName,Avatar,RegStatus, Date) VALUES(?,?,?,?,?,1,NOW())");
					$stmt->execute(array($username, $hashPassword, $email, $fullName,$avatar));
					//Echo succes message
					$theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Record Inserted</div>';
					redirectHome($theMsg,'back');
				}
			}
		} else {
			$theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly</div>";
			redirectHome($theMsg);
		}
	} elseif ($do == 'Edit') { //EDIT PAGE

		// check if the id numeric 
		$userid = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;
		// select the data to update from the db with userid
		$stmt = $con->prepare("SELECT  * FROM  users WHERE User_ID=? ");
		$stmt->execute(array($userid));
		//fetch the data
		$row = $stmt->fetch();
		// row count
		$count = $stmt->rowCount();
		//if rcount gretter than one i show the form with the value of it
		if ($count > 0) {  ?>
			<h1 class="text-center"> Edit Member</h1>

			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="userid" value="<?php echo $userid ?>" />
					<!-- start username field -->
					<div class="mb-3 row">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6 controlStar">
							<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
						</div>

					</div>
					<!-- End username field -->
					<!-- start password field -->
					<div class="mb-3 row">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="hidden" name="oldpass" value="<?php echo $row['Password'] ?>" />
							<input type="password" name="newpass" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
						</div>

					</div>
					<!-- End password field -->
					<!-- start Email field -->
					<div class="mb-3 row">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6 controlStar">
							<input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" autocomplete="off" required="required" />

						</div>

					</div>
					<!-- End Email field -->
					<!-- start FullName field -->
					<div class="mb-3 row">
						<label class="col-sm-2 control-label">FullName</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="fullName" class="form-control" value="<?php echo $row['FullName'] ?>" autocomplete="off" required="required" />
						</div>

					</div><br>
					<!-- End FullName field -->
					<!-- start Avatar field -->
						<div class="mb-3 row">
							<label class="col-sm-2 control-label" for="avatar">Avatar</label>
							<div class="col-sm-10 col-md-6">
								<input type="file" name="avatar" class="form-control"  placeholder="Add Your Image Here."value="<?php echo $row['Avatar'] ?>" />
							</div>

						</div>
				<!-- End Avatar field -->
					<!-- start Button field -->
					<div class="mb-3 row">

						<!-- <div class="col-sm-offset-2 col-sm-10"> -->
						<div class="col-4 offset-md-4">
							<input type="submit" value="Update Member" class="btn btn-primary btn-lg" />
						</div>

					</div>
					<!-- End Buuton field -->
				</form>
			</div>

<?php
		} else {
			$theMsg= "<div class='alert alert-danger'> This No Id Found.</div>";
			redirectHome($theMsg, 'back');
		}
	} elseif ($do == 'Update') {//UPDATE PAGE
		echo "<h1 class='text-center'> Update Member</h1>";
		echo " <div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {


			$avatarName= $_FILES['avatar']['name'];
			echo $avatarName;
			$avatarSize= $_FILES['avatar']['size'];
			$avatarTmp= $_FILES['avatar']['tmp_name'];
			$avatarType= $_FILES['avatar']['type'];

			//List Of Allowed File Type To Upload
			$avatarAllowedExtention= array("jpeg","jpg","png","gif");

			//Get Avatar Extention
			$splitAvatarextention=explode(".",$avatarName);
			//Must separate the code like so, the explode return aan array and we cant render 
			//the value direct in an other function. we get err of refference.
			$avatarExtention=strtolower(end($splitAvatarextention));





			$id = $_POST['userid'];

			$username = $_POST['username'];

			$email = $_POST['email'];

			$fullName = $_POST['fullName'];
			

			//password trick
			$pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);
			//validate form
			$formErrors = array();
			if (strlen($username) < 4) {
				$formErrors[] = ' <div class="alert alert-danger">Username Cant Be Less Than <strong>4 Characters</strong> </div>';
			}

			if (empty($username)) {
				$formErrors[] = '<div class="alert alert-danger">Username Cant Be <strong>Empty</strong></div>';
			}
			if (empty($fullName)) {
				$formErrors[] = '<div class="alert alert-danger">Full Name Cant Be <strong>Empty</strong></div>';
			}
			if (empty($email)) {

				$formErrors[] = '<div class="alert alert-danger">Email Cant Be <strong>Empty</strong></div>';
			}
			
			if(!empty($avatarName) && !in_array($avatarExtention,$avatarAllowedExtention)){
				$formErrors[] = 'This Extention Is Not <strong>Allowed</strong>';
			}
			if($avatarSize>4194304){
				$formErrors[] = 'This Size Of The Picture Cant Be Larger Than <strong>4MB</strong>';
			}
			foreach ($formErrors as $error) {
				echo $error;
			}
			// check if there is no errors , proceed to update operation
			if (empty($formErrors)) {
				$avatar=rand(0, 100000).'_'.$avatarName;
				move_uploaded_file($avatarTmp,"uploads\avatars\\".$avatar);
				$stmt2 = $con->prepare(" SELECT *
				           				FROM users 
										WHERE Username = ? 
										AND User_ID != ? ");
				$stmt2->execute(array($username,$id));
				$count= $stmt2->rowCount();
				// return $count;
				if($count >=1){
					echo'<div class="container">';
						$theMsg ='<div  class="alert alert-danger"> Sorry This User Name Member Already Exist!!</div>';
						redirectHome($theMsg, 'back');
					echo'</div>';
					
				}else{
				
				
				$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Avatar=?, Password=?  WHERE User_ID = ? ");
				$stmt->execute(array($username, $email, $fullName,$avatar, $pass, $id));
				//Echo succes message
				$theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Record Updated</div>';
				redirectHome($theMsg, 'back');
			    }
			}
		} else {
			$theMsg= '<div class="alert alert-danger"> Sorry you cant Browse this page Directly</div>';
			redirectHome($theMsg);
		}
		echo "</div>";
	} elseif ($do == 'Delete') {
		//Delete user
		echo '<h1 class="text-center">Delete Member</h1>';
		echo '<div class="container">';
		$userid = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;
		$check=checkItem('User_ID', 'users', $userid);
		
		//if count gretter than 0 i show the form with the value of it
		if ($check > 0) {
			$stmt = $con->prepare("DELETE  FROM  users WHERE User_ID=? ");
			$stmt->execute(array($userid));
			$theMsg= '<div class="alert alert-danger">' . $stmt->rowCount() . ' Record Deleted</div>';
			redirectHome($theMsg,'back');
		} else {
			$theMsg= "<div class='alert alert-danger'> This No Id Found.</div>";
			redirectHome($theMsg);
		}
		echo '</div>';
	}elseif ($do=='Activate'){
		//Activate user
		echo '<h1 class="text-center">Activate Member</h1>';
		echo '<div class="container">';
		// Check if Get Request userId is Numeric & Get The Integer Value Of It
		$userid = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;
		//Select All Data Depand This Id
		$check=checkItem('User_ID', 'users', $userid);

		//if rcount gretter than one i show the form with the value of it
		if ($check > 0) {
			$stmt = $con->prepare("UPDATE users SET RegStatus = 1  WHERE User_ID=? ");
			$stmt->execute(array($userid));
			$theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Activated</div>';
			redirectHome($theMsg,'back');
		} else {
			$theMsg= "<div class='alert alert-danger'> This No Id Found.</div>";
			redirectHome($theMsg);
		}
		echo '</div>';
	}
	include $tmpl . 'footer.php';
} else {
	header('Location: index.php');
	exit();
}
