<?php
$host = "localhost";
$user = "root";
$password = "tm88808mafia"; // leave it empty if you're using default XAMPP
$database = "p2p";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
