<?php
header("Access-Control-Allow-Origin: *");
require_once 'orion_handler.php';

session_start();


if($_POST['type'] === "search_con"){


    $queryString = "type=search_con";


    $queryString =$queryString. "&term=". $_POST['term'];

    $ch = curl_init();
    $url = "http://restProxy:1030/showConReq.php";

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
    //curl_close($ch);
    exit();

}
else if($_POST['type'] === "rst_tbl"){

    $queryString = "type=rst_tbl";


    $ch = curl_init();
    $url = "http://restProxy:1030/showConReq.php";

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
    //curl_close($ch);
    exit();
}

else if($_POST['type'] === "Add_to_fav"){

    $queryString = "type=Add_to_fav";


    $queryString =$queryString. "&conid=". $_POST['conid']."&title=". $_POST['title']."&date=". $_POST['date'].
    "&userid=". $_POST['userid'];



    $ch = curl_init();
    $url = "http://restProxy:1030/showConReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    
    //Make subscription and get the subid
    if ($response === "Added to Favorites!!!")
        $res=subscribe($_POST['userid'],$_POST['conid']);
    //file_put_contents('response.txt', print_r($response, TRUE));
    //save subid with userid so we can delete it if we want later 
    
    
    if ($response === "Added to Favorites!!!"){
        $queryString = "type=Add_Sub";
        $queryString =$queryString. "&conid=". $_POST['conid']."&subid=".$res.
        "&userid=". $_POST['userid'];

        $ch = curl_init();
        $url = "http://restProxy:1030/showConReq.php";

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            "X-Auth-Token: Secret_key"
        ));

        $resp = curl_exec($ch);
        curl_close($ch);
    }

    // $res is the subscription id and can be used to update/delete the subscription
    //var_dump($res);
    echo $response;
    exit();

}
else if($_POST['type'] === "del_fav"){

    $queryString = "type=del_fav";


    $queryString =$queryString. "&conid=". $_POST['conid']."&userid=". $_POST['userid'];


    //Delete from favorites
    $ch = curl_init();
    $url = "http://restProxy:1030/showConReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    //Get subid to delete the subscription 
    $queryString = "type=Get_sub";


    $queryString =$queryString. "&concertid=". $_POST['concertid']."&userid=". $_POST['userid'];
    //file_put_contents('concertid.txt', print_r($queryString, TRUE));

    $ch = curl_init();
    $url = "http://restProxy:1030/showConReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $subid = curl_exec($ch);
    curl_close($ch);

    //delete sub  from our mongo db
    $queryString = "type=Del_Sub";


    $queryString =$queryString. "&concertid=". $_POST['concertid']."&userid=". $_POST['userid']."&subid=".$subid;
    $ch = curl_init();
    $url = "http://restProxy:1030/showConReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $ress = curl_exec($ch);
    curl_close($ch);

    //Delete sub from orion 
    unsubscribe($subid);


    echo $response;
    exit();


    



}
else if($_POST['type'] === "show_news_feed"){
    $queryString = "type=show_feed";
    $queryString =$queryString."&userID=".$_SESSION['userid'];
    //echo "<p> New Not </p>";

    $ch = curl_init();
    $url = "http://restProxy:1030/showNotificationsReq.php";

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$queryString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array(
        "X-Auth-Token: Secret_key"
    ));

    $response = curl_exec($ch);
    echo $response;
    curl_close($ch);
}


?>