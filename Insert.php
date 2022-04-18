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
    $regerror = '';
    $exterror = '';
    $IdError = '';
    if (isset($_POST["submit"])) {
        $errorcount = 0;
        $ID = $_POST["ID"];
        $Category = $_POST["Category"];
        $Brand = $_POST["Brand"];
        $Description = $_POST["Description"];
        $Status = $_POST["Status"];
        $regCost = $_POST["regCost"];
        $extCost = $_POST["extCost"];
        $moneyPattern = '/^[0-9]+(\.[0-9]{1,2})?$/';
        $admin = $_SESSION['userObject'];

        if (!preg_match($moneyPattern, $regCost)) {
            $regerror = 'Invalid money format please re enter';
            $errorcount++;
        }
        if (!preg_match($moneyPattern, $extCost)) {
            $exterror = 'Invalid money format please re enter';
            $errorcount++;
        }
        include('Dbconnect.php');
        $stmt = mysqli_prepare($conn, "SELECT COUNT(1) FROM equipment WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, "s", $ID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $res);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        if ($res != 0) {
            $IdError = 'ProductID already exists';
            $errorcount++;
        }

        if ($errorcount == 0) {
            $admin->insertEquipment($ID, $Category, $Brand, $Description, $Status, $regCost, $extCost);
        }
    }
    ?>
    <div class="container mt-3"> 
        <form action="Insert.php" method="post" autocomplete="off" class="form-wrapper mx-auto text-center" style="width:25%; ">
            <div class="mb-1">
                <label class="form-label" for="ID">Product ID:</label><br>
                <input class="form-control" type="text" id="ID" name="ID" required>
                <?php
                echo '<h5>' . $IdError . '</h5>';
                ?>
                <br>
            </div>

            <div class="mb-1">
                <label class="form-label" for="Category">Category:</label><br>
                <input class="form-control" type="text" id="Category" name="Category" required><br>
            </div>

            <div class="mb-1">
                <label class="form-label" for="Brand">Brand:</label><br>
                <input class="form-control" type="text" id="Brand" name="Brand" required><br>
            </div>

            <div class="mb-1">
                <label class="form-label" for="Description">Description:</label><br>
                <input class="form-control" type="text" id="Description" name="Description" required><br>
            </div>

            <div class="mb-1">
                <label class="form-label" for="Status">Product status:</label><br>
                <input type="radio" id="Available" name="Status" value="Available" required>
                <label class="form-label" for="Available">Available</label><br>
                <input type="radio" id="Rented" name="Status" value="Rented" required>
                <label class="form-label" for="Rented">Rented</label><br>
            </div>

            <div class="mb-1">
                <label class="form-label" for="regCost">Regular Cost per day:</label><br>
                <input class="form-control" type="text" id="regCost" name="regCost" required><br>
                <?php
                echo '<h5>' . $regerror.'</h5>';
                ?>
            </div>

            <div class="mb-1">
                <label class="form-label" for="extCost">Extended Cost per day:</label><br>
                <input class="form-control" type="text" id="extCost" name="extCost" required><br>
                <?php
                echo '<h5>' . $exterror. '</h5>';
                ?>
            </div>
            <input class="btn btn-primary" type="submit" name="submit" value="Insert new Equipment">
        </form>
    </div>
</body>

</html>