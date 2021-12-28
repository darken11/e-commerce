<?php
/*
============================================================
==   Items Page
============================================================
*/
ob_start(); //Output Buffering Start
session_start();
$pageTitle="Items";
if(isset($_SESSION['Username'])){
      include 'init.php';
      $do=isset($_GET['do'])? $_GET['do']: 'Manage';

    if($do == 'Manage'){
       
		$stmt = $con->prepare("SELECT items.*, categories.Name As Category_Name, users.Username AS Member_Name
                               FROM items
                               INNER JOIN categories ON categories.Cat_ID = items.item_Cat_Id
                               INNER JOIN users On users.User_ID = items.item_member_Id 
                               ORDER BY items.Item_ID DESC");
		$stmt->execute();
		$items = $stmt->fetchAll();
        if(! empty($items)){

    ?>
		<h1 class="text-center">Manage Items</h1>
		<div class="container">
			<div class="table-responsive">

				<table class="main-table text-center table table-bordered">
					<tr>
						<td>#ID</td>
						<td>Name</td>
						<td>Description</td>
						<td>Price</td>
						<td>Country</td>
						<td>Add Date</td>
                        <td>Category Name</td>
                        <td>Member Name</td>
                        <td>Tags</td>


						<td>Controle</td>
					</tr>
					<?php
					foreach ($items as $rowItem) {

						echo "<tr>";
						echo "<td>" . $rowItem['Item_ID'] . "</td>";
						echo "<td>" . $rowItem['Name'] . "</td>";
						echo "<td>" . $rowItem['Description'] . "</td>";
						echo "<td>" . $rowItem['Price'] . "</td>";
						echo "<td>" . $rowItem['Country_Made'] . "</td>";
						echo "<td>" . $rowItem['Add_Date'] . "</td>";
						echo "<td>" . $rowItem['Category_Name'] . "</td>";
                        echo "<td>" . $rowItem['Member_Name'] . "</td>";
                        echo "<td>" . $rowItem['Tags'] . "</td>";

						
						echo '<td>
                            <a href="items.php?do=Edit&itemId=' . $rowItem['Item_ID'] . '" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                                <a href="items.php?do=Delete&itemId=' . $rowItem['Item_ID'] . '" class="btn btn-danger confirm"><i class="far fa-times-circle"></i> Delete</a>';
                         if($rowItem['Approve'] == 0 ){
					    echo '<a href="items.php?do=Approve&itemId=' . $rowItem['Item_ID'] . '"
                             class="btn btn-success activate">
                             <i class="fas fa-check"></i> Approve</a>';

				             }
					     echo  '</td>';
					    echo "</tr>";
                            }
					?>


				</table>


			</div>
			<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Items</a>

		</div>
    <?php   
       	}else{
               echo'<div class="container">';
                   echo'<div  class="nice-alert"> There\'s No Data To Show';
   
                   echo'</div>';
                   echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Items</a>';
               echo'</div>';
           }
        
        
    }
    elseif($do == 'Add'){?>
        <h1 class="text-center"> Add New Item</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">

                <!-- start Name field -->
                <div class=" my-3 row">
                    <label for="name" class="col-sm-2 control-label">Name Item</label>
                    <div class="col-sm-10 col-md-6 controlStar" >
                        <input type="text" name="name" id="name" class="form-control" autocomplete="off" placeholder=" Name Of Item." required="required"  />
                    </div>

                </div>
                <!-- End Name field -->
                <!-- start Description field -->
                <div class="mb-3 row">
                    <label for="description"  class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6 controlStar">
                        <textarea type="text" name="description" id="description" class="form-control"  placeholder="Descrip The Item." required="required"  ></textarea>
                        
                    </div>

                </div>
                <!-- End Description field -->
                <!-- start Description field -->
                <div class="mb-3 row">
                    <label for="price"  class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6 controlStar">
                        <input type="text" name="price" id="price" class="form-control"  placeholder="Price Of Item."  required="required"/>
                        
                    </div>

                </div>
                <!-- End Description field -->
                <!-- start Country field -->
                <div class="mb-3 row">
                    <label for="country"  class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6 controlStar">
                        <input type="text" name="country" id="country" class="form-control"  placeholder="Country Of Item." required="required" />
                        
                    </div>

                </div>
                <!-- End Country field -->
                <!-- Start Status -->
                <div class="mb-3 row">
                    <label for="status"  class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status" >
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Used</option>
                        </select>
                    </div>

                </div>
                <!-- End Status -->
                 <!-- Start Members -->
                 <div class="mb-3 row">
                    <label for="member"  class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                            
                               $stmMember=getAllFrom("*","users", "", "","User_ID");
                              
                              foreach($stmMember as $user){
                                  echo '<option value="'.$user['User_ID'].'">' .$user['Username']. '</option>';
                              }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members -->
                 <!-- Start Category -->
                 <div class="mb-3 row">
                    <label for="category"  class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                            $stmtCat= getAllFrom("*","categories", "WHERE Parent = 0", "","Cat_ID");
                              
                              foreach($stmtCat as $cat){
                                  echo '<option value="'.$cat['Cat_ID'].'">' .$cat['Name']. '</option>';
                                        $stmtChildCat= getAllFrom("*","categories", " WHERE Parent={$cat['Cat_ID']}", "","Cat_ID");
                                    
                                        foreach($stmtChildCat as $c){
                                            echo '<option value="'.$c['Cat_ID'].'">' .' ---> '.$c['Name']. '</option>';
                                            
                                        }

                              }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category -->
                <!-- Start Tags Field -->
                <div class="mb-3 row">
                    <label for="tags" class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">

                    <input type="text" name="tags" id="tags" class="form-control" placeholder="Separete Your Tags With Coma (,)">
                    </div>
                </div>
                <!-- End Tags Field -->
                <!-- start Button field -->
                <div class="mt-3 ">

                    <div class="col-sm-8 offset-sm-4 ">
                        <button type="submit" class="btn btn-primary btn-lg mx-auto"><i class="fa fa-plus"></i> Add New Item</button>
                    </div>

                </div>
                <!-- End Buuton field -->
            </form>

        </div>
<?php    }
  elseif($do == 'Insert'){

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        echo "<h1 class='text-center'> Insert Items</h1>";
        echo " <div class='container'>";


        $name = $_POST['name'];

        $desc = $_POST['description'];
        

        $price = $_POST['price'];

        $country = $_POST['country'];

        $status=$_POST['status'];

        $member=$_POST['member'];

        $category=$_POST['category'];

        $tags= $_POST['tags'];

      
        //validate form
        $formErrors = array();
      

        if (empty($name)) {
            $formErrors[] = 'Name Cant Be <strong>Empty</strong>';
        }
        if (empty($desc)) {
            $formErrors[] = 'Description Cant Be <strong>Empty</strong>';
        }
        if (empty($price)) {
            $formErrors[] = 'Price Cant Be <strong>Empty</strong>';
        }
        if (empty($country)) {

            $formErrors[] = 'Country Cant Be <strong>Empty</strong>';
        }
        if ($status == 0) {

            $formErrors[] = 'You Must Choose The <strong>Status</strong>';
        }
        if ($member == 0) {

            $formErrors[] = 'You Must Choose The <strong>Member</strong>';
        }
        if ($category == 0) {

            $formErrors[] = 'You Must Choose The <strong>Category</strong>';
        }
        foreach ($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        // check if there is no errors , proceed to update operation
        if (empty($formErrors)) {


          
                //Insert new item data
                $stmt = $con->prepare("INSERT  into items  (Name,Description,Price,Country_Made,StatusItem, Add_Date,item_Cat_Id,item_member_Id,Tags) 
                                       VALUES(?,?,?,?,?,NOW(),?,?,?)");
                $stmt->execute(array($name, $desc, $price, $country,$status,$category,$member,$tags));
                //Echo succes message
                $theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Record Inserted</div>';
                redirectHome($theMsg,'back');
            }
        
    
    } else {
        $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly</div>";
        redirectHome($theMsg);
    }
    echo '</div>';
    }
    elseif($do == 'Edit'){
        
        // check if the id numeric 
		$itemid = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;
		// select the data to update from the db with item id
		$stmt = $con->prepare("SELECT * FROM  items WHERE Item_ID=? ");
		$stmt->execute(array($itemid));
		//fetch the data
		$itemRow = $stmt->fetch();
     
		// row count
		$count = $stmt->rowCount();
		//if rcount gretter than one i show the form with the value of it
		if ($count > 0) {  ?>
			<h1 class="text-center"> Edit Item</h1>

			<div class="container item-edit">
				<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
					<!-- start Name field -->
					<div class="mb-3 row">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6 controlStar">
							<input type="text" name="name" id="name" class="form-control" value="<?php echo $itemRow['Name'] ?>" autocomplete="off" required="required" />
						</div>

					</div>
					<!-- End Name field -->
					<!-- start Description field -->
					<div class="mb-3 row">
						<label for="description" class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6 controlStar">
							<input type="text" name="description" class="form-control" value="<?php echo $itemRow['Description'] ;?>"  autocomplete="off" required="required" />
						</div>

					</div>
					<!-- End Description field -->
					<!-- start Price field -->
					<div class="mb-3 row">
						<label for="price" class="col-sm-2 control-label">Price</label>
						<div class="col-sm-10 col-md-6 controlStar">
							<input type="text" name="price" class="form-control"  id="price" value="<?php echo $itemRow['Price']; ?>" autocomplete="off" required="required" />

						</div>

					</div>
					<!-- End Price field -->
                    <!-- start Country field -->
					<div class="mb-3 row">
						<label for="country" class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10 col-md-6 controlStar">
							<input type="text" name="country" class="form-control"  id="country" value="<?php echo $itemRow['Country_Made']; ?>" autocomplete="off" required="required" />

						</div>

					</div>
					<!-- End Country field -->
					
                    <!-- Start Status -->
                    <div class="mb-3 row">
                    <label for="status"  class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                    <select name="status">
                            <option value="0">...</option>
                            <option value="1" <?php if($itemRow['StatusItem']==1) {echo'selected';}?>>New</option>
                            <option value="2" <?php if($itemRow['StatusItem']==2) {echo'selected';}?>>Like New</option>
                            <option value="3" <?php if($itemRow['StatusItem']==3) {echo'selected';}?>>Used</option>
                            <option value="4" <?php if($itemRow['StatusItem']==4) {echo'selected';}?>>Very Used</option>
                        </select>
                    </div>

                </div>
                <!-- End Status -->
                  <!-- Start Members -->
                  <div class="mb-3 row">
                    <label for="member"  class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                        <option value="0">...</option>
                            <?php
                              $users=getAllFrom("*","users", "", "","User_ID");
                              
                              foreach($users as $user){
                                  echo '<option value="'.$user['User_ID'].'"';
                                  if($itemRow['item_member_Id']==$user['User_ID']) {echo'selected';}
                                  echo '>'.$user['Username']. '</option>';
                              }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members -->
                 <!-- Start Category -->
                 <div class="mb-3 row">
                    <label for="category"  class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            
                        <option value="0">...</option>
                            <?php

                              $categories=getAllFrom("*","categories", "WHERE Parent = 0", "","Cat_ID");
                              foreach($categories as $cat){
                                  echo '<option value="'.$cat['Cat_ID'].'"';
                                  if($itemRow['item_Cat_Id']==$cat['Cat_ID']) {echo'selected';}
                                  echo '>' .$cat['Name']. '</option>';

                                  $subCategories=getAllFrom("*","categories", "WHERE Parent = {$cat['Cat_ID']}", "","Cat_ID");
                                  foreach($subCategories as $c){
                                      echo '<option value="'.$c['Cat_ID'].'"';
                                      if($itemRow['item_Cat_Id'] == $c['Cat_ID']) {echo'selected';}
                                      echo '>' .'---> ' .$c['Name']. '</option>';
                                      
                                  
                                  }
                              }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category -->
                <!-- Start Tags Field -->
                 <div class="mb-3 row">
                     <label for="tags" class="col-sm-2 control-label">Tags</label>
                     <div class="col-sm-10 col-md-6">
                         <input type="text" name="tags" id="tags" class="form-control" placeholder="Separate Your Tags With Coma ( , )" 
                         value="<?php echo $itemRow['Tags']; ?>">
                     </div>
                 </div>
                <!-- End Tags Field -->
					<!-- start Button field -->
					<div class="mb-3">

						<!-- <div class="col-sm-offset-2 col-sm-10"> -->
						<div class="col-sm-8 offset-sm-4">
							<input type="submit" value="Save Item" class="btn btn-primary btn-lg" />
						</div>

					</div>
					<!-- End Buuton field -->
				</form>
                <!--  -->
                <?php
                $stmt = $con->prepare(" SELECT comments.*,  users.Username AS Member_Name
                                FROM comments
                                INNER JOIN users On users.User_ID = comments.com_mem_Id 
                                WHERE comments.com_Item_Id= ?");
        $stmt->execute(array($itemid));
        $comments = $stmt->fetchAll();
        if(!empty($comments)){

    ?>
        <h1 class="text-center">Manage [ <?php echo $itemRow['Name'];?> ] Comments</h1>
        
            <div class="table-responsive">

                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>Comment</td>
                        <td>Comment Date</td>
                        <td>User Name</td>
                        <td>Controle</td>
                    </tr>
                <?php
                    foreach ($comments as $comment) {

                        echo "<tr>";
                        echo "<td>" . $comment['Comment'] . "</td>";
                        echo "<td>" . $comment['Comment_Date'] . "</td>";
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
                    
    
<?php
           }
           else{
            echo'<div class="container">';
                echo'<div  class="nice-alert"> There\'s No Data To Show';

                echo'</div>';
            echo'</div>';
            }
           
		} else {
			$theMsg= "<div class='alert alert-danger'> This No Id Found.</div>";
			redirectHome($theMsg, 'back');
		}

        echo '</div>';

    }
    elseif($do == 'Update'){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'> Update Items</h1>";
            echo " <div class='container'>";
    
            $id = $_POST['itemid'];
            $name = $_POST['name'];
    
            $desc = $_POST['description'];
    
            $price = $_POST['price'];
    
            $country = $_POST['country'];
    
            $status=$_POST['status'];
    
            $member=$_POST['member'];
    
            $category=$_POST['category'];

            $tags=$_POST['tags'];
    
          
            //validate form
            $formErrors = array();
          
    
            if (empty($name)) {
                $formErrors[] = 'Name Cant Be <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Description Cant Be <strong>Empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price Cant Be <strong>Empty</strong>';
            }
            if (empty($country)) {
    
                $formErrors[] = 'Country Cant Be <strong>Empty</strong>';
            }
            if ($status == 0) {
    
                $formErrors[] = 'You Must Choose The <strong>Status</strong>';
            }
            if ($member == 0) {
    
                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }
            if ($category == 0) {
    
                $formErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // check if there is no errors , proceed to update operation
            if (empty($formErrors)) {
    
    
              
                    //Insert new item data
                    $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, StatusItem = ?, item_Cat_Id = ?, item_member_Id = ?, Tags = ? 
                                          WHERE Item_ID = ?");
                    $stmt->execute(array($name, $desc, $price, $country,$status,$category,$member,$tags,$id));
                   
                    //Echo succes message
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Item Updated</div>';
                    redirectHome($theMsg, 'back');
                }
            
        
        } else {
            $theMsg = "<div class='alert alert-danger'>Sorry You Cant Browse This Page Directly</div>";
            redirectHome($theMsg);
        } 
        echo'</div>';  
    }
    elseif($do == 'Delete'){
          

           /*
           ** Delete Item
           */

		echo '<h1 class="text-center">Delete Item</h1>';
		echo '<div class="container">';
		$itemid = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;
        // $check=getAllFrom('Item_ID','items', 'WHERE Item_ID = $itemid', '','Item_ID');
     
		$check=checkItem('Item_ID', 'items', $itemid);
		
		//if count gretter than 0 i show the form with the value of it
		if ($check > 0) {
			$stmt = $con->prepare("DELETE  FROM  items WHERE Item_ID=? ");
			$stmt->execute(array($itemid));
			$theMsg= '<div class="alert alert-danger">' . $stmt->rowCount() . ' Item Deleted</div>';
            redirectHome($theMsg,'back');
		} else {
			$theMsg= "<div class='alert alert-danger'> This Id Not Found.</div>";
			redirectHome($theMsg);
		}
		echo '</div>';

    }
    elseif ($do=='Approve'){

		/*Approve  Item*/
		echo '<h1 class="text-center">Approve Item Ads</h1>';
		echo '<div class="container">';

		/* Check if Get Request itemId is Numeric & Get The Integer Value Of It*/

        $itemid = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;

		/*Select All Data Depand This itemId*/
		$check=checkItem('Item_ID', 'items', $itemid);

		/*if count gretter than 0 i show the form with the value of it*/

		if ($check > 0) {
			$stmt = $con->prepare("UPDATE items SET Approve = 1  WHERE Item_ID=? ");
			$stmt->execute(array($itemid));
			$theMsg= '<div class="alert alert-success">' . $stmt->rowCount() . ' Item Activated</div>';
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