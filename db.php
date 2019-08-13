<?php
    require "./admin/Vendor/php/class.database.php";

    $host = "localhost";
    $username = "root";
    $password = "";
    $database_name = "isk_mnk";
    
//    $conn = mysqli_connect($servername, $username, $password, $database);
//    
//    if(!$conn){
//        die("Database Not Connected : ". mysqli_connect_error());
//    } else {
//        echo "Database connected !!!";
//    }

    $db = new Database($database_name, $username, $password, $host); // $host is optional and defaults to 'localhost'
?>