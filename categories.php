<?php $pageTitle = 'Home';

include 'init.php';?>

<div class="container">
<h1 class="text-center">Category</h1>
    <div class="row">
        <?php
     
      
                      // check if Get Request Item is Numeric & Get It's Integer Value 
                    if(isset($_GET['pageId']) && is_numeric($_GET['pageId'])){
                        $category=intval($_GET['pageId']);
                    $allItems=getAllFrom("*", "items", "WHERE item_Cat_Id = {$category}", "AND Approve = 1" , "Item_ID" );
                    // if(! empty($allItems)){
                    foreach($allItems as $item){?>
                    
                    <div class="col-6 col-md-3">
                        <div class="card m-2 item-box" >

                            <span class="price-tag"><?php echo $item['Price'] ?></span>
                            <img class="img-fluid" src="./layout/images/item-chogan1.png" alt="card image cap"/>
                
                            <div class="card-body text-center">
                                <h5 class="card-title"><a href="items.php?itemId=<?php echo $item['Item_ID']?>"><?php echo $item['Name'] ?></a></h5>
                                <p class="card-text text-wrap"><?php echo $item['Description']?></p>
                                <p class="date"><?php echo $item['Add_Date']?></p>  
                            </div>
                        </div>
                    </div>
                    
                    
                <?php	}
                }else{
                    echo "<div class='alert alert-danger'>There Is No Such Page Id .</div>";
                }
      
		?>
    </div>
</div>





<?php include $tmpl . 'footer.php';?>