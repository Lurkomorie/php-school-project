<?php
setlocale(LC_TIME, "C");
session_start();
$error='';
$username='';
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }
    else
    {
// Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
        $db = new SQlite3('user-store.db');
        $db->exec('PRAGMA journal_mode = wal;');
// To protect MySQL injection for Security purpose
// SQL query to fetch information of registerd users and finds user match.

        $numRows = getRows($username, $password, $db);
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        if ($numRows == 1) {
            $_SESSION['login_user']=$username;// Initializing Session
            $stmt = $db->prepare("INSERT INTO Sessions VALUES(NULL , '$username', '" . getCurrentDate() . "' ,'$ipaddress','login')");
            $stmt->execute();
            header("location: profile.php"); // Redirecting To Other Page
        } else {
            $error = "Username or Password is invalid";
        }// Closing Connection
    }
}

function getCurrentDate(){
    $mytime =  strftime('%d') . '-' . strftime('%m') . '-' .strftime('%Y') . ' ' .  strftime('%H') . '/' . strftime('%M');
    return $mytime;
}

function getRows($username, $password, $db){

    $query = $db->query("SELECT COUNT(*) as count FROM User WHERE userName='$username' and password='$password'");
    $row = $query->fetchArray(SQLITE3_ASSOC);
    return $row['count'];
}

?>