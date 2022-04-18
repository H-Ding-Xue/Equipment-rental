<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap-5.0.2-dist\css\bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>
<?php
if(isset($_GET['Rented'])){
    echo '<script>alert("Item successfully rented \nCost : $'.$_GET['cost'].'\nReturn date : '.$_GET['enddate'].'")</script>';
    unset($_GET['Rented']);
}
if(isset($_GET['Returned'])){
    echo '<script>alert("Item successfully returned")</script>';
    unset($_GET['Returned']);
}
if(isset($_GET['Extend'])){
    echo '<script>alert("Duration of item extended \nNew Overall Cost : $'.$_GET['newcost'].'\nNew Return date : '.$_GET['newdate'].'")</script>';
    unset($_GET['Extend']);
}
require_once('UserClass.php');
session_start();
echo '<nav class="navbar navbar-expand-sm">';
echo'<div class="container">'; 
echo'<a class="nav-link" href="Client.php">Home</a>';
echo'<a class="nav-link" href="ViewAvailable.php">View Available Products</a>';
echo'<a class="nav-link" href="RentHistory.php">View Rental History</a>';
echo'<a class="nav-link" href="RentCurrent.php">View Products Rented Currently</a>';
echo'<a class="nav-link" href="Search.php">Search Products</a>';
echo'<a class="nav-link" href="Profile.php">View Profile</a>';
echo '<a class="nav-link" href="logout.php">Logout</a>';
echo'</div>';
echo'</nav>';
echo '<div class="container-fluid p-5 bg-primary text-white text-center">';
echo '<h1>Welcome '.$_SESSION["ID"].'</h1>';
echo '</div>';
$clientObject = new Client($_SESSION["ID"]);
$_SESSION['userObject'] = $clientObject;
$clientObject=null;
?>
</body>
</html>