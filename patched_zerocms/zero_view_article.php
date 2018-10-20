<?php
// (c)Perez Karjee(www.aas9.in)
// Project Site www.aas9.in/zerocms
// Created March 2014
require_once('db.kate.php');
require_once('zero_http_functions.kate.php');
require_once('functions.kate.php');


$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
if ( $mysqli->connect_errno ){
  die("There was an error connecting to the MySQL database.");
}


include 'header.kate.php';
output_story($_GET['article_id']);

show_comments($_GET['article_id'], TRUE);

include 'footer.kate.php';
?>
