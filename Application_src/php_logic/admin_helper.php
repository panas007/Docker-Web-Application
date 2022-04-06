<?php
require_once 'db_handler.php';
require_once 'index_functions.php';

if($_POST['type'] === "1"){

    $id = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $pass = $_POST["pass"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $val = $_POST["val"];



    if(invalidEmail($email) !== false){
        showUsersTable($conn);
        exit();
    }

    if(existedUsername($conn , $username,$email) !== false){
        showUsersTable($conn);
        exit();
    }
    if ($val==="1")
        $val=true;
    else
        $val=false;

    $sql = "INSERT INTO users (id, name, surname, username, password, email, role,confirmed) VALUES (?, ?, ?, ?, ?, ?, ?,?);";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo "Fail to prepare SQL";
        exit();
    }

    $hashpww = password_hash($pass,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param( $stmt,"ssssssss",$id,$name,$surname,$username,$hashpww, $email ,$role,$val);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    showUsersTable($conn);

}
else if($_POST['type'] === "2"){



    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE id='$id' ;";

    $qry_result = mysqli_query($conn,$sql);

    $sql = "DELETE FROM favorites WHERE iserid='$id' ;";


    $qry_result = mysqli_query($conn,$sql);

    showUsersTable($conn);
}
else if($_POST['type'] === "3"){

    $id = $_POST["id"];
    $newid = $_POST["newid"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $val = $_POST["val"];

    if ($val==="1")
        $val=true;
    else
        $val=false;

    $sql = "UPDATE users SET id='$newid', name='$name', surname='$surname', username='$username', email='$email', role='$role',confirmed='$val'
            WHERE id='$id' ;";

    if (mysqli_query($conn,$sql)) {
        showUsersTable($conn);
    }else{
        echo "Error";
    }
}



















?>