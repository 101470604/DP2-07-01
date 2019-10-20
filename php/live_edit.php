
<?php  
include 'db_connection.php';
$dbConnection = connectToDb();

$input = filter_input_array(INPUT_POST);

$Item_ID = mysqli_real_escape_string($dbConnection, $input["Item_ID"]);
$Quantity_Sold = mysqli_real_escape_string($dbConnection, $input["Quantity_Sold"]);
$Date_Sold = mysqli_real_escape_string($dbConnection, $input["Date_Sold"]);
$Discount = mysqli_real_escape_string($dbConnection, $input["Discount"]);

if($input["action"] === 'edit')
{
 $query = "
    UPDATE `sales`
    SET Item_ID = '{$Item_ID}', 
    Quantity_Sold = '{$Quantity_Sold}',   
    Date_Sold = '{$Date_Sold}',
    Discount = '{$Discount}'
    WHERE Sale_ID = '{$input["Sale_ID"]}';
    ";

 mysqli_query($dbConnection, $query);

}
if($input["action"] === 'delete')
{
   $query = "DELETE FROM `sales` WHERE Sale_ID = " . mysqli_real_escape_string($dbConnection, $input["Sale_ID"]) . ";";
   mysqli_query($dbConnection, $query);
}

echo json_encode($input);

?>