<?php
include "db_connection.php";

function getColumns($dbConnection)
{
        $query = "SELECT sales.Sale_ID, items.Product_Name, sales.Quantity_Sold, sales.Date_Sold, sales.Discount 
        FROM sales, items 
        WHERE items.Item_ID = sales.Item_ID
        ORDER BY Date_Sold;";
        /*"SELECT `COLUMN_NAME` 
        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
        WHERE `TABLE_SCHEMA`='sales_records' 
        AND `TABLE_NAME`='sales';";*/
    $result = mysqli_query($dbConnection, $query);

    if (empty($result))
    {
        // TODO ERRORS / EXCEPTIONS
        echo "Unable to retrieve sales data.";
    }
    else
    {
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                echo "<tr>";
                echo "<td>" . $row["Sale_ID"] . "</td>";
                echo "<td>" . $row["Product_Name"] . "</td>";
                echo "<td>" . $row["Quantity_Sold"] . "</td>";
                echo "<td>" . $row["Date_Sold"] . "</td>";
                echo "<td>" . $row["Discount"] . "</td>";
                echo "</tr>";
            }
        }
    }
}

// MAIN BODY
$dbConnection = connectToDb();
initItemsTable($dbConnection);
initSalestable($dbConnection);
getColumns($dbConnection);

?>