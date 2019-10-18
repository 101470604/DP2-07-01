<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="author" content="Aaron Douglas"  />
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
	<link href = "styles/layout.css" rel="stylesheet"/>
	<link rel="icon" href="phpico.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script> 
	<script src="tabledit_plugin.js"></script>
	<title> Peoples Health Pharmacy </title>
</head>

<body>
	<article>
	
		<!--Table of sales data-->
			<div class="page">
			
				<div>					
					<a href="login.html"><button class="topbutton signout fa fa-lock"> Sign Out</button></a>
					<a href="settings.html"><button class="topbutton settings fa fa-gear"> Settings</button></a> 				
				</div>	
				
			<?php 
			Echo '<h1>Peoples Health Pharmacy ';
			
			//check if a view has been selected in settings
			if(!isset($_COOKIE['View']))
			{
				Echo 'Total Sales Records</h1>';
			}
			else
			{
				Echo $_COOKIE['View'] . ' Sales Records</h1>';
			}
			
			?>
			
				
				<div class="table-head">
					<table class="editable" id="editable">
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
							include "php_scripts/displaySales.php";
							$dbConn = connectToDb();
							initItemsTable($dbConn);
							initSalestable($dbConn);
							
							getColumns();


							?>
							<script>
									$('#editable').editableTableWidget();
							</script>
						</tbody>
					</table>						
				</div>
	
				<div class="buttons">
					<button class="bottombutton download fa fa-cloud-download"> Download CSV</button>
					<button class="bottombutton download fa fa-cloud-upload"> Upload CSV</button>
					<a href="addData.html"><button class="bottombutton addData fa fa-book">  Add Data</button> </a>
				</div>
			</div>
		</article>
</body>

</html>