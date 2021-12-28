<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />

	<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo $css; ?>all.min.css" />
	<link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />

	
	<link rel="stylesheet" href="<?php echo $css; ?>myStyle-frontEnd.css" />


	<title><?php echo $pageTitle; ?></title>
</head>

<body>
	<div class="upper-bar">
		<div class="container">
			
				<div class="row row-cols-auto info"> 
						
				   		
								<?php 
								if (isset($_SESSION['user'])) {?>
								<div class="col">
									<img  src="./layout/images/no-avatar-300x300.png" class="img-thumbnail img-circle" alt="card image cap"/>
								</div>
									<div class="col">
										<span class=" contact ">
											<span class="phone"><i class="fas fa-phone-volume"></i>0032 485137165</span>
											<span class="email"><i class="fas fa-at"></i>rda@gmail.com</span>
											<span class="address"><i class="fas fa-map-marker-alt"></i>Belgium</span>
										</span>  
									</div> 
								
									<div class=" dropdown my-information">
											<button class="btn btn-default dropdown-toggle" type="button" id="menuInfo" data-bs-toggle="dropdown" aria-expanded="false">
											<?php echo $sessionUser ?>
											</button>
										<ul class="dropdown-menu" aria-labelledby="menuInfo">
											<li><a href="profile.php">My Profile</a></li>
											<li><a href="newads.php">Add New Item</a></li>
											<li><a href="profile.php#my-ads">My Items</a></li>
											<li><a href="logout.php">Log Out</a></li>
										</ul>
									</div>
				   
				      
				         <?php
						}else{
						?>
							   <a href="login.php">
								<span class="float-end">Login/Signup</span>
								</a>
				       <?php } ?>
			       </div> 
		
		</div>
	</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php"><?php echo lang('HOME'); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav" >
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0" >
        <?php  
			$cats=getAllFrom('*','categories',' Where Parent = 0','','Cat_ID','ASC');
			foreach($cats as $cat){
				echo '<li>
				<a class="links" href="categories.php?pageId='. $cat['Cat_ID'].'">
				 '. $cat['Name'].'
				 </a>
				</li>';
				
			}
		?>
      </ul>
     

    </div>
  </div>
</nav>
