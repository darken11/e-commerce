<?php
/* get Title for page function v1.0
*/
function getTitle()
{

	global $pageTitle;

	if (isset($pageTitle)) {

		echo $pageTitle;
	} else {
		echo 'Default';
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



/* 
** Home redirect function v2.0
** We decide to develope our function to have redirect dynamicly for all other function
** So the good one is to update this function not just when we have error but also when we have succes 
** This function accept params 
** $theMsg = Echo The Message[Error | Success | Warning ]
** $url = The link whe i would to be redirect
** $seconds = Seconds Before redirecting
*/
function redirectHome($theMsg, $url = null, $seconds = 3)
{
	$link = '';
	if ($url === null) {
		$url = 'index.php';
	} else {
		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
			$url = $_SERVER['HTTP_REFERER'];
		} else {
			$url = 'index.php';
		}
	}
	echo "<div class='container'><br>";
	echo $theMsg;
	echo '<div class="alert alert-info">You Will Be Redirect to Previous Page  After ' . $seconds . ' Seconds </div>';
	echo "</div>";
	header("refresh:$seconds; url=$url");
	exit();
}

/*
** Count Numbers Of Items Function V1.0
** Function To count Numbers Of Items Rows
**$countItem = The Items To Count
**$table = The table To Count From
*/
function countItems($countItem, $table)
{
	global $con;
	$stm2 = $con->prepare("SELECT COUNT($countItem)FROM $table");
	$stm2->execute();
	return $stm2->fetchColumn();
}



/*
	** This method check if the Description of Category is Empty, if it's i show the message like so.
	**$description: the description to show.
	*/

function checkDescription($description)
{
	if ($description == "") {
		echo "This Category Has No Description.";
	} else {
		echo $description;
	}
}

/*
** This function retourn the Comments with the name of the users who write this comment and also realtion with Items
**
*/
// function getLatestComments($order, $limit = 1)
// {
// 	global $con;
// 	$stmt = $con->prepare(" SELECT comments.*,  users.Username AS Member_Name
// 			FROM comments
// 			INNER JOIN users On users.User_ID = comments.com_mem_Id 
// 			ORDER BY $order DESC
// 			Limit $limit ");
// 	$stmt->execute();
// 	$comments = $stmt->fetchAll();
// 	return $comments;
// }
function getAllJoin($field1,$field2,$table,$innerJoinTable, $key,$value,$orderField,$limit=1,$ordering='DESC'){
	global $con;
	$stmt=$con->prepare("SELECT $field1,$field2 
	                     FROM $table 
						 INNER JOIN $innerJoinTable 
						 ON $key = $value
	                     ORDER BY $orderField $limit $ordering");
		$stmt->execute();
		$comments = $stmt->fetchAll();
	return $comments;		 
}