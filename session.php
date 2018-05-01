<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
session_start();// Starting Session
// Storing Session
$db = new SQlite3('user-store.db');
// SQL Query To Fetch Complete Information Of User
$user_check=$_SESSION['login_user'];
$query = $db->query("SELECT userName FROM User WHERE userName='$user_check'");
$row = $query->fetchArray(SQLITE3_ASSOC);
$login_session =$row['userName'];
echo  $login_session;
