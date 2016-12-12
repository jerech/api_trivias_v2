<?php
/*
	Class to send push notifications using Google Cloud Messaging for Android

	Example usage
	-----------------------
	$an = new GCMPushMessage($apiKey);
	$an->setDevices($devices);
	$response = $an->send($message);
	-----------------------
	
	$apiKey Your GCM api key
	$devices An array or string of registered device tokens
	$message The mesasge you want to push out

	@author Matt Grundy

	Adapted from the code available at:
	http://stackoverflow.com/questions/11242743/gcm-with-php-google-cloud-messaging

*/
class GCMPushMessage {

	var $url = 'https://fcm.googleapis.com/fcm/send';
	var $serverApiKey = "";
	var $devices  = array();
	
	/*
		Constructor
		@param $apiKeyIn the server API key
	*/
	function GCMPushMessage(){
		$this->serverApiKey = 'AIzaSyAfnz_3TMPkyWzSS_3LO_SsygVBx2FWeWU';
	}

	/*
		Set the devices to send to
		@param $deviceIds array of device tokens to send to
	*/
	function setDevices($deviceIds){
	
		if(is_array($deviceIds)){
			$this->devices = $deviceIds;
		} else {
			$this->devices = array($deviceIds);
		}
		//$devices = $deviceIds;
	
	}

	/*
		Send the message to the device
		@param $message The message to send
		@param $data Array of data to accompany the message
	*/
	function send($message){
		

	    $fields = array (
	            'registration_ids' => $this->devices,
	            'data' => $message
	            
	    );

	    $headers = array (
	            'Authorization: key=' . $this->serverApiKey,
	            'Content-Type: application/json'
	    );

	    $ch = curl_init ();
	    curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));


	    $result = curl_exec($ch);               
        if ($result === FALSE) {
            return 'Curl failed: '.curl_error($ch);
        }
	  
	    curl_close ( $ch );

	    return $result;
		
	}
		
	function error($msg){
			echo "Android send notification failed with error:";
			echo "\t" . $msg;
			exit(1);
	}
}
