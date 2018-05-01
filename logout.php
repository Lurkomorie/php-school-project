<?php
session_start();
$db = new SQlite3('user-store.db');
$db->exec('PRAGMA journal_mode = wal;');
$user=$_SESSION['login_user'];
$ipaddress = $_SERVER['REMOTE_ADDR'];
if(session_destroy()) // Destroying All Sessions
{
    $stmt = $db->prepare("INSERT INTO Sessions VALUES(NULL , '$user', '" . getCurrentDate() . "' ,'$ipaddress','logout')");
    $stmt->execute();
    header("Location: index.php"); // Redirecting To Home Page
}

function getCurrentDate(){
    $mytime =  strftime('%d') . '-' . strftime('%m') . '-' .strftime('%Y') . ' ' .  strftime('%H') . '/' . strftime('%M');
    return $mytime;
}
?>