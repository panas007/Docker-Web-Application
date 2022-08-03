<?php

require_once 'orion_handler.php';
header("Access-Control-Allow-Origin: *");
session_start();



if($_POST['type'] === "Add_con"){


    //Adding Concert to mongodb
    $queryString = "type=Add_con";


    $queryString =$queryString. "&title=". $_POST['title']. "&date=". $_POST['date']. "&artistname=". $_POST['artistname']
    . "&category=". $_POST['category']. "&organizerid=". $_POST['organizerid']. "&startdate=". $_POST['startdate']
    . "&enddate=". $_POST['enddate']. "&tickets=". $_POST['tickets'];

    $ch = curl_init();
    $url = "http://restProxy:1030/showOrgReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);
    //var_dump($_POST['tickets']);


    //Create orion Entity on Concert

    $concertid = $response->id;
    //file_put_contents('concertid.txt', print_r($concertid, TRUE));
    create_entity($concertid, $_POST['title'], $_POST['startdate'], $_POST['enddate'], $_POST['organizerid'], $_POST['tickets']);
    
    //Call rest API to show table with added Concert
    $queryString = "type=show_con_org";
    $queryString =$queryString. "&id=". $_POST['organizerid'];

    $ch = curl_init();
    $url = "http://restProxy:1030/showOrgReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);


    echo $response;

    exit();

}
else if($_POST['type'] === "Edit_con"){

    $queryString = "type=Edit_con";


    $queryString =$queryString. "&title=". $_POST['title']. "&date=". $_POST['date']. "&artistname=". $_POST['artistname']
    . "&category=". $_POST['category']. "&organizerid=". $_POST['organizerid']. "&startdate=". $_POST['startdate']
    . "&enddate=". $_POST['enddate']. "&tickets=". $_POST['tickets']. "&id=". $_POST['id'];
    //var_dump($queryString);
    $ch = curl_init();
    $url = "http://restProxy:1030/showOrgReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);

    //Edit orion Entity on Concert
    $concertid = $_POST['id'];
    update_entity($concertid, $_POST['title'], $_POST['startdate'], $_POST['enddate'], $_POST['organizerid'], $_POST['tickets']);
    


    //Call rest API to show table with added Concert
    $queryString = "type=show_con_org";
    $queryString =$queryString. "&id=". $_POST['organizerid'];

    $ch = curl_init();
    $url = "http://restProxy:1030/showOrgReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);


    echo $response;

    exit();

}
else if($_POST['type'] === "Del_con"){

    $queryString = "type=Del_con";


    $queryString =$queryString."&id=". $_POST['id'];

    $ch = curl_init();
    $url = "http://restProxy:1030/showOrgReq.php";
    //var_dump($queryString);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);
    //var_dump($response);

    //Delete orion Entity on Concert
    delete_entity($_POST['id']) ;
    
    //Call rest API to show table with added Concert
    $queryString = "type=show_con_org";
    $queryString =$queryString. "&id=". $_POST['organizerid'];


    $ch = curl_init();
    $url = "http://restProxy:1030/showOrgReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);


    echo $response;

    exit();

}



















?>