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
				
						<table>
							<thead>
								<tr>
									<th>Sale ID</th>
									<th>Item Name</th>
									<th>Quantity Sold</th>
									<th>Date Sold</th>
									<th>Discount</th>
									<!--Do not remove! This is for lining up the tables -->
									<th class = "spacing" ></th>
								</tr>
							</thead>
						</table>

					<div class="table-body">
						<table>
							<tbody>
		
								<?php
								include "php_scripts/displaySales.php";
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