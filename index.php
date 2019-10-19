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
							

			<h1>Peoples Health Pharmacy Login</h1>
		
			<form method="post" action="php/attempt_login.php">
							
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" placeholder="Enter your username .." required>

				<label for="password">Password:</label>
				<input type="text" id="password" name="password" placeholder="Enter your password .." required>
				
				<p><br/></p>
				
				<input type="submit" class="bottombutton button loginbutton fa fa-sign-in" value="&#xf090; Login">
				
			</form>
			
		</div>
	
	</article>
</body>

</html>