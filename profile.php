<?php

session_start();
$pageTitle = 'Profile';

include 'init.php';
if(isset($_SESSION['user'])){

    $getUserStm = $con->prepare("SELECT * FROM users WHERE Username=?");
    $getUserStm -> execute(array($sessionUser));
    $info = $getUserStm->fetch();
    // $info=getAllFrom("*","users","WHERE Username=($_SESSION['user']","","");
    
?>
<h1 class="text-center"><?php echo'Welcome: '. $sessionUser;?></h1>
<div class="information block">
                <div class="container">
                    <div class="card  m-3">
                        <div class="card-header text-white bg-primary">My Informations</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                            
                                <li class="list-group-item">
                                    <i class="fa fa-unlock fa-fw"></i>
                                    <span>Login Name</span> : <?php echo $info['Username']; ?>
                                </li>
                                <li class="list-group-item">
                                <i class="far fa-envelope fa-fw"></i>
                                    <span>Email</span>  : <?php echo $info['Email']; ?>
                                </li>
                                <li class="list-group-item">
                                <i class="fa fa-user fa-fw"></i>
                                    <span>Full Name</span>  : <?php echo $info['FullName']; ?>
                                </li>
                                <li class="list-group-item">
                                <i class="fas fa-calendar-alt"></i>
                                    <span>Regited Date</span>  : <?php echo $info['Date']; ?>
                                </li>
                                <li class="list-group-item">
                                <i class="fa fa-tag fa-fw"></i>
                                    <span>Regited Fav</span>  : 
                                </li>
                            </ul>
                            <a href="#" class="btn btn-default mt-3" role="button">Edit Profile</a>
                        </div>
                    </div>
                </div>
</div>
<div id="my-ads" class="my-ads block">
                <div class="container">
                    <div class="card  m-3">
                        <div class="card-header text-white bg-primary">My Ads</div>
                        <div class="card-body">
                        <div class="row">

                            <?php
                            $getItems=getAllFrom("*","items","WHERE item_member_Id={$info['User_ID']}","","Item_ID");
                            if(! empty($getItems)){
                                    foreach($getItems as $item){ ?>
                                    
                                    <div class="col-6 col-sm-4 col-md-4">
                                        <div class="card m-2 item-box border-0 shadow-lg" >
                                            <?php if($item['Approve'] == 0){ echo "<span class='approve-status'>Not Approved.</span>";}?>
                                            <span class="price-tag"><?php echo $item['Price'] ?></span>
                                            <img class="img-thumbnail rounded mx-auto d-block border-light" src="./layout/images/avatar-pngrepo-com.png" alt="card image cap"/>
                                
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><a href="items.php?itemId=<?php echo $item['Item_ID']?>"><?php echo $item['Name'] ?></a></h5>
                                                <p class="card-text"><?php echo $item['Description']?></p>
                                                <p class="date"><?php echo $item['Add_Date']?></p>                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                <?php	
                                }
                            }else{
                            echo "<div class='alert alert-danger'>There Is No Ads For This Profile. Creat <a href='newads.php'>New Ads</a> </div>";
                            }
                        ?>
                        </div>
                    </div>
                </div>
           </div>
</div>
<div id="my-comments" class="latestComments block">
                <div class="container">
                    <div class="card  m-3">
                        <div class="card-header  text-white bg-primary">Latest Comments</div>
                        <div class="card-body">
                        
                        <div class="row g-0">
                 
<?php
   $getComents=getAllFrom("*","comments","WHERE com_mem_Id={$info['User_ID']}","","Comment_ID");

if(! empty($getComents)){
        foreach($getComents as $comment){ ?>
        
        <div class="col-6 col-md-4">
            <div class="card mb-3 comment-block border-0 shadow-lg" >
              
    
                <div class="card-body ">
                    <h5 class="card-text">Comment: <?php echo $comment['Comment']?></h5>
                    <p class="text-muted">Date Of Add: <?php echo $comment['Comment_Date'] ?></p>

                </div>
            </div>
        </div>
        
        
        
    <?php	
    }
}else{
echo "<div class='alert alert-danger'>There Is No Comments For This Profile.</div>";
}
?>

</div>
                            
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
?>