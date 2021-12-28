<?php
ob_start();
session_start();
$pageTitle = 'Creat New Ads';

include 'init.php';
if(isset($_SESSION['user'])){
    // print_r()// : permet d'afficher un tableau ou un objet par contre //echo //permet d'afficher un string
    if($_SERVER['REQUEST_METHOD'] =='POST'){
        $formErrors =array();

        $name      = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $desc      = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price     = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
        $country   = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $status    = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $category  = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
        $tags      = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);


        if( strlen($name)<4){
            $formErrors[]  = 'The Title Must Be At Least <strong> 4 </strong> Chars';
        }
        if( strlen($desc)<10){
            $formErrors[]  = 'The Description Must Be At Least <strong> 10</strong> Chars';
        }
        if( strlen($country)<2){
            $formErrors[]  = 'The Country Must Be At Least <strong>2</strong> Chars';
        }
        if( empty($price)){
            $formErrors[]  = 'The Price Must Be  <strong>Not Empty</strong> ';
        }
        if( empty($status)){
            $formErrors[]  = 'The Status Must Be  <strong>Not Empty</strong> ';
        }
        if( empty($category)){
            $formErrors[]  = 'The Category Must Be  <strong>Not Empty</strong> ';
        }
        if(empty($formErrors)){
            $stm = $con->prepare("INSERT INTO items 
                                (Name,Description,Country_Made,Price,Add_Date,StatusItem,item_Cat_Id,item_member_Id,Tags)
                                VALUE(?,?,?,?,now(),?,?,?,?)");
            $stm->execute(array($name, $desc , $country , $price.' €',$status ,$category,$_SESSION['userId'], $tags));

            if($stm){
               $successMessage= 'Item Has Added.';
            }
        }
    }
    
?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="creat-ad block">
                <div class="container">
                    <div class="card  mb-3">
                        <div class="card-header text-white bg-primary"><?php echo $pageTitle ?></div>
                        <div class="card-body">
                           <div class="row">
                               <div class="col-md-8 col-sm-8">
                                    <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

                                            <!-- start Name field -->
                                            <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="name" class="col-form-label mx-3">Name</label>
                                                </div>
                                                <div class="col-sm-9 col-md-8 controlStar" >
                                                    <input type="text" 
                                                           pattern=".{4,}"
                                                           title="This Filed required At Least 4 Chars."
                                                           name="name" 
                                                           id="name" 
                                                           class="form-control live" 
                                                           autocomplete="off"
                                                           placeholder=" Name Of Item." 
                                                           required = "required"
                                                           data-class='.live-name'/>
                                                </div>

                                            </div>
                                            <!-- End Name field -->
                                            <!-- start Description field -->
                                            <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="description" class="col-form-label mx-3">Description</label>
                                                </div>
                                                <div class="col-sm-9 col-md-8 controlStar" >
                                                    <textarea type="text" 
                                                              pattern=".{10,}"
                                                              title="This Filed required At Least 10 Chars."
                                                              name="description" 
                                                              id="description" 
                                                              class="form-control live"  
                                                              placeholder="Descrip The Item."  
                                                              required
                                                              
                                                              data-class='.live-desc'></textarea>
                                                    
                                                </div>

                                            </div>
                                            <!-- End Description field -->
                                            <!-- start Price field -->
                                            <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="price" class="col-form-label mx-3">Price</label>
                                                </div>
                                                <div class="col-sm-9 col-md-8 controlStar" >
                                                    <input type="text" 
                                                           name="price" 
                                                           id="price" 
                                                           class="form-control live"  
                                                           placeholder="Price Of This Item." 
                                                           required
                                                           data-class='.live-price'/>
                                                </div>

                                            </div>
                                            <!-- start Country field -->
                                            <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="country" class="col-form-label mx-3">Country</label>
                                                </div>
                                                <div class="col-sm-9 col-md-8 controlStar" >
                                                    <input type="text" name="country" id="country" required class="form-control"  placeholder="Country Of The Item." />
                                                </div>

                                            </div>
                                            <!-- End Country field -->
                                             <!-- Start Status -->
                                             <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="status" class="col-form-label mx-3">Status</label>
                                                </div>
                                                    <div class="col-sm-9 col-md-8">
                                                        <select name="status" id="status" required >
                                                            <option value="">...</option>
                                                            <option value="1">New</option>
                                                            <option value="2">Like New</option>
                                                            <option value="3">Used</option>
                                                            <option value="4">Very Used</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            <!-- End Status -->
                                             <!-- Start Category -->
                                             <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="category" class="col-form-label mx-3">Category</label>
                                                </div>
                                                    <div class="col-sm-9 col-md-8">
                                                        <select name="category" id="category" required>
                                                            <option value="">...</option>
                                                            <?php
                                                            
                                                           
                                                            $categories = getAllFrom('*','categories','WHERE Parent = 0','','Cat_ID');
                                                            foreach($categories as $cat){
                                                                
                                                                echo '<option value="'.$cat['Cat_ID'].'">' .$cat['Name']. '</option>';
                                                                        $subCategories = getAllFrom("*","categories","WHERE Parent = {$cat['Cat_ID']}","","Cat_ID");
                                                                        foreach($subCategories as $c){
                                                                            echo '<option value="'.$c['Cat_ID'].'">' .'***'.$c['Name']. '</option>';
                                                                        }
                                                                    
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <!-- End Category -->
                                            <!-- Start Tags Field -->
                                            <div class="row g-2 mb-3 ">
                                                <div class="col-3">
                                                <label for="tags" class="col-form-label mx-3">Tags</label>
                                                </div>
                                                        <div class="col-sm-9 col-md-8">

                                                        <input type="text" name="tags" id="tags" class="form-control" placeholder="Separete Your Tags With Coma (,)">
                                                        </div>
                                                    </div>
                                                    <!-- End Tags Field -->
                                            <!-- start Button field -->
                                            <div class="m-3">

                                                <div class="col-sm-3  col-md-3 offset-md-3 ">
                                                    <button type="submit" class="btn btn-primary btn-lg">Add Item</button>
                                                </div>

                                            </div>
                                            <!-- End Buuton field -->
                                    </form>
                               </div>
                               <div class="col-sm-4">
                                   
                                    <div class="card mb-3 mx-2 item-box border-0 shadow-lg text-center" >
                                            <span class="price-tag live-price">0 €</span>
                                            <img class=" img-thumbnail mx-auto d-block border border-light" src="./layout/images/no-avatar-300x300.png" alt="card image cap"/>
                                
                                            
                                                <h5 class="card-title live-name">Title</h5>
                                                <p class="card-text live-desc">Test</p>
                                            
                                  </div>
                                </div>
                           </div>
                            <!-- Start Looping Through Errors -->

                            <?php 

                            if(! empty($formErrors)){
                                foreach($formErrors as $error){
                                    echo'<div class="alert alert-danger">'.$error.'</div>';
                                }
                            }
                            if(isset($successMessage)){
                                echo'<div class="alert alert-success">'.$successMessage.'</div>';
                            }
                            ?>
                            <!-- End Looping Through Errors -->

                        </div>
                    </div>
                </div>
</div>


<?php
}else{
    header('Location: login.php');
	exit();
}
include $tmpl . 'footer.php';
ob_end_flush();
?>