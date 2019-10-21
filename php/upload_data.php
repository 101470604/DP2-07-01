<?php
session_start();
unset($_SESSION["dataUploadFailed"]);

include 'db_connection.php';
$dbConnection = connectToDb();

$item_id   = mysqli_escape_string($dbConnection, $_POST["item_id"]);
$no_sold   = mysqli_escape_string($dbConnection, $_POST["no_sold"]);
$date_sold = mysqli_escape_string($dbConnection, $_POST["date_sold"]);
$discount  = mysqli_escape_string($dbConnection, $_POST["discount"]);

if($dbConnection){
    $query = "SELECT * FROM `ITEMS` WHERE Item_ID = {$item_id}";
        echo $query."<br>";
    $result = mysqli_query($dbConnection, $query);
    if (mysqli_num_rows($result) != 0) {
        $insertQuery = "INSERT INTO `SALES` (Item_ID, Quantity_Sold, Date_Sold, Discount)
                        VALUES ('{$item_id}', '{$no_sold}', '{$date_sold}', '{$discount}');";
        echo $insertQuery;
        mysqli_query($dbConnection, $insertQuery);
        header ('location: ../web_pages/view_SALES_records.php');
    }
    else
    {
        $_SESSION["dataUploadFailed"] = "That item ID does not exist. Please try again.";
        header ('location: ../web_pages/data_entry_form.php');
    }
}
else {
   $_SESSION["dataUploadFailed"] = "Connection to database failed unexpectedly. Please try again.";
   header ('location: ../web_pages/data_entry_form.php');
}

exit();
 
?>