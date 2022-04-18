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
require_once('UserClass.php');
session_start();
    echo '<nav class="navbar navbar-expand-sm">';
    echo '<div class="container">';
    echo '<a class="nav-link" href="Admin.php">Home</a>';
    echo '<a class="nav-link" href="Search.php">Search Products</a>';
    echo '<a class="nav-link" href="Insert.php">Insert Products</a>';
    echo '<a class="nav-link" href="Profile.php">View Profile</a>';
    echo '<a class="nav-link" href="ViewAll.php">View All Products</a>';
    echo '<a class="nav-link" href="ViewRented.php">View Rented Products</a>';
    echo '<a class="nav-link" href="ViewAvailable.php">View Available Products</a>';
    echo '<a class="nav-link" href="logout.php">Logout</a>';
    echo '</div>';
    echo '</nav>';
    echo '<div class="container-fluid p-5 bg-primary text-white text-center">';
    echo '<h1>Welcome ' . $_SESSION["ID"] . '</h1>';
    echo '</div>';
$user = $_SESSION['userObject'];
$result = $user -> viewAll();
if(isset($result)){
    if(sizeof($result)==0){
        echo '<p>No Products</p>';
    }
    else{
        echo '<div class = "container mt-3 center">';
        echo '<table class = "table table-hover">';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Category</th>';
        echo '<th>Brand</th>';
        echo '<th>Description</th>';
        echo '<th>Status</th>';
        echo '<th>Regular</th>';
        echo '<th>Extended</th>';
        echo '</tr>';
        foreach ($result as $i)  {
            echo '<tr>';
            echo '<td>'.'<a href="ViewItem.php?ID='.$i[0].'&Status='.$i[4].'">'.$i[0].'</a>'.'</td>' ;
            echo '<td>'.$i[1].'</td>' ;
            echo '<td>'.$i[2].'</td>' ;
            echo '<td>'.$i[3].'</td>' ;
            echo '<td>'.$i[4].'</td>' ;
            echo '<td>'.$i[5].'</td>' ;
            echo '<td>'.$i[6].'</td>' ;
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }
}
?>
</body>

</html>