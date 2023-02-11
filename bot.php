<?php	
error_reporting(1);

	require('telegram.php');
	require('functions.php');
	require('db.php');

	$bot_username = 'thepolite_bot';
	$server_link = 'https://myserver.com';
	
	
	/***********************
		  CONSTANTES 
	***********************/
	define("TXT_ENTER_GROUP", "🇺🇸 Be polite\n\n🇧🇷Seja educado\n\nI need admin access to do some magic");
	define("TXT_START_PRIVATE", "🇺🇸 Welcome to the Polite bot. Add me in groups as administrator and I will delete, edit and resend unpolite messages. If I am not an admin, I will not be able to delete messages\n\n🇧🇷Bem vindo ao Polite Bot. Me adicione em grupos como administrador e eu irei deletar, editar e reenviar mensagens mal educadas. Se eu não for admin, não poderei deletar as mensagens");
	define("TXT_RANKING", "🇧🇷 Veja o Ranking\n\n🇺🇸 See the ranking");
	define("TXT_HELP", "Ajuda");


	/***********************
		RECEBIM. DE JSON
	***********************/
	$json = file_get_contents("php://input");	
	if(strstr($json,'_"message"')){
		$json = str_replace('_"message"','"message"',$json);
	}

	if(strstr($json,'_"inline_query"')){
		$json = str_replace('_"inline_query"','"inline_query"',$json);
	}	
	
	$dados = json_decode($json,true);	
	//debug(MY_ID, API, $json);		
	
	//Chat ID
	$chat_id = $dados['message']['chat']['id'];
	
	
	
	
	/***********************
			EVENTOS 
	***********************/
	
	
	//Entrada em Grupo
	if($dados['message']['new_chat_participant']){
		if($dados['message']['new_chat_participant']['username'] == $bot_username){
			sendMessage($chat_id,TXT_ENTER_GROUP);
			$group_id = $dados['message']['chat']['id'];
			$group_name = $dados['message']['chat']['title'];
			db_add_new_group($group_id, $group_name);
		}	
	}
	
	//Saída do grupo
	if($dados['message']['left_chat_participant']){
		if($dados['message']['left_chat_participant']['username'] == $bot_username){	
			$group_id = $dados['message']['chat']['id'];
			db_leave_group($group_id);
		}
		
	}
	
	/***********************
	  ORIGEM DA REQUISIÇÃO
	  
	  PV | GROUP | INLINE
	***********************/	
	
	//Private
	if($dados['message']['chat']['type'] == 'private'){	
		$nome_do_user = $dados['message']['from']['first_name'];
		$username = $dados['message']['from']['username'];	
		$first_name = $dados['message']['from']['first_name'];
		$last_name = $dados['message']['from']['last_name'];

		if(!$last_name){
			$last_name = "-";
		}
		if(!$username){
			$username = "-";
		}	
		$language_code = $dados['message']['from']['language_code'];
		
		db_new_private_user($chat_id, $first_name, $last_name, $username, $language_code);
		
		//$timestamp = $dados['message']['date'];
		//date_default_timezone_set('America/Sao_Paulo');
		//$data = date("d-m-Y\ H:i:s", $timestamp);
		$termo = $dados['message']['text'];	

		switch($termo){
			case '/start':
				sendChatAction($chat_id, 'typing');
				sendMessage($chat_id,TXT_START_PRIVATE,$id_reply = "",$reply_markup = "");			
				break;
			case '/ranking':
				sendChatAction($chat_id, 'typing');
				$inline_keyboard = 
				[
					[
						['text' => '📊 Ranking', 'url' => $server_link . '/ranking/']	
					],
				]; 	
				
				$reply_markup = json_encode(['inline_keyboard' => $inline_keyboard]);
				sendMessage($chat_id,TXT_RANKING,$id_reply = "",$reply_markup);			
				break;
			default:
				//Security Check   command = param1,param2
				if($chat_id == MY_ID){

					//Get command
					$command_and_args = explode('=',$termo);
					$command = trim($command_and_args[0]);
					$all_args = trim($command_and_args[1]);	
					
					//Args
					$args = explode(',',$all_args);
					
					if($command == "broadcast"){
						$msg = trim($args[0]);
						$type = trim($args[1]);
						broadcast($msg, $type);
					}
					
				}			
				break;
		}
	
	}
	
	//Group
	else if($dados['message']['chat']['type'] == 'group' || $dados['message']['chat']['type'] == 'supergroup'){
		
		
		$group_name = $dados['message']['chat']['title'];
		$group_username = $dados['message']['chat']['username'];
		$user_id = $dados['message']['from']['id'];
		$first_name = $dados['message']['from']['first_name'];
		$last_name = $dados['message']['from']['last_name'];		
		$username = $dados['message']['from']['username'];
		
		if(!$last_name){
			$last_name = "";
		}
		if(!$group_username){
			$group_username = "-";
		}
		
		$name_of_user = $first_name.' '.$last_name;
		
		if(!$username){
			$identifier = $name_of_user;
		}
		else{
			$identifier = '@'.$username;
		}
		
		$termo = $dados['message']['text'];	
		
		
		//Command or other term
		switch($termo){
			case '/ranking@'.$bot_username:
				sendChatAction($chat_id, 'typing');
				$inline_keyboard = 
				[
					[
						['text' => '📊 Ranking', 'url' => $server_link . '/ranking/']	
					],
				]; 	
				
				$reply_markup = json_encode(['inline_keyboard' => $inline_keyboard]);
				sendMessage($chat_id,TXT_RANKING,$id_reply = "",$reply_markup);			
				break;	
				
				default:
					$id_msg = $dados['message']['message_id'];		
					$id_to_reply = $dados['message']['reply_to_message']['message_id'];
					
					if(!$id_to_reply){
						$id_to_reply = '';
					}
					
					$polite = search_for_unpolite_terms($termo);
					if($termo != $polite){//$polite will be modified version of $term, if there is any unpolite terms
						
						//Delete the message
						deleteMessage($chat_id, $id_msg);
						
						//Send unpolite alert message			
						$message = "*Polite version of " .$identifier. " message :\n\n💬*"."_".$polite."_\n\n❌ *Oh no! Polite points of this group was decreased*";
						$inline_keyboard = 
						[
							[
								['text' => '📊 Ranking', 'url' => $server_link . '/ranking/']	
							],
						]; 	
						
						$reply_markup = json_encode(['inline_keyboard' => $inline_keyboard]);	
						sendMessage($chat_id,$message,'',$reply_markup);
						increment_unpolite_occurrency($chat_id);
					}
					
					//Was not unpolite, but let's check if was polite, and not 'neutral'
					else{
						//Search for polite terms
						$find_polites = search_for_polite_terms($termo);
						if($find_polites){
							
							//Send polite alert message			
							$message = "*✅ Nice! Polite points of this group was increased*";
							$inline_keyboard = 
							[
								[
									['text' => '📊 Ranking', 'url' => $server_link . '/ranking/']	
								],
							]; 	
						
							$reply_markup = json_encode(['inline_keyboard' => $inline_keyboard]);
							sendMessage($chat_id,$message,$id_msg,$reply_markup);
							increment_polite_occurrency($chat_id);
						}
					}
					break;
		}
	
	}
		
	
	die();
			
			
?>
