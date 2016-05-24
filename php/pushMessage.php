<?php

//curl --header "Authorization: key=AIzaSyB3bqW0J8E5c9cCx-QE9HflRIWGhnU9MmU" --header "Content-Type: application/json" https://android.googleapis.com/gcm/send -d "{\"registration_ids\":[\"dSRo-Y9SOsU:APA91bHvTzRFkxY9QKQOhgls0vcwfcnPytXQ-opLsMKcF8HqWTw7uZYRvXudmmAAaV4r1d2We0exQSDMCxLIIhzw7RRSj8vjyCp2K5F-53N8q8zPvncnyXXm9N-nb9nrv5EQTYiWPSoo\"]}"
                                                                                                                                                                                 //dSRo-Y9SOsU:APA91bHvTzRFkxY9QKQOhgls0vcwfcnPytXQ-opLsMKcF8HqWTw7uZYRvXudmmAAaV4r1d2We0exQSDMCxLIIhzw7RRSj8vjyCp2K5F-53N8q8zPvncnyXXm9N-nb9nrv5EQTYiWPSoo
	require_once('conf.php');
	require_once('wp-db_php5_4.php');
	
	global $wpdb;  

  $url="https://gcm-http.googleapis.com/gcm/send";
  $apikey="AIzaSyB3bqW0J8E5c9cCx-QE9HflRIWGhnU9MmU"; //key servidor
  $header = array( 
            'Authorization: key='.$apikey,
            'Content-Type: application/json'
            );

  $posts=array();
  $idRegistrados=array();

	$consulta="SELECT * FROM `registro` WHERE `subscrito`=true ORDER BY `id` ASC";
	$resultadoQuery = $wpdb->get_results($consulta);
    
  foreach ($resultadoQuery as $index) { 
        array_push($posts,array(
             'registration_ids'  => array($index->endPoint)
        ) );
        array_push($idRegistrados, $index->id);
  }

  $resultado = array();
  $curly = array();
  $pm = curl_multi_init();
  foreach ($posts as $id => $d) {
        $curly[$id] = array('curl'=>curl_init(),'idRegistro'=>$idRegistrados[$id]);
        curl_setopt($curly[$id]['curl'], CURLOPT_URL, $url);
        curl_setopt($curly[$id]['curl'], CURLOPT_POST, true);
        curl_setopt($curly[$id]['curl'], CURLOPT_HTTPHEADER, $header);
        curl_setopt($curly[$id]['curl'], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curly[$id]['curl'], CURLOPT_POSTFIELDS, json_encode( $d ) );
        curl_multi_add_handle($pm, $curly[$id]['curl']);
  }


  $running = null;
  do {
    curl_multi_exec($pm, $running);
  } while ($running);


  foreach($curly as $id => $c) {
      curl_multi_remove_handle($pm, $c['curl']);
      $resultado[$id] = json_decode(curl_multi_getcontent($c['curl']),true);
  }

  curl_multi_close($pm);

  foreach ($resultado as $i => $o) {
    $success;
    if($o['success']=="1"){
      $success="success";
    }else{
      $success="failure";
    }

    $insert  = $wpdb->prepare("INSERT INTO `push` (`idRegistro`,`resultado`) VALUES (%s,%s)", $curly[$i]['idRegistro'],$success);
    $wpdb->query($insert);

  }



	/*foreach ($resultado as $index) { 

     
      $data = array( 'message' => 'Hello World!' );
      $apikey="AIzaSyB3bqW0J8E5c9cCx-QE9HflRIWGhnU9MmU"; //key servidor
      //$url="https://android.googleapis.com/gcm/send";
      $url="https://gcm-http.googleapis.com/gcm/send";
      $post=array(
        'registration_ids'  => array($index->endPoint),
        'data' => array('hola'=>'holap')//$data
        );
      $headers = array( 
            'Authorization: key='.$apikey,
            'Content-Type: application/json'
            );


     // Initialize curl handle       
        $ch = curl_init();

        // Set URL to GCM endpoint      
        curl_setopt( $ch, CURLOPT_URL, $url );

        // Set request method to POST       
        curl_setopt( $ch, CURLOPT_POST, true );

        // Set our custom headers       
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        // Get the response back as string instead of printing it       
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        // Set JSON post data
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $post ) );

        // Actually send the push 

        $result = curl_exec( $ch );

        

        // Error handling
        if ( curl_errno( $ch ) )
        {
            echo 'GCM error: ' . curl_error( $ch );
        }

        // Close curl handle
        curl_close( $ch );

        // Debug GCM response       
        echo $result;



	}
	*/


?>	