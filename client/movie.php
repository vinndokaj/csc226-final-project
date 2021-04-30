<?php
    //TODO check for cookies to allow access

    include '../database.php';

    //if no mid is specificed redirect to homepage 
    if(isset($_GET['mid'])){
        $movie_id = $_GET['mid'];
        $query = "SELECT * FROM movie WHERE mid=?";

        try{
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $movie_id);
            $stmt->execute();
            $movieResult = $stmt->get_result();
        }catch(Exception $e){
            error_log($e->getMessage());
            exit("Error connecting to the database to get movie with id $movie_id.");
        }

        //if user types into movie that doesnt exist redirect to home page
        if($movieResult->num_rows === 0){
            header("Location: home.php");
        }
    } else {
        header("Location: home.php");
    }

    //TODO new review form: rating, title, content

    //TODO get all movies reviews using movie_id

    //TODO page design
?>

<?php 
    while($row = $movieResult->fetch_assoc()){
        foreach($row as $key => $value){
            echo $key.': '.$value.'<br>';
        }
    }
?>