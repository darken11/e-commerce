<?php $pageTitle = 'Home';

include 'init.php';?>

<div class="container">
<h1 class="text-center"><?php echo $_GET['name'] ?></h1>

<div class="row">
    
                <?php
     
      
                    if(isset($_GET['name'])){
                        $tag=$_GET['name'];?>
                           <?php }
                                   $allTagsItems=getAllFrom("*", "items", "WHERE Tags like '%$tag%'", "" , "Item_ID" );
                                if(! empty($allTagsItems)){
                                    foreach($allTagsItems as $item){  ?>
            
                                    
                           
                                    <div class="col-6 col-md-3">
                                          <div class="card m-2 item-box" >

                                            <span class="price-tag"><?php echo $item['Price']?> </span>
                                             <img class="img-fluid" src="./layout/images/no-avatar-300x300.png" alt="card image cap"/>
                                
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title"><a href="items.php?itemId=<?php echo $item['Item_ID']?>"><?php echo $item['Name']?></a></h5>
                                                          <p class="card-text"><?php echo $item['Description']?></p>
                                                        <p class="date"><?php echo $item['Add_Date']?></p>  
                                                 </div>
                                        </div>
                             </div>
                             
                             <?php    }
                                }
               
                else{
                    echo "<div class='alert alert-danger'>There Is No Such Page Id .</div>";
                }
        	?>
               </div>
</div>
<?php include $tmpl . 'footer.php';?>