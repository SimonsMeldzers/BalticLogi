<?php
$servername = "127.0.0.1:3307";
$username = "admin"; 
$password = "password123";        
$dbname = "balticlogi";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>