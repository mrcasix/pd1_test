<?php
include_once("functions.php");

function select_articolo($id_articolo){
	global $db_conn;
	$query = "SELECT * from articoli where id_articolo  = $id_articolo";
	$res = mysqli_query($db_conn,$query);
	if(!$res) return array();
	return get_result_to_array($res,true);
}
function aggiungi_giudizio(){
	global $db_conn;
	$id_commento = $_POST['id_commento'];
	$tipo = $_POST['tipo_giudizio'];
	$id_utente = $_SESSION['id_utente'];
	$query = "INSERT INTO giudizi (fk_id_utente,fk_id_commento,tipo)
		   SELECT * FROM (select \"$id_utente\" as id_utente,\"$id_commento\" as id_commento,\"$tipo\" as tipo) AS tmp
		   WHERE NOT EXISTS (
				SELECT fk_id_commento,fk_id_utente FROM giudizi WHERE fk_id_commento = \"$id_commento\" and fk_id_utente = \"$id_utente\" group by fk_id_commento,fk_id_utente having count(*)>=3
		   ) LIMIT 1;";
	 
	$res = mysqli_query($db_conn,$query);
	
	if(!$res) return false;
	return true;
}
function aggiungi_commento(){
	global $db_conn;
	
	$testo = mysqli_real_escape_string($db_conn, $_POST["testo_commento"]);
	$punteggio = mysqli_real_escape_string($db_conn, $_POST["punteggio_commento"]);
	$id_articolo = mysqli_real_escape_string($db_conn, $_POST["id_articolo"]);
	$id_utente = $_SESSION['id_utente'];
	
	$query = "INSERT INTO commenti (testo,punteggio,fk_id_articolo,fk_id_utente)
	  	   SELECT * FROM (select \"$testo\" as testo,\"$punteggio\" as punteggio,\"$id_articolo\" as id_articolo,\"$id_utente\" as id_utente) AS tmp
		   WHERE NOT EXISTS (
			SELECT fk_id_articolo,fk_id_utente FROM commenti WHERE fk_id_articolo = \"$id_articolo\" and fk_id_utente = \"$id_utente\"
		   ) LIMIT 1;";
	
	$res = mysqli_query($db_conn,$query);
}
function elimina_commento(){
	global $db_conn;
	$id_commento = $_POST['id_commento'];
	mysqli_autocommit($db_conn, FALSE);
	try{
		$sql="DELETE FROM giudizi WHERE fk_id_commento=$id_commento";
		$status = mysqli_query($db_conn, $sql);
		if(!$status) throw new Exception();
		
		$sql="DELETE FROM commenti WHERE id_commento=$id_commento";
		$status = mysqli_query($db_conn, $sql);
		if(!$status) throw new Exception();
		
		mysqli_commit($db_conn);
		mysqli_autocommit($db_conn, TRUE);
		return 1;
	}catch(Exception $e){
		mysqli_rollback($db_conn);
		mysqli_autocommit($db_conn, TRUE);
		return false;
	}
	
}
function commenti_articolo($id_articolo){
	global $db_conn;
	$id_utente = (isset($_SESSION['id_utente']))? $_SESSION['id_utente'] : 0;
	if($id_utente!=0)
		$filtro_risorsa="and fk_id_utente = ".$id_utente;
	else 
		$filtro_risorsa="";
	$query = "select commenti.*,utenti.*,IFNULL((select sum(tipo) from giudizi where fk_id_commento = commenti.id_commento),0) as giudizio_totale,IFNULL((select count(*) from giudizi where fk_id_commento = commenti.id_commento $filtro_risorsa),0) as giudizi_utente
		  from commenti 
		  inner join utenti on commenti.fk_id_utente = utenti.id_utente where commenti.fk_id_articolo = $id_articolo";
	$res = mysqli_query($db_conn,$query);
	if(!$res) return array();
	else return get_result_to_array($res);
}
function giudizi_commento($id_commenti){
	global $db_conn;
	$query = "select giudizi.*
	          from giudizi
		  where giudizi.fk_id_commento = $id_commento";
	$res = mysqli_query($db_conn,$query);
	if(!$res) return array();
	else return get_result_to_array($res);
}
function gia_commentato($id_articolo){
	global $db_conn;
	$id_utente = (isset($_SESSION['id_utente']))? $_SESSION['id_utente'] : 0;
	if($id_utente!=0)
		$filtro_risorsa="and fk_id_utente = ".$id_utente;
	else 
		$filtro_risorsa="";
	$query = "SELECT count(*) as numero_commenti  from commenti where fk_id_articolo  = $id_articolo $filtro_risorsa";
	$res = mysqli_query($db_conn,$query);
	$row = mysqli_fetch_array($res);
	if($row["numero_commenti"] > 0) { 
		return true;
	}
	return false;
}
function controlla_login($mail,$password){
	global $db_conn;
	$mail = mysqli_real_escape_string($db_conn, $mail);
	$query = "SELECT * from utenti where utenti.mail = \"$mail\"";
	
	$res = mysqli_query($db_conn,$query);
	if(!$res) return array("esito"=>false);
	$dati = get_result_to_array($res,true);
	
	if(!isset($dati['password'])){
		return array("esito"=>false);
	}
	else if(md5($password)!=$dati['password']){
		return array("esito"=>false);
	}
	else {
		$dati['esito']=true;
		return $dati;
	}
}
function crea_utente($nome,$cognome,$mail,$password,$ripassword){
	global $db_conn;
	$mail = mysqli_real_escape_string($db_conn, $mail);
	$nome = mysqli_real_escape_string($db_conn, $mail);
	$cognome = mysqli_real_escape_string($db_conn, $mail);
	$password = md5($password);
	$query = "INSERT INTO utenti (nome,cognome,mail,password)
	  	  values(\"$nome\",\"$cognome\",\"$mail\",\"$password\")";
	
	$res = mysqli_query($db_conn,$query);
	if(!$res) return array("esito"=>false);
	$id_utente = mysqli_insert_id($db_conn);
	if(!isset($id_utente) || $id_utente==0)return array("esito"=>false);
	$dati["id_utente"]=$id_utente;
	$dati["esito"]=true;
	return $dati;
	
}
function mail_gia_usata($mail){
	global $db_conn;
	$mail = mysqli_real_escape_string($db_conn, $mail);
	$query = "SELECT count(*) as conteggio from utenti where utenti.mail = \"$mail\"";
	
	$res = mysqli_query($db_conn,$query);
	if(!$res) return array("esito"=>false);
	$dati = get_result_to_array($res,true);
	if($dati["conteggio"] > 0) { 
		return true;
	}
	return false;
	
}

?>
