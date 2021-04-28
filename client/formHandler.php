<?php
    function santizeInput($input){
        $input = trim($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }
    
    $errors = [];

    if(isset($_POST['submit'])){
        $email = santizeInput($_POST['email']);
        $password = santizeInput($_POST['password']);

        if($email === ""){
            $errors['emptyEmail'] = 'Please enter a email address.';
        }

        if($password === ""){
            $errors['emptyPassword'] = 'Please enter a password.';
        }

        if(isset($_POST['confirmPassword'])){
            $confirmPassword = santizeInput($_POST['confirmPassword']);
            
            if($confirmPassword === ""){
                $errors['emptyConfirmPassword'] = 'Please confirm your password.';
            } else {
                if($confirmPassword != $password){
                    $errors['nonMatchingPasswords'] = 'Your passwords do not match.';
                }
            }
        }
    }
?>