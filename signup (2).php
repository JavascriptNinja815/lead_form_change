<?php

// create both cURL resources
$ch1 = curl_init();
$ch2 = curl_init();

// set URL and other appropriate options
//--------------------------CH1
curl_setopt_array($ch1, array(
  CURLOPT_URL => "URL",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"ai":"******","ci":"***","gi":"***","userip":"'.$_REQUEST['ip'].'","firstname":"'.$_REQUEST['firstname'].'", "lastname":"'.$_REQUEST['lastName'].'", "email":"'.$_REQUEST['email'].'", "password":"'.$_REQUEST['password'].'", "phone":"'.$_REQUEST['phonenumber'].'", "prefix":"'.$_REQUEST['phonecode'].'" }', 
  CURLOPT_HTTPHEADER => array(
    "x-api-key: *******",
    "x-trackbox-password: *******",
    "x-trackbox-username: *******"
      
   ),
)); 
//-------------------------CH2
curl_setopt_array($ch2, array(
  CURLOPT_URL => "ZAPURL",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"userip":"'.$_REQUEST['ip'].'","firstname":"'.$_REQUEST['firstname'].'", "lastname":"'.$_REQUEST['lastname'].'", "email":"'.$_REQUEST['email'].'", "password":"'.$_REQUEST['password'].'", "phone":"'.$_REQUEST['phonenumber'].'", "prefix":"'.$_REQUEST['phonecode'].'" }', 
)); 

//create the multiple cURL handle
$mh = curl_multi_init();

//add the two handles
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);

/* While we're still active, execute curl
$active = null;
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mh == CURLM_CALL_MULTI_PERFORM);
 
while ($active && $mrc == CURLM_OK) {
    // Wait for activity on any curl-connection
    if (curl_multi_select($mh) == -1) {
        continue;
    }
 
    // Continue to exec until curl is ready to
    // give us more data
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
}
*/

//running the requests
$running = null;
do {
  curl_multi_exec($mh, $running);
} while ($running);



//close the handles
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_close($mh);

$response1 = curl_multi_getcontent($ch1);
$response2 = curl_multi_getcontent($ch2);


$json_array = json_decode($response1, true);
$loginurl = $json_array['data'];
header("Location: $loginurl");
