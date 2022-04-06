<?php


//some checking first for email//
if ( !filter_var($_POST['email1'],FILTER_VALIDATE_EMAIL)) {
    header("location: index.php?error=invalidemail");
    exit();
}

$client_id='ce9c1647-2028-4513-8dd5-6de8ef502de4';
$client_secret='8ddf7a19-8f85-4572-a78d-f86a0819e381';



$auth_key = base64_encode($client_id.":".$client_secret);

$curl = curl_init();

curl_setopt($curl,CURLOPT_URL, "http://keyrock:3005/oauth2/token");

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic '.$auth_key,
    'Content-Type: application/x-www-form-urlencoded'
));

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);

curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'password',
    'username'    => $_POST['email1'],
    'password' => $_POST['passwo'],
    'scope' => 'permanent'
]));

$result = curl_exec($curl);
curl_close($curl);
$result= json_decode($result);

//Check the login and setting Session parameters for the app
$curl = curl_init();
$url = "http://keyrock:3005/user"."?" .http_build_query([
    'access_token' => $result->access_token
]);

curl_setopt($curl,CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_HTTPGET, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($curl);
curl_close($curl);

$response = json_decode($response);

$user_info = array(
    "access_token" => $result->access_token, 
    "refresh_token" => $result->refresh_token,
    "role" => $response->roles[0]->name,
    "username" => $response->username,
    "email" => $response->email,
    "user_id" => $response->id
);

//var_dump($array);

if(is_null($user_info["access_token"])){
    header("location: index.php?error=invalidusername");
    exit();

}
elseif ($user_info["role"] != "User" and $user_info["role"]!= "Organizer"){
    header("location: index.php?error=notconfirmed");
    exit();
}
else{
    session_start();
    $_SESSION["role"] = $user_info["role"];
    $_SESSION["username"] = $user_info["username"];
    $_SESSION["email"] = $user_info["email"];
    $_SESSION["userid"] = $user_info["user_id"];
    $_SESSION["accessToken"] = $user_info["access_token"];
    $_SESSION["refreshToken"] = $user_info["refresh_token"];
    
    header("Location: welcome.php");
    exit();
}



?>