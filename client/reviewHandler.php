<?php
    include 'functions.php';

    $errors = [];

    if(isset($_POST['submit'])){
        $title = santizeInput($_POST['title']);
        $content = santizeInput($_POST['content']);
        $rating = santizeInput($_POST['rating']);


        if($title === ""){
            $errors['emptyTitle'] = 'Please enter a title.';
        }

        if($content === ""){
            $errors['emptyContent'] = 'Please provide a review.';
        }

        if($rating === ""){
            $errors['emptyRating'] = 'Please provide a rating.';
        }
    }
?>