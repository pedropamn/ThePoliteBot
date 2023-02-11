<?php
//Main function
function search_for_unpolite_terms($msg){

	//$replace = str_ireplace($badwords,'🚫' ,$msg);
	
	$replace = preg_replace(
		[
			"/\bcaralho\b/iu",
			"/\bcu\b/iu",
			"/\bvai tomar no cú\b/iu",
			"/\bcú\b/iu",
			"/\bcú\b/iu",
			"/\bcu\b/iu",
			"/\bmerda\b/iu",
			"/\bbosta\b/iu",
			"/\bputa\b/iu",
			"/\bfilho da puta\b/iu",
			"/\bfuck\b/iu",
			"/\bfuck you\b/iu",
			"/\bfucking\b/iu",
			"/\bcum\b/iu",
			"/\bbuceta\b/iu",
			"/\bpiroca\b/iu",
			"/\bfoda\b/iu",
			"/\bfoda-se\b/iu",
			"/\bfoder\b/iu",
			"/\bfoda-se\b/iu",
			"/\bcarai\b/iu",
			"/\bshit\b/iu",
			"/\bbitch\b/iu",
			"/\bson of a bitch\b/iu",
			"/\bDick\b/iu",
			"/\bPussy\b/iu",
			"/\bporra\b/iu",
			"/\bAss\b/iu"
		], 
		'🚫', $msg);



	return $replace;
}

function search_for_polite_terms($msg){
	
		//Ignore some double meaning terms, replacing it for a '...'
		$ignore = [
			"Fui obrigado",
			"Não sou obrigado",
			"Sou obrigado",
			"Está obrigado"
		];
		
		foreach($ignore as $ig){
			$busca = stripos ($msg,$ig);
			
			if($busca !== FALSE){
				$msg = str_replace($ig,"...",$msg);
			}
		}
		
		//Search for polite
		$polites = [
			"Por favor",
			"Please",
			"Obrigado",
			"Muito Obrigado",
			"Thanks",
			"Thank you",
			"Deus te abençoe",
			"Deus abençoe",
			"God Bless You",
			"Bless You",
			"Parabéns",
			"Parabens",
			"Congratulations",
			"Congrats",
			"Com licença",
			"Excuse-me"
		];
	
	
	foreach($polites as $polite){
		$busca = stripos ($msg,$polite);
		if($busca !== FALSE){
			return true;
		}
	}
}
?>