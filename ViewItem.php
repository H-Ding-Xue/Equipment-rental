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
  $ID = $_GET['ID'];
  $user = $_SESSION['userObject'];
  $Item = $user->viewSingleEquipment($ID);

  if (isset($_POST["Rent"])) {
    $startdate = date('y:m:d');
    $enddate = Date('y:m:d', strtotime('+' . $_POST["Days"] . ' days'));
    $Status = 'Rented';
    $cost = $_POST["Days"] * $Item[5];
    $user->rentProduct($_SESSION["ID"], $ID, $startdate, $enddate, $Status, $cost);
    $enddate = Date('d:m:y', strtotime('+' . $_POST["Days"] . ' days'));
    header('Location:Client.php?Rented="true"&enddate=' . $enddate . '&cost=' . $cost);
    die();
  }

  if (isset($_POST["Return"])) {
    $user->returnProduct($_SESSION["ID"], $ID);
    header('Location:Client.php?Returned="true"');
    die();
  }

  if (isset($_POST["Extend"])) {
    $result = $user->extendProduct($_SESSION["ID"], $ID, $_POST["ExtendedDays"], $Item[6]);
    header('Location:Client.php?Extend="true"&newdate=' . $result[1] . '&newcost=' . $result[0]);
    die();
  }

  if ($_SESSION["Type"] == 1) {
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
  } else {
    echo '<nav class="navbar navbar-expand-sm">';
    echo '<div class="container">';
    echo '<a class="nav-link" href="Client.php">Home</a>';
    echo '<a class="nav-link" href="ViewAvailable.php">View Available Products</a>';
    echo '<a class="nav-link" href="RentHistory.php">View Rental History</a>';
    echo '<a class="nav-link" href="RentCurrent.php">View Products Rented Currently</a>';
    echo '<a class="nav-link" href="Search.php">Search Products</a>';
    echo '<a class="nav-link" href="Profile.php">View Profile</a>';
    echo '<a class="nav-link" href="logout.php">Logout</a>';
    echo '</div>';
    echo '</nav>';
    echo '<div class="container-fluid p-5 bg-primary text-white text-center">';
    echo '<h1>Welcome ' . $_SESSION["ID"] . '</h1>';
    echo '</div>';
  }
  echo '<div class = "container mt-3 center">';
  echo '<table class = "table table-hover">';
  echo '<tr>';
  echo '<th>ID</th>';
  echo '<td>' . $Item[0] . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Category</th>';
  echo '<td>' . $Item[1] . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Brand</th>';
  echo '<td>' . $Item[2] . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Description</th>';
  echo '<td>' . $Item[3] . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Status</th>';
  echo '<td>' . $Item[4] . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Regular</th>';
  echo '<td>' . $Item[5] . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Extended</th>';
  echo '<td>' . $Item[6] . '</td>';
  echo '</tr>';
  echo '</table>';
  echo '</div>';

  if ($_GET['Status'] == 'Available' && $_SESSION["Type"] != 1) {

    echo '<form autocomplete="off" action="ViewItem.php?ID='. $Item[0] .'" method="post" class="form-wrapper mx-auto text-center" style="width:25%;>';
    echo '<div class="mb-1">';
    echo '<label class="form-label" for="Days">Duration of rental:</label><br>';
    echo '<input class="form-control" type="number" min="1" id="Days" name="Days" required><br><br>';
    echo '</div>';
    echo '<input class="btn btn-primary" type="submit" value="Rent Product" name="Rent" style="text-align: center">';
    echo '</form>';
    

  } elseif ($_GET['Status'] == 'Rented' && $_SESSION["Type"] != 1) {
    include('Dbconnect.php');
    $stmt = mysqli_prepare($conn, "SELECT UserID FROM rental WHERE ProdID = ? AND Status = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $ID, $_GET['Status']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $res);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    if ($res == $_SESSION["ID"]) {
      
      echo '<form autocomplete="off" action="ViewItem.php?ID=' . $Item[0] . '" method="post" class="form-wrapper mx-auto text-center" style="width:25%;>';
      echo '<div class="mb-1">';
      echo '<label for="ExtendedDays">Duration of extension:</label><br>';
      echo '<input type="number" min="1" id="ExtendedDays" name="ExtendedDays" required><br><br>';
      echo '</div>';
      echo '<input class="btn btn-primary" type="submit" value="Extend Rental" name="Extend">';
      echo '</form>';
      
      echo '<br>';
      echo '<form action="ViewItem.php?ID=' . $Item[0] . '" method="post" class="form-wrapper mx-auto text-center" >';
      echo '<input class="btn btn-primary" type="submit" value="Return Product" name="Return">';
      echo '</form>';
      
      
    }
  }
  ?>
</body>

</html>