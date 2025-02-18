<?php

$servername = "localhost";
$username = "cp187059_admin";
$password = "7QhZD, ]i[^!o";
$dbname = "cp187059_sk";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

mysqli_set_charset($conn, "utf8");

// Check connection

if ($conn->connect_error) {

  die("ไม่สามารถเชื่อมต่อฐานข้อมูล : " . $conn->connect_error);

} 
$baseHTTP = "http://";

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {

  $baseHTTP = "https://";

}
$baseURL = $_SERVER['SERVER_NAME']."/";


?>
