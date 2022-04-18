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
    $errorsmg = '';
    require_once('UserClass.php');
    session_start();
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
    if (isset($_POST["submit"])) {
        $errorcount = 0;
        $user = $_SESSION['userObject'];
        $expectedData = 'ssss';
        $searchData = array();
        $sql = "SELECT * FROM equipment WHERE ID LIKE ? AND Category LIKE ? AND Brand LIKE ? AND Status LIKE ?";

        if (empty(trim($_POST["ID"]))) {
            $sql = str_replace("ID LIKE ? AND", "", $sql);
            $expectedData = substr_replace($expectedData, "", -1);
            $errorcount++;
        } else {
            array_push($searchData, '%' . $_POST["ID"] . '%');
        }
        if (empty(trim($_POST["Category"]))) {
            $sql = str_replace(" Category LIKE ? AND", "", $sql);
            $expectedData = substr_replace($expectedData, "", -1);
            $errorcount++;
        } else {
            array_push($searchData, '%' . $_POST["Category"] . '%');
        }
        if (empty(trim($_POST["Brand"]))) {
            $sql = str_replace("Brand LIKE ? AND ", "", $sql);
            $expectedData = substr_replace($expectedData, "", -1);
            $errorcount++;
        } else {
            array_push($searchData, '%' . $_POST["Brand"] . '%');
        }
        if (empty(trim($_POST["Status"]))) {
            $sql = str_replace("Status LIKE ?", "", $sql);
            $expectedData = substr_replace($expectedData, "", -1);
            $sql = substr($sql, 0, -4);
            $errorcount++;
        } else {
            array_push($searchData, '%' . $_POST["Status"] . '%');
        }

        if ($errorcount != 4) {
            $searchResults = $user->searchEquipment($sql, $expectedData, $searchData);
        } elseif ($errorcount == 4) {
            $errorsmg = 'Please do not leave all fields empty';
        }
    }

    ?>
    <div class="container mt-4">
        <form action="Search.php" method="post" class="form-wrapper mx-auto text-center" autocomplete="off" style=" width:25%;">
            <div class="mb-2">
                <label class="form-label" for="ID">Product ID:</label><br>
                <input class="form-control" type="text" id="ID" name="ID"><br>
            </div>
            <div class="mb-2">
                <label class="form-label" for="Category">Category:</label><br>
                <input class="form-control" type="text" id="Category" name="Category"><br>
            </div>
            <div class="mb-2">
                <label class="form-label" for="Brand">Brand:</label><br>
                <input class="form-control" type="text" id="Brand" name="Brand"><br>
            </div>
            <div class="mb-2">
                <label class="form-label" for="Status">Status:</label><br>
                <input class="form-control" type="text" id="Status" name="Status"><br>
            </div>
            <input class="btn btn-primary" type="submit" name="submit" value="Search"><br>
            <span>
            <?php
            echo $errorsmg . '<br>';
            ?>
            </span>
        </form>
    </div>
    <?php
    if (isset($searchResults)) {
        if (sizeof($searchResults) == 0) {
            echo '<p>No Result Found</p>';
        } else {
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
            foreach ($searchResults as $i) {
                echo '<tr>';
                echo '<td>' . '<a href="ViewItem.php?ID=' . $i[0] . '&Status=' . $i[4] . '">' . $i[0] . '</a>' . '</td>';
                echo '<td>' . $i[1] . '</td>';
                echo '<td>' . $i[2] . '</td>';
                echo '<td>' . $i[3] . '</td>';
                echo '<td>' . $i[4] . '</td>';
                echo '<td>' . $i[5] . '</td>';
                echo '<td>' . $i[6] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
        }
    }
    ?>
</body>
</html>