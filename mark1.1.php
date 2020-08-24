<?php

    $error_empty_msg = "Please, fill this field to continue";

    
if ($_POST['form_name'] == 'to_sign') {
    
    $sign_msg = [];

    $name = filter_var(trim($_POST['sign_username']), FILTER_SANITIZE_STRING);
    $mail = filter_var(trim($_POST['sign_mail']), FILTER_SANITIZE_EMAIL);
    $password = filter_var(trim($_POST['sign_pwd']), FILTER_SANITIZE_STRING);
    $repeated_password = filter_var(trim($_POST['sign_repeat_pwd']), FILTER_SANITIZE_STRING);

    if(empty($name) || empty($mail) || empty($password) || empty($repeated_password) || $password != $repeated_password){

        if(empty($name)){$sign_msg['sign_username_error_msg'] = $error_empty_msg;}
        if(empty($mail)){$sign_msg['sign_mail_error_msg'] = $error_empty_msg;}
        if(empty($password)){$sign_msg['sign_pwd_error_msg'] = $error_empty_msg;}
        if(empty($repeated_password)){$sign_msg['sign_repeat_pwd_error_msg'] = $error_empty_msg;}
        if($password != $repeated_password){$sign_msg['sign_repeat_pwd_error_msg'] = "Password confirmation and Password must match.";}

        print_r(json_encode($sign_msg));
        return;
    }

    else{
        //connect to database
        include 'includes/pdo_connect.php';

        $checkQuery="SELECT * FROM user where (username=:username ||  email=:email)";
        $check = $dbh -> prepare($checkQuery);
        $check->bindParam(':username', $name, PDO::PARAM_STR);
        $check->bindParam(':email', $mail, PDO::PARAM_STR);
        $check->execute();
        $results = $check->fetchAll(PDO::FETCH_OBJ);
        
        if($check->rowCount() == 0) {
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("INSERT INTO user (username, email, pwd) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hash_password, PDO::PARAM_STR);
            $stmt->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $sign_msg['sign_success_msg'] = "You have signup successfully";
            } else {
                
                $sign_msg['sign_problem_msg'] = "Something wrong here. Try again";
            }
        }
        else {
                $sign_msg['sign_problem_msg'] = "Username or Email already in use Please choose a different one";
        }

        print_r(json_encode($sign_msg));
    }
}


if ($_POST['form_name'] == 'to_log') {
    
    $log_msg = [];

    $uname_or_mail = filter_var(trim($_POST['log_uname_or_mail']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST['log_pwd']), FILTER_SANITIZE_STRING);


    if(empty($uname_or_mail) ||  empty($password)){

        if(empty($uname_or_mail)){$log_msg['log_uname_or_mail_error_msg'] = $error_empty_msg;}
        if(empty($password)){$log_msg['log_pwd_error_msg'] = $error_empty_msg;}

        print_r(json_encode($log_msg));
        return;
    }

    else{
        //connect to database
        include 'includes/pdo_connect.php';

       
            
            $stmt = $dbh->prepare("SELECT user_id, username, pwd FROM user WHERE username = ? OR email = ? ");
            $stmt->bindParam( 1, $uname_or_mail, PDO::PARAM_STR);
            $stmt->bindParam( 2, $uname_or_mail, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 0){
                $log_msg['log_problem_msg'] = "Username, Email and/or Password are not correct. Please try again";
            }
            elseif($stmt->rowCount() == 1){
                if(password_verify($password, $results['pwd'])){
                    session_start();

                    //search for user's likes and rating and store them in session
                    $stmt = $dbh->prepare("SELECT book_id, book_like, book_rate FROM library WHERE user_id = ?");
                    $stmt->bindParam( 1, $results['user_id']);
                    $stmt->execute();
                    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_UNIQUE);


                    $_SESSION["records"] = $records;


                    $_SESSION["username"] = $results['username'];
                    $_SESSION["user_id"] = $results['user_id'];
                    $log_msg['log_success'] = "home.php";
                }
                else{
                    $log_msg['log_problem_msg'] = "Username, Email and/or Password are not correct. Please try again";
                }
            }
            else{
                $log_msg['log_problem_msg'] = "Something wrong here. Please contact our help center";
            }

            print_r(json_encode($log_msg));

    }       
    
}
?>