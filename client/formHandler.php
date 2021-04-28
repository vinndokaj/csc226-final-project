<?php
    function santizeInput($input){
        $input = trim($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    $errors = array(
        'emptyEmail' => false,
        'emptyPassword' => false,
        'emptyConfirmPassword' => false,
        'invalidCredentials' => false,
        'nonMatchingPasswords' => false,
    );
    // $errors = [];

    if(isset($_POST['submit'])){
        $email = santizeInput($_POST['email']);
        $password = santizeInput($_POST['password']);

        if($email === ""){
            $errors['emptyEmail'] = true;
        } else {
            $errors['emptyEmail'] = false;
        }

        if($password === ""){
            $errors['emptyPassword'] = true;
        } else {
            $errors['emptyPassword'] = false;
        }

        if(isset($_POST['confirmPassword'])){
            $confirmPassword = santizeInput($_POST['confirmPassword']);
            
            if($confirmPassword === ""){
                $errors['emptyConfirmPassword'] = true;
            } else {
                $errors['emptyConfirmPassword'] = false;
                if($confirmPassword === $password){
                    $errors['nonMatchingPasswords'] = false;
                } else {
                    $errors['nonMatchingPasswords'] = true;
                }
            }
        }
    }
?>