<?php
class Slack_Message {

	public $channel;
	public $text;
	public $auth;
	public $thread_ts;
	public $blocks;

	public function Send(){
		//$blocks - array-formatted blocks (not json, but PHP-array). You can find examples at https://app.slack.com/block-kit-builder
		//$auth - auth token for your app
		//$thread_ts - if you want to reply to thread

		$jsonData = array(
		    "channel" => $this->channel,
		    "blocks" => $this->blocks
		);

		if( $this->username != NULL ){
			$jsonData["username"] = $this->username;
		}

		if( $this->thread_ts != NULL ){
			$jsonData["thread_ts"] = $this->thread_ts;
		}

		$send = curl_init( 'https://slack.com/api/chat.postMessage' );
		curl_setopt( $send, CURLOPT_POST, 1 );
		curl_setopt( $send, CURLOPT_POSTFIELDS, json_encode( $jsonData ) );
	    curl_setopt( $send, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $send, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Bearer ' . $this->auth ) ); 
		return json_decode(curl_exec( $send ), 1 );
	}

	public function Send_Ephemeral( $user ){//Ephemeral message will be seen to a specific user only

		$send = curl_init( 'https://slack.com/api/chat.postEphemeral' );

		$jsonData = array(
			"channel" => $this->channel,
			"text" => $this->text,
			"user" => $user
		);

		curl_setopt( $send, CURLOPT_POST, 1 );
		curl_setopt( $send, CURLOPT_POSTFIELDS, json_encode( $jsonData ) );
		curl_setopt( $send, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Bearer ' . $this->auth ) ); 

		return json_decode( curl_exec( $send ), 1 );
	}

	public function Update_By_Url( $response_url ){//After recieving an event via Slack Events API (https://api.slack.com/events-api) you can update the initial message using just link and no auth
		//$this->blocks here requires json-formatted blocks, not PHP-array

		$update = curl_init($response_url);

		$jsonData = '{"replace_original": true,' . $this->blocks . '}';
		
		curl_setopt( $update, CURLOPT_POST, 1 );
		curl_setopt( $update, CURLOPT_POSTFIELDS, $jsonData );
	    curl_setopt( $update, CURLOPT_RETURNTRANSFER, true );
	    
		return json_decode( curl_exec( $update ), 1 );
	}

	public function Update(){

		$update = curl_init( 'https://slack.com/api/chat.update' );

		$jsonData = array(
			"channel" => $this->channel,
			"blocks" => $this->blocks,
			"ts" => $this->thread_ts,
			"as_user"=>true
		);

		curl_setopt( $update, CURLOPT_POST, 1 );
		curl_setopt( $update, CURLOPT_POSTFIELDS, json_encode( $jsonData ) );
		curl_setopt( $update, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $update, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Bearer ' . $this->auth ) ); 
			
		return json_decode( curl_exec( $update ), 1 );
	}

	public function Add_Emoji( $emoji_text, $timestamp ){

		$add = curl_init( 'https://slack.com/api/reactions.add' );

		$jsonData = array(
			"channel" => $this->channel,
			"name"=> $emoji_text,
			"timestamp"=>$timestamp
		);

		curl_setopt( $add, CURLOPT_POST, 1 );
		curl_setopt( $add, CURLOPT_POSTFIELDS, json_encode( $jsonData ) );
	    curl_setopt( $add, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $add, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Bearer ' . $this->auth ) ); 
		
		return json_decode( curl_exec( $add ), 1 );
    }

    public function Remove_Emoji( $emoji_text, $timestamp ){

		$remove = curl_init('https://slack.com/api/reactions.remove');

		$jsonData = array(
			"channel" => $this->channel,
			"name"=> $emoji_text,
			"timestamp"=>$timestamp
		);

		curl_setopt( $remove, CURLOPT_POST, 1 );
		curl_setopt( $remove, CURLOPT_POSTFIELDS, json_encode( $jsonData ) );
	    curl_setopt( $remove, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $remove, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Bearer ' . $this->auth ) ); 
		
		return json_decode( curl_exec( $remove ), 1 );  
    }

    public function Discover_User_PM_Channel_ID( $user_slack_id ){//Before chating with user, your app needs to discover its PM channel ID with this person

    	$discover = curl_init( 'https://slack.com/api/conversations.open' );

		$jsonData = array(
			"users" => $user_slack_id
		);

		curl_setopt( $discover, CURLOPT_POST, 1 );
		curl_setopt( $discover, CURLOPT_POSTFIELDS, json_encode( $jsonData ) );
	    curl_setopt( $discover, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $discover, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Authorization: Bearer ' . $this->auth ) ); 
		
		$result = json_decode( curl_exec( $discover ), 1 );
		
		return $result["channel"]["id"];
		
    }

}
?>