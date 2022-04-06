<?php

$server="db";
$dbUsername='root';
$dbpPassword="example";
$dbName="users_db";



$conn= mysqli_connect($server,$dbUsername,$dbpPassword,$dbName);


//Check if the connection is a success
if(!$conn)
{
    die("db Error, connection failed ". mysqli_connect_error());

}
?>