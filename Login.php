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
    $LoginError = '';
    $message = '';
    if (!empty($_GET['message'])) {
        $message = $_GET['message'];
    }
    if (isset($_POST["reg"])) {
        header('Location: Register.php');
        die();
    }

    if (isset($_POST["login"])) {
        include('Dbconnect.php');
        $stmt = mysqli_prepare($conn, "SELECT Type FROM usertable WHERE BINARY ID = ? AND BINARY Password = ?");
        mysqli_stmt_bind_param($stmt, "ss", $_POST["ID"], $_POST["Password"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $res);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($res === 1) {
            session_start();
            $_SESSION["ID"] = $_POST["ID"];
            $_SESSION["Type"] = $res;
            header('Location: Admin.php');
            die();
        } elseif ($res === 0) {
            session_start();
            $_SESSION["ID"] = $_POST["ID"];
            $_SESSION["Type"] = $res;
            header('Location: Client.php');
            die();
        } elseif ($res == null) {
            $LoginError = 'Invalid ID or Password';
        }
    }
    ?>
    <div class="container mt-4">
        <span>
            <?php echo $message; ?>
        </span>
        <form action="Login.php" method="post" autocomplete="off" style="width:25%">
            <div class="mb-2">
                <label for="ID" class="form-label">ID:</label><br>
                <input type="text" id="ID" name="ID" class="form-control" required><br>
            </div>
            <label for="Password" class="form-label">Password:</label><br>
            <input type="password" id="Password" name="Password" class="form-control" required><br>
            <input type="submit" name="login" value="Login" class="btn btn-primary">
        </form>
    </div>

    <div class="container mt-4">
        <form action="Login.php" method="post">
            <input type="submit" name="reg" value="Register" class="btn btn-primary">
        </form>
        <span class="error" style="color:red">
            <?php echo $LoginError; ?>
        </span>
    </div>
</body>

</html>