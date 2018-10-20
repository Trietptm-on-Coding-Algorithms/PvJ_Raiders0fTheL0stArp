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

$article_id = (isset($_GET['article_id']) && ctype_digit($_GET['article_id'])) ? $_GET['article_id'] : '';
echo '<h2>Article Review</h2>';


output_story( $article_id);

$sql = 'SELECT
		is_published, UNIX_TIMESTAMP(publish_date) AS publish_date,access_level
	FROM
		zero_articles a INNER JOIN zero_users u ON a.user_id = u.user_id
	WHERE
		article_id = (?)';

if (! ( $query = $mysqli->prepare($sql) )){

	die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
}

if ( ! $query->bind_param("i", $article_id ) ){
	die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
}
// die($_GET['search']);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

$is_published = $row['is_published'];
$publish_date = $row['publish_date'];
$access_level = $row['access_level'];

// $result = mysql_query($sql, $dbx) or die(mysql_error());
// $row = mysql_fetch_array($result);
// extract($row);
// mysql_free_result($result);

if(!empty($date_published) and $is_published){
	echo '<h4>Published: ' . date('l F j, Y H:i', $date_published) . '</h4>';
}
?>
<form method="post" action="zero_transact_article.php">
<div>
	<input type="submit" name="action" value="Edit"/>
<?php
if($access_level > 1 || $_SESSION['access_level'] > 1){
	if($is_published){
		echo '<input type="submit" name="action" value="Retract"/>';
	}else{
		echo'<input type="submit" name="action" value="Publish"/>';
		echo'<input type="submit" name="action" value="Delete"/>';
		}
	}
?>
<input type="hidden" name="article_id" value="<?php echo $article_id; ?>"/>
</div>
</form>
<?php
include 'footer.kate.php';
?>
