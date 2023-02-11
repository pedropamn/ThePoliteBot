	<?php
error_reporting(1);
//Get connection
date_default_timezone_set('America/Sao_Paulo');


//Datas para o formato do Mysql aaaa-mm-dd
function data_iso($data){
	$temp = str_replace('/','-',$data); 
	return date("Y-m-d",strtotime($temp)); //Data no formate DATE para o MySql
}

//Data e Hora para o formato Mysql
function data_hora_iso($data){
	$temp = str_replace('/','-',$data); 
	return date("Y-m-d H:i:s",strtotime($temp)); //Data no formate DATE para o MySql
}

function get_conn(){
	
	$test = false;
	
	if($test == true){
		$host = "localhost";
		$user = "root";
		$pass = "";
		$db = "bot";
	}
	else{
		$host = "url";
		$user = "root";
		$pass = "pass";
		$db = "dbname";
		
	}
		
		
		$conn = new mysqli($host, $user, $pass, $db);
		if ($conn->connect_error) {
			die("Erro ao conectar ao banco: " . $conn->connect_error);
		}
		mysqli_set_charset($conn,"utf8");
		return $conn;
}

function db_add_new_group($group_id, $group_name){
	
	
	$date_in = data_iso(date('d/m/Y'));
	$active = 1;
	
	$conn = get_conn();	
	//Check connection
	if ($conn->connect_error) {
		//die("Erro ao conectar ao banco: " . $conn->connect_error);
	}
	
	$stmt1 = $conn->prepare('SELECT id_group FROM groups WHERE id_group = ?');
	$stmt1->bind_param("i",$group_id); 
	$stmt1->execute();
	$stmt1->store_result();
	$num_de_resultados = $stmt1->num_rows;
	echo $num_de_resultados;
	if($num_de_resultados > 0){
		
		//Grupo já existe
		$reactive = 1;
		$stmt2 = $conn->prepare('UPDATE groups SET active = ? WHERE id_group = ?');
		$stmt2->bind_param("ii", $reactive, $group_id);
		$stmt2->execute();
	}
	else{
		$stmt3 = $conn->prepare('INSERT INTO groups (id_group, name, date_in, active) VALUES (?,?,?,?)');
		$stmt3->bind_param("issi", $group_id, $group_name, $date_in, $active);
		$stmt3->execute();
		if($stmt3->affected_rows > 0){
			//ok
		}
		else{
			//print_r($stmt);
		}
	}
}


function db_new_private_user($chat_id, $first_name, $last_name = "-", $username = "-", $language_code){
	$date_in = data_iso(date('d/m/Y'));
	
	$conn = get_conn();	
	//Check connection
	if ($conn->connect_error) {
		//die("Erro ao conectar ao banco: " . $conn->connect_error);
	}
	
	//Check if user already exists
	$stmt_check = $conn->prepare('SELECT id_user FROM users WHERE id_user = ?');
	$stmt_check->bind_param("i",$chat_id); 
	$stmt_check->execute();
	$stmt_check->store_result();
	$num_de_resultados = $stmt_check->num_rows;
	if($num_de_resultados < 1){
		
		//Insert user
		$stmt = $conn->prepare('INSERT INTO users (id_user, first_name, last_name, username, language_code, created_at) VALUES (?,?,?,?,?,?)');
		$stmt->bind_param("isssss",$chat_id, $first_name, $last_name, $username, $language_code, $date_in);
		$stmt->execute();
		if($stmt->affected_rows > 0){
			//ok
		}
		else{
			print_r($stmt);
		}
	}
}

function db_leave_group($id_group){
	$conn = get_conn();

	//Check connection
	if ($conn->connect_error) {
		//die("Erro ao conectar ao banco: " . $conn->connect_error);
		//header('Location: index.php?status=errobanco');
	}
	$active = 0;
	$stmt = $conn->prepare('UPDATE groups SET active = ? WHERE id_group = ?');
	$stmt->bind_param("ii", $active, $id_group);
	$stmt->execute();
	$num_de_resultados = $stmt->affected_rows;
	
}


function get_all_group_id(){
	$conn = get_conn();
	$stmt1 = $conn->prepare('SELECT id_group FROM groups GROUP BY id_group');
	$stmt1->execute();
	$result = $stmt1->get_result();
	$ids = [];
	while ($row = $result->fetch_assoc()) {
		$ids[] = $row['id_group'];
	}
	
	return $ids;
	
}

function get_all_private_id(){
	$conn = get_conn();
	$stmt1 = $conn->prepare('SELECT id_user FROM users GROUP BY id_user');
	$stmt1->execute();
	$result = $stmt1->get_result();
	$ids = [];
	while ($row = $result->fetch_assoc()) {
		$ids[] = $row['id_user'];
	}
	
	return $ids;
	
}

function broadcast($message, $type = ''){
	
	//Get type
	if($type == 'groups'){
		$ids = get_all_group_id();
		
	}
	else if($type == 'private'){
		$ids = get_all_private_id();
	}
	else{
		$groups = get_all_group_id();
		$privates = get_all_private_id();
		$ids = array_merge($groups, $privates);

	}
	foreach($ids as $chat_id){
		sendMessage($chat_id,$message);
		//echo $chat_id."\n";
	}

}

function increment_unpolite_occurrency($group_id){
	$conn = get_conn();
	$stmt1 = $conn->prepare('UPDATE groups SET unpolite_occurrences = unpolite_occurrences + 1 WHERE id_group = ?');
	$stmt1->bind_param('i', $group_id);
	$stmt1->execute();	
}


function increment_polite_occurrency($group_id){
	$conn = get_conn();
	$stmt1 = $conn->prepare('UPDATE groups SET polite_occurrences = polite_occurrences + 1 WHERE id_group = ?');
	$stmt1->bind_param('i', $group_id);
	$stmt1->execute();	
}




?>
