<?php 
session_start();
date_default_timezone_set("Europe/Athens");
function create_entity($concertid,$title,$startdate,$enddate,$userid,$ticket){
    $curl = curl_init();
    //var_dump ($concertid.$title.$startdate.$enddate.$userid.$ticket);
    // set the POST request body and parameters

    $startd = new DateTime($startdate);
    $endd = new DateTime($enddate);
    $current_time = new DateTime();

    //$now_interval_end_date = $now->diff($start_date_time);
    //$diff = $now_interval_end_date->format('%R%a');
    //var_dump ($now_interval_end_date->d);
    //var_dump ($diff);

    if ( ($startd<=$current_time) &&  ($current_time<=$endd) ){
        $str_sell=1;
    }
    else{
        $str_sell=0;
    }

    if ( ($startd<$current_time) &&  ($current_time>$endd) ){
        $end_sell=1;
    }
    else{
        $end_sell=0;
    }

    if($tickets <= 0){
        $sold_tick=1;
    }
    else{
        $sold_tick=0;
    }


    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://orionProxy:1029/v2/entities',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "id": "'.$concertid.'",
        "type": "Concert",
        "title": {
            "value": "'. $title.'",
            "type": "text"
        },
        "startdate": {
            "value": "'.$startdate.'",
            "type": "Date"
        },
        "enddate": {
            "value": "'.$enddate.'",
            "type": "Date"
        },
        "user": {
            "value": "'.$userid.'",
            "type": "text"
        },
        "startsale": {
            "value": "'.$str_sell.'",
            "type": "Integer"
        },
        "endsale": {
            "value": "'.$end_sell.'",
            "type": "Integer"
        },
        "sold_tick": {
            "value": "'.$sold_tick.'",
            "type": "Integer"
        },
        "tickets": {
            "value": "'.$ticket.'",
            "type": "Integer"
        }  
    }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'X-Auth-Token: Secret_key',
        'Accept: application/json'
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

}


function update_entity($concertid,$title,$startdate,$enddate,$userid,$ticket){
    $curl = curl_init();
    //var_dump ($concertid.$title.$startdate.$enddate.$userid.$ticket);
    
    $startd = new DateTime($startdate);
    $endd = new DateTime($enddate);
    $current_time = new DateTime();


    if ( ($startd<=$current_time) &&  ($current_time<=$endd) ){
        $str_sell=1;
    }
    else{
        $str_sell=0;
    }

    if ( ($startd<$current_time) &&  ($current_time>$endd) ){
        $end_sell=1;
    }
    else{
        $end_sell=0;
    }

    if($ticket <= 0){
        $sold_tick=1;
    }
    else{
        $sold_tick=0;
    }

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://orionProxy:1029/v2/entities/".$concertid."/attrs", // use orion-proxy (PEP Proxy for Orion CB) IP address and port instead of Orion CB's 
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'PATCH',
      CURLOPT_POSTFIELDS =>'{
        "startdate": {
            "value": "'.$startdate.'",
            "type": "Date"
        },
        "title": {
            "value": "'. $title.'",
            "type": "text"
        },
        "enddate": {
            "value": "'.$enddate.'",
            "type": "Date"
        },
        "user": {
            "value": "'.$userid.'",
            "type": "text"
        },
        "startsale": {
            "value": "'.$str_sell.'",
            "type": "text"
        },
        "endsale": {
            "value": "'.$end_sell.'",
            "type": "text"
        },
        "sold_tick": {
            "value": "'.$sold_tick.'",
            "type": "text"
        },
        "tickets": {
            "value": "'.$ticket.'",
            "type": "text"
        }  
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-Auth-Token: Secret_key',
        ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
}


function delete_entity($concertid){
    $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://orionProxy:1029/v2/entities/".$concertid.'?type=Concert',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                "X-Auth-Token: Secret_key"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
}


function subscribe($userid,$conid){

    $curl = curl_init();

    // set the POST request body and parameters
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://orionProxy:1029/v2/subscriptions/',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => true,
      CURLOPT_POST => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
      "description": "'.$userid.'",
      "subject": {
          "entities": [
              {
                  "id": "'.$conid.'",
                  "type": "Concert"
              }
          ],
          "condition": {
              "attrs": [
                  "startsale",
                  "endsale",
                  "sold_tick"
              ]
          }
       },
       "notification": {
          "http": {
              "url": "http://restProxy:1030/notifications.php" 
          }, 
          "attrs": [
            "startdate",
            "startsale",
            "endsale",
            "title",
            "enddate",
            "user",
            "sold_tick",
            "tickets"
          ]
       },
      "expires": "2030-01-01T14:00:00.00Z",
      "throttling": 3
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'X-Auth-Token: Secret_key',
        'Accept: application/json'
      ),
    ));
    
    $response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    curl_close($curl);
    
    $parts = explode(":", $header);
    $tpart = $parts[5];
    $parts = explode("/", $tpart)[3];
    $subid = explode("\n", $parts)[0];
    
    $subid = trim($subid);
    //var_dump($subid);
    

    return $subid;

}


function unsubscribe($sub){
    $cu = curl_init();

    curl_setopt_array($cu, array(
        CURLOPT_URL => "http://orionProxy:1029/v2/subscriptions/".$sub,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'X-Auth-Token: Secret_key',
        ),
    ));
    
    curl_exec($cu);
    curl_close($cu);

}









?>