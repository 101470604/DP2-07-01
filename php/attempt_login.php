<?php
    include 'db_connection.php';

    session_start();
    $_SESSION["loggedIn"] = false;

    $dbConnection = connectToDb();
    initAuthTable($dbConnection);

    $username =  mysqli_escape_string($dbConnection, $_POST["username"]);
    $password =  mysqli_escape_string($dbConnection, $_POST["password"]);
    
    $query = "SELECT `Password` FROM `userauth` WHERE Username = '$username'";
    $result = mysqli_query($dbConnection, $query);
    
    if($result){
        $row = mysqli_fetch_assoc($result);
        $_SESSION["loggedIn"] = ($row["Password"] == $password ? true : false);
    }

    if ($_SESSION["loggedIn"]){
        header ('location: ../web_pages/view_sales_records.php');
    }
    else{
        header ("location: ../web_pages/login.php");
    }

    exit();
?>