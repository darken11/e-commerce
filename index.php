<?php
ob_start();
session_start();
$pageTitle = 'Home';

include 'init.php';
?>

<div class="container">
    <h1 class="text-center">Category</h1>
    <div class="row">
    <?php
    
            $allItems=getAllFrom('*','items','WHERE Approve = 1','','Item_ID');
			foreach($allItems as $item){?>
              
            <div class="col-6 col-md-3">
                <div class="card m-2 item-box border-0 shadow-lg" >

                    <span class="price-tag"><?php echo $item['Price'] ?></span>
                   <img class="img-fluid" src="./layout/images/no-avatar-300x300.png" alt="card image cap"/>
          
                    <div class="card-body text-center">
                        <h5 class="card-title"><a href="items.php?itemId=<?php echo $item['Item_ID']?>"><?php echo $item['Name'] ?></a></h5>
                        <p class="card-text"><?php echo $item['Description']?></p>
                        <p class="date"><?php echo $item['Add_Date']?></p>  
                    </div>
                </div>
            </div>
            
            
		<?php	}
       
		?>
    </div>
</div>

<?php

include $tmpl . 'footer.php';
ob_end_flush();
?>