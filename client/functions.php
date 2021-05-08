<?php
    function santizeInput($input){
        $input = trim($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    function createMovieCard($info){
        $mid = $info['mid'];
        $imgPath = $info['cover_art'];
        $title = $info['title'];
        $description = $info['description'];
        echo "
        <div class='col mb-3'>
            <div class='card p-3 h-100' style='width: 22rem;'>
                <img src='$imgPath' class='card-img-top' alt='...'>
                <div class='card-body'>
                    <h5 class='card-title'>$title</h5>
                    <p class='card-text'>$description</p>
                    <a href='movie.php?mid=$mid' class='btn btn-primary'>Reviews</a>
                </div>
            </div>
        </div>
        ";
    }
?>