<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "group2man ipt project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//echo "connected";
?>  