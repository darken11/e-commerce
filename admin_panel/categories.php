<?php
/*
================================================================
== Category Page
================================================================
*/
ob_start();// Output Buffers
session_start();
$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {

	
	include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $sort= 'ASC';
        $ordering='Ordering';

        $ordering_array=['Cat_ID','Name','Ordering','Visibility'];

        $sort_array=['ASC','DESC'];
        // checking if this order exist in our array
        if( isset($_GET['ordering']) && in_array($_GET['ordering'], $ordering_array)){

            $ordering=$_GET['ordering'];
        }
        if( isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

            $sort=$_GET['sort'];
        }
        

        $stmt = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY $ordering $sort");
		$stmt->execute();
		$categories = $stmt->fetchAll();
        
        if(!empty($categories)){
        ?>
       <h1 class="text-center"> Manage Categories</h1>
       <div class="container categories">
           <div class="card">
               <div class="card-header">
               <div class="float-start titre-header"><i class="fa fa-edit"></i> Manage Categories</div>
                   <div class="options float-end">
                   <i class="fas fa-sort"></i> Ordering:
                        <!-- Example single danger button -->[
                                <div class="btn-group">
                                    <button  class="btn btn-info dbtn" >
                                        Action
                                    </button>
                                    <div class="dropdown-content">
                                        <a  href="?ordering=Cat_ID">Category Id</a>
                                        <a  href="?ordering=Visibility">Visibility</a>
                                        <a  href="?ordering=Alow_Ads">Allow Ads</a>
                                </div>
                                </div>
                       <a class="<?php if($sort== 'ASC') echo 'active';?>" href="?sort=ASC"><i class="fas fa-sort-alpha-up"></i></a>
                       <a class="<?php if($sort== 'DESC') echo 'active';?>" href="?sort=DESC"><i class="fas fa-sort-alpha-down"></i></a>  ]
                       <i class="fa fa-eye"></i> View: [
                       <span class="active" data-view="full">Full</span> |
                       <span data-view="Full">Classic</span> ]
                   </div>
               </div>
               <div class="card-body">
               <ul class="list-group list-group-flush">
                   <?php
                foreach($categories as $cat){ ?>
                   <li class="list-group-item cat">
                        <div class=" hidden-btn">
                            <a href="categories.php?do=Edit&catId=<?php echo $cat['Cat_ID']?>" class=" btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
                           <a href="categories.php?do=Delete&catId=<?php echo $cat['Cat_ID']?>" class=" btn btn-sm btn-danger confirm "><i class="far fa-times-circle"></i> Delete</a>
                        </div>
                        <h3><?php echo $cat['Name']?></h3>
                        <div class="full-view">
                            <p><?php checkDescription($cat['Description'])?></p>
                            
                        <?php    
                            if($cat['Visibility']==0){echo '<span class="span-cat visibl"><i class="fa fa-eye"></i> Hidden </span>';}
                            if($cat['Alow_Comment']==0){ echo '<span class="span-cat alow-comment"><i class="fas fa-comment-slash"></i> Comment Disabled </span>';}
                            if($cat['Alow_Ads']==0) {echo '<span class="span-cat alow-ads"><i class="fas fa-times"></i> Ads Not Allowded </span>';}
                            
                            $childCats=getAllFrom("*","categories", "where Parent= {$cat['Cat_ID']}", "","Cat_ID");
                            if(!empty($childCats)){?>

                                <h5 class="child-cat-title">Sub Category</h5>
                                    <ul class="list-group sub-category ">
                                            <?php 

                                            foreach($childCats as $c){ 
                                                echo '<li class="child-link">
                                                <a class="child-lin" href="categories.php?do=Edit&catId='.$c['Cat_ID'].'"> '.$c['Name'].'</a>
                                                <a class="child-link-d confirm"   href="categories.php?do=Delete&catId='. $c['Cat_ID'].' "> Delete</a>';
                                                $childOfChild=getAllFrom("*","categories", "where Parent= {$c['Cat_ID']}", "","Cat_ID");
                                                    if(!empty($childOfChild)){?>
                                                        <?php foreach($childOfChild as $cofc){ 
                                                                echo' <li class="child-link second-child-link">
                                                                    <a class="child-lin" href="categories.php?do=Edit&catId='.$cofc['Cat_ID'].'"> '.'  &nbsp; <i class="fas fa-long-arrow-alt-right"></i>  '.$cofc['Name'].'</a>
                                                                    <a class="child-link-d confirm"   href="categories.php?do=Delete&catId='. $cofc['Cat_ID'].' "> Delete</a></li>';
                                                            
                                                            
                                                            
                                                                echo' </li>';
                                                        }
                                                    }
                                            }
                                            ?>
									</ul>
                          <?php  }
                            ?>
                        </div>
                        
                    </li>
                   
              <?php        
               }
                ?>
               </ul>
               </div>
           </div>
           <a href="categories.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add Category</a>
       </div>

<?php

            }else{
                echo'<div class="container">';
                    echo'<div  class="nice-alert"> There\'s No Data To Show';

                    echo'</div>';
                    echo'  <a href="categories.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add Category</a>';
                echo'</div>';
        }


        
    }elseif($do=='Add'){?>
       <h1 class="text-center"> Add New Category</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">

                <!-- start Name field -->
                <div class=" my-3 row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10 col-md-6 controlStar" >
                        <input type="text" name="name" id="name" class="form-control" autocomplete="off" placeholder=" Name Of Category." required="required" />
                    </div>

                </div>
                <!-- End Name field -->
                <!-- start Description field -->
                <div class="mb-3 row">
                    <label for="description"  class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10 col-md-6 controlStar">
                        <input type="text" name="description" id="description" class="form-control"  placeholder="Descrip The Category."  />
                        
                    </div>

                </div>
                <!-- End Description field -->
                <!-- Start Show Parent Ctegory -->
                <div class="mb-3 row">
                    <label for="parent"  class="col-sm-2 col-form-label">Parent</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="parent" id="parent">
                                <option value="0">None</option>
                                <?php 
                                
                                    $getCats= getAllFrom('*','categories', 'Where Parent = 0', '','Cat_ID');
                                    foreach($getCats as $cat){
                                        echo '<option value="'.$cat['Cat_ID'].'">'.$cat['Name'].'</option>';
                                    }
                  
                                ?>

                        </select>
                    </div>
                </div>            
                <!-- End Show Parent Ctegory -->
                
                <!-- start Ordering field -->
                <div class="mb-3 row">
                    <label for="ordering" class="col-sm-2 col-form-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="int" name="ordering" id="ordering" class="form-control"  placeholder="Number To Arrange The Category" />
                    </div>

                </div>
                <!-- End Ordering field -->
                <!-- start Visibility field -->
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Visibility</label>
                    <div class="col-sm-10 col-md-6">
                        <div class="form-check">
                            <input type="radio" name="visibility" id="visibility-Yes" class="form-check-input"   value="1"  checked/>
                            <label for="visibility-Yes" class="form-check-label"> YES</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="visibility" id="visibility-No" class="form-check-input"   value="0"  />
                            <label for="visibility-No"  class="form-check-label"> NO</label>
                        </div>
                    </div>
                    

                </div>
                <!-- End Visibility field -->
                <!-- start Alow-Comment field -->
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Alow Comment</label>
                    <div class="col-sm-10 col-md-6">
                        <div class="form-check">
                            <input type="radio" name="alow-comment" id="alow-comments-Yes" class="form-check-input"   value="1"  checked/>
                            <label for="alow-comments-Yes" class="form-check-label"> YES</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="alow-comment" id="alow-comments-No" class="form-check-input"   value="0"  />
                            <label for="alow-comments-No" class="form-check-label"> NO</label>
                        </div>
                    </div>
                    

                </div>
                <!-- End Alow-comments field -->
                <!-- start Alow-Ads field -->
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Alow Ads</label>
                    <div class="col-sm-10 col-md-6">
                        <div class="form-check">
                            <input type="radio" name="alow-ads" id="alow-ads-Yes" class="form-check-input"  value="1"  checked/>
                            <label for="alow-ads-Yes" class="form-check-label"> YES</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="alow-ads" id="alow-ads-No" class="form-check-input"   value="0"  />
                            <label for="alow-ads-No" class="form-check-label"> NO</label>
                        </div>
                    </div>
                    

                </div>
                <!-- End Alow Ads field -->
                <!-- start Button field -->
                <div class="my-2">

                    <div class="col-sm-4 offset-md-2">
                        <button type="submit" class="btn btn-primary btn-lg">Add New Category</button>
                    </div>

                </div>
                <!-- End Buuton field -->
            </form>

        </div>


   <?php }elseif($do=='Insert'){
        

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			echo "<h1 class='text-center'> Update Categories</h1>";
			echo " <div class='container'>";

			// $id=$_POST['userid'];

			$name = $_POST['name'];

			$description = $_POST['description'];

			$parent = $_POST['parent'];
             
			$order = $_POST['ordering'];

			$visibility = $_POST['visibility'];

			$alowComment = $_POST['alow-comment'];

            $alowAds = $_POST['alow-ads'];

		    // Check if the Category Exist In DATABASE

            $checkAlready = checkItem("Name", "categories", $name);

				if ($checkAlready == 1) {

					$theMsg = "<div class='alert alert-danger'>Sorry This Category Already Existe In DATABASE.</div>";
				    
                    redirectHome($theMsg, 'back');
				
                } else {
					//Insert user data

					$stmt = $con->prepare("INSERT  into categories  (Name, Description,Parent,Ordering,Visibility,Alow_Comment, Alow_Ads) VALUES(?,?,?,?,?,?,?)");
					
                    $stmt->execute(array($name, $description, $parent, $order, $visibility,$alowComment,$alowAds ));
					
                    // //Echo succes message
					
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Record Inserted</div>';
				    
                    redirectHome($theMsg,'back');
                }
        }
        else {
			$theMsg= '<div class="alert alert-danger"> Sorry you cant Browse this page Directly</div>';
			redirectHome($theMsg);
		}


    }elseif($do=='Edit'){

        // Check If the Id Numeric 

		$categoryid = isset($_GET['catId']) && is_numeric($_GET['catId']) ? intval($_GET['catId']) : 0;

		// Select The Data To Update From The DB With Catid
		$stmt = $con->prepare("SELECT  * FROM  categories WHERE Cat_ID=? ");

		$stmt->execute(array($categoryid));

		//Fetch The Data
		$row = $stmt->fetch();

		// Row Count
		$count = $stmt->rowCount();

		//If Count Gretter Than 0, Than We Show The Form With The Value Of It

		if ($count > 0) {  ?>

			<h1 class="text-center"> Edit Category</h1>

            <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $categoryid ?>" />
                        <!-- start Name field -->
                        
                        <div class=" my-3 row" id="controlStar">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6" >
                                <input type="text" name="name" id="name" value="<?php echo $row['Name'] ?>" class="form-control" autocomplete="off" placeholder=" Name Of Category." required="required" />
                            </div>

                        </div>
                        <!-- End Name field -->
                        <!-- start Description field -->
                        <div class="mb-3 row">
                            <label for="description"  class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" id="description" value="<?php echo $row['Description'] ?>" class="form-control"  placeholder="Descrip The Category."  />
                                
                            </div>

                        </div>
                        <!-- End Description field -->
                        <!-- Start Show Parent Ctegory -->
                            <div class="mb-3 row">
                                <label for="parent"  class="col-sm-2 control-label">Parent</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="parent" id="parent">
                                            <option value="0">None</option>
                                            <?php 
                                            
                                                $getCats= getAllFrom('*','categories', 'Where Parent = 0', '','Cat_ID');

                                                foreach($getCats as $cat){
                                                            echo '<option value="'.$cat['Cat_ID'].'"';
                                                            if($row['Parent'] == $cat['Cat_ID'] ){ echo 'selected';}
                                                            echo'>'.$cat['Name'].'</option>';
                                                            $getChildOf= getAllFrom("*","categories", "Where Parent = {$cat['Cat_ID']}", "","Cat_ID");

                                                    foreach($getChildOf as $c){
                                                            echo '<option value="'.$c['Cat_ID'].'"';
                                                            if($row['Parent'] == $c['Cat_ID'] ){ echo 'selected';}
                                                            echo'>'.$c['Name'].'</option>';

                                                    }
                                                }
                            
                                            ?>

                                    </select>
                                </div>
                            </div>            
                            <!-- End Show Parent Ctegory -->
                        <!-- start Ordering field -->
                        <div class="mb-3 row">
                            <label for="ordering" class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="int" name="ordering" id="ordering" class="form-control" value="<?php echo $row['Ordering'] ?>" placeholder="Number To Arrange The Category" />
                            </div>

                        </div>
                        <!-- End Ordering field -->
                        <!-- start Visibility field -->
                        <div class="mb-3 row">
                            <label  class="col-sm-2 control-label">Visibility</label>
                            <div class="col-sm-10 col-md-6">
                                <div class="form-check">
                                    <input type="radio" name="visibility" id="visibility-Yes" class="form-check-input"  
                                     value="1" <?php if($row['Visibility']== 1){echo 'checked';}?> />
                                    <label for="visibility-Yes" class="form-check-label"> YES</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="visibility" id="visibility-No" class="form-check-input"   value="0"
                                    <?php if($row['Visibility']== 0){echo 'checked';}?>  />
                                    <label for="visibility-No"  class="form-check-label"> NO</label>
                                </div>
                            </div>
                            

                        </div>
                        <!-- End Visibility field -->
                        <!-- start Alow-Comment field -->
                        <div class="mb-3 row">
                            <label  class="col-sm-2 control-label">Alow Comment</label>
                            <div class="col-sm-10 col-md-6">
                                <div class="form-check">
                                    <input type="radio" name="alow-comment" id="alow-comments-Yes" class="form-check-input"  <?php if($row['Alow_Comment']== 1){echo 'checked';}?> 
                                    value="1"  checked/>
                                    <label for="alow-comments-Yes" class="form-check-label"> YES</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="alow-comment" id="alow-comments-No" class="form-check-input" <?php if($row['Alow_Comment']== 0){echo 'checked';}?>  value="0"  />
                                    <label for="alow-comments-No" class="form-check-label"> NO</label>
                                </div>
                            </div>
                    

                            </div>
                            <!-- End Alow-comments field -->
                            <!-- start Alow-Ads field -->
                            <div class="mb-3 row">
                                <label  class="col-sm-2 control-label">Alow Ads</label>
                                <div class="col-sm-10 col-md-6">
                                    <div class="form-check">
                                        <input type="radio" name="alow-ads" id="alow-ads-Yes" class="form-check-input" <?php if($row['Alow_Ads']== 1){echo 'checked';}?> value="1"  checked/>
                                        <label for="alow-ads-Yes" class="form-check-label"> YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="alow-ads" id="alow-ads-No" class="form-check-input"  <?php if($row['Alow_Comment']== 0){echo 'checked';}?> value="0"  />
                                        <label for="alow-ads-No" class="form-check-label"> NO</label>
                                    </div>
                                </div>
                                

                            </div>
                            <!-- End Alow Ads field -->
                            <!-- start Button field -->
                            <div class="mt-3">

                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary btn-lg">Update Category</button>
                                </div>

                            </div>
                            <!-- End Buuton field -->
                        </form>

                    </div>
    <?php
         } else {
            $theMsg= "<div class='alert alert-danger'> This It Not Found.</div>";
             redirectHome($theMsg, 'back');
    }echo'</div>';
    }elseif($do=='Update'){
        echo "<h1 class='text-center'> Update Category</h1>";
		echo " <div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['catid'];

            $name = $_POST['name'];

			$description = $_POST['description'];

            $parent = $_POST['parent'];

			$order = $_POST['ordering'];

			$visibility = $_POST['visibility'];

			$alowComment = $_POST['alow-comment'];

            $alowAds = $_POST['alow-ads'];

		    // Check if the Category Exist In DATABASE


        //    $chekCategoryName=getAllFrom("*","categories","WHERE Name = {$_POST['name'] }","","Cat_ID");
        //    if(!empty($chekCategoryName)){
        //        echo $chekCategoryName;
        //    }else{
         
                $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Parent = ?,  Ordering = ?, Visibility=? , Alow_Comment=?,  Alow_Ads=?  WHERE Cat_ID = ? ");
				$stmt->execute(array($name, $description,$parent, $order, $visibility, $alowComment, $alowAds, $id));
				//Echo succes message
				$theMsg= '<div class="alert alert-success">' . $stmt->rowCount()  . ' Category Updated</div>';
				redirectHome($theMsg, 'back');
        //    }
        }
        else {
			$theMsg= '<div class="alert alert-danger"> Sorry you cant Browse this page Directly.</div>';
			redirectHome($theMsg);
		}
		echo "</div>";

    }elseif($do=='Delete'){
        echo '<h1 class="text-center">Delete Category</h1>';
		echo '<div class="container">';
		$catid = isset($_GET['catId']) && is_numeric($_GET['catId']) ? intval($_GET['catId']) : 0;
		$category=checkItem('Cat_ID', 'categories', $catid);
        if ($category > 0) {
			$stmt = $con->prepare("DELETE  FROM  categories WHERE Cat_ID=? ");
			$stmt->execute(array($catid));
			$theMsg= '<div class="alert alert-danger">' . $stmt->rowCount() . ' Category Deleted</div>';
            redirectHome($theMsg, 'back');
		} else {
			$theMsg= "<div class='alert alert-danger'> This No Category Found.</div>";
			redirectHome($theMsg);
		}
		echo '</div>';
    }
    include $tmpl . 'footer.php';

} else {

	header('Location: index.php');
	exit();
}