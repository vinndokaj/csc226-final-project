<?php
    include "functions.php";
    
    $errors = [];

    if(isset($_POST['submit'])){
        //Sanitize
        $email = santizeInput($_POST['email']);
        $password = santizeInput($_POST['password']);

        //Create salt and hashed password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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