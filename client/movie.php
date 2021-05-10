<?php
    include "../session.php";
    include 'reviewHandler.php';

    if(!isset($_SESSION['user_email']) || !isset($_SESSION['uid'])){
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

    //save review to db
    if(isset($_POST['submit']) && count($errors) === 0){
        $insertReviewQuery = 'INSERT INTO review (title, content, rating, movie_id, user_id) VALUES (?,?,?,?,?)';

        try{
            $stmt = $conn->prepare($insertReviewQuery);
            $stmt->bind_param("ssiii", $title, $content, $rating, $movie_id, $_SESSION['uid']);
            $stmt->execute();
            header("Refresh:0");
        }catch(Exception $e){
            error_log($e->getMessage());
            exit("Error inserting review into the database.");
        }
    }

    //get all movies reviews using movie_id
    $reviewQuery = "SELECT * FROM review WHERE movie_id=?";

    //query to include user email if desired
    //$reviewQuery = 'SELECT r.*, u.email FROM review as r JOIN user as u ON u.uid=r.user_id WHERE movie_id=?;'

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

    if(isset($_POST['deleteReview'])){
        $delQuery = "DELETE FROM review WHERE rid=?";
        try{
            $stmt = $conn->prepare($delQuery);
            $stmt->bind_param("i", $_POST['deleteReview']);
            $stmt->execute();
            header("Refresh:0");
        }catch(Exception $e){
            error_log($e->getMessage());
            exit("Error connecting to the database to delete review.");
        }
    }

    function deleteReview($review_id){
        var_dump("poop");
    }
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
                        <span class="p-3"><?php echo $avgReview === 0 ? 'No Reviews Yet' : round($avgReview, 1)?><span>
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
            <form class="w-100" method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="title" id="title" 
                        value="<?php echo $_POST['title'] ?? '' ?>"
                        placeholder="Your Title Here (up to 255 characters)" 
                        maxlength="255"
                    >
                    <small class="text-danger">
                        <?php echo (isset($errors['emptyTitle']) ? $errors['emptyTitle'] : "" ); ?>
                    </small>
                </div>
                <div class="form-group d-inline-flex">
                    <label class="mr-3" for="rating">Rating</label>
                    <select class="form-control" name="rating" id="rating">
                        <option value="">-- Select --</option>
                        <?php
                            for($i = 1; $i <= 5; $i++){
                                echo "<option value='$i'";
                                if(isset($_POST['rating']) && $_POST['rating'] == $i){
                                    echo " selected";
                                }
                                echo ">$i</option>";
                            }
                        ?>
                    </select>               
                </div>
                <small class="text-danger">
                    <?php echo (isset($errors['emptyRating']) ? $errors['emptyRating'] : "" ); ?>
                </small>  
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea 
                        class="form-control" 
                        name="content" 
                        id="content" 
                        placeholder="Your review..." 
                        rows="4"
                    ><?php echo $_POST['content'] ?? '' ?></textarea>
                    <small class="text-danger">
                        <?php echo (isset($errors['emptyContent']) ? $errors['emptyContent'] : "" ); ?>
                    </small>
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
                    $rid = $review['rid'];
                    $title = $review['title'];
                    $rating = $review['rating'];
                    $content = $review['content'];
                    echo "
                    <div class='row p-4 border rounded mb-1'>
                        <div class=''>
                            <span class='font-weight-bold'>$title</span>
                            <br>
                            <span class='font-italic'>Rating: $rating</span>
                            <br>
                            <span class='text-muted'>$content</span>
                    ";
                    if($_SESSION['uid'] === $review['user_id']){
                        echo "
                            <br>
                            <form method='POST'>
                            <button class='mt-1 btn btn-outline-danger' name='deleteReview' value='$rid'>Delete Review</button>
                            </form>";
                    }
                    echo "
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