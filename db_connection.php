<?php

function connectToDb() : mysqli {
    include 'db_login.php';
    $dbConnection = new mysqli($servername, $username, $password, $database);

    if  (!$dbConnection)
    {
        echo "connection failed";
        die("Connection Failed: " . mysqli_connect_error());
        
    }

    return $dbConnection;
}

function initItemsTable($dbConnection)
{
    $query = "SELECT * FROM ITEM LIMIT 1";
    $result = mysqli_query($dbConnection, $query);

    if (empty($result))
    {
        $query = "CREATE TABLE ITEMS ( 
            Item_ID int (11) NOT NULL AUTO_INCREMENT, 
            Product_Name varchar (30), 
            Price decimal (7,2) NOT NULL,
            PRIMARY KEY (Item_ID)
            );";

        $result = mysqli_query($dbConnection, $query);
        if (empty($result))
        {
            // TO DO : EXCEPTIONS // ERRORS
        }
    }
}

function initSalestable($dbConnection)
{
    $query = "SELECT * FROM SALES LIMIT 1";
    $result = mysqli_query($dbConnection, $query);

    if (empty($result))
    {
        $query = "CREATE TABLE SALES (
            Sale_ID int (11) NOT NULL,
            Item_ID int (11) NOT NULL,
            Quantity_Sold int(11) NOT NULL,
            Date_Sold date NOT NULL,
            Discount int (5),
            PRIMARY KEY (Sale_ID, Item_ID),
            FOREIGN KEY (Item_ID) REFERENCES ITEMS(Item_ID)
        );";

        $result = mysqli_query($dbConnection, $query);
        if (empty($result))
        {
            // TO DO : EXCEPTIONS // ERRORS
        }
    }
}


// Initialize DB of items
function populateTestData($dbConnection)
{
    $query = "INSERT INTO ITEMS (Product_Name, Price) VALUES 
    ('Multivitamin', 31.99),
    ('Toothpaste', 3.99),
    ('Baby Formula', 20.49),
    ('Cold and Flu', 17.99),
    ('Betadine', 7.99),
    ('Primer', 24.99),
    ('Makeup Remover', 4.99),
    ('Shampoo', 9.99);
    ";

    mysqli_query($dbConnection, $query);
}



?>