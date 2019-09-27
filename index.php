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
				
					<h1>Peoples Health Pharmacy Data Entry</h1>
				
					<!-- form -->
					
					<form action="add_data.php" method="POST">
						<table>

							<tr>
								<td>
									<label for="DATA5">Item ID</label>
								</td>
								<td>
									<input type="text" id="item_id" name="item_id" placeholder="Enter Item ID here..">
								</td>
							</tr>
							<tr>
								<td>
									<label for="DATA5">No. Sold</label>
								</td>
								<td>
									<input type="text" id="no_sold" name="no_sold" placeholder="Enter the no. of items sold here..">
								</td>
							</tr>
							<tr>
								<td>
									<label for="DATA5">Date Sold</label>
								</td>
								<td>
									<input type="date" id="date_sold" name="date_sold" placeholder="--/--/--">
								</td>
							</tr>
							<tr>
								<td>
									<label for="DATA5">Discount</label>
								</td>
								<td>
									<input type="text" id="discount" name="discount" placeholder="Enter any applied discounts here">
								</td>
							</tr>								
						</table>
						<div class="buttons">
								<a href="viewTable.html">	
								<input class = "button enterData" type="submit" value="Enter Data">
								<button class="button Cancel">  Cancel </button>
							</div>
						
					</form>
					
				
					
				</div>
	
	
	</article>
</body>

</html>