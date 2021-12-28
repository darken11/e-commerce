<?php
/* get Title for page function v1.0
*/
function getTitle(){

	global $pageTitle;

	if(isset($pageTitle)){

		echo $pageTitle;
	}else{
		echo'Default';
	}
}




// Get All records From Any table Function V2.0
/*
** It's Global Function For Searching Record in Any Table
**
*/
function getAllFrom($field,$table, $where = NULL, $and = NULL,$orderField = NULL, $ordering = 'DESC')
{
	global $con;


	$getAll = $con->prepare("SELECT $field FROM $table $where $and  ORDER BY $orderField $ordering");
	$getAll->execute();
	$all = $getAll->fetchAll();
	return $all;
}



/* home redirect function v1.0
 **  Thi function accept params 
**$erroMsg =Echo the errors message
$seconds =seconds befor redirect
*/
// function redirectHome($errorMsg, $seconds=3){
// 	echo "<div class='container'><br>";
// 	echo '<div class="alert alert-danger">'.$errorMsg.'</div>';
// 	echo '<div class="alert alert-info">You will be redirect after '.$seconds.' seconds </div>';
// 	echo "</div>";
// 	header("refresh:$seconds; url=index.php");
// 	exit();
// }


/* 
** Home redirect function v2.0
** We decide to develope our function to have redirect dynamicly for all other function
** So the good one is to update this function not just when we have error but also when we have succes 
** This function accept params 
** $theMsg = Echo The Message[Error | Success | Warning ]
** $url = The link whe i would to be redirect
** $seconds = Seconds Before redirecting
*/
function redirectHome($theMsg, $url=null ,$seconds=3){
		$link ='';
	if($url=== null){
		$url='index.php';
	
	}else{
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
			$url =$_SERVER['HTTP_REFERER'];
			
		}else{
			$url='index.php';
		}
		
	}
	echo "<div class='container'><br>";
	echo $theMsg;
	echo '<div class="alert alert-info">You Will Be Redirect to Previous Page  After '.$seconds.' Seconds </div>';
	echo "</div>";
	header("refresh:$seconds; url=$url");
	exit();
}
/* 
** Check items Function v1.0
** Function to check if members exist in data base [Function accepte params]
** $select = The Table to select [Exempel : user, item, Category]
** $From = The Table to select From [Exempel : users, items, Categories]
** $value = The Value to select [Exempel : Rabi, Box, Electronics]
*/
function checkItem($select, $from, $value){

	global $con;
	$statement =$con->prepare("SELECT $select FROM $from WHERE $select=?");
    $statement->execute(array($value));
	$count = $statement ->rowCount();

	/* It's better to use return in the place of echo 
	** echo show the result directly and dont give you chance to handle it
	** but return give you hand to handle result if the front
	*/
	return $count;
}


/*
** Count Numbers Of Items Function V1.0
** Function To count Numbers Of Items Rows
**$countItem = The Items To Count
**$table = The table To Count From
*/
 function countItems($countItem, $table){
	 global $con;
	$stm2 = $con -> prepare("SELECT COUNT($countItem)FROM $table");
	$stm2 -> execute();
	return $stm2->fetchColumn();
 }
/*
** Count Numbers Of Items Function V1.0
** this function is merge of two function
*/
function checkItemGlo($select, $table,$restOfQuery = NULL){
	global $con;
	
				
		$stm2 = $con -> prepare("SELECT COUNT($select)FROM $table  $restOfQuery");
		$stm2->execute();
			
		
		// la methode rowCount return just one item is the last one.
		// $count = $stm2 ->rowCount();
		$count = $stm2 ->fetchColumn();
	return $count;
}
/*
**Get the Latest Records Function v1.0
**Function To Get the Latest Items from Database [Users, Items, Comments]
** $select: field to search
** $table:table where i select the data
** $limit:How many record would i show
*/
function getLatest($select,$table,$order,$limit = 5){
	global $con;
	$getState= $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$getState -> execute();
	$row = $getState ->fetchAll();
	return $row;

}

	/*
	** This method check if the Description of Category is Empty, if it's i show the message like so.
	**$description: the description to show.
	*/

function checkDescription($description){
	if($description == ""){
		echo "This Category Has No Description.";
	}
	else{
		echo $description;
	}
}

/*
** This function retourn the Comments with the name of the users who write this comment and also realtion with Items
**
*/
function getLatestComments($order,$limit = 1){
	global $con;
	$stmt = $con->prepare(" SELECT comments.*,  users.Username AS Member_Name
			FROM comments
			INNER JOIN users On users.User_ID = comments.com_mem_Id 
			ORDER BY $order DESC
			Limit $limit ");
	$stmt->execute();
	$comments = $stmt->fetchAll();
	return $comments;

}