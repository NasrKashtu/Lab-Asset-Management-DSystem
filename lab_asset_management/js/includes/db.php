<?php
$servername = "localhost";
$username = "lab_asset_app";
$password = "lab_asset_app_pw";
$dbname = "lab_asset_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
