<?php
// (c)Perez Karjee(www.aas9.in)
// Project Site www.aas9.in/zerocms
// Created March 2014
require_once('db.kate.php');
require_once('functions.kate.php');


// JOHN: This page is patched.

$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
if ( $mysqli->connect_errno ){
  die("There was an error connecting to the MySQL database.");
}


include 'header.kate.php';

$search = (isset($_GET['search'])) ? $_GET['search'] : '';

if (! ( $query = $mysqli->prepare("SELECT article_id FROM zero_articles WHERE article_text LIKE (?) ORDER BY title DESC") )){

die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
}
$search = '%' . $_GET['search'] . '%';
if ( ! $query->bind_param("s", $search ) ){
die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
}
// die($_GET['search']);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();


if($result->num_rows == 0){
	echo '<p><strong>No Articles Found.</strong></p>';
}else{
	while($row = $result->fetch_assoc()){

		output_story($row['article_id'], TRUE);
	}
}


// $sql = 'SELECT
// 		article_id
// 	FROM
// 		zero_articles
// 	WHERE
// 		MATCH(title, article_text) AGAINST ("' . 
// 			mysql_real_escape_string($search, $dbx) . '" IN BOOLEAN MODE)
// 	ORDER BY
// 		MATCH(title, article_text) AGAINST ("' . 
// 			mysql_real_escape_string($search, $dbx) . '" IN BOOLEAN MODE)
	
// 	DESC';
// $result = mysql_query($sql, $dbx) or die(mysql_error($dbx));

// if(mysql_num_rows($result) == 0){
// 	echo '<p><strong>No Articles Found.</strong></p>';
// }else{
// 	while($row = mysql_fetch_array($result)){
// 		output_story($dbx, $row['article_id'], TRUE);
// 	}
// }
// mysql_free_result($result);
include 'footer.kate.php';
?>
