<?php


function fetch_google_place_data($req) {

  $lat = $req["lat"];
  $lng = $req["lng"];
  $placeString = $req["placeString"];

  $data_encryption = new FSDHH\Data_Encryption();
  $googleKey = $data_encryption->decrypt(get_option( 'google_api_key' ));

  if(!empty($googleKey)){

    $curl = curl_init();
  
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$placeString}&inputtype=textquery&locationbias=circle%3A2000%40{$lat}%2C{$lng}&fields=formatted_address%2Cname%2Cplace_id%2Cgeometry%2Crating%2Cphotos&key={$googleKey}",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      return new WP_Error( 'error', 'Invalid Request', array( 'status' => 404 ) );
    } else {
      $res = new WP_REST_Response($response);
      $res->set_status(200);
    
      return $res;
    }


  }


  return new WP_Error( 'error', 'Please enter a google API key in settings to use this feature', array( 'status' => 403 ) );


}