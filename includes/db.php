<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lakshi_bookshop';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
