<?php
// (c)Perez Karjee(www.aas9.in)
// Project Site www.aas9.in/zerocms
// Created March 2014

// JOHN: This page should be patched...

require_once('db.kate.php');
require_once('functions.kate.php');

// $dbx = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB) 
// or die( "There was an error connecting to the database." );

$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
if ( $mysqli->connect_errno ){
  die("There was an error connecting to the MySQL database.");
}

include 'header.kate.php';


if (! ( $query = $mysqli->prepare("SELECT article_id FROM zero_articles WHERE is_published = TRUE ORDER BY publish_date DESC") )){

  die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
}

// if ( ! ( $query->bind_param("ss", $password) ) ){
//   die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
// }

$query->execute();
$result = $query->get_result();
// $rows = $result->fetch_assoc();

// $result = mysqli_query($sql, $dbx);


if ( $result->num_rows == 0 ){
  echo '<p><strong>No Articles.</strong></p>';
  
} else {

  while ($row = $result->fetch_assoc()) {
    // echo "article goes here";
    // print_r($row['article_id']);
  	output_story($row['article_id'], TRUE);
  }
}

include 'footer.kate.php';
?>
