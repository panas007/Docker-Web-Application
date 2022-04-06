<?php

//echo "<p>hello</p>";
//exit();
//if(isset($_POST["submit"])){

    
    $username = $_POST["username"];
    $pass = $_POST["passwo"];


    require_once 'db_handler.php';
    require_once 'index_functions.php';
    loginUser($conn, $username ,$pass);
//}
//else{
    //header("location: http://php_logic/index.php");
    exit();
//}




?>