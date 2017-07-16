<?php

require_once("functions.php");

function track_op($op, $params="0"){
	$myip = $_SERVER['REMOTE_ADDR'];
	$sql_log = mysqli_query($GLOBALS["___mysqli_ston"], "insert into track_session set idst=".$_SESSION['sito'].", data_in='".date('Y-m-d H:i:s')."', op='".$op."', params='".$params."' , ip='".$myip."'");
}

//////////////////////////////////////////////////////////////////////////
function name_surname($id){
	$res = mysqli_query($GLOBALS["___mysqli_ston"], "select * from anagrafica where idst=".$id);
	$dato = mysqli_fetch_array($res);
	$nome = $dato["cognome"]." ".$dato["nome"];
	return $nome;
}

function edit_password(){
	session_start();
	$password = $_POST["password"];
	$password_criptata = md5($password);
	$idst = $_SESSION["sito"];
	$update = mysqli_query($GLOBALS["___mysqli_ston"], "update anagrafica set modifica_password='".date('Y-m-d H:i:s')."', password='".$password_criptata."', da_aggiornare_docebo=1 where idst=".$idst);
	return true;
}

function fiendly_name($id){
	$res = mysqli_query($GLOBALS["___mysqli_ston"], "select * from anagrafica where idst=".$id);
	$dato = mysqli_fetch_array($res);
	$nome = $dato["nome"];
	return $nome;
}

function get_table($table, $fields, $conditions){
	$query = 'SELECT '.$fields.' FROM '.$table;
	if ($conditions!=NULL) $query .= ' WHERE '.$conditions;
	
	
	
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	
	if(!$res) return null;
	
	$result_data = array();	
	
	while($result = mysqli_fetch_assoc($res)):
		foreach($result as $key=>$value):
			$array_temp[$key] = $value;
		endforeach;
		$result_data[] = $array_temp;
	endwhile;
	return $result_data;
	
}

function get_table_test($table, $fields, $conditions){
	$query = 'SELECT '.$fields.' FROM '.$table;
	if ($conditions!=NULL) $query .= ' WHERE '.$conditions;
	
	
	
	return $query;
	
}

function save_tb_db($fields, $tb_name, $pk){
	
	$arr = (array_keys($fields));
	$last_field = $arr[count($arr)-1];
	$query = 'insert into '.$tb_name.' set ';
	foreach($fields as $field=>$value)	:
		if($field!='tb'){
			$query .= $field.'="'.addslashes($value).'"';
		
			if($field!=$last_field) $query .= ', ';
		}
	endforeach;
	

	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$pk = mysqli_insert_id($GLOBALS["___mysqli_ston"]);
	return $pk;
}

function update_tb_db($fields, $tb_name, $pk){
	$last_field = count($fields);
	$i=0;
	$query = 'update '.$tb_name.' set ';
	foreach($fields as $field=>$value)	:
	
		$query .= $field.'="'.addslashes($value).'"';
		$i++;
		if($i!=$last_field) $query .= ', ';
		
	endforeach;
	
	
	
	$pk_name = array_keys($pk);
	$pk_value = array_values($pk);	
	$query .= ' where '.$pk_name[0].'='.$pk_value[0];
	
	
	
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	return $pk_value[0];
}

function del_tb_db($tb_name, $pk){
	$pk_name = array_keys($pk);
	$pk_value = array_values($pk);		
	$query = 'delete from '.$tb_name.' where '.$pk_name[0].'='.$pk_value[0];
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	return $query;
}

function save_attivita_db($values){
	$id_att = $values['id_att'];
	//$attori_array = json_decode($values['attori']);
	$attori = $values['attori'];
	$fields = array('cod_attivita'=>$values['cod_att'],
				   'attivita'=>$values['attivita'],
				   'fk_id_dom_fasi'=>$values['fk_id_fase']);

	if($id_att==0) $id_att_db = save_tb_db($fields, 'dom_attivita', 0);
	else  $id_att_db = update_tb_db($fields, 'dom_attivita', array('id_dom_attivita'=>$id_att));
	
	for($a=0; $a<count($attori); $a++):
		$chiave = $attori[$a]['name'];
		$valore = $attori[$a]['value'];	
		$id_attore =  substr($chiave, strpos($chiave, '_')+1);
		
		$query_del = mysqli_query($GLOBALS["___mysqli_ston"], 'delete from dom_attivita_x_attori where fk_id_dom_attivita='.$id_att_db.' and fk_id_dom_attori='.$id_attore);
		
		$query = 'insert into dom_attivita_x_attori set
				  fk_id_dom_attivita='.$id_att_db.',
				  fk_id_dom_attori='.$id_attore.',
				  tipo_responsabilita="'.$valore.'"';
		$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	endfor;
	
	return $id_att_db;

}

function get_profili_sottoprocessi($id_profilo){
	$query = 'select * from dom_profili_x_sottoprocessi';
	if($id_profilo>0) $query .= ' where deleted=0 and fk_id_dom_profili_ruolo='.$id_profilo;
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_profilo = $info['fk_id_dom_profili_ruolo'];
		$id_processo = $info['fk_id_dom_sottoprocessi'];
		$list[$id_profilo][] = $id_processo;
	endwhile;
	return $list;
}

function get_profili_competenze($id_profilo){
	$query = 'select * from dom_profili_x_competenze';
	if($id_profilo>0) $query .= ' where fk_id_dom_profili_ruolo='.$id_profilo;
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_profilo = $info['fk_id_dom_profili_ruolo'];
		$id_competenza = $info['fk_id_dom_competenze'];
		$list[$id_profilo][] = $id_competenza;
	endwhile;
	return $list;
}

function get_profilo($id_profilo){
	$query = 'select * from dom_profili_ruolo where deleted=0 and id_dom_profili_ruolo='.$id_profilo;
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
	
		$list []= array('cod_profilo'=>$info['cod_profilo'],
											 'profilo'=>$info['profilo'],
											 'fk_id_dom_aree_aziendali'=>$info['fk_id_dom_aree_aziendali'],
											 'descrizione'=>$info['descrizione'],
											 'fk_id_dom_criteri_valutazione'=>$info['fk_id_dom_criteri_valutazione']);
		
	endwhile;
	
	
	return  $list;
}


function get_profili_info_full($id_profilo){
	$query = 'select * from dom_profili_x_competenze a
			  join dom_competenze b on a.fk_id_dom_competenze = b.id_dom_competenze
			  join dom_competenze_area c on b.fk_id_dom_competenze_area=c.id_dom_competenze_area
			  join dom_competenze_tipologie d on c.fk_id_dom_competenze_tipologie = d.id_dom_competenze_tipologie
			  where b.deleted=0 and c.deleted=0 and d.deleted=0';
	if($id_profilo>0) $query .= ' and a.fk_id_dom_profili_ruolo='.$id_profilo;

	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_profilo = $info['fk_id_dom_profili_ruolo'];
		$id_tipo = $info['id_dom_competenze_tipologie'];
		$list[$id_profilo][$id_tipo][] = array('id_area'=>$info['id_dom_competenze_area'],
											 'id_comp'=>$info['fk_id_dom_competenze'],
											 'cod_comp'=>$info['cod_comp'],
											 'competenze'=>$info['competenza'],
											 'descrizione'=>$info['descrizione'],
											 'conoscenze'=>$info['conoscenze'],
											 'abilita'=>$info['abilita'],
											 'atteso'=>$info['atteso']);
		
	endwhile;
	return $list;
}

function get_sottoprocessi_profilo_info_full($id_profilo){
	
	$query = 'select distinct a.fk_id_dom_sottoprocessi, a.fk_id_dom_profili_ruolo,
					 c.id_dom_fasi,
					 e.id_dom_attivita, e.cod_attivita, e.cadenza, e.attivita
					 from dom_profili_x_sottoprocessi a
			  join dom_sottoprocessi b on a.fk_id_dom_sottoprocessi = b.id_dom_sottoprocessi
			  join dom_fasi c on b.id_dom_sottoprocessi = c.fk_id_dom_sottoprocessi
			  join dom_attivita e on c.id_dom_fasi = e.fk_id_dom_fasi
			  where b.deleted=0 and c.deleted=0 and e.deleted=0';
	
	if($id_profilo>0) $query .= ' and a.fk_id_dom_profili_ruolo='.$id_profilo;

	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_profilo = $info['fk_id_dom_profili_ruolo'];
		$id_sp = $info['fk_id_dom_sottoprocessi'];
		$id_fase = $info['id_dom_fasi'];
		$list[$id_profilo][$id_sp][$id_fase][] = array('id_att'=>$info['id_dom_attivita'], 'cod_attivita'=>$info['cod_attivita'], 'attivita'=>$info['attivita'], 'cadenza'=>$info['cadenza']);
	endwhile;
	return $list;
}

function delete_attivita_profilo_db($fk_id_dom_attivita, $fk_id_dom_profili_ruolo){
	$query = "";
	$res = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE dom_profili_x_sottoprocessi set deleted='1' WHERE fk_id_dom_profili_ruolo=".$fk_id_dom_profili_ruolo." AND fk_id_dom_attivita=".$fk_id_dom_attivita);
	if(!$res) return 0;
	return 1;
}
function reset_attivita_profilo_db($fk_id_dom_attivita, $fk_id_dom_profili_ruolo){
	$query = "";
	$res = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE dom_profili_x_sottoprocessi set deleted='0' WHERE fk_id_dom_profili_ruolo=".$fk_id_dom_profili_ruolo." AND fk_id_dom_attivita=".$fk_id_dom_attivita);
	if(!$res) return 0;
	return 1;
}


function save_profilo_processi_db($id_sp, $id_profilo, $action){
	//$res = mysqli_query($GLOBALS["___mysqli_ston"], 'delete from dom_profili_x_sottoprocessi where fk_id_dom_profili_ruolo='.$id_profilo.' and fk_id_dom_sottoprocessi='.$id_sp);
	if($action=='aggiungi'){ 
		
		$sottoprocessi = get_table('dom_sottoprocessi', '*', 'deleted=0 and id_dom_sottoprocessi='.$id_sp);
		for($x=0; $x<count($sottoprocessi); $x++):
			$fasi = get_table('dom_fasi', '*', 'deleted=0 and fk_id_dom_sottoprocessi='.$id_sp);
			for($i=0; $i<count($fasi); $i++):
				$attivita = get_table('dom_attivita', '*', 'deleted=0 and fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi']);
				for($j=0; $j<count($attivita); $j++):
					$res = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT count(*) AS conteggio from dom_profili_x_sottoprocessi where fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].' and fk_id_dom_sottoprocessi='.$id_sp.' and fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi']);
					while($row = mysqli_fetch_assoc($res)):
						if($row['conteggio']>0) :
							$res = mysqli_query($GLOBALS["___mysqli_ston"], 'UPDATE dom_profili_x_sottoprocessi SET fk_id_dom_profili_ruolo='.$id_profilo.',fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].',fk_id_dom_sottoprocessi='.$id_sp.',fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi'].',deleted=0 WHERE fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].' and fk_id_dom_sottoprocessi='.$id_sp.' and fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi']);
						else:
							$res = mysqli_query($GLOBALS["___mysqli_ston"], 'INSERT dom_profili_x_sottoprocessi SET fk_id_dom_profili_ruolo='.$id_profilo.',fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].',fk_id_dom_sottoprocessi='.$id_sp.',fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi'].',deleted=0');
						endif;
					endwhile;
				endfor;
			endfor;
		endfor;
		
	//	$res = mysqli_query($GLOBALS["___mysqli_ston"], 'insert into dom_profili_x_sottoprocessi set fk_id_dom_profili_ruolo='.$id_profilo.', fk_id_dom_sottoprocessi='.$id_sp);
	
	} 
	else if($action=='rimuovi'){
		$sottoprocessi = get_table('dom_sottoprocessi', '*', 'deleted=0 and id_dom_sottoprocessi='.$id_sp);
		for($x=0; $x<count($sottoprocessi); $x++):
			$fasi = get_table('dom_fasi', '*', 'deleted=0 and fk_id_dom_sottoprocessi='.$id_sp);
			for($i=0; $i<count($fasi); $i++):
				$attivita = get_table('dom_attivita', '*', 'deleted=0 and fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi']);
				for($j=0; $j<count($attivita); $j++):
					$res = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT count(*) AS conteggio from dom_profili_x_sottoprocessi where fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].' and fk_id_dom_sottoprocessi='.$id_sp.' and fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi']);
					while($row = mysqli_fetch_assoc($res)):
						if($row['conteggio']>0) :
							$res = mysqli_query($GLOBALS["___mysqli_ston"], 'UPDATE dom_profili_x_sottoprocessi SET fk_id_dom_profili_ruolo='.$id_profilo.',fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].',fk_id_dom_sottoprocessi='.$id_sp.',fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi'].',deleted=1 WHERE fk_id_dom_attivita='.$attivita[$j]['id_dom_attivita'].' and fk_id_dom_sottoprocessi='.$id_sp.' and fk_id_dom_fasi='.$fasi[$i]['id_dom_fasi']);
						endif;
					endwhile;
				endfor;
			endfor;
		endfor;
	}
	
	return true;	
}

function save_profilo_competenze_db($id_comp, $id_profilo, $action, $atteso){
	$res = mysqli_query($GLOBALS["___mysqli_ston"], 'delete from dom_profili_x_competenze where fk_id_dom_profili_ruolo='.$id_profilo.' and fk_id_dom_competenze='.$id_comp);
	if($action=='aggiungi' && $atteso>=0) $res = mysqli_query($GLOBALS["___mysqli_ston"], 'insert into dom_profili_x_competenze set fk_id_dom_profili_ruolo='.$id_profilo.', fk_id_dom_competenze='.$id_comp.', atteso='.$atteso);
	
	
	return true;
}

function autovalutazioni_enabled($id_profilo, $idst=0){
	$query = 'select *, a.deleted as val_deleted from dom_profili_utenti_valutazioni a
			  join dom_profili_x_utenti b on b.id_dom_profili_x_utenti=a.fk_id_dom_profili_x_utenti where 1=1';
	  
	if($id_profilo>0) $query .= ' and b.fk_id_dom_profili_ruolo='.$id_profilo;
	if($idst>0) $query .= ' and b.fk_idst='.$idst;
	
	$query.=' order by completata desc,data desc';
	
	
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	
	
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_profilo = $info['fk_id_dom_profili_ruolo'];
		$fk_idst_valutato = $info['fk_idst'];
		
		if(!isset($list[$id_profilo]))$list[$id_profilo]=array();
		if(!isset($list[$id_profilo][$fk_idst_valutato]))$list[$id_profilo][$fk_idst_valutato]=array();
		$list[$id_profilo][$fk_idst_valutato][] = array('id_profili_utenti'=>$info['id_dom_profili_x_utenti'], 'data'=>$info['data'], 'fk_idst_valutatore'=>$info['fk_idst_valutatore'], 'id_valutazione'=>$info['id_dom_profili_utenti_valutazioni'], 'deleted'=>$info['val_deleted']);
	endwhile;
	
	
	
	return $list;
}

/// cerca se io ho qualcuno a cui è stato chiesto di valutarmi
function eterovalutazioni_enabled($id_profilo, $idst){
	$query = 'select *, a.deleted as val_deleted from dom_profili_utenti_valutazioni a
			  join dom_profili_x_utenti b on b.id_dom_profili_x_utenti=a.fk_id_dom_profili_x_utenti
			  where b.fk_id_dom_profili_ruolo='.$id_profilo.' and b.fk_idst='.$idst.' and a.fk_idst_valutatore <>'.$idst;
	
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_profilo = $info['fk_id_dom_profili_ruolo'];
		$fk_idst_valutato = $info['fk_idst'];
		$list[$idst][] = array('id_profili_utenti'=>$info['id_dom_profili_x_utenti'], 'data'=>$info['data'], 'fk_idst_valutatore'=>$info['fk_idst_valutatore'], 'id_valutazione'=>$info['id_dom_profili_utenti_valutazioni'], 'deleted'=>$info['val_deleted']);
	endwhile;
	return $list;
}

// cerca se io sono stato invitato a valutare qualcuno
function myEterovalutazioni($id_profilo, $idst){
	$query = 'select *, a.deleted as val_deleted from dom_profili_utenti_valutazioni a
			  join dom_profili_x_utenti b on b.id_dom_profili_x_utenti=a.fk_id_dom_profili_x_utenti
			  where b.fk_idst<>'.$idst.' and a.fk_idst_valutatore ='.$idst.' order by completata';
	
	if($id_profilo>0) $query .= ' and b.fk_id_dom_profili_ruolo='.$id_profilo;
	
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$fk_id_prof = $info['fk_id_dom_profili_ruolo'];
		$fk_idst_valutato = $info['fk_idst'];
		$list[$fk_id_prof][$fk_idst_valutato][] = array('id_profili_utenti'=>$info['id_dom_profili_x_utenti'], 'data'=>$info['data'], 'fk_idst_valutatore'=>$info['fk_idst_valutatore'], 'id_valutazione'=>$info['id_dom_profili_utenti_valutazioni'], 'deleted'=>$info['val_deleted']);
	endwhile;
	return $list;
}

function check4_valutation_complete_db($id_valutazione, $id_profilo){
	$query_num_comp_proflilo = 'select count(fk_id_dom_competenze) n from dom_profili_x_competenze where fk_id_dom_profili_ruolo='.$id_profilo;
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query_num_comp_proflilo);
	$info = mysqli_fetch_assoc($res);
	$n_comp_attese = $info['n'];
	
	$query_num_comp_valutate = 'select count(fk_id_dom_competenze) n from dom_profili_utenti_valutazioni_valori where fk_id_dom_profili_utenti_valutazioni='.$id_valutazione;
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query_num_comp_valutate);
	$info = mysqli_fetch_assoc($res);
	$n_competenze_valutate = $info['n'];
	
	if($n_comp_attese==$n_competenze_valutate) update_tb_db(array('data'=>date('Y-m-d H:i:s')), 'dom_profili_utenti_valutazioni', array('id_dom_profili_utenti_valutazioni'=>$id_valutazione));
	return true;
}

function elenco_criteri_valutazione(){
	$query = 'select * from dom_criteri_valutazione where deleted=0';
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_dom_criteri_valutazione = $info['id_dom_criteri_valutazione'];
		$values = array(); 
		
		
		$nome_criterio = $info['nome_criterio'];
		$list[$id_dom_criteri_valutazione]= $nome_criterio;
	endwhile;
	return $list;
}




function elenco_descrizione_livelli(){
	$query = 'select * from dom_descrizione_livelli where deleted=0';
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$list = array();
	while($info = mysqli_fetch_assoc($res)):
		$id_dom_descrizione_livelli = $info['id_dom_descrizione_livelli'];
		$values = array(); 
		$ret["descrizione"] = $info['descrizione'];
		$ret["definizione"] = $info['definizione'];
		$ret["conoscenze"] = $info['conoscenze'];
		$ret["abilita"] = $info['abilita'];
		$ret["competenze"] = $info['competenze'];
		$ret["fk_id_dom_criteri_valutazione"] = $info['competenze'];
		$list[$id_dom_descrizione_livelli]= $ret;
	endwhile;
	return $list;
}


?>
