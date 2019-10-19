<?php
    include 'toolbar.php';

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