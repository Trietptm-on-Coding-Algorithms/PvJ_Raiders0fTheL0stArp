<?php
// (c)Perez Karjee(www.aas9.in)
// Project Site www.aas9.in/zerocms
// Created March 2014


// JOHN: This page should be patched...

function trim_body($text, $max_length = 500, $tail = '...') {
    $tail_len = strlen($tail);
    if (strlen($text) > $max_length) {
        $tmp_text = substr($text, 0, $max_length - $tail_len);
        if (substr($text, $max_length - $tail_len, 1) == ' ') {
            $text = $tmp_text;
        }
        else {
            $pos = strrpos($tmp_text, ' ');
            $text = substr($text, 0, $pos);
        }
        $text = $text . $tail;
    }
    return $text;
}


function output_story($article_id, $preview_only = FALSE) {
    
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    if ( $mysqli->connect_errno ){
      die("There was an error connecting to the MySQL database.");
    }

// function output_story($dbx, $article_id, $preview_only = FALSE) {
    
    if (empty($article_id)) {
        return;
    }


    if (! ( $query = $mysqli->prepare("SELECT a.name, is_published, title, article_text, UNIX_TIMESTAMP(submit_date) AS submit_date, UNIX_TIMESTAMP(publish_date) AS publish_date FROM  zero_articles a JOIN zero_users u ON a.user_id = u.user_id WHERE article_id = (?)") )){

        die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
      }

      if ( ! ( $query->bind_param("i", $article_id) ) ){
        die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
      }

      $query->execute();
      $result = $query->get_result();
      $row = $result->fetch_assoc();


    // $sql = 'SELECT
    //         name, is_published, title, article_text,
    //         UNIX_TIMESTAMP(submit_date) AS submit_date,
    //         UNIX_TIMESTAMP(publish_date) AS publish_date
    //     FROM
    //         zero_articles a JOIN zero_users u ON a.user_id = u.user_id
    //     WHERE
    //         article_id = ' . $article_id;
    // $result = mysql_query($sql, $dbx) or die(mysql_error($dbx));

    if ($row != NULL ) {
        // extract($row);
        $name = $row['name'];
        $title = $row['title'];
        $is_published = $row['is_published'];
        $publish_date = $row['publish_date'];
        
        $article_text = $row['article_text'];
        echo '<h2>' . htmlspecialchars($title) . '</h2>';
        echo '<p>By: ' . htmlspecialchars($name) . '</p>';
        echo '<p>';
        if ($row['is_published']) {
            echo date('F j, Y', $publish_date);
        } else {
            echo 'Article is not yet published.';
        }
        echo '</p>';
        if ($preview_only) {
        echo '<p>' . nl2br(htmlspecialchars(trim_body($article_text))) . '</p>';
        echo '<p><a href="zero_view_article.php?article_id=' . $article_id . 
            '">Read Full Story</a></p>';
        } else {
            echo '<p>' . nl2br(htmlspecialchars($article_text)) . '</p>';
        }
    }


}

// function show_comments($dbx, $article_id, $show_link = TRUE) {
function show_comments($article_id, $show_link = TRUE) {


    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    if ( $mysqli->connect_errno ){
      die("There was an error connecting to the MySQL database.");
    }

    if (empty($article_id)) {
        return;
    }
    if (! ( $query = $mysqli->prepare("SELECT is_published FROM zero_articles WHERE article_id = (?)") )){


        die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);

      }

      if ( ! ( $query->bind_param("i", $article_id) ) ){
        die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
      }

      $query->execute();
      $result = $query->get_result();
      $row = $result->fetch_assoc();
      $is_published = $row['is_published'];






    // $sql = 'SELECT is_published FROM zero_articles WHERE article_id = ' . 
    //     $article_id;
    // $result = mysql_query($sql, $dbx) or die(mysql_error($dbx));
    // $row = mysql_fetch_assoc($result);
    // $is_published = $row['is_published'];
    // mysql_free_result($result);


     // if (! ( $query = $mysqli->prepare("SELECT comment_text, UNIX_TIMESTAMP(comment_date) AS comment_date, name, email FROM zero_comments c LEFT OUTER JOIN zero_users u ON c.user_id = u.user_id WHERE article_id = (?) ORDER BY comment_date DESC") )){

     if (! ( $query = $mysqli->prepare('SELECT
            comment_text, UNIX_TIMESTAMP(comment_date) AS comment_date,
            name, email
        FROM
           zero_comments
        WHERE
            article_id = (?)
        ORDER BY
            comment_date DESC') )){
       
        die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);

      }

      if ( ! ( $query->bind_param("i", $article_id) ) ){
        die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
      }

      $query->execute();
      $result = $query->get_result();
      
      

    if ($show_link) {
        echo '<h3>' . $result->num_rows . ' Comments';
        if (isset($_SESSION['user_id']) and $is_published) {
            echo ' - <a href="zero_comment.php?article_id=' . $article_id .
                '">Add one</a>';
        }
        echo '</h3>';
    }


        
    $sql = 'SELECT
            comment_text, UNIX_TIMESTAMP(comment_date) AS comment_date,
            name, email
        FROM
           zero_comments c LEFT OUTER JOIN zero_users u ON c.user_id = u.user_id
        WHERE
            article_id = ' . $article_id . '
        ORDER BY
            comment_date DESC';
    // $result = mysql_query($sql, $dbx) or die(mysql_error($dbx));

    // if ($show_link) {
    //     echo '<h3>' . mysql_num_rows($result) . ' Comments';
    //     if (isset($_SESSION['user_id']) and $is_published) {
    //         echo ' - <a href="zero_comment.php?article_id=' . $article_id .
    //             '">Add one</a>';
    //     }
    //     echo '</h3>';
    // }

    if ($result->num_rows ) {
        echo '<div>';
        while ($row = $result->fetch_assoc()) {
            // extract($row);
            $name = $row['name'];
            $comment_date = $row['comment_date'];
            $comment_text = $row['comment_text'];
            echo '<span>' . htmlspecialchars($name) . '</span>';
            echo '<span> (' . date('l F j, Y H:i', $comment_date) . ')</span>';
            echo '<p>' . nl2br(htmlspecialchars($comment_text)) . '</p>';
        }
        echo '</div>';
    }
    echo '<br>';
    // mysql_free_result($result);
}
?>
