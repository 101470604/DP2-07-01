
<?php  
//action.php
$connect = mysqli_connect('localhost', 'root', '', 'sales_records');

$input = filter_input_array(INPUT_POST);

$Item_ID = mysqli_real_escape_string($connect, $input["Item_ID"]);
$Quantity_Sold = mysqli_real_escape_string($connect, $input["Quantity_Sold"]);
$Date_Sold = mysqli_real_escape_string($connect, $input["Date_Sold"]);
$Discount = mysqli_real_escape_string($connect, $input["Discount"]);

if($input["action"] === 'edit')
{
 $query = "
    UPDATE sales
    SET Item_ID = '". $Item_ID ."', 
    Quantity_Sold = '". $Quantity_Sold ."',   
    Date_Sold = '". $Date_Sold ."',
    Discount = '". $Discount ."'
    WHERE Sale_ID = '".$input["Sale_ID"]."';
    ";

 mysqli_query($connect, $query);

}
if($input["action"] === 'delete')
{
   $query = "DELETE FROM sales WHERE Sale_ID = " . mysqli_real_escape_string($connect, $input["Sale_ID"]) . ";";
   mysqli_query($connect, $query);
}

echo json_encode($input);

?>