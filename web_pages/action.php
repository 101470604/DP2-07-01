<?php  
//action.php
$connect = mysqli_connect('localhost', 'root', '', 'testing');

if (!$connect)
{
    echo "could not connot";
}

$input = filter_input_array(INPUT_POST);

$first_name = mysqli_real_escape_string($connect, $input["Item_ID"]);
$last_name = mysqli_real_escape_string($connect, $input["Quantity_Sold"]);

if($input["action"] === 'edit')
{
 $query = "
 UPDATE sales
 SET Sale_ID = '".$first_name."', 
 last_name = '".$last_name."' 
 WHERE id = '".$input["id"]."'
 ";

 mysqli_query($connect, $query);

}
if($input["action"] === 'delete')
{
 $query = "DELETE FROM sales WHERE Sale_ID = '". mysqli_real_escape_string($connect, $input["Sale_ID"]).";'
 ";
 
 mysqli_query($connect, $query);
}

echo json_encode($input);

?>