<?php
session_start();
/*
require_once 'db_handler.php';
require_once 'index_functions.php';

checkUser($conn);*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>welcome page</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="welcome_css.css" />
</head>
<body>

    <section>
        <div class="main-text">
            <!--<img src="sidepic.jpg" alt="Concert picture" style="" width:auto;"";>-->
        </div>
        <div class="welcome_con">
            <h1 class="form_title">You dont have permission for this page</h1>
            <p class="descr">
                Please press the button and you will redirect to welcome page<br />
            </p>
            <p class="descr"></p>
            <br >
            <br >
            <form name="wrongf" action="php_logic/wrong_helper.php" class="form" id="wr" method="post">

                 <button name="Addnuser"  type="submit" class="btn btn-primary" >Go Back</button>

            </form>

        </div>


    </section>
</body>
</html>
