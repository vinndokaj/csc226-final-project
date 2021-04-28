<?php
    //TODO check for cookies/session if user types into the url (include file)
    include 'database.php';
    include 'formHandler.php';

    if(isset($_POST['submit']) && count($errors) === 0){
        $query = "INSERT INTO user (email, pass) VALUES (?, ?);";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            var_dump($result);
        } catch(Exception $e) {
            error_log($e->getMessage());
            $errors['userAlreadyExists'] = "An account with that email already exists.";
        }
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
    <link rel="stylesheet" href="css/style.css">

    <title>Login</title>
  </head>
  <body class="bg-image">

    <div class="container mt-5">
        <div class="row">
            <div class="col d-flex justify-content-center align-items-center">
                <div class="card w-75">
                    <div class="card-header">
                        Register
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <!-- TODO error for user with that email already exists -->
                            <small class="text-danger">
                                <?php echo (isset($errors['userAlreadyExists']) ? $errors['userAlreadyExists'] : "" ); ?>
                            </small>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo $_POST['email'] ?? '' ?>">
                                <small class="text-danger">
                                    <?php echo (isset($errors['emptyEmail']) ? $errors['emptyEmail'] : "" ); ?>
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" value="<?php echo $_POST['password'] ?? '' ?>">
                                <small class="text-danger">
                                    <?php 
                                        echo (isset($errors['emptyPassword']) ? $errors['emptyPassword'] : "" ); 
                                        echo (isset($errors['nonMatchingPasswords']) ? $errors['nonMatchingPasswords'] : "" ); 
                                    ?>
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" value="<?php echo $_POST['confirmPassword'] ?? '' ?>">
                                <small class="text-danger">
                                    <?php 
                                        echo (isset($errors['emptyConfirmPassword']) ? $errors['emptyConfirmPassword'] : "" ); 
                                        echo (isset($errors['nonMatchingPasswords']) ? $errors['nonMatchingPasswords'] : "" ); 
                                    ?>
                                </small>                     
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Register</button>
                            <div class="form-group text-center">
                                <small>Already have an account?</small>
                                <br>
                                <a href="login.php">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  </body>
</html>