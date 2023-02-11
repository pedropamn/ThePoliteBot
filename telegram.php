<?php
header("Content-type: text/html; charset=utf-8");
define("API", "telegram_api_key");

//$post é um array
function Curl($target_url,$post){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$target_url);
		//curl_setopt($ch, CURLOPT_HEADER,true); //Retorna o Header na saída
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$response = curl_exec($ch);
		return $response;
		//var_dump($response);
		if (!$response){
			echo curl_error($ch);
		}	
}

function sendMessage($chat_id,$text,$id_reply = "",$reply_markup = "",$parse_mode = 'markdown'){
		$target_url = "https://api.telegram.org/bot" . API ."/sendMessage";
		$post = array(
			'chat_id'=> $chat_id,		
			'text'   => $text,
			'parse_mode' => $parse_mode,
			'disable_web_page_preview' => true,
			'reply_to_message_id' => $id_reply,
			'reply_markup' => $reply_markup
		);
		
		Curl($target_url,$post);		
 }
 
 function sendPhoto($chat_id,$photo,$caption,$id_reply = ""){
		$target_url = "https://api.telegram.org/bot" . API ."/sendPhoto";
		$post = array(
			'chat_id'=> $chat_id,
			'photo'   => $photo,
			'caption'   => $caption,
			'reply_to_message_id' => $id_reply
		);
		
		Curl($target_url,$post);		
 }
 
 function sendAudio($chat_id,$audio,$id_reply = "",$caption){
		$target_url = "https://api.telegram.org/bot" . API ."/sendAudio";
		$post = array(
			'chat_id'=> $chat_id,
			'audio'   => $audio,
			'reply_to_message_id' => $id_reply,
			'caption'   => $caption
		);
		
		Curl($target_url,$post);		
 }
 
 function sendDocument($chat_id,$document,$id_reply = "",$caption){
		$target_url = "https://api.telegram.org/bot" . API ."/sendDocument";
		$post = array(
			'chat_id'=> $chat_id,
			'document'   => $document,
			'reply_to_message_id' => $id_reply,
			'caption'   => $caption
		);
		
		Curl($target_url,$post);		
 }
 
 function sendSticker($chat_id,$sticker,$id_reply = ""){
		$target_url = "https://api.telegram.org/bot" . API ."/sendSticker";
		$post = array(
			'chat_id'=> $chat_id,
			'sticker'   => $sticker,
			'reply_to_message_id' => $id_reply
		);
		
		Curl($target_url,$post);		
 }
 
 function sendVideo($chat_id,$video,$id_reply = "",$caption){
		$target_url = "https://api.telegram.org/bot" . API ."/sendVideo";
		$post = array(
			'chat_id'=> $chat_id,
			'video'   => $video,
			'reply_to_message_id' => $id_reply,
			'caption'   => $caption
		);
		
		Curl($target_url,$post);		
 }
 
 function sendVoice($chat_id,$voice,$id_reply = "",$caption){
		$target_url = "https://api.telegram.org/bot" . API ."/sendVoice";
		$post = array(
			'chat_id'=> $chat_id,
			'voice'   => $voice,
			'reply_to_message_id' => $id_reply,
			'caption'   => $caption
		);
		
		Curl($target_url,$post);		
 }
 
 
 
 //KEYBOARD


 function KeyboardCancelar($chat_id){
 $target_url = "https://api.telegram.org/bot" . API ."/sendMessage";
	
	$keyboard = array(
		//array('📄 Listar Hashtags')
		array('❌ CANCELAR')
	);

$reply_markup = [
    'keyboard' => $keyboard, 
    'resize_keyboard' => true,
    'one_time_keyboard' => false
];

$reply_markup = json_encode($reply_markup);

	$post = array(
		'chat_id'   => $chat_id,
		'text'   => "Use o botão para anular a operação",
		'reply_markup' => $reply_markup
	);
	
	Curl($target_url,$post);	
}


function KeyboardListar($chat_id){
 $target_url = "https://api.telegram.org/bot" . API ."/sendMessage";
	
	$keyboard = array(
		//array('📄 Listar Hashtags')
		array('📄 LISTAR')
	);

$reply_markup = [
    'keyboard' => $keyboard, 
    'resize_keyboard' => true,
    'one_time_keyboard' => false
];

$reply_markup = json_encode($reply_markup);

	$post = array(
		'chat_id'   => $chat_id,
		'text'   => "Use o botão para listar suas hashtags",
		'reply_markup' => $reply_markup
	);
	
	Curl($target_url,$post);	
}


function EscondeKeyboard($chat_id){
 $target_url = "https://api.telegram.org/bot" . API ."/sendMessage";
	
	$reply_markup = [
		'hide_keyboard' => true
	];

	$reply_markup = json_encode($reply_markup);

		$post = array(
			'chat_id'   => $chat_id,
			'text'   => "Operação Cancelada",
			'reply_markup' => $reply_markup
		);
		
		Curl($target_url,$post);
	
}


function check_admin($chat_id_grupo,$chat_id){
	$target_url = "https://api.telegram.org/bot" . API ."/getChatMember";
		$post = array(
			'chat_id'=> $chat_id_grupo,
			'user_id'   => $chat_id
		);
		
		$curl = Curl($target_url,$post);
		return $curl;
}

function debug($chat_id, $bot_api_key, $json){
	$post = [
		'chat_id' => $chat_id,
		'text' => $json
	];
	
	Curl("https://api.telegram.org/bot" .$bot_api_key. "/sendMessage",$post);
}


function sendChatAction($chat_id, $action){
	/*
	
	typing 
	upload_photo 
	record_video 
	upload_video 
	record_audio
	upload_audio
	upload_document 
	find_location 
	record_video_note 
	upload_video_note 
	
	*/
	file_get_contents("https://api.telegram.org/bot" .API. "/sendChatAction?chat_id=".$chat_id."&action=".$action);

}

/* Delete a message */
function deleteMessage($chat_id, $message_id){
	file_get_contents("https://api.telegram.org/bot" .API. "/deleteMessage?chat_id=".$chat_id."&message_id=".$message_id);
}

function answerInlineQuery($inline_query_id, $results){
	  $post = array(
                'inline_query_id' => $inline_query_id,
                'results' => $results
            );
	Curl("https://api.telegram.org/bot" .API. "/answerInlineQuery",$post);

}

