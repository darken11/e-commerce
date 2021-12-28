<?php
/*
============================================================
==   Comment Page
==   YOU Cabn Edit | Update | Approve
============================================================
*/
ob_start(); //Output Buffering Start
session_start();
$pageTitle="Comment";
if(isset($_SESSION['Username'])){
      include 'init.php';
      $do=isset($_GET['do'])?$_GET['do']: 'Manage';


    if($do == 'Manage'){

                    
        $stmt = $con->prepare(" SELECT comments.*, items.Name As Item_Name, users.Username AS Member_Name
                                FROM comments
                                INNER JOIN items ON items.Item_ID = comments.com_Item_Id
                                INNER JOIN users On users.User_ID = comments.com_mem_Id
                                ORDER BY comments.Comment_ID DESC");
        $stmt->execute();
        $comments = $stmt->fetchAll();
        if(!empty($comments)){
    ?>
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">

                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Comment Date</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Controle</td>
                    </tr>
                <?php
                    foreach ($comments as $comment) {

                        echo "<tr>";
                        echo "<td>" . $comment['Comment_ID'] . "</td>";
                        echo "<td>" . $comment['Comment'] . "</td>";
                        echo "<td>" . $comment['Comment_Date'] . "</td>";
                        echo "<td>" . $comment['Item_Name'] . "</td>";
                        echo "<td>" . $comment['Member_Name'] . "</td>";

                            
                        echo '<td>
                            <a href="comments.php?do=Edit&commentId=' . $comment['Comment_ID'] . '" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                            <a href="comments.php?do=Delete&commentId=' . $comment['Comment_ID'] . '" class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';
                            if($comment['Status'] == 0 ){
                        echo '<a href="comments.php?do=Approve&commentId=' . $comment['Comment_ID'] . '"
                            class="btn btn-success activate">
                            <i class="fas fa-check"></i> Approve</a>';

                        }
                        echo  '</td>';
                        echo "</tr>";
                        }

                ?>


                </table>


             </div>

        </div>
    <?php
        }
        else{
            echo'<div class="container">';
                echo'<div  class="nice-alert"> There\'s No Data To Show';

                echo'</div>';
            echo'</div>';
        }


    }
  
    elseif($do == 'Edit'){
          
          // check if the id numeric 

		$commentid = isset($_GET['commentId']) && is_numeric($_GET['commentId']) ? intval($_GET['commentId']) : 0;
		// select the data to update from the db with commentId
		$stmt = $con->prepare("SELECT * FROM  comments WHERE Comment_ID=? ");
		$stmt->execute(array($commentid));
		//fetch the data
		$comment = $stmt->fetch();
		// row count
		$count = $stmt->rowCount();
		//if rcount gretter than one i show the form with the value of it
		if ($count > 0) {  ?>
			<h1 class="text-center"> Edit Comment</h1>

			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="commentId" value="<?php echo $commentid ?>" />
					<!-- start Comment field -->
					<div class="mb-3 row">
						<label for="comment" class="col-sm-2 control-label">Comment</label>
						<div class="col-sm-10 col-md-6">
							<textarea  name="comment" id="comment" class="form-control"><?php echo $comment['Comment'] ?></textarea> 
						</div>
					</div>
                <!-- End Comment field -->
					<!-- start Button field -->
					<div class="mb-3">

						<!-- <div class="col-sm-offset-2 col-sm-10"> -->
						<div class="col-sm-offset-4 col-sm-8">
							<input type="submit" value="Save Item" class="btn btn-primary btn-lg" />
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

        echo '</div>';

    }
    elseif($do == 'Update'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo "<h1 class='text-center'> Update Comment</h1>";
                    echo " <div class='container'>";
            
                    $id = $_POST['commentId'];
                    $comment = $_POST['comment'];
    
     
                    //Insert new item data
                    $stmt = $con->prepare("UPDATE comments SET Comment = ?
                                          WHERE Comment_ID = ?");
                    $stmt->execute(array($comment,$id));
                   
                    //Echo succes message
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Comment Updated</div>';
                    redirectHome($theMsg, 'back');
                
            
        
        } else {
            $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly</div>";
            redirectHome($theMsg);
        } 
        echo'</div>';  
    
    }
    elseif($do == 'Delete'){
          
           /*
           ** Delete Comment
           */

		echo '<h1 class="text-center">Delete Comment</h1>';
		echo '<div class="container">';
		$commentid = isset($_GET['commentId']) && is_numeric($_GET['commentId']) ? intval($_GET['commentId']) : 0;
		$check=checkItem('Comment_ID', 'comments', $commentid);
		
		/*
        **
        ** If count gretter than 0 i show the form with the value of it
        */
		if ($check > 0) {
			$stmt = $con->prepare("DELETE  FROM  comments WHERE Comment_ID=? ");
			$stmt->execute(array($commentid));
			$theMsg= '<div class="alert alert-danger">' . $stmt->rowCount() . ' Comment Deleted</div>';
            redirectHome($theMsg,'back');
		} else {
			$theMsg= "<div class='alert alert-danger'> This Id Not Found.</div>";
			redirectHome($theMsg);
		}
		echo '</div>';
    }
    elseif($do == 'Approve'){
         /*Approve  Item*/
		echo '<h1 class="text-center">Approve Comment</h1>';
		echo '<div class="container">';

		/* Check if Get Request Commentid is Numeric & Get The Integer Value Of It*/

        $commentid = isset($_GET['commentId']) && is_numeric($_GET['commentId']) ? intval($_GET['commentId']) : 0;

		/*Select All Data Depand This commentId*/
		$check=checkItem('Comment_ID', 'comments', $commentid);

		/*if count gretter than 0 i show the form with the value of it*/

		if ($check > 0) {
			$stmt = $con->prepare("UPDATE comments SET Status = 1  WHERE Comment_ID=? ");
			$stmt->execute(array($commentid));
			$theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' Comment Approved</div>';
			redirectHome($theMsg,'back');
		} else {
			$theMsg= "<div class='alert alert-danger'> This Id Not Found.</div>";
			redirectHome($theMsg);
		}
		echo '</div>'; 
    }
    include $tmpl.'footer.php';
}else{

    header('Location: index.php');
    exit();
}
ob_end_flush();//Release the Output
?>