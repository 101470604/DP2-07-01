<?php
    session_start();
    unset($_SESSION["loggedIn"]);
    header ('location: ../web_pages/login.php');
?>