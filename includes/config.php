<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "affiliate_marketing";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">

<style>
    * {
        direction:rtl ;
        text-align:right
    }
</style>