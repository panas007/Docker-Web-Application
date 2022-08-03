<?php

$f1=20;
$f2=14;
//admin auth token,roles ids,app id
$userid="ce9c1647-2028-4513-8dd5-6de8ef502de4";
$organizerid="1d191786-f7ea-4c20-88a0-0e072686070c";
$user_role="33f61397-f01a-4313-8eb5-7506a553a4cb";
$organizer_role="1d191786-f7ea-4c20-88a0-0e072686070c";
$app_id="ce9c1647-2028-4513-8dd5-6de8ef502de4";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://keyrock:3005/v1/auth/tokens");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"name\": \"admin@test.com\",
  \"password\": \"1234\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json"
));

$response = curl_exec($ch);
$hz = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, $f1, $hz-$f1);
curl_close($ch);
$parts = explode(":", $header);
$tpart = $parts[$f2];
$parts = explode("\n", $tpart);
$token = trim($parts[0]);
//var_dump($token);




$ch = curl_init();

$user="\"".$_POST["username"]."\"";
$mail="\"".$_POST["email"]."\"";
$pass="\"".$_POST["passwo"]."\"";

curl_setopt($ch, CURLOPT_URL, "http://keyrock:3005/v1/users");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
    \"user\": {
      \"username\": ". $user .",
      \"email\": ".$mail.",
      \"password\": ". $pass."
    }
  }");



curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "X-Auth-token: ".$token
));

$response = curl_exec($ch);
$response = json_decode($response);
curl_close($ch);
$res=$response->user;
//var_dump($res->id);
$usid=$res->id;


/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://keyrock:3005/v1/applications/ce9c1647-2028-4513-8dd5-6de8ef502de4/roles");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "X-Auth-token: ".$token
));

$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response);*/

//var_dump($response);


if(!is_null($usid)){
    //add role 

    if( $_POST["role"] === 'EVENTORGANIZERS'){
        $roleid=$organizer_role;
    }
    else{
        $roleid=$user_role;
    }
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'http://keyrock:3005/v1/applications/'.$app_id.'/users/'.$usid.'/roles/'.$roleid);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "X-Auth-token: ".$token
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);



    //var_dump($response);
}
if($response->error->message == "Email already used"){

     header("Location: index.php?error=takenidoremail");
}
else
     header("Location: index.php?error=none");


?>