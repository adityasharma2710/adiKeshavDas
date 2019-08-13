<?php
    require "./db.php";
    session_start();
    if(isset($_SESSION['username'])){
        header("location: ./home.php");
    } else {
        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $db->select('users', ['username'=> $username]);

            if($db->row_array()){
                $db_username = $db->row_array()['username'];
                $db_password = $db->row_array()['password'];

                if($username === $db_username && $password === $db_password){
                    $_SESSION['username'] = $db_username;
                    header("location: ./home.php");
                } else {
                    $response_text = "Please Check Your Username & Password !!!";
                }
            } else {
                $response_text = "user not Exist !!!";
            }
        }
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-image: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url(Resources/img/login-bg.jpg);
            background-size: cover;
        }
        .login {
            position: absolute;
            width: 300px;
            top: 40%;
            left: 50%;
            transform: translate(-50%,-50%);
            text-align: center;
        }
        
        .form-wrapper {
            background-color: rgba(255,255,255,0.5);
            border-radius: 5px;
            padding: 20px;        
        }
        
        .form-wrapper > img {
            width: 200px;
            margin-bottom: 30px;
        }
        .login-form {
            display: flex;
            flex-direction: column;
        }
        .login-form input {
            margin-bottom: 10px;
            padding: 10px;
            box-sizing: border-box;
            border-radius: 5px;
            border: none;
        }
        .login-form input:focus {
          border: 1px solid #333;
          outline: none;
        }
        .login-form input[type=submit] {
            cursor: pointer;
        }
        .login-form input[type=submit]:focus {
            cursor: pointer;
        }
        .response-text {
            color: red;
            margin-bottom: 30px;
            background-color: rgba(255,255,255,1);
            border-radius: 5px;
            padding: 10px;       
        }
    </style>
</head>
<body>
   <div class="login">
        <?php 
            if(isset($response_text)){
        ?>
            <div class="response-text">
                <?php echo $response_text; ?>
            </div>
        <?php
            }
        ?>
        <div class="form-wrapper">
            <img src="Resources/img/logo.png" alt="">
            <form class="login-form" action="login.php" method="post" enctype="multipart/form-data">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="submit">
            </form>
        </div>
   </div>
    
    <!-- To Prevent Data Submission on Refresh -->
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>