<?php
$conn = new mysqli("localhost", "root", "", "druk_agro");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>
