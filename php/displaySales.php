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
	<td>' . $row_sale['Discount'] . '% </td>
	<td>' ;
	if ($row_sale['Percent_Change'] > 0)
	{
		echo '+';
	}
	echo $row_sale['Percent_Change'] .'% </td>
	</tr>';
	
	
}

function getRowAsCSV($row_sale){
	return "\r\n" . $row_sale['Sale_ID'] . 
			',' . $row_sale['Item_ID'] .
			',' . $row_sale['Product_Name'] .
			',' . $row_sale['Price'] .
			',' . $row_sale['Quantity_Sold'] .
			',' . $row_sale['Date_Sold'] .
			',' . $row_sale['Discount'] .
			',' . $row_sale['Percent_Change'] ;
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
		<td> N/A </td>
    </tr>';
	

    if(!$db_connection)
	{
		echo "connection failed";
    }
    else 
	{
		generatePrediction();
        $sale_query = mysqli_query($db_connection, "SELECT SALES.Sale_ID, SALES.Item_ID, ITEMS.Product_Name, 
		SALES.Quantity_Sold, SALES.Date_Sold, ITEMS.Price, SALES.Discount, PREDICTION.Percent_Change
        FROM SALES, ITEMS, PREDICTION
        WHERE ITEMS.Item_ID = SALES.Item_ID AND PREDICTION.Item_ID = ITEMS.Item_ID
		ORDER BY Date_Sold;");
		

        if (mysqli_num_rows($sale_query) == 0) 
        {
 
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
					
							showColumns($row_sale);
							$csvData .= getRowAsCSV($row_sale);
						}			
					}
					$csvData .= "\r\n,,,,,,," . $TotalQuantity . "," . $TotalPrice;
				}
				
			}
			
		}
		return $csvData;
	}
	



?>