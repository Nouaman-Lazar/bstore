<?php
$plain_password = '123456';
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

echo $hashed_password; // Use this hash in the INSERT INTO `admin` SQL statement
?>