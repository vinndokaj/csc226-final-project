<?php
//Destroy the session data and send user to landing page
session_start();
session_destroy();
header("Location: index.php"); 

?>