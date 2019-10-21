<?php

function connectToDb() : mysqli {
    include 'db_login.php';
    $dbConnection = new mysqli($servername, $username, $password, $database);

    if  (!$dbConnection)
    {
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
    mysqli_query($dbConnection, $query);    mysqli_query($dbConnection, $query);

        $result = mysqli_query($dbConnection, $query);

        $query = "ALTER TABLE ITEMS ADD CONSTRAINT item_unique UNIQUE(Product_Name);";
    mysqli_query($dbConnection, $query);    mysqli_query($dbConnection, $query);
        $result = mysqli_query($dbConnection, $query);
    }
}

function initSalestable($dbConnection)
{
    $query = "SELECT * FROM SALES LIMIT 1";
    $result = mysqli_query($dbConnection, $query);
    
    if (empty($result))
    {
        $query = "CREATE TABLE SALES (
            Sale_ID int (11) AUTO_INCREMENT,
            Item_ID int (11) NOT NULL,
            Quantity_Sold int(11) NOT NULL,
            Date_Sold date NOT NULL,
            Discount int (5),
            PRIMARY KEY (Sale_ID, Item_ID),
            FOREIGN KEY (Item_ID) REFERENCES ITEMS(Item_ID)
        );";
    mysqli_query($dbConnection, $query);    mysqli_query($dbConnection, $query);

        $result = mysqli_query($dbConnection, $query);
    }
}

// Range == "Weekly" || "Monthly", simply sets date range
function initPredictTable()
{
    
    $dbConnection = connectToDb();
    $query = "CREATE TABLE IF NOT EXISTS PREDICTION(
        Item_ID INT (11) NOT NULL,
        Avg_1 DECIMAL (7,2),
        Avg_2 DECIMAL (7,2),
        Percent_Change DECIMAL (7,2),
        PRIMARY KEY (Item_ID),
        FOREIGN KEY (Item_ID) REFERENCES ITEMS(Item_ID)
    );";
    mysqli_query($dbConnection, $query);    mysqli_query($dbConnection, $query);
    
    mysqli_query($dbConnection, $query);
}

function generatePrediction()
{   
    initPredictTable();
    // Reset the PREDICTIONs
    $dbConnection = connectToDb();
    $query = "DELETE FROM PREDICTION;";
    mysqli_query($dbConnection, $query);
    $result = mysqli_query($dbConnection, $query);

    $query = "Select Item_ID from ITEMS";
    $result = mysqli_query($dbConnection, $query);
    
    $startDate1 = "";
    $startDate2 = "";
    $endDate1 = "";
    $endDate2 = "";

    if((!isset($_COOKIE['View'])) || ($_COOKIE['View'] == 'Total') || ($_COOKIE['View'] == 'Monthly'))
    {
        $startDate1 = "'2019-09-01'";
        $startDate2 = "'2019-09-30'";
        $endDate1 = "'2019-10-01'";
        $endDate2 = "'2019-10-01'";
    }
    else
    {
        $startDate1 = "'2019-10-01'";
        $startDate2 = "'2019-10-14'";
        $endDate1 = "'2019-10-15'";
        $endDate2 = "'2019-10-21'";
        
    }

    // Ensure that a row exists for each item ID 
    while($row = $result->fetch_assoc())
    {
        $query = "INSERT INTO PREDICTION (Item_ID) VALUES (" . $row["Item_ID"]  . ");";
        mysqli_query($dbConnection, $query);    
        
        $query = "UPDATE PREDICTION SET Avg_1 = (SELECT AVG(Quantity_Sold) from SALES WHERE Item_ID = ". $row["Item_ID"] ." AND Date_Sold BETWEEN " . $startDate1 ." AND " . $endDate1 .") WHERE Item_ID = " . $row["Item_ID"] .';';
        mysqli_query($dbConnection,$query);
        
        $query = "UPDATE PREDICTION SET Avg_2 = (SELECT AVG(Quantity_Sold) from SALES WHERE Item_ID = ". $row["Item_ID"] ." AND Date_Sold BETWEEN " . $startDate2 ." AND " . $endDate2 .") WHERE Item_ID = " . $row["Item_ID"] . ";";
        mysqli_query($dbConnection,$query);
       
        $query = "UPDATE PREDICTION set Percent_Change = (SELECT ((Avg_2 - Avg_1) / Avg_2) * 100 from PREDICTION WHERE Item_ID = " . $row["Item_ID"] .") WHERE Item_ID = " . $row["Item_ID"] .";";
        mysqli_query($dbConnection,$query);
      }

}


function initAuthTable($dbConnection)
{
    $query = "SELECT * FROM USERAUTH LIMIT 1";
    $result = mysqli_query($dbConnection, $query);
    
    if (empty($result))
    {
        $query = "CREATE TABLE USERAUTH ( 
            Username varchar (30) NOT NULL PRIMARY KEY, 
            Password varchar (30) NOT NULL
        );";
    mysqli_query($dbConnection, $query);    mysqli_query($dbConnection, $query);

        $result = mysqli_query($dbConnection, $query);

        $query = "INSERT INTO USERAUTH (Username, Password) VALUES ('admin', 'admin');";
    mysqli_query($dbConnection, $query);    mysqli_query($dbConnection, $query);
        $result = mysqli_query($dbConnection, $query);
    }
}


// Initialize DB of ITEMS
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

    $query = "ALTER TABLE SALES AUTO_INCREMENT = 1";
    mysqli_query($dbConnection, $query);
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (6,17,'2019-10-16',6),(7,45,'2019-10-10',17),(5,35,'2019-10-10',17),(8,45,'2019-10-19',10),(2,45,'2019-10-05',14),(7,10,'2019-10-07',14),(8,22,'2019-10-19',7),(3,24,'2019-10-14',6),(7,43,'2019-10-03',14),(4,23,'2019-10-07',17);";
    mysqli_query($dbConnection, $query);   
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (7,32,'2019-10-14',7),(4,21,'2019-10-15',16),(3,21,'2019-10-09',6),(8,18,'2019-10-08',7),(7,13,'2019-10-06',1),(5,9,'2019-10-02',13),(4,7,'2019-10-06',12),(8,22,'2019-10-03',7),(4,17,'2019-10-11',13),(5,38,'2019-10-13',10);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (1,14,'2019-10-02',11),(6,12,'2019-10-18',3),(7,26,'2019-10-15',2),(8,19,'2019-10-14',15),(4,3,'2019-10-19',7),(6,22,'2019-10-05',13),(5,32,'2019-10-10',9),(8,13,'2019-10-05',13),(1,20,'2019-10-18',5),(6,44,'2019-10-08',11);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (6,32,'2019-10-12',1),(1,23,'2019-10-12',5),(2,8,'2019-10-03',2),(2,50,'2019-10-01',13),(2,45,'2019-10-04',17),(1,10,'2019-10-15',4),(2,2,'2019-10-11',15),(7,6,'2019-10-02',15),(2,20,'2019-10-12',15),(2,12,'2019-10-20',17);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT GNORE INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (6,39,'2019-10-07',13),(7,18,'2019-10-08',4),(5,47,'2019-10-04',5),(2,24,'2019-10-15',14),(3,15,'2019-10-16',10),(6,17,'2019-10-03',14),(3,19,'2019-10-02',14),(3,33,'2019-10-06',5),(2,33,'2019-10-15',3),(3,27,'2019-10-11',5);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (6,17,'2019-10-14',4),(2,11,'2019-10-08',10),(2,23,'2019-10-01',8),(1,46,'2019-10-13',8),(7,37,'2019-10-08',8),(7,35,'2019-10-15',15),(3,48,'2019-10-17',17),(6,42,'2019-10-04',9),(2,1,'2019-10-13',8),(7,25,'2019-10-20',8);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,44,'2019-10-07',7),(3,1,'2019-10-13',3),(5,22,'2019-10-03',11),(4,32,'2019-10-01',2),(5,21,'2019-10-16',14),(2,22,'2019-10-09',3),(1,19,'2019-10-19',7),(4,44,'2019-10-06',11),(6,29,'2019-10-20',15),(7,49,'2019-10-15',10);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,39,'2019-10-11',11),(4,26,'2019-10-13',17),(8,44,'2019-10-01',16),(4,48,'2019-10-15',16),(4,49,'2019-10-08',8),(3,35,'2019-10-14',13),(2,32,'2019-10-09',13),(5,8,'2019-10-06',17),(7,21,'2019-10-14',14),(8,23,'2019-10-02',4);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (2,4,'2019-10-05',13),(4,32,'2019-10-06',5),(4,20,'2019-10-11',14),(1,25,'2019-10-19',7),(1,15,'2019-10-12',2),(7,48,'2019-10-08',16),(1,20,'2019-10-08',17),(3,37,'2019-10-03',10),(7,29,'2019-10-04',6),(7,45,'2019-10-14',9);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (8,49,'2019-10-20',17),(1,20,'2019-10-10',2),(3,33,'2019-10-05',7),(1,19,'2019-10-20',4),(3,45,'2019-10-07',9),(7,39,'2019-10-18',14),(4,19,'2019-10-06',7),(1,3,'2019-10-12',11),(6,6,'2019-10-06',9),(6,9,'2019-10-16',7);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (2,39,'2019-10-07',17),(2,46,'2019-10-10',14),(8,29,'2019-10-14',10),(2,26,'2019-10-12',10),(1,48,'2019-10-11',6),(3,43,'2019-10-19',3),(6,16,'2019-10-18',11),(4,17,'2019-10-14',11),(2,18,'2019-10-02',9),(3,7,'2019-10-14',1);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,14,'2019-10-02',16),(3,30,'2019-10-13',16),(3,5,'2019-10-18',7),(7,39,'2019-10-08',1),(2,44,'2019-10-03',13),(6,33,'2019-10-15',11),(2,44,'2019-10-18',9),(1,26,'2019-10-09',2),(3,10,'2019-10-03',8),(5,18,'2019-10-05',8);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (8,9,'2019-10-18',6),(1,10,'2019-10-13',9),(5,15,'2019-10-05',16),(3,42,'2019-10-12',1),(8,5,'2019-10-20',6),(4,27,'2019-10-10',10),(4,3,'2019-10-03',3),(5,6,'2019-10-10',16),(1,37,'2019-10-14',1),(5,31,'2019-10-09',6);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,44,'2019-10-14',15),(3,45,'2019-10-01',9),(4,16,'2019-10-12',17),(1,18,'2019-10-08',14),(5,21,'2019-10-18',10),(5,7,'2019-10-16',12),(6,3,'2019-10-15',14),(6,31,'2019-10-03',7),(4,41,'2019-10-14',14),(3,50,'2019-10-14',9);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (7,28,'2019-10-17',1),(5,15,'2019-10-17',6),(6,31,'2019-10-04',13),(8,17,'2019-10-19',8),(1,15,'2019-10-01',5),(8,40,'2019-10-02',11),(2,47,'2019-10-04',12),(2,48,'2019-10-08',8),(8,3,'2019-10-15',1),(1,45,'2019-10-02',2);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (7,30,'2019-10-12',16),(5,27,'2019-10-14',9),(3,4,'2019-10-15',15),(2,36,'2019-10-17',10),(4,27,'2019-10-11',4),(4,15,'2019-10-04',14),(5,36,'2019-10-12',14),(8,16,'2019-10-20',7),(8,18,'2019-10-08',17),(8,1,'2019-10-12',17);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (6,6,'2019-10-14',9),(6,41,'2019-10-17',17),(6,13,'2019-10-15',8),(3,18,'2019-10-15',12),(2,32,'2019-10-02',6),(5,1,'2019-10-10',5),(2,33,'2019-10-06',6),(7,44,'2019-10-10',12),(8,48,'2019-10-18',12),(1,11,'2019-10-06',14);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,17,'2019-10-10',5),(1,28,'2019-10-06',12),(7,13,'2019-10-13',14),(1,20,'2019-10-13',15),(6,9,'2019-10-08',6),(6,17,'2019-10-02',9),(8,38,'2019-10-09',2),(3,46,'2019-10-06',8),(6,39,'2019-10-20',5),(8,47,'2019-10-10',12);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,42,'2019-10-05',3),(1,2,'2019-10-11',9),(2,32,'2019-10-05',8),(8,13,'2019-10-02',2),(6,15,'2019-10-19',7),(6,45,'2019-10-05',15),(1,16,'2019-10-13',6),(7,4,'2019-10-12',12),(4,31,'2019-10-08',9),(5,38,'2019-10-15',12);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (8,32,'2019-10-11',16),(2,10,'2019-10-15',15),(7,43,'2019-10-16',14),(6,41,'2019-10-13',12),(4,29,'2019-10-02',8),(1,48,'2019-10-10',9),(2,35,'2019-10-03',12),(3,47,'2019-10-04',16),(2,19,'2019-10-16',16),(3,46,'2019-10-06',3);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (7,33,'2019-10-15',13),(4,5,'2019-10-09',8),(3,19,'2019-10-01',1),(7,48,'2019-10-12',16),(4,39,'2019-10-07',2),(3,32,'2019-10-07',10),(1,18,'2019-10-07',16),(2,9,'2019-10-04',14),(7,48,'2019-10-08',3),(4,34,'2019-10-16',4);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (8,15,'2019-10-18',4),(3,39,'2019-10-07',14),(8,5,'2019-10-12',9),(2,20,'2019-10-06',15),(1,22,'2019-10-18',8),(1,3,'2019-10-13',16),(4,2,'2019-10-14',3),(2,2,'2019-10-07',12),(6,38,'2019-10-15',11),(1,49,'2019-10-15',9);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (1,16,'2019-10-03',13),(2,29,'2019-10-20',7),(1,12,'2019-10-17',7),(3,38,'2019-10-14',14),(1,48,'2019-10-11',1),(7,29,'2019-10-13',5),(1,7,'2019-10-16',17),(7,20,'2019-10-08',15),(8,22,'2019-10-06',15),(4,18,'2019-10-10',7);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (1,18,'2019-10-17',8),(3,20,'2019-10-03',14),(8,32,'2019-10-15',12),(8,22,'2019-10-18',17),(6,15,'2019-10-04',6),(6,10,'2019-10-07',5),(3,24,'2019-10-13',3),(1,44,'2019-10-11',8),(3,27,'2019-10-01',12),(5,40,'2019-10-01',1);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (6,7,'2019-10-01',3),(7,12,'2019-10-12',1),(8,25,'2019-10-03',5),(4,45,'2019-10-07',17),(6,15,'2019-10-16',1),(7,26,'2019-10-05',8),(3,36,'2019-10-18',1),(1,13,'2019-10-20',3),(4,37,'2019-10-08',16),(7,8,'2019-10-07',13);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,45,'2019-10-14',2),(3,14,'2019-10-03',12),(4,3,'2019-10-11',14),(3,45,'2019-10-01',16),(1,4,'2019-10-18',5),(5,2,'2019-10-14',3),(2,38,'2019-10-14',16),(1,23,'2019-10-04',9),(3,17,'2019-10-14',16),(6,22,'2019-10-16',11);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (2,19,'2019-10-13',3),(2,43,'2019-10-05',9),(4,48,'2019-10-08',9),(3,35,'2019-10-12',10),(6,13,'2019-10-16',14),(7,23,'2019-10-08',17),(8,44,'2019-10-17',8),(3,14,'2019-10-12',12),(1,10,'2019-10-01',9),(2,12,'2019-10-18',4);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,30,'2019-10-20',5),(6,12,'2019-10-09',9),(1,24,'2019-10-11',17),(3,14,'2019-10-15',9),(7,16,'2019-10-12',12),(6,20,'2019-10-05',5),(6,27,'2019-10-02',11),(4,1,'2019-10-13',10),(2,7,'2019-10-12',11),(7,38,'2019-10-04',12);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,35,'2019-10-10',13),(8,8,'2019-10-09',16),(3,30,'2019-10-12',17),(1,43,'2019-10-14',6),(6,5,'2019-10-19',9),(7,34,'2019-10-09',17),(4,28,'2019-10-17',13),(7,35,'2019-10-18',17),(7,5,'2019-10-20',13),(3,34,'2019-10-20',3);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,26,'2019-10-15',8),(1,5,'2019-10-17',17),(6,8,'2019-10-07',2),(6,16,'2019-10-03',17),(7,50,'2019-10-01',13),(7,30,'2019-10-13',16),(5,32,'2019-10-09',7),(5,36,'2019-10-02',3),(4,24,'2019-10-19',16),(6,29,'2019-10-09',8);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (1,2,'2019-09-23',7),(3,32,'2019-09-07',4),(4,16,'2019-09-19',8),(3,25,'2019-09-14',10),(2,3,'2019-09-27',4),(1,27,'2019-09-20',3),(4,20,'2019-09-14',4),(5,9,'2019-09-18',4),(8,28,'2019-09-15',5),(1,18,'2019-09-10',3);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,26,'2019-09-25',2),(2,40,'2019-09-18',6),(1,39,'2019-09-03',10),(7,22,'2019-09-02',5),(3,18,'2019-09-27',7),(2,22,'2019-09-28',1),(1,34,'2019-09-26',5),(3,46,'2019-09-08',5),(3,21,'2019-09-15',3),(4,11,'2019-09-27',3));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,38,'2019-09-19',1),(3,42,'2019-09-20',5),(8,2,'2019-09-17',7),(4,12,'2019-09-01',6),(4,23,'2019-09-13',9),(3,6,'2019-09-07',9),(7,25,'2019-09-26',4),(8,7,'2019-09-27',4),(8,7,'2019-09-07',9),(2,48,'2019-09-05',7));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,25,'2019-09-02',9),(7,0,'2019-09-22',4),(2,37,'2019-09-02',7),(5,2,'2019-09-16',9),(2,46,'2019-09-25',9),(7,12,'2019-09-23',2),(4,9,'2019-09-11',4),(7,11,'2019-09-16',10),(3,2,'2019-09-16',2),(3,34,'2019-09-21',10));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,48,'2019-09-22',9),(5,31,'2019-09-16',3),(1,25,'2019-09-27',3),(2,26,'2019-09-18',4),(8,43,'2019-09-08',5),(8,29,'2019-09-16',5),(6,37,'2019-09-13',5),(2,13,'2019-09-17',5),(8,15,'2019-09-22',9),(6,18,'2019-09-05',10));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (2,10,'2019-09-21',4),(8,41,'2019-09-18',1),(2,10,'2019-09-29',2),(8,40,'2019-09-13',7),(7,35,'2019-09-07',4),(5,27,'2019-09-10',9),(8,40,'2019-09-16',1),(7,6,'2019-09-30',2),(7,47,'2019-09-01',7),(2,10,'2019-09-12',10));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,49,'2019-09-12',10),(3,27,'2019-09-10',9),(3,5,'2019-09-02',8),(1,6,'2019-09-10',4),(1,21,'2019-09-17',8),(5,26,'2019-09-06',9),(3,17,'2019-09-15',5),(7,1,'2019-09-24',2),(6,30,'2019-09-17',10),(2,24,'2019-09-13',3));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,6,'2019-09-22',8),(5,30,'2019-09-08',9),(3,43,'2019-09-10',6),(1,48,'2019-09-23',6),(2,26,'2019-09-04',7),(6,15,'2019-09-30',2),(7,9,'2019-09-20',3),(8,41,'2019-09-07',6),(7,21,'2019-09-02',4),(4,36,'2019-09-26',4));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,22,'2019-09-15',7),(3,17,'2019-09-17',3),(4,17,'2019-09-17',2),(6,25,'2019-09-04',4),(7,40,'2019-09-01',1),(8,2,'2019-09-18',1),(3,3,'2019-09-19',4),(2,30,'2019-09-24',1),(4,16,'2019-09-10',2),(1,46,'2019-09-19',4));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (7,33,'2019-09-12',3),(3,38,'2019-09-28',9),(6,39,'2019-09-15',2),(1,40,'2019-09-17',9),(3,27,'2019-09-05',3),(5,36,'2019-09-12',7),(7,24,'2019-09-17',6),(7,13,'2019-09-14',2),(4,3,'2019-09-13',8),(3,43,'2019-09-14',7);";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,30,'2019-09-03',4),(5,19,'2019-09-23',5),(5,33,'2019-09-28',2),(8,43,'2019-09-05',8),(1,22,'2019-09-11',3),(7,11,'2019-09-05',8),(8,29,'2019-09-20',4),(2,21,'2019-09-22',10),(8,50,'2019-09-21',9),(7,19,'2019-09-10',3));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,15,'2019-09-02',4),(1,16,'2019-09-08',7),(7,34,'2019-09-20',7),(5,47,'2019-09-06',8),(8,18,'2019-09-17',3),(5,23,'2019-09-16',7),(8,25,'2019-09-29',2),(6,48,'2019-09-23',5),(3,0,'2019-09-02',10),(8,1,'2019-09-09',8));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,37,'2019-09-04',6),(2,13,'2019-09-08',9),(7,7,'2019-09-07',3),(7,44,'2019-09-23',8),(3,8,'2019-09-10',10),(1,50,'2019-09-05',5),(8,43,'2019-09-02',9),(3,2,'2019-09-13',9),(2,21,'2019-09-11',8),(8,4,'2019-09-08',3));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,38,'2019-09-03',7),(2,38,'2019-09-01',8),(6,19,'2019-09-12',6),(2,45,'2019-09-11',3),(4,49,'2019-09-07',1),(2,46,'2019-09-27',4),(6,46,'2019-09-03',2),(1,48,'2019-09-27',9),(7,25,'2019-09-21',6),(8,32,'2019-09-27',10));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (5,38,'2019-09-26',10),(5,36,'2019-09-30',5),(5,15,'2019-09-14',2),(2,17,'2019-09-05',9),(3,1,'2019-09-24',6),(5,44,'2019-09-01',9),(8,18,'2019-09-10',1),(3,2,'2019-09-18',6),(3,13,'2019-09-16',2),(6,30,'2019-09-16',8));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (2,40,'2019-09-07',6),(3,49,'2019-09-04',8),(3,28,'2019-09-19',2),(6,12,'2019-09-22',8),(3,33,'2019-09-01',1),(3,11,'2019-09-16',8),(1,18,'2019-09-17',1),(4,2,'2019-09-22',6),(7,41,'2019-09-11',10),(8,44,'2019-09-17',5));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (8,42,'2019-09-03',9),(8,18,'2019-09-11',2),(4,37,'2019-09-03',6),(8,39,'2019-09-15',3),(5,12,'2019-09-02',9),(3,48,'2019-09-06',10),(3,5,'2019-09-07',10),(3,26,'2019-09-06',6),(7,21,'2019-09-11',4),(7,32,'2019-09-30',7));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (3,31,'2019-09-10',5),(1,3,'2019-09-03',10),(4,49,'2019-09-08',5),(5,34,'2019-09-18',3),(3,24,'2019-09-08',4),(5,15,'2019-09-08',7),(1,45,'2019-09-12',6),(5,19,'2019-09-01',6),(2,19,'2019-09-22',10),(4,4,'2019-09-08',4));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,2,'2019-09-09',2),(4,18,'2019-09-05',2),(1,28,'2019-09-10',2),(5,36,'2019-09-03',1),(3,22,'2019-09-07',1),(3,7,'2019-09-27',8),(2,11,'2019-09-05',9),(4,33,'2019-09-18',8),(4,19,'2019-09-16',1),(6,14,'2019-09-17',4));";
    mysqli_query($dbConnection, $query);    
    $query = "INSERT INTO SALES (Item_ID,Quantity_Sold,Date_Sold,Discount) VALUES (4,13,'2019-09-10',8),(2,32,'2019-09-21',1),(4,26,'2019-09-14',8),(1,26,'2019-09-20',5),(6,37,'2019-09-19',5),(8,18,'2019-09-15',8),(4,36,'2019-09-12',4),(3,27,'2019-09-03',2),(5,33,'2019-09-04',7),(4,39,'2019-09-10',5));";
    mysqli_query($dbConnection, $query);    
}

?>