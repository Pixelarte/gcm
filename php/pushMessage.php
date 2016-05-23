<?php

//curl --header "Authorization: key=AIzaSyB3bqW0J8E5c9cCx-QE9HflRIWGhnU9MmU" --header "Content-Type: application/json" https://android.googleapis.com/gcm/send -d "{\"registration_ids\":[\"dSRo-Y9SOsU:APA91bHvTzRFkxY9QKQOhgls0vcwfcnPytXQ-opLsMKcF8HqWTw7uZYRvXudmmAAaV4r1d2We0exQSDMCxLIIhzw7RRSj8vjyCp2K5F-53N8q8zPvncnyXXm9N-nb9nrv5EQTYiWPSoo\"]}"
                                                                                                                                                                                 //dSRo-Y9SOsU:APA91bHvTzRFkxY9QKQOhgls0vcwfcnPytXQ-opLsMKcF8HqWTw7uZYRvXudmmAAaV4r1d2We0exQSDMCxLIIhzw7RRSj8vjyCp2K5F-53N8q8zPvncnyXXm9N-nb9nrv5EQTYiWPSoo
	require_once('conf.php');
	require_once('wp-db_php5_4.php');
	
	global $wpdb;  

	


	$consulta="SELECT * FROM `registro` WHERE `subscrito`=true ORDER BY `id` ASC";
	$resultado = $wpdb->get_results($consulta);
    

	foreach ($resultado as $index) { 

     
      $data = array( 'message' => 'Hello World!' );
      $apikey="AIzaSyB3bqW0J8E5c9cCx-QE9HflRIWGhnU9MmU"; //key servidor
      //$url="https://android.googleapis.com/gcm/send";
      $url="https://gcm-http.googleapis.com/gcm/send";
      $post=array(
        'registration_ids'  => array($index->endPoint),
        'data' => $data
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
	


?>	