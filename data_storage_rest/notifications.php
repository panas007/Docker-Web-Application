<?php
require_once 'mongo_con.php';

//Take notifications and writes it to the database 

// Takes raw data from the request 
$json = file_get_contents('php://input');

// Converts it into a PHP object
$datanot = json_decode($json);

//some testing here to see if it works 
//file_put_contents('testfile.txt', print_r($datanot->data[0]->title->value, TRUE));

//take all the data we want to save in our database for the user feed 
$subscription = $datanot->subscriptionId;
$title=$datanot->data[0]->title->value;
$startdate=$datanot->data[0]->startdate->value;
$enddate=$datanot->data[0]->enddate->value;
$startsale=$datanot->data[0]->startsale->value;
$endsale=$datanot->data[0]->endsale->value;
$tickets=$datanot->data[0]->tickets->value;
$sold_tick=$datanot->data[0]->sold_tick->value;

//Get the id of the user who made the subscription 

$ch = curl_init();

curl_setopt_array($ch, array(
   CURLOPT_URL => 'http://orionProxy:1029/v2/subscriptions/'.$subscription,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => '',
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 0,
   CURLOPT_FOLLOWLOCATION => true,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => 'GET',
   CURLOPT_HTTPHEADER => array(
      "X-Auth-Token: Secret_key"
  ),
));

$res = curl_exec($ch);

curl_close($ch);
$res= json_decode($res);
$usid = $res->description;


//Make the request to the database
$date = new DateTime("now", new DateTimeZone("Europe/Athens"));
$createdTime= $date->format("Y-m-d h:i:s");

$filter = [
    'subscription' =>$subscription,
    'title' => $title,
    'startdate' => $startdate,
    'enddate' => $enddate,
    'startsale' => $startsale,
    'endsale' => $endsale,
    'tickets' => $tickets,
    'sold_tick' => $sold_tick,
    'created' =>  $createdTime,
    'readed' =>  "none",
    'isSeen' => 0,
    'userid' => $usid         
];
//file_put_contents('testfile1.txt', print_r($filter, TRUE));
$writer = new MongoDB\Driver\BulkWrite();
$conc = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 200);
$writer->insert($filter);
$res = $mongodb->executeBulkWrite('condata_db.feeds', $writer,$conc);

//file_put_contents('testfile2.txt', print_r($res->getInsertedCount(), TRUE));
/*
$filter = [];
    $options = [];
    $query = new \MongoDB\Driver\Query($filter, $options);
    
    $concertlist = $mongodb->executeQuery("condata_db.feeds", $query);
    foreach($concertlist as $con) {

        $conarr[] = array( 
            'subscription' => $con->subscription,
            'title' => $con->title,
            'startdate' => $con->startdate,
            'enddate' => $con->enddate,
            'startsale' => $con->startsale,
            'endsale' => $con->endsale,
            'tickets' => $con->tickets,
            'sold_tick' => $con->sold_tick,
            'userid' => $usid         
  
        );
    }

    foreach($conarr as $row) {
        file_put_contents('testfile.txt', print_r($conarr, TRUE));
    }
*/
?>