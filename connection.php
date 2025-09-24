<?php

// 1. Connection to Local MySQL Server (using XAMPP or MAMPP)

$username = "root";
$password = "";
$database = "meu-caledario";

$conn = new mysqli("localhost", $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

?>