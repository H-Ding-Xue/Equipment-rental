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
  $IdError = '';
  $PhoneError = '';
  $EmailError = '';
  $ID = '';
  $Password = '';
  $Name = '';
  $Surname = '';
  $Phone = '';
  $Email = '';
  $Type = '';
  if (isset($_POST["Register"])) {
    $ID = $_POST["ID"];
    $Password = $_POST["Password"];
    $Name = $_POST["Name"];
    $Surname = $_POST["Surname"];
    $Phone = $_POST["Phone"];
    $Email = $_POST["Email"];
    $Type = $_POST["Type"];
    $ErrorCount = 0;
    $PhonePattern = "/[896][0-9]{7}$/";
    include('Dbconnect.php');
    $stmt = mysqli_prepare($conn, "SELECT COUNT(1) FROM usertable WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "s", $ID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $res);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    if ($res != 0) {
      $IdError = 'Username is already taken';
      $ErrorCount++;
    }
    if (preg_match($PhonePattern, $Phone) != 1) {
      $PhoneError = ' Invalid phone number ';
      $ErrorCount++;
    }

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
      $EmailError = ' Invalid email address ';
      $ErrorCount++;
    }

    if ($ErrorCount == 0) {
      include('Dbconnect.php');
      $stmt = mysqli_prepare($conn, "INSERT INTO usertable (ID, Password, Name, Surname, Phone, Email, Type) VALUES (?, ?, ?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "sssssss", $ID, $Password, $Name, $Surname, $Phone, $Email, $Type);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      header("Location: Login.php?message=Account Successfully created you can now Login");
      die();
    }
  }
  ?>
  <div class="container mt-4">
    <a href="Login.php" class="btn btn-primary mb-3">Back</a>
    <form action="Register.php" method="post" autocomplete="off" style="width:25%">
      <div class="mb-2">
        <label for="ID" class="form-label">ID:</label><br>
        <input class="form-control" type="text" id="ID" name="ID" required value=<?php echo $ID; ?>>
        <span>
          <?php echo $IdError; ?>
        </span>
      </div>
      <br>
      <div class="mb-2">
        <label for="Password" class="form-label">Password:</label><br>
        <input class="form-control" type="password" id="Password" name="Password" required value=<?php echo $Password; ?>><br>
      </div>

      <div class="mb-2">
        <label for="Name" class="form-label">Name:</label><br>
        <input class="form-control" type="text" id="Name" name="Name" required value=<?php echo $Name; ?>><br>
      </div>

      <div class="mb-2">
        <label for="Surname" class="form-label">Surname:</label><br>
        <input class="form-control" type="text" id="Surname" name="Surname" required value=<?php echo $Surname; ?>><br>
      </div>

      <div class="mb-2">
        <label for="Phone" class="form-label">Phone:</label><br>
        <input class="form-control" type="text" id="Phone" name="Phone" required value=<?php echo $Phone; ?>>
        <span>
          <?php echo $PhoneError; ?>
        </span>
      </div>
      <br>
      <div class="mb-2">
        <label for="Email" class="form-label">Email:</label><br>
        <input class="form-control" type="text" id="Email" name="Email" required value=<?php echo $Email; ?>>
        <span>
          <?php echo $EmailError; ?>
        </span>
      </div>
      <br>
      <div class="mb-2">
        <input type="radio" id="Client" name="Type" value="0" required>
        <label for="Client" class="form-label">Client</label><br>
      </div>

      <div class="mb-2">
        <input type="radio" id="Admin" name="Type" value="1" required>
        <label for="Admin" class="form-label">Admin</label><br><br>
      </div>

      <input class="btn btn-primary" type="submit" name="Register">
    </form>
  </div>
</body>

</html>