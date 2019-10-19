<?php
	session_start();
	if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]){
		header ("location: login.php");
	}
?>

<html>  
<head>  
     <meta charset="UTF-8">
     <title>Peoples Health Pharmacy</title>  
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
     <link href = "../styles/layout.css" rel="stylesheet"/>
     <link rel="icon" href="phpico.png">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>            
     <script src="../libraries/jquery_tabledit/jquery.tabledit.min.js"></script>           
     <script src="../javascript/download.js"></script>
</head>  
<body>  
     <div class="page">  
          <?php 
			include "reuseable_fragments/table_title.php"
		?>

          <div class="table-responsive">    
               <table id="editable_table" class="table">
                    <thead class=table-head >
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
                    <tbody class="table-body">
                         <?php
                              include "../php/displaySales.php";
                              $csvData = getColumns();
                         ?>
                    </tbody>
               </table>
               <div style='display:none' id="tableCSV"><?php echo $csvData ?></div>
          </div>  
          <div class="buttons">
			<button id="downloadCSVbtn" class="bottombutton download fa fa-cloud-download"> Download CSV</button>
			<button id="uploadCSVbtn" class="bottombutton download fa fa-cloud-upload"> Upload CSV</button>
			<a href="data_entry_form.php"><button class="bottombutton addData fa fa-book">  Add Data</button> </a>
		</div>
     </div>  
</body>  
</html>  
<script>  
     $(document).ready(function(){  
          $('#editable_table').Tabledit({
               url:'../php/live_edit.php',
               columns:{
                    identifier:[0, "Sale_ID"],
                    editable:[
                         [1, 'Item_ID'], 
                         [4, 'Quantity_Sold'],
                         [5, 'Date_Sold'], 
                         [6, 'Discount']
                    ]
               },
               restoreButton:false,
               onSuccess:function(data, textStatus, jqXHR)
               {
                    if(data.action == 'delete')
                    {
                         $('#'+data.id).remove();
                    }
                    location.reload();
               }
          });

          $('#downloadCSVbtn').click(()=>{
               var text = $("#tableCSV").text();
               var viewType = document.cookie.replace(/(?:(?:^|.*;\s*)View\s*\=\s*([^;]*).*$)|^.*$/, "$1");
               var filename = `${viewType}SalesData.csv`;
               download(filename, text);
          })
          
     });  
</script>

