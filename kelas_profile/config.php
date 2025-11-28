<?php
$host = 'localhost';
$user = 'root'; // Default Laragon
$pass = ''; // Default Laragon
$db = 'kelas_profile';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>