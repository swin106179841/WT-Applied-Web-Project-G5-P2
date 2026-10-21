<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
require_once("settings.php");

$conn = @mysqli_connect($host, $user,$pwd);
if ($conn) {
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$sql_db`");
    mysqli_select_db($conn,$sql_db);

    $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    mysqli_query($conn,$createUsersTable);
}
?>