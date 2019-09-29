<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="author" content="Aaron Douglas"  />
  <meta charset="UTF-8">
  
  <link href = "styles/layout.css" rel="stylesheet"/>

  <title> Peoples Health Pharmacy </title>
</head>

<body>
	<article>
	
	<!--Table of sales data-->
	
	
				<div class="page">
				
					<h1>Peoples Health Pharmacy Sales Records</h1>
					<div class="table-head">
						<table>
							<thead>
								<tr>
									<th>Sale ID</th>
									<th>Item ID</th>
									<th>Item Name</th>
									<th>Item Price</th>
									<th>Quantity Sold</th>
									<th>Date Sold</th>
									<th>Discount</th>
									<!--Do not remove! This is for lining up the tables -->
									<th class = "spacing" ></th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="table-body">
						<table>
							<tbody>	
								<?php
									include 'db_connection.php';
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

									if($db_connection){
										$sale_query = mysqli_query($db_cnnection, "SELECT * FROM SALES");
										if (!mysqli_num_rows($sale_query) == 0) {
											while ($row_sale = mysqli_fetch_assoc($sale_query)){
												$sale_id = $row_sale['Sale_ID'];
												$item_id = $row_sale['Item_ID'];
												$quanity_sold = $row_sale['Quanity_Sold'];
												$date_sold = $row_sale['Date_Sold'];												
												$discount = $row_sale['Discount'];

												$item_query = mysqli_query($db_connection, "SELECT * FROM ITEMS WHERE Item_ID = $item_id");
												if (!mysqli_num_rows($item_query) == 0) {
													$row_item = mysqli_fetch_assoc($item_query);
													$item_name = $row_item['Product_Name'];
													$item_price = $row_item['Price'];

													echo '<tr>
														<td> $sale_id </td>
														<td> $item_id </td>
														<td> $item_name </td>
														<td> $item_price </td>
														<td> $quanity_sold </td>
														<td> $date_sold </td>
														<td> $discount </td>
													</tr>';
												}
												else{
													echo $failed_row;
												}
											}
										}
										else{
											echo $failed_row;
										}
									}
									else{
										echo $failed_row;
									}
								?>
															

							</tbody>
						</table>						
					</div>

					<div class="buttons">
						<button class="button download"> Download CSV</button>
						<a href="addData.html"><button class="button addData">  Add Data</button>
					</div>
				</div>
	
	
	</article>
</body>

</html>