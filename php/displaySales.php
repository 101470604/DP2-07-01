<?php
include 'db_connection.php';

function showColumns($row_sale)
{
	echo '<tr>
	<td>' . $row_sale['Sale_ID'] . '</td>
	<td>' . $row_sale['Item_ID'] .'</td>
	<td>' . $row_sale['Product_Name'] .'</td>
	<td>$' . $row_sale['Price'] .'</td>
	<td>' . $row_sale['Quantity_Sold'] .'</td>
	<td>' . $row_sale['Date_Sold'] .'</td>
	<td>' . $row_sale['Discount'] . '% </td> </tr>';
	
}

function getRowAsCSV($row_sale){
	return "\r\n" . $row_sale['Sale_ID'] . 
			',' . $row_sale['Item_ID'] .
			',' . $row_sale['Product_Name'] .
			',' . $row_sale['Price'] .
			',' . $row_sale['Quantity_Sold'] .
			',' . $row_sale['Date_Sold'] .
			',' . $row_sale['Discount'] .
			',,'  ;
			
}

function getColumns()
{
	//Returns columns as CSV at end of function
	$csvData = "Sale ID,Item ID,Item Name,Item Price,Quantity Sold,Date Sold,Discount,TotalQuantity,TotalPrice";

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
	

    if(!$db_connection)
	{
		echo "connection failed";
    }
    else 
	{
	$sale_query = mysqli_query($db_connection, "SELECT sales.Sale_ID, sales.Item_ID, items.Product_Name, sales.Quantity_Sold, sales.Date_Sold, items.Price, sales.Discount 
        FROM sales, items 
        WHERE items.Item_ID = sales.Item_ID
        ORDER BY Date_Sold DESC, Sale_ID DESC;");


        if (mysqli_num_rows($sale_query) == 0) 
        {
			echo "no results"; 
            echo $failed_row;
        }
        else
		{
			if((!isset($_COOKIE['View'])) || ($_COOKIE['View'] == 'Total'))
			{
				while ($row_sale = mysqli_fetch_assoc($sale_query))
				{		
					$TotalQuantity = ($row_sale['Quantity_Sold']) + $TotalQuantity;

					if ($row_sale['Discount'] == 0)
					{
						$TotalPrice = $TotalPrice + ($row_sale['Price'] * $row_sale['Quantity_Sold']);
					} 
					else
					{
						$TotalPrice = $TotalPrice + (($row_sale['Price'] * $row_sale['Quantity_Sold']) * ($row_sale['Discount'] / 100));
					}
					showColumns($row_sale);
					$csvData .= getRowAsCSV($row_sale);	
				}
				$csvData .= "\r\n,,,,,,," . $TotalQuantity . "," . $TotalPrice;
			}
			else
			{
				if($_COOKIE['View'] == 'Monthly')
				{
					$year = date('Y');
					$month = date('m');
					
					while ($row_sale = mysqli_fetch_assoc($sale_query))
					{				
						$current = explode("-",$row_sale['Date_Sold']);
							
						if (($current[1] == $month) && ($current[0] == $year))
						{	
							$TotalQuantity = ($row_sale['Quantity_Sold']) + $TotalQuantity;

							if ($row_sale['Discount'] == 0)
							{
								$TotalPrice = $TotalPrice + ($row_sale['Price'] * $row_sale['Quantity_Sold']);
							} 
							else
							{
								$TotalPrice = $TotalPrice + (($row_sale['Price'] * $row_sale['Quantity_Sold']) * ($row_sale['Discount'] / 100));
							}

							showColumns($row_sale);
							$csvData .= getRowAsCSV($row_sale);
						}			
					}
					$csvData .= "\r\n,,,,,,," . $TotalQuantity . "," . $TotalPrice;
				}
				
				if($_COOKIE['View'] == 'Weekly')
				{

					$FirstDay = date("Y-m-d", strtotime('sunday last week'));  
					$LastDay = date("Y-m-d", strtotime('monday next week'));  
					$TotalQuantity = 0;
					$TotalPrice = 0;
					
					while ($row_sale = mysqli_fetch_assoc($sale_query))
					{				
						$Date = ($row_sale['Date_Sold']);
						if ($Date > $FirstDay && $Date < $LastDay)
						{	
							$TotalQuantity = ($row_sale['Quantity_Sold']) + $TotalQuantity;

							if ($row_sale['Discount'] == 0)
							{
								$TotalPrice = $TotalPrice + ($row_sale['Price'] * $row_sale['Quantity_Sold']);
							} 
							else
							{
								$TotalPrice = $TotalPrice + (($row_sale['Price'] * $row_sale['Quantity_Sold']) * ($row_sale['Discount'] / 100));
							}
							showColumns($row_sale);
							$csvData .= getRowAsCSV($row_sale);
						}			
					}
					$csvData .= "\r\n,,,,,,," . $TotalQuantity . "," . $TotalPrice;
				}
				
			}
			
			
			
        }
	}
	
	return $csvData;
}

?>