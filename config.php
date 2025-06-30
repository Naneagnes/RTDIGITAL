<?php
$host = 'localhost';
$user = 'root'; // default user XAMPP
$pass = ''; // default password XAMPP
$db   = 'rt_info';

$conn = mysqli_connect("localhost", "root", "", "rt_info");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
