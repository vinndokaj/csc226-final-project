<?php
  include "functions.php";
  include "../session.php";

  //If there is no session, redirect to landing page
  if(!isset($_SESSION['user_email'])){
    header("Location: index.php");
    exit(0);
  }

  include '../database.php';
  include 'favoritesHandler.php';

  $query = "SELECT movie.*, f.user_id 
    FROM movie 
    LEFT JOIN ( 
      SELECT * 
      FROM favorite
      WHERE user_id = ?) as f
    ON movie.mid = f.movie_id ORDER BY movie.mid;";

  try {
      $stmt = $conn->prepare($query);
      $stmt->bind_param("i", $_SESSION['uid']);
      $stmt->execute();
      $allMovieResult = $stmt->get_result();
  } catch (Exception $e){
      error_log($e->getMessage());
      exit("Error connecting to the database to get all movies.");
  }
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
    integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <title>Browse Movies</title>
</head>

<body class="bg-light">
  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="home.php">NetflixClone</a>
    <li class="navbar-nav mr-auto">
      <a class="nav-link" href="favorites.php">Favorites</a>
    </li>
    <form class="form-inline">
      <a class="btn btn-sm btn-outline-danger" href="logout.php">Log Out</a>
    </form>
  </nav>

  <div class="container mt-3">
    <p class="mt-5">All Movies</p>
    <hr>
    <div class="row row-cols-3">
      <?php
        while($row = $allMovieResult->fetch_assoc()){
          createMovieCard($row);
        }
      ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
  </script>
</body>

</html>