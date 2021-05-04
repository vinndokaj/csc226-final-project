<?php

//Sends user to home page or login page based on cookies
  if(isset($_COOKIE['$cookie_value'] )){
      header("Location: login.php"); 
    }
?>


<div>Welcome</div>