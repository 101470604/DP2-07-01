<?php 

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
	 
    if (isset($_POST["All"])) 
	{
		setrawcookie('View', 'Total', time() + 86400, "/"); 
    } 
	elseif (isset($_POST["Monthly"]))
	{
		setrawcookie("View", "Monthly", time() + 86400, "/");
	} 
	else if(isset($_POST["Weekly"]))
	{
		setrawcookie("View", "Weekly", time() + 86400, "/");
	}
	header ("location: ../viewTable.php");
	exit();
}
?>