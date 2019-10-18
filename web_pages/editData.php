<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="author" content="Aaron Douglas"  />
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">		
	<link href = "styles/layout.css" rel="stylesheet"/>
	<link rel="icon" href="phpico.png">
	
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
			
			<h1>Peoples Health Pharmacy Edit Data</h1>
		
			<!-- form -->
			<form method="post" action="php_scripts/submit_data.php">
				<label for="item_id">Item ID</label>
				<input type="text" id="item_id" name="item_id" placeholder="Enter the item ID.." pattern="[0-9]{1, 11}" required>

				<label for="no_sold">Number of Item Sold</label>
				<input type="text" id="no_sold" name="no_sold" placeholder="Enter the amount of this item in the sale.." pattern="[0-9]{1, 11}" required>	
			
				<label for="date_sold">Date of Sale</label>
				<input type="date" id="date_sold" name="date_sold" placeholder="Enter enter the date of sale.." required>

				<label for="discount">Discount Applied</label>
				<input type="text" id="discount" name="discount" placeholder="Enter enter any applied discount (0 is a valid answer).." pattern="[0-9]{0, 5}" required>	
			
				<div class="buttons">						
					<input type="submit" class="bottombutton button removeData fa fa-trash" value="&#xf1f8; Delete">
					<input type="submit" class="bottombutton button saveData fa fa-save" value="&#xf0c7;  Save Data">
				</div>

			</form>
			
		</div>
	
	</article>
</body>

</html>