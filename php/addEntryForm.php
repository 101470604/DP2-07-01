<?php

// Functions
function itemExists () : bool {
    
    return true;
}

// MAIN BODY

include 'db_connection.php';
$dbConnection = connectToDb();


$item_id   = mysqli_escape_string($dbConnection, $_POST["item_id"]);
$no_sold   = mysqli_escape_string($dbConnection, $_POST["no_sold"]);
$date_sold = mysqli_escape_string($dbConnection, $_POST["date_sold"]);
$discount  = mysqli_escape_string($dbConnection, $_POST["discount"]);



if($dbConnection){
    if (mysqli_query($dbConnection, "SELECT * FROM ITEMS WHERE Item_ID = $item_id"))
    {
        mysqli_query($dbConnection, "INSERT INTO SALES (Item_ID, Quantity_Sold, Date_Sold, Discount)
                                     VALUES ($item_id, $no_sold, $date_sold, $discount);");
    }
    else
    {
        echo "Item does not exist";
    }
}
else {
    //echo "<p>Database connection failure"</p>
}

header ('location: viewTable.php');
exit();
 
?>