<?php

//Reports mysqli errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
  //Define the constants
  define("servername", "localhost");
  define("username", "root");
  define("password", "password");
  define("database", "NetflixClone");

  // Create connection
  $conn = new mysqli(servername, username, password, database);
  
  $conn -> set_charset("utf8mb4");
} catch(Exception $e){
  error_log($e ->getMessage());
  exit("Error connecting to the database."); //Error message
}

?>