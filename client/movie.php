<?php
    include "../session.php";

    if(!isset($_SESSION['user_email'])){
        header("Location: index.php");
        exit(0);
    }

    include '../database.php';

    //if no mid is specificed redirect to homepage 
    if(!isset($_GET['mid'])){
        header("Location: home.php");
    }

    $movie_id = $_GET['mid'];
    $movieQuery = "SELECT * FROM movie WHERE mid=?";

    try{
        $stmt = $conn->prepare($movieQuery);
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $movieResult = $stmt->get_result();
        $movieInfo = $movieResult->fetch_assoc();
    }catch(Exception $e){
        error_log($e->getMessage());
        exit("Error connecting to the database to get movie with id $movie_id.");
    }

    //if user types into movie that doesnt exist redirect to home page for now
    //could do a movie not found page.
    if($movieResult->num_rows === 0){
        header("Location: home.php");
    }

    //TODO new review form: rating, title, content

    //get all movies reviews using movie_id
    $reviewQuery = "SELECT * FROM review WHERE movie_id=?";

    try{
        $stmt = $conn->prepare($reviewQuery);
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $reviewResult = $stmt->get_result();
    }catch(Exception $e){
        error_log($e->getMessage());
        exit("Error connecting to the database to get movie with id $movie_id.");
    }

    //get average review for movie
    $noReviewsFlag = $reviewResult->num_rows === 0;
    $reviews = [];

    $avgReview = 0;
    while($row = $reviewResult->fetch_assoc()){
        array_push($reviews, $row);
        $avgReview += $row['rating'];
    }
    if(!$noReviewsFlag){
        $avgReview = $avgReview / $reviewResult->num_rows;
    }

    //TODO save review to db
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title><?php echo $movieInfo['title']?></title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="home.php">NetflixClone</a>
        <form class="form-inline">
            <a class="btn btn-sm btn-outline-danger" href="logout.php">Log Out</a>
        </form>
    </nav>

    <div class="container my-3">
        <!-- Movie Info -->
        <div class="row">
            <div class="col-4">
                <img class="img-fluid border border-info shadow-lg rounded" src="<?php echo $movieInfo['cover_art']?>" alt="Movie poster cover art.">
            </div>
            <div class="col-8">
                <div class="d-flex flex-column">
                    <p class="display-4 p-3"><?php echo $movieInfo['title']?></p>
                    <div>
                        <span class="font-weight-bold p-3">Rating:</span>
                        <span class="p-3"><?php echo $avgReview === 0 ? 'No Reviews Yet' : $avgReview ?><span>
                    </div>
                    <br>
                    <div>
                        <span class="font-weight-bold p-3">Description</span>
                        <p class="font-weight-light p-3"> <?php echo $movieInfo['description']?> </p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="text-center">
                    <span class="display-4">Reviews</span>
                </div>
            </div>
        </div>
        <!-- new review form -->
        <!-- TODO save review to db -->
        <div class="row p-4 border rounded mb-1">
            <span class="font-weight-bold">Write a new Review!</span>
            <form class="w-100" onsubmit="return false">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Your Title Here (up to 255 characters)" maxlength="255">
                </div>
                <div class="form-group d-inline-flex">
                    <label class="mr-3" for="rating">Rating</label>
                    <select class="form-control" id="rating">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" placeholder="Your review..." rows="4"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
        <!-- All reviews -->
        <div class="row mt-3 mb-1">
            <div class="col">
                <div class="text-center">
                    <span class="h4">User Reviews</span>
                </div>
            </div>
        </div>
        <?php if($noReviewsFlag) : ?>
            <div class="row p-4 border rounded">
                <span class="font-weight-bold">Be the first to write a review!</span>
            </div>
        <?php 
            else :
                foreach($reviews as $review){
                    //consider also displaying username
                    $title = $review['title'];
                    $rating = $review['rating'];
                    $content = $review['content'];
                    echo "
                    <div class='row p-4 border rounded mb-1'>
                        <div class='d-flex flex-column'>
                            <span class='font-weight-bold'>$title</span>
                            <span class='font-italic'>Rating: $rating</span>
                            <span class='text-muted'>$content</span>
                        </div>
                    </div>
                    ";
                }
            endif; 
        ?>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  </body>
</html>