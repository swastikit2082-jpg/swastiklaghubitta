<?php
// Database connection for Swastik Laghubitta
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swastiklbs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 for Nepali characters
$conn->set_charset("utf8mb4");
?>
