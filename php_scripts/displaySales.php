<?php
	include 'php_scripts/db_connection.php';

function getColumns()
{

    $db_connection = connectToDb();
    $failed_row = 
    '<tr>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
        <td> N/A </td>
    </tr>';

    if(!$db_connection){
    }
    else {
        $sale_query = mysqli_query($db_connection, "SELECT sales.Sale_ID, sales.Item_ID, items.Product_Name, sales.Quantity_Sold, sales.Date_Sold, items.Price, sales.Discount 
        FROM sales, items 
        WHERE items.Item_ID = sales.Item_ID
        ORDER BY Date_Sold;");


        if (mysqli_num_rows($sale_query) == 0) 
        {
            echo $failed_row;
        }
        else{
            while ($row_sale = mysqli_fetch_assoc($sale_query)){
                
                echo '<tr>';
                echo '<td>' . $row_sale['Sale_ID'] . '</td>';
                echo '<td>' . $row_sale['Item_ID'] .'</td>';
                echo '<td>' . $row_sale['Product_Name'] .'</td>';
                echo '<td>' . $row_sale['Price'] .'</td>';
                echo '<td>' . $row_sale['Quantity_Sold'] .'</td>';
                echo '<td>' . $row_sale['Date_Sold'] .'</td>';
                echo '<td>' . $row_sale['Discount'] . ' </td> </tr>';
            }
        }
    }
}

?>