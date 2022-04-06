<?php

if($_POST['type'] === "search_con"){


$queryString = "type=search_con";


$queryString =$queryString. "&term=". $_POST['term'];;

$ch = curl_init();
$url = "http://php_logic/events_helper.php";

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

$response = curl_exec($ch);
curl_close($ch);
echo $response;
//curl_close($ch);
exit();

}

?>