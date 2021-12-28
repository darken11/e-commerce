<?php
    ob_start();
    session_start();
    $pageTitle = 'Show Item';
    include 'init.php';
  
      // check if Get Request Item is Numeric & Get It's Integer Value 
		$itemid = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 'No Such Id Found In DB';
         
		// Select the data to update from the db with item id
		$stmt = $con->prepare("SELECT items.*, categories.Name As Category_Name, users.Username AS Username 
                              FROM  items
                              INNER JOIN categories ON Cat_ID = item_Cat_Id 
                              INNER JOIN users ON User_ID = item_member_Id 
                              WHERE Item_ID=?  AND Approve = 1");

		$stmt->execute(array($itemid));
        $count=$stmt->rowCount();
        if($count>0){

		//fetch the data
		$itemRow = $stmt->fetch();
        ?>
    

       <h1 class="text-center"><?php echo $itemRow['Name']?></h1>
       

			<div class="container ">
                <div class="row">
                    <div class="col-md-3">
                         <img class="img-fluid img-thumbnail shadow-lg" src="./layout/images/no-avatar-300x300.png" alt="card image cap"/>
                    </div>
                    <div class="col-md-9 showItem">
                        <h2><?php echo $itemRow['Name']?></h2>
                        <p><?php echo $itemRow['Description']?></p>
                        <ul class="list-unstyled">
                            
                                <li>
                                    <i class="fa fa-calendar fa-fw"></i>
                                    <span>Add Date  : </span><?php echo $itemRow['Add_Date']?>
                                </li>
                                <li>
                                    <i class="fas fa-dollar-sign fa-fw"></i>
                                    <span> Price    : </span><?php echo $itemRow['Price']?>
                                </li>
                                <li>
                                    <i class="far fa-building fa-fw"></i>
                                    <span> Country  : </span><?php echo $itemRow['Country_Made']?>
                                </li>
                                <li>
                                    <i class="fa fa-tag fa-fw"></i>
                                    <span> Category : </span><a href="categories.php?pageId=<?php echo $itemRow['item_Cat_Id'] ;?>"><?php echo $itemRow['Category_Name']?></a>
                                </li>
                                <li> 
                                    <i class="fas fa-user fa-fw"></i>
                                    <span> Username : </span><a href="#"><?php echo $itemRow['Username']?></a>
                                </li>
                                <li> 
                                <i class="fas fa-hashtag"></i>
                                    <span> Tags : </span>
                                        <?php 

                                        $allTags=explode(",",$itemRow['Tags']);
                                        foreach($allTags as $tag){
                                            $tag=str_replace(' ','',$tag);
                                            $tag = strtolower($tag);
                                                if(!empty($tag)){
                                                echo '<a  class="tags" href="tags.php?name='.$tag.'">'.$tag.'</a>';
                                                }
                                        }
                                        ?>
                                </li>
                        </ul>
                    </div>
                </div>
                <hr class="custom-hr">
                <?php if(isset($_SESSION['user'])){ 
                    ?>
                <!-- Start Add New Comment -->
                <div class="row">
                    <div class="col-md-3 offset-md-3">
                            <div class="add-comment">
                            <h2>New comment</h2>
                            <form action="<?php echo $_SERVER['PHP_SELF'] .'?itemId='.$itemRow['Item_ID']?>" method="POST">
                                <textarea name="comment" id="comment"  cols="70" rows="6" required></textarea>
                                <input type="submit" value="Add Comment">
                            </form>
                            <?php if($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $formErros=array();
                                $comment= $_POST['comment'];
                                $comment =filter_var($comment,FILTER_SANITIZE_STRING);
                                if(empty($comment)){
                                    $formErros="The Comments Area Must Not Be <strong>Empty</strong>";
                                  
                                } 
                                if($comment < 10){
                                    $formErros="The Comments  Must Contain At Least  <strong>10 Chars</strong>";
                                  
                                }  
                               
                                if(empty($formErros)){
                                $stmComment= $con->prepare("INSERT into comments (Comment, Status, Comment_Date,com_Item_Id, com_mem_Id)
                                                           VALUE(?,0,now(),?,?)");
                                 $stmComment->execute(array($comment,$itemRow['Item_ID'],$_SESSION['userId']));

                                 if( $stmComment){
                                 echo '<div class="alert alert-success">Your Comment is Add.</div>'; 
                                  }                  
                                }                        
                             }?>
                        </div>
                    </div>
                </div>
              <?php }else{
                echo '<a href="login.php">Login</a> Or <a href="login.php" >Registred</a> To Add Comment';
              }
              
              ?>
                <!-- End Add New Comment  -->
                <hr class="custom-hr">
                <?php 
                           
                           
                                    
                           // Select the data to update from the db with item id
                           $stmt = $con->prepare("SELECT comments.*, users.Username AS Username 
                                               FROM  comments
                                               INNER JOIN users ON users.User_ID = comments.com_mem_Id 
                                               WHERE com_Item_Id = ?
                                               AND   Status=1 
                                               ORDER BY Comment_ID DESC ");

                           $stmt->execute(array($itemRow ['Item_ID']));

                           //fetch the data
                           $comments = $stmt->fetchAll();
                           ?>
                          <?php foreach ($comments as $comment) {?>
                                <div class="show-comment">
                                        <div class="row">
                                                        
                                                <div class="col-sm-2  text-center">
                                                                <img  src="./layout/images/no-avatar-300x300.png" class="img-thumbnail img-circle mx-auto d-block" alt="card image cap"/>
                                                                <?php echo $comment['Username']; ?>
                                                </div>
                                                <div class="col-sm-10">
                                                        <div class="show-comment">    
                                                                <p class="lead"><?php echo $comment['Comment'];?><p>
                                                                <span class="date"><?php echo $comment['Comment_Date'];?><span>
                                                        </div>
                                                </div>
                                        </div>
                                     <hr class="custom-hr">

                                </div>

                                <?php    }?>
                        </div>
                </div>
            </div>
<?php
    }else{   echo '<div class="container">';
            echo "<div class='alert alert-danger text-center my-5 '>There's No Such ID Or This Item Waiting For Be Approve.</div>";
            echo '</div>';
    }
    include $tmpl . 'footer.php';
    ob_end_flush();
?>