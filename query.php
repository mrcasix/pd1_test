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
	else if(hash('sha512',$password)!=$dati['password']){
		die(hash('sha512',$password)."<br>".$dati['password']);



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
	$password = hash('sha512',$password);
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
	if(!$res) return false;
	$dati = get_result_to_array($res,true);
	if($dati["conteggio"] > 0) { 
		return true;
	}
	return false;
	
}
function get_prenotazioni($id_utente=NULL){
	global $db_conn;
	$id_utente = mysqli_real_escape_string($db_conn, $id_utente);
	$query = "SELECT * FROM prenotazioni p,utenti u,orario_prenotazioni o where p.fk_id_utente=u.id_utente and o.id_orario_prenotazioni=p.fk_id_orario_prenotazione";

	if($id_utente!=NULL)
 		$query.=" and p.fk_id_utente=".$id_utente;


 	$query.=" order by p.ora_inizio_prenotazione";
	
	$res = mysqli_query($db_conn,$query);
	
	if(!$res) return array();
	return get_result_to_array($res,false);
}


function get_orario($id_orario=__FASCIA_ORARIA_PREDEFINITA__){
	global $db_conn;
;
	$query = "SELECT * FROM orario_prenotazioni where id_orario_prenotazioni=".$id_orario;
	$res = mysqli_query($db_conn,$query);

	if(!$res) return array();
	return get_result_to_array($res,true);
	
}

function totale_prenotazione(){
	global $db_conn;
;
	$query = "SELECT IFNULL(sum(durata_assegnata),0) as totale FROM prenotazioni";
	$res = mysqli_query($db_conn,$query);

	if(!$res) return array();
	return get_result_to_array($res,true);
	
}


function prenotazioni_richieste(){
	global $db_conn;
;
	$query = "SELECT id_prenotazione,durata_richiesta FROM prenotazioni order by ora_inizio_prenotazione DESC";
	$res = mysqli_query($db_conn,$query);

	if(!$res) return array();
	return get_result_to_array($res,false);
	
}




function save_prenotazione(){
	

	global $db_conn;
	$id_utente = mysqli_real_escape_string($db_conn, $_SESSION['id_utente']);

	$orario = get_orario();
	$totale_prenotazioni = totale_prenotazione();
	$totale_precedente = (int) $totale_prenotazioni["totale"];
	$elenco_richieste =  prenotazioni_richieste();
	$totale_orari_inizio = array();
	
	

	if($totale_precedente<180){
		
		$durata = mysqli_real_escape_string($db_conn, $_POST['durata']);
		$nuove_durate=array();
		
		if($totale_precedente+$durata>180){
			$totale = $totale_precedente+$durata;

			for($i=0;$i<count($elenco_richieste);$i++){
				$nuove_durate[$i] = round(((float) $elenco_richieste[$i]["durata_richiesta"]/$totale)*180);
			}	
			$nuove_durate[$i] = round(((float) $durata/$totale)*180);
			
			for($i=0;$i<count($elenco_richieste);$i++){

				if(count($totale_orari_inizio)==0){
					$ora_inizio = $orario["ora_inizio"];
					$ora_fine = add_minute_to_hour($ora_inizio,$nuove_durate[$i]);
				}
				else {
					$ora_inizio = array_pop((array_slice($totale_orari_inizio, -1)));
					$ora_fine = add_minute_to_hour($ora_inizio,$nuove_durate[$i]);
				}

				$totale_orari_inizio[] = $ora_fine;

				$query = "UPDATE prenotazioni SET ora_inizio_prenotazione='".$ora_inizio."',ora_fine_prenotazione='".$ora_fine."',durata_assegnata='".$nuove_durate[$i]."' WHERE id_prenotazione=".$elenco_richieste[$i]['id_prenotazione'];
				$res = mysqli_query($db_conn,$query);
			}

			if(count($totale_orari_inizio)==0){
				$ora_inizio = $orario["ora_inizio"];
				$ora_fine = add_minute_to_hour($ora_inizio,$nuove_durate[$i]);
			}
			else {
				$ora_inizio = array_pop((array_slice($totale_orari_inizio, -1)));
				$ora_fine = add_minute_to_hour($ora_inizio,$nuove_durate[$i]);
			}

			$query = "INSERT INTO prenotazioni(fk_id_utente,durata_assegnata,durata_richiesta,ora_inizio_prenotazione,ora_fine_prenotazione,fk_id_orario_prenotazione) VALUES ($id_utente,$nuove_durate[$i],$durata,'$ora_inizio','$ora_fine',".__FASCIA_ORARIA_PREDEFINITA__.")";
	
			$res = mysqli_query($db_conn,$query);

		}
		else {

			$totale_orari_inizio[] = $orario["ora_inizio"];

			for($i=0;$i<count($elenco_richieste);$i++){
				$totale_orari_inizio[] = add_minute_to_hour(array_pop((array_slice($totale_orari_inizio, -1))),$elenco_richieste[$i]["durata_richiesta"]);
			}

			$ora_inizio = array_pop((array_slice($totale_orari_inizio, -1)));
			$ora_fine = add_minute_to_hour($ora_inizio,$durata);
			
			$query = "INSERT INTO prenotazioni(fk_id_utente,durata_assegnata,durata_richiesta,ora_inizio_prenotazione,ora_fine_prenotazione,fk_id_orario_prenotazione) VALUES ($id_utente,$durata,$durata,'".$ora_inizio."','".$ora_fine."',".__FASCIA_ORARIA_PREDEFINITA__.")";

			$res = mysqli_query($db_conn,$query);


		}
	}
}

function delete_prenotazione(){
	global $db_conn;
	$id_utente = mysqli_real_escape_string($db_conn, $_SESSION['id_utente']);

	$id_prenotazione = mysqli_real_escape_string($db_conn, $_POST['id_prenotazione']);
	$query = "DELETE FROM prenotazioni WHERE fk_id_utente=$id_utente AND id_prenotazione=$id_prenotazione";
	mysqli_query($db_conn,$query);


	$orario = get_orario();
	$elenco_richieste =  prenotazioni_richieste();
	$totale = 0;

	$totale_orari_inizio = array();
	$totale_orari_inizio[] = $orario["ora_inizio"];
	
	// Riassegno le durate richieste

	for($i=0;$i<count($elenco_richieste);$i++){

		$ora_inizio = array_pop((array_slice($totale_orari_inizio, -1)));
		$ora_fine = add_minute_to_hour($ora_inizio,$elenco_richieste[$i]["durata_richiesta"]);
		$totale_orari_inizio[] = $ora_fine;	

		$query = "UPDATE prenotazioni SET ora_inizio_prenotazione='".$ora_inizio."',ora_fine_prenotazione='".$ora_fine."',durata_assegnata='".$elenco_richieste[$i]["durata_richiesta"]."' WHERE id_prenotazione=".$elenco_richieste[$i]['id_prenotazione'];
		$res = mysqli_query($db_conn,$query);
	}	
	
}


?>
