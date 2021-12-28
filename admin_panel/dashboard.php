<?php
ob_start();// Output Buffers
session_start();
if (isset($_SESSION['Username'])) {

	$pageTitle = 'Dashboard';

	include 'init.php';
	// start Dashboard Page;

	$limitUser = 5;//Number of limit
	$latestUsers=getLatest('*','users','User_ID',  $limitUser);
	// $rows=getAllFrom("*","users", "", "","User_ID");


	$limitItem = 6;//Number of limit Item
	$latestItems=getLatest('*','items','Item_ID', $limitItem);

	$limitComment = 3;
	$comments=getLatestComments('Comment_ID',$limitComment);
		
	?>


<div class="container home-stats text-center">
	<h1>DashBoard</h1>
	<div class="row">
			<div class="col-md-3">
				
				<div class="stat st-members">
				<i class="fa fa-users"></i>
					<div class="info">
					
					Total Members
						<span>
							<a href="members.php"><?php echo checkItemGlo('User_ID', 'users') ?></a>
						</span>
					</div>
				</div>
			</div>
		<div class="col-md-3">
			<div class="stat st-pending">
			<i class="fas fa-user-plus"></i>
					<div class="info">
			Pending Members
				<span>
					<a href="members.php?do=Manage&page=Pending">
					

					<?php echo checkItemGlo('RegStatus', 'users','WHERE RegStatus=0') ?>
					</a>
				</span>
			</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="stat st-items">
			<i class="fas fa-tags"></i>
					<div class="info">Total Items
				<span>
				<a href="items.php"><?php echo checkItemGlo('Item_ID', 'items') ?></a>

				</span>
			</div></div>
		</div>
		<div class="col-md-3">
			<div class="stat st-comments">
			<i class="fas fa-comments"></i>
					<div class="info">
						Total Comments
				<span>
				<a href="comments.php"><?php echo checkItemGlo('Comment_ID', 'comments') ?></a>
					
				</span>
			</div>
			</div>
		</div>
		</div>
	</div>
</div>
<div class="container latest">
	<div class="row">
		<div class="col-sm-6">
			 <div class="card">
				<div class="card-header">
					<i class="fa fa-users"></i> Latest <?php echo '<strong>'.$limitUser.'</strong>'; ?>  Registerd Users
					<span class="float-end toggle-info"><i class="fa fa-plus fa-lg"></i></span>
				</div>
				<div class="card-body">
				<ul class="list-group list-group-flush">
					<?php 
					if(!empty($latestUsers)){
					 foreach($latestUsers as $user){
					
						 echo '<li class="list-group-item">';
						 			echo $user['Username'];
									  echo '<a href="members.php?do=Edit&userId=' . $user['User_ID'] . '">';
						 			 	echo '<span class="btn btn-warning float-end">';
											 echo '<i class="fa fa-edit"></i>Edit';
										
										echo'</span>';
									 echo '</a>';
									 if($user['RegStatus']==0 ){
										echo '<a href="members.php?do=Activate&userId=' . $user['User_ID'] . '" class="btn btn-info float-end activate"><i class="fas fa-user-check"></i> Activate</a>';
					
									   }
						 echo'</li>';
						
					 }
					}else{
						echo'<div class="container">';
						echo'<div  class="alert alert-danger"> There\'s No Data To Show';
		
						echo'</div>';
						echo'</div>';
					}
					?>
					</ul>
				</div>
			 </div>
		</div>
		<div class="col-sm-6">
			
			 <div class="card">
				<div class="card-header">
					<i class="fas fa-tags"></i> Latest <?php echo '<strong>'.$limitItem.'</strong>'; ?> Regitered Items
					<span class="float-end toggle-info"><i class="fa fa-plus fa-lg"></i></span>
				</div>
				<div class="card-body">
				<ul class="list-group list-group-flush">
					<?php 
					if(!empty($latestItems)){
					 foreach($latestItems as $item){
					
						 echo '<li class="list-group-item">';
						 			echo $item['Name'];
									  echo '<a href="items.php?do=Edit&itemId=' . $item['Item_ID'] . '">';
						 			 	echo '<span class="btn btn-warning float-end">';
											 echo '<i class="fa fa-edit"></i>Edit';
										
										echo'</span>';
									 echo '</a>';
									 if($item['Approve']==0 ){
										echo '<a href="items.php?do=Approve&itemId=' . $item['Item_ID'] . '" 
										class="btn btn-success float-end activate"><i class="fas fa-check"></i> Approve</a>';
					
									   }
						 echo'</li>';
						
					 }
					}else{
						echo'<div class="container">';
						echo'<div  class="alert alert-danger"> There\'s No Data To Show';
		
						echo'</div>';
						echo'</div>';
					}
					?>
					</ul>
				</div>
			 </div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			 <div class="card">
				<div class="card-header">
				<i class="fas fa-comments"></i> Latest <?php echo '<strong>'.$limitComment.'</strong>'; ?>  Registerd Comments
					<span class="float-end toggle-info"><i class="fa fa-plus fa-lg"></i></span>
				</div>
				<div class="card-body">
					<?php 
					if(! empty($comments)){

						foreach($comments as $comment){
						

							echo '<div class="comment-box">';
								
									echo '<a href="members.php?do=Edit&userId=' . $comment['com_mem_Id'] . '">
									      <span class="member-n">'.$comment['Member_Name'].'</span>
										  </a>';
									echo '<p class="member-c">'.$comment['Comment'].'</p>';
									// echo'<div>';
									// 	echo'<a href="comments.php?do=Edit&commentId=' . $comment['Comment_ID'] . '" class="btn btn-warning float-end"><i class="fa fa-edit"></i> Edit</a>';
									// 	echo'<a href="comments.php?do=Delete&commentId=' . $comment['Comment_ID'] . '" class="btn btn-danger float-end confirm"><i class="far fa-times-circle"></i> Delete</a>';
									// 	if($comment['Status']==0 ){
									// 	echo '<a href="comment.php?do=Approve&commentId=' . $comment['Comment_ID'] . '" class="btn btn-info float-end activate"><i class="fas fa-check"></i> Approve</a>';
				
									// }
									// echo'</div>';
								
								
							echo'</div>';
						
						}
					}else{
						echo'<div class="container">';
							echo'<div  class="alert alert-danger"> There\'s No Data To Show';
			
							echo'</div>';
						echo'</div>';
					}
					 
					?>
				</div>
			 </div>
		</div>
		
	</div>

</div>


	<?php
	// End Dashboard Page
	include $tmpl . 'footer.php';
} else {
	header('Location:index.php');
	exit();
}
ob_end_flush();
?>