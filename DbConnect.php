<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "a3";
try{
$conn = mysqli_connect($servername, $username, $password,$dbname);
}
catch (mysqli_sql_exception $e)
{
die("Connection failed: ". mysqli_connect_errno(). " - " . mysqli_connect_error()); 
}
?>
