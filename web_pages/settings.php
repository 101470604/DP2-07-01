<?php
	session_start();
	if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]){
		header ("location: login.php");
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="author" content="Aaron Douglas"  />
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">		
	<link href = "../styles/layout.css" rel="stylesheet"/>
	<link rel="icon" href="phpico.png">
	
	<title>  Peoples Health Pharmacy </title>
</head>

<body>
	<article>
	<!--Table of sales data-->
		<div class="page">
							
			<?php 
				include 'reuseable_fragments/toolbar.php';
			?>			
			
			<h1>Peoples Health Pharmacy Settings</h1>

			<form method="post" action="../php/get_settings.php">	
				
				<input type="submit" class="bottombutton button weekly fa fa-calendar" value="&#xf073;  Weekly Sales" name = 'Weekly'>
				<input type="submit" class="bottombutton button monthly fa fa-calendar" value="&#xf073;  Monthly Sales" name = 'Monthly'>
				<input type="submit" class="bottombutton button all fa fa-calendar" value="&#xf073;  All Sales" name = 'All'>
	
			</form>
		
		
		</div>
	
	</article>
</body>

</html>