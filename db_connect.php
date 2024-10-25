<?php
// Database connection (db_connect.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket_system";

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
