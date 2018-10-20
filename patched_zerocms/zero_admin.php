<?php
// (c)Perez Karjee(www.aas9.in)
// Project Site www.aas9.in/zerocms
// Created March 2014
require_once('db.kate.php');
require_once('functions.kate.php');

include 'header.kate.php';


$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
if ( $mysqli->connect_errno ){
  die("There was an error connecting to the MySQL database.");
}


$sql = 'SELECT
		access_level, access_name
	FROM
		zero_access_levels
	ORDER BY
		access_name ASC';

// $result = mysql_query($sql, $dbx) or die(mysql_error($dbx));

$result = $mysqli->query($sql) or die("There wan an error connecting to the database.");
$privileges = array();

while ($row = $result->fetch_assoc()){
	$privileges[$row['access_level']] = $row['access_name'];
}

// mysql_free_result($result);


echo '<h2>Admin Panel</h2>';

$limit = count($privileges);
for($i = 1; $i <= $limit; $i++){
	echo '<h3>' . $privileges[$i] . '</h3>';
	

	if (! ( $query = $mysqli->prepare("SELECT user_id, name FROM zero_users WHERE access_level = (?) ORDER BY name ASC") )){

	    die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
	}

	if ( ! ( $query->bind_param("i", $i ) ) ){
	  die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
	}

	$query->execute();
	$result = $query->get_result();

	if($result->num_rows == 0){
		echo '<p><strong>There are no ' . $privileges[$i] . ' accounts ' . ' registered</strong></p>';
	}else{
		echo '<ul>';
		while ($row = $result->fetch_assoc()){
			if($_SESSION['user_id'] == $row['user_id']){
				echo '<li>' . htmlspecialchars($row['name']) . '</li>';
				}else{
					echo '<li><a href="zero_user_account.php?user_id=' . $row['user_id'] . '">' . htmlspecialchars($row['name']) . 
					'</a></li>';
					}
				}
		echo '</ul>';
	}




	// $row = $result->fetch_assoc();

	// $sql = 'SELECT
	// 		user_id, name
	// 	FROM
	// 		zero_users
	// 	WHERE
	// 		access_level = ' . $i . '
	// 	ORDER BY
	// 		name ASC';
	// $result = mysql_query($sql, $dbx) or die(mysql_error($dbx));
	// if(mysql_num_rows($result) == 0){
	// 	echo '<p><strong>There are no ' . $privileges[$i] . 'accounts' . 'registered</strong></p>';
	// }else{
	// 	echo '<ul>';
	// 	while ($row = mysql_fetch_assoc($result)){
	// 		if($_SESSION['user_id'] == $row['user_id']){
	// 			echo '<li>' . htmlspecialchars($row['name']) . '</li>';
	// 			}else{
	// 				echo '<li><a href="zero_user_account.php?user_id=' . $row['user_id'] . '">' . htmlspecialchars($row['name']) . 
	// 				'</a></li>';
	// 				}
	// 			}
	// 	echo '</ul>';
	// }
	// mysql_free_result($result);
}
require 'footer.kate.php';
?>
