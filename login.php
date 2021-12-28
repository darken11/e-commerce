<?php 
ob_start(); //Output Buffering Start
session_start();
$pageTitle='Login';

if (isset($_SESSION['user'])) {
	header('Location:index.php'); //redirect to Home page
}
include 'init.php' ?>

<?php

//check if user coming from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if(isset($_POST['login'])){
                  $user  = $_POST['username'];
                  $pass  = $_POST['password'];

                  $hashedPass = sha1($pass);
                  //check if the user exist in db
                  $stmt = $con->prepare("SELECT 
                                                User_ID,Username, Password 
                                          FROM 
                                                users 
                                          WHERE 
                                                Username=? 
                                          AND 
                                                Password=? 
                                          ");
                  $stmt->execute(array($user, $hashedPass));
                  $getUser=$stmt->fetch();
                  $count = $stmt->rowCount();

                  //if count >0 this mean the database contain record about this username
                  if ($count > 0) {
                        $_SESSION['user'] = $user; //Register Session Name

                        $_SESSION['userId'] = $getUser['User_ID']; //Register  Id in Session


                        header('Location:index.php');
                        exit();
                  }
            
          } else {
                   $formErrors = array();
                 

			$username=$_POST['username'];
			$password=$_POST['password'];
			$password2=$_POST['password2'];
			$email=$_POST['email'];

                  if (isset($username)) {
				$filterUsername=filter_var($username,FILTER_SANITIZE_STRING);

                        if (strlen($filterUsername) < 4) {
                              $formErrors [] = 'Username Can\'t Be Less Than <strong>4 Characters</strong> ';
                        }
      
                        
			}
			if (isset($password) && isset($password2)) {
                        if(empty($password)){

                              $formErrors [] = 'Your Passwords Must Not Be <strong>Empty!</strong> ';
                        }
                              $passwordHashed  = sha1($password);
                              $passwordHashed2 = sha1($password2);
                              
                         if($passwordHashed !== $passwordHashed2){
                              $formErrors [] = 'Your Passwords are Not <strong>Match!</strong> ';
                         }
			}
                  if (isset($email)) {
                               $filterEmail=filter_var($email,FILTER_SANITIZE_EMAIL);

                        if (filter_var($email,FILTER_VALIDATE_EMAIL) != true) {

                              $formErrors [] = 'This Email Is Not <strong>Valid</strong> ';
                        }
                  }
                  
                      /*  
                      
                      ** for the future validation if iw ould to get complex pass
                      
                      
                      if( strlen($password ) < 8 ) {
                              $formErrors []= "Password Too Short!";
                        }
                        if( strlen($password ) > 20 ) {
                              $formErrors []= "Password Too Long!";
                        }
                        if (!preg_match("#[0-9]+#",$password)) {
                              $formErrors [] = 'Password Must Include At Least <strong>One Number!</strong>';
                        }
                        if (!preg_match("#[a-z]+#",$password)) {
                              $formErrors [] = 'Password Must Include At Least <strong>One letter!</strong>';
                        }
                        if (!preg_match("#[A-Z]+#",$password)) {
                              $formErrors [] = 'Password Must Include At Least <strong>One CAPS!</strong>';
                        }
                        if (!preg_match("@[^\w]@",$password)) {
                              $formErrors [] = 'Password Must Include At Least <strong>One Symbol!</strong>';
                        }
                        */
                  
		
                  
			// check if there is no errors , proceed to update operation
                  if (empty($formErrors)) {


				$checkAlready = checkItem("Username", "users", $username); 

				if ($checkAlready == 1) {
					$formErrors []= "Sorry This User Already Existe In DATABASE.";
				} else {
					//Insert user data
					$stmt = $con->prepare("INSERT  into users  (Username,Password,Email,RegStatus, Date) VALUES(?,?,?,0,NOW())");
					$stmt->execute(array($username, $passwordHashed, $email ));
					//Echo succes message
					$succesMsg= 'Nice, You Are Registred User';
					
				}
			}

            }
      }
 
?>
    <div class="container page-login">
            <h1 class="text-center">
                <span class="selected" data-class="login" >Login</span> | <span  data-class="signup">Sign Up</span>
            </h1>
            <!-- Start Login Form -->
            <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            
					<div class="controlStar">
                             <input type="text" class="form-control mb-3" name="username" id="username" placeholder="Your Login Please" autocomplete="off" required>
                    </div>
                    <div  class="controlStar">
                             <input type="password" class="form-control mb-3" name="password" id="password" placeholder="Your Password Please" autocomplete="new-password" required>
                    </div>
                             <input type="submit" class="btn btn-primary col-12 mx-auto mb-3"  name="login" value="Login">

            </form>
            <!-- End Login Form -->

            <!-- Start Signup Form -->

            <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                     <div class="controlStar">
                            <input type="text" class="form-control mb-3" name="username" 
                            pattern=".{4,}"
                            title="Your User name must Be 4 Chars."
                            id="username" placeholder="Type Your Login." autocomplete="off" >
                     </div>
                    <div class="controlStar"> <input type="password" class="password form-control mb-3" 
                      name="password"
                      id="password" 
                      minlength="6"
                      placeholder="Type A Complex Password."
                      autocomplete="new-password"  required >
						<i class="show-pass far fa-eye"></i>
                    </div>

                    <div class="controlStar"> <input type="password" class="password form-control mb-3"
                     name="password2"
                     id="password2"
                     minlength="6"
                     placeholder="Type Your Password Again." autocomplete="new-password" required  >
						<i class="show-pass far fa-eye"></i>
                    </div>
                    
                    <div class="controlStar"><input type="email" class="form-control mb-3" name="email"   required id="email" placeholder="Type Your Valid Email."  >
                    </div>
                        <input type="submit" class="btn btn-success col-12 mx-auto mb-3" value="Sign up">

            </form>
            <!-- End Signup Form -->
            <div class="the-errors">
            <?php if (!empty($formErrors)) {

                  foreach ($formErrors as $error) {
                        echo "<p class='error msg text-center'>" . $error . "</p>";
                  }
           }
           if(isset($succesMsg)){
                echo' <div class="msg succes">'
                    .$succesMsg.
                ' </div>';
           }
            ?>
            </div>


    </div>

<?php include $tmpl.'footer.php' ;

ob_end_flush();//Release the Output
?>
