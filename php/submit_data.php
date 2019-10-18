<?php

// Functions
function itemExists () : bool {
    
    return true;
}

// MAIN BODY

include 'db_connection.php';

$dbConn = connectToDb();
initItemsTable($dbConn);
initSalestable($dbConn);

$item_id   = mysqli_escape_string($dbConn, $_POST["item_id"]);
$no_sold   = mysqli_escape_string($dbConn, $_POST["no_sold"]);
$date_sold = strtotime(mysqli_escape_string($dbConn, $_POST["date_sold"]));
$discount  = mysqli_escape_string($dbConn, $_POST["discount"]);

$date_sold = date('Y-m-d', $date_sold);

if (mysqli_query($dbConn, "SELECT * FROM ITEMS WHERE Item_ID = $item_id"))
{
    mysqli_query($dbConn, "INSERT INTO SALES (Item_ID, Quantity_Sold, Date_Sold, Discount)
                                 VALUES ($item_id, $no_sold, $date_sold, $discount);");
}
else
{
    echo "Item does not exist";
}

header ('location: ../viewTable.php');
exit();
 
?>