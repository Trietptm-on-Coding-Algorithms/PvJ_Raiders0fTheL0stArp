<?php
// (c)Perez Karjee(www.aas9.in)
// Project Site www.aas9.in/zerocms
// Created March 2014

// JOHN: This should be fully patched.



require_once('db.kate.php');
require_once('zero_http_functions.kate.php');

$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
if ( $mysqli->connect_errno ){
  die("There was an error connecting to the MySQL database.");
}


// $dbx = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
//     die ('Unable to connect. Check your connection parameters.');

if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) {
    case 'Login':
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $password = (isset($_POST['password'])) ? $_POST['password'] : '';

        if (! ( $query = $mysqli->prepare("SELECT user_id, access_level, name FROM zero_users WHERE email = (?) AND password = (?)") )){

            die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
          }

          if ( ! ( $query->bind_param("ss", $email, $password) ) ){
            die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
          }

          $query->execute();
          $result = $query->get_result();
          $row = $result->fetch_assoc();

          if ( $row == NULL ){
            die("This user was not found in the database, or wrong password.");
          }else{

            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['access_level'] = $row['access_level'];
            $_SESSION['name'] = $row['name'];
          }

        redirect('index.php');
        break;

    case 'Logout':
        session_start();
        session_unset();
        session_destroy();
        redirect('index.php');
        break;

    case 'Create Account':
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $password_1 = (isset($_POST['password_1'])) ? $_POST['password_1'] : '';
        $password_2 = (isset($_POST['password_2'])) ? $_POST['password_2'] : '';
        $password = ($password_1 == $password_2) ? $password_1 : '';

        $access_level = 1;

        if (!empty($name) && !empty($email) && !empty($password)) {


            if (! ( $query = $mysqli->prepare("INSERT INTO zero_users (`email`, `password`, `name`, `access_level`) VALUES ((?), (?), (?), (?))") )) {

                die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
              }

              if ( ! ( $query->bind_param("sssi", $email, $password, $name, $access_level) ) ){
                die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
              }

              $query->execute();
              $query->close();


              $result = $mysqli->query("SELECT user_id FROM zero_users ORDER BY user_id DESC LIMIT 1");
            $row = $result->fetch_assoc();
            
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['access_level'] = 1;
            $_SESSION['name'] = $name;
            redirect('index.php');
        }
        break;

    case 'Modify Account':
        $user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : '';
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        $access_level = (isset($_POST['access_level'])) ? $_POST['access_level']
            : '';
        if (!empty($user_id) && !empty($name) && !empty($email) &&
            !empty($access_level) && !empty($user_id)) {

             if (! ( $query = $mysqli->prepare("UPDATE `zero_users` SET email = '(?)', name = '(?)', access_level = (?) WHERE user_id = (?)") )) {

                die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
              }

              if ( ! ( $query->bind_param("ssii", $email, $name, $access_level, $user_id) ) ){
                die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
              }

              $query->execute();
              $query->close();

        }
        redirect('zero_admin.php');
        break;

    case 'Recover Password':
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        if (!empty($email)) {

            if (! ( $query = $mysqli->prepare("SELECT email FROM zero_users WHERE email = (?)") )) {

                die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
              }

              if ( ! ( $query->bind_param("s", $email ) ) ){

                die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
              }

              $query->execute();
              $result = $query->get_result();
              $row = $result->fetch_assoc();

              if ( $row == NULL ){
                die("This email was not found in the database.");
              }else{

                $password = strtoupper(substr(sha1(time()), rand(0, 32), 8));
                $subject = 'Comic site password reset';
                $body = 'Forgot your password? we will send you a new one. ' . 
                    'We\'ve reset it for you!' . "\n\n";
                $body .= 'Your new password is: ' . $password;
                mail($email, $subject, $body);
              }

        }

        redirect('zero_login.php');
        break;

    case 'Change my info':
        session_start();
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        if (!empty($name) && !empty($email) && !empty($_SESSION['user_id']))
        {

             if (! ( $query = $mysqli->prepare("UPDATE zero_users SET email = ?, name = ? WHERE user_id = ?") )) {

                die('Preparing statement failed: (' . $mysqli->errno . ') ' . $mysqli->error);
              }

              if ( ! ( $query->bind_param("ssi", $email, $name, $_SESSION['user_id']) ) ){
                
                die('Binding parameters failed: (' . $query->errno . ') ' . $query->error);
              }

              $query->execute();
              $query->close();

            $_SESSION['name'] = $name;

        }
        redirect('zero_cpanel.php');
        break;


    default:
        redirect('index.php');
    }


} else {
    redirect('index.php');
}
?>
