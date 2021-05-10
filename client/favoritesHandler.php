<?php
    //must be included below a database connection
    if(isset($_POST['favorite'])){
        $query = "INSERT INTO favorite (movie_id, user_id) VALUES (?,?)";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $_POST['favorite'], $_SESSION['uid']);
            $stmt->execute();
            header("Refresh:0");
        } catch(Exception $e){
            error_log($e->getMessage());
            exit("Error inserting favorite into database.");
        }
    }
    if(isset($_POST['unfavorite'])){
        $query = "DELETE FROM favorite WHERE movie_id = ? AND user_id = ?";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $_POST['unfavorite'], $_SESSION['uid']);
            $stmt->execute();
            header("Refresh:0");
        } catch(Exception $e){
            error_log($e->getMessage());
            exit("Error deleting favorite from database.");
        }
    }

?>