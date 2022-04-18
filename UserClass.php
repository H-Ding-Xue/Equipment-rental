
<?php
    class User {
        protected $userId;
        protected $password;
        protected $name;
        protected $surname;
        protected $phone;
        protected $email;
        protected $type;
        function __construct($userId) {
          $this->userId = $userId;
        }

        function __destruct(){

	      }
        function searchEquipment($sql, $expectedData,$searchData){
          $searchResult = array ();
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,$sql);
          mysqli_stmt_bind_param($stmt,$expectedData,... $searchData);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          while($row=mysqli_fetch_assoc($result)){
              $searchEntry = array();
              array_push($searchEntry,$row['ID']);
              array_push($searchEntry,$row['Category']);
              array_push($searchEntry,$row['Brand']);
              array_push($searchEntry,$row['Description']);
              array_push($searchEntry,$row['Status']);
              array_push($searchEntry,$row['Regular']);
              array_push($searchEntry,$row['Extended']);
              array_push($searchResult,$searchEntry);
          }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $searchResult;
        }

        function viewSingleEquipment($iD){
          $entry = array ();
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"SELECT * FROM equipment WHERE ID = ?");
          mysqli_stmt_bind_param($stmt,'s',$iD);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          while($row=mysqli_fetch_assoc($result)){
              array_push($entry,$row['ID']);
              array_push($entry,$row['Category']);
              array_push($entry,$row['Brand']);
              array_push($entry,$row['Description']);
              array_push($entry,$row['Status']);
              array_push($entry,$row['Regular']);
              array_push($entry,$row['Extended']);
          }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $entry;
        }

        function viewProfile($iD){
          $profile = array ();
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"SELECT ID, Name, Surname, Phone, Email, Type FROM usertable WHERE ID = ?");
          mysqli_stmt_bind_param($stmt,'s',$iD);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          while($row=mysqli_fetch_assoc($result)){
              array_push($profile,$row['ID']);
              array_push($profile,$row['Name']);
              array_push($profile,$row['Surname']);
              array_push($profile,$row['Phone']);
              array_push($profile,$row['Email']);
              if($row['Type']==1){
                $row['Type']='Administrator';
              }
              if($row['Type']==0){
                $row['Type']='Client';
              }
              array_push($profile,$row['Type']);
              
          }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $profile;
        }

        function viewAvailableOrRented($toSearch){
          $availableProducts = array ();
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"SELECT * FROM equipment WHERE Status = ?");
          mysqli_stmt_bind_param($stmt,'s', $toSearch);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          while($row=mysqli_fetch_assoc($result)){
              $availableEntry = array();
              array_push($availableEntry,$row['ID']);
              array_push($availableEntry,$row['Category']);
              array_push($availableEntry,$row['Brand']);
              array_push($availableEntry,$row['Description']);
              array_push($availableEntry,$row['Status']);
              array_push($availableEntry,$row['Regular']);
              array_push($availableEntry,$row['Extended']);
              array_push($availableProducts,$availableEntry);
          }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $availableProducts;
        }
      }
      class Administrator extends User {
        function insertEquipment($iD, $category, $brand, $description, $status, $regCost, $extCost){
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"INSERT INTO equipment (ID , Category, Brand, Description, Status, Regular, Extended) VALUES (?, ?, ?, ?, ?, ?, ?)");
          mysqli_stmt_bind_param($stmt,"sssssdd", $iD, $category, $brand, $description, $status, $regCost, $extCost);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          echo '<script>alert("Product Inserted")</script>';
        }

        function viewAll(){
          $allProducts = array ();
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"SELECT * FROM equipment");
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          while($row=mysqli_fetch_assoc($result)){
              $entry = array();
              array_push($entry,$row['ID']);
              array_push($entry,$row['Category']);
              array_push($entry,$row['Brand']);
              array_push($entry,$row['Description']);
              array_push($entry,$row['Status']);
              array_push($entry,$row['Regular']);
              array_push($entry,$row['Extended']);
              array_push($allProducts,$entry);
          }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $allProducts;
        }
      }

      class Client extends User {
        function rentProduct($userID,$prodID,$startDate,$endDate,$status,$cost){
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"INSERT INTO rental (UserID, ProdID, Startdate, Enddate,Status,Cost) values (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE Startdate = ?
          , Enddate = ?, Status = ?, Cost = ?");
          mysqli_stmt_bind_param($stmt,"sssssdsssd", $userID, $prodID, $startDate, $endDate, $status, $cost, $startDate, $endDate, $status, $cost);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
          $stmt = mysqli_prepare($conn,"UPDATE equipment SET Status=? WHERE ID = ?");
          mysqli_stmt_bind_param($stmt,"ss", $status, $prodID);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
        }

        function viewCurrentorHistory($iD,$toSearch){
          $final = array ();
          include('Dbconnect.php');
          if($toSearch=='Rented'){
            $stmt = mysqli_prepare($conn,"SELECT equipment.ID , equipment.Category ,equipment.Brand ,equipment.Description ,equipment.Status ,equipment.Regular ,equipment.Extended
            FROM equipment INNER JOIN rental ON rental.ProdID =  equipment.ID WHERE rental.UserID=? AND rental.Status=?");
            mysqli_stmt_bind_param($stmt,"ss", $iD, $toSearch);
          }
          elseif($toSearch=='All'){
            $stmt = mysqli_prepare($conn,"SELECT equipment.ID , equipment.Category ,equipment.Brand ,equipment.Description ,equipment.Status ,equipment.Regular ,equipment.Extended
            FROM equipment INNER JOIN rental ON rental.ProdID =  equipment.ID WHERE rental.UserID=?");
            mysqli_stmt_bind_param($stmt,"s", $iD);
          }
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          while($row=mysqli_fetch_assoc($result)){
            $entry = array();
            array_push($entry,$row['ID']);
            array_push($entry,$row['Category']);
            array_push($entry,$row['Brand']);
            array_push($entry,$row['Description']);
            array_push($entry,$row['Status']);
            array_push($entry,$row['Regular']);
            array_push($entry,$row['Extended']);
            array_push($final,$entry);
        }
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $final;
        }

        function returnProduct($iD,$prodID){
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"UPDATE equipment SET Status = 'Available' WHERE ID = ?");
          echo $prodID;
          mysqli_stmt_bind_param($stmt,"s", $prodID);
          mysqli_stmt_execute($stmt);
          $stmt = mysqli_prepare($conn,"UPDATE rental SET Status = 'Returned' WHERE UserID = ? AND ProdID=?");
          mysqli_stmt_bind_param($stmt,"ss",$iD, $prodID);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
        }

        function extendProduct($iD,$prodID,$dayExtend,$extendCost){
          $result = array();
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"SELECT Enddate,Cost FROM rental WHERE UserID = ? AND ProdID = ?");
          mysqli_stmt_bind_param($stmt,"ss", $iD, $prodID);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $enddate,$cost);
          mysqli_stmt_fetch($stmt);
          $newEndDate = date('Y-m-d', strtotime($enddate. ' + '.$dayExtend.' days'));
          $newCost = $cost +($dayExtend*$extendCost);
          array_push($result,$newCost,$newEndDate);
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          include('Dbconnect.php');
          $stmt = mysqli_prepare($conn,"UPDATE rental SET Enddate = ?,Cost = ? WHERE UserID = ? AND ProdID = ?");
          mysqli_stmt_bind_param($stmt,"sdss", $newEndDate, $newCost ,$iD,$prodID);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          return $result;
        }
      }
?>

