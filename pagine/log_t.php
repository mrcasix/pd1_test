<?php

//require_once '../lib/session.php';
		require_once '../build/p_lib/dbconnect.php';
		session_start();
		$pk = $_GET["t"];
		$idst = base64_decode($_GET["i"]);
		$idst = (int)$idst;
		$check = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM anagrafica WHERE idst = ".$idst) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$info = mysqli_fetch_array($check);
		$pkdb = md5($info["idst"].$info["cognome"].$info["nome"]);

		if($pk !== $pkdb):
			header("Location: login.php?lerr");
		else:
			$_SESSION['sito'] = $info['idst'];
			$_SESSION['info'] = array($info['userid'], $info['password'], $info['codice_fiscale'], $info['SSO']);
			
			$res_perm_pers = mysqli_query($GLOBALS["___mysqli_ston"], "select * from permessi_utenti where idst=".$info['idst']);
			$exists = mysqli_num_rows($res_perm_pers);
			
			if($exists > 0):
				$res = mysqli_query($GLOBALS["___mysqli_ston"], "select a.permessi, a.ownership, a.id_gruppo, b.permission
									from permessi_utenti a 
									join permessi_gruppo b
									on a.id_gruppo = b.id_gruppo
									where a.idst = ".$info['idst']);
				$info_perm = mysqli_fetch_assoc($res);
				$perm = $info_perm["permessi"];
				if ($perm != "") $permessi = $info_perm["permessi"];
				else $permessi = $info_perm["permission"];
				$ownership = $info_perm["ownership"];
				if($info_perm["id_gruppo"]==1):
					$is_god_admin = 1;
				endif;
			else:
				$res = mysqli_query($GLOBALS["___mysqli_ston"], "select permission from permessi_gruppo where id_gruppo = 2");
				$info_perm = mysqli_fetch_assoc($res);
				$permessi = $info_perm["permission"];
				$ownership = 1000;
			endif;
			$_SESSION["is_owner"] = explode("*",$ownership);
			///
			$arrayize = explode("*",$permessi);
			$level = array();
			for($a=0; $a<count($arrayize); $a++):
				$sub_array = explode(",", $arrayize[$a]);
				$level[] = $sub_array;
			endfor;
			
			// cerco se è docente
			/*
			$res_docente = mysql_query("select idst from albo_docenti where idst = ".$_SESSION["sito"]);
			if(mysql_num_rows($res_docente)):
				$_SESSION["is_docente"] = 1;
				$level[1][0] = 1; //imposto i diritti di visione dell'area edizioni	
				$level[1][2] = 1;
				$level[1][3] = 1;
				$level[1][4] = 1;
				$level[1][5] = 1;
			else:
				$_SESSION["is_docente"] = 0;
			endif;
			*/
			////////////////////////////////////////////////
			//cf_org_manager, cf_hr_manager refer to conf.php
			/// stabilisco se è un hr manager
	
			if($info['SSO']!=''):
				$res_hr = mysqli_query($GLOBALS["___mysqli_ston"], "select idst from anagrafica where status=1 and sso_hr_manager = '".$info['SSO']."'");
				$num_hr = mysqli_num_rows($res_hr);
				/// stabilisco se è un capo
				$res_c = mysqli_query($GLOBALS["___mysqli_ston"], "select idst from anagrafica where status=1 and sso_org_manager = '".$info['SSO']."'");
				$num = mysqli_num_rows($res_c);
				/// stabilisco se è un supervisor
				$res_s = mysqli_query($GLOBALS["___mysqli_ston"], "select idst from anagrafica where status=1 and sso_supervisore1 = '".$info['SSO']."'");
				$num_s = mysqli_num_rows($res_s);
			else:
				$num_hr = 0;
				$num = 0;
				$num_c = 0;
			endif;
	
			if($num_hr > 0 and ($num  >0 or $num_s>0)):
				$level[] = "both"; // sia capo, sia hr manager
			elseif($num_hr > 0 and $num == 0 and $num_s ==0 ):
				$level[] = "research_key_2"; // hr manager
			elseif($num_hr == 0 and ($num > 0 or $num_s > 0)): 
				$level[] = "research_key_1"; // è capo o supervisore
			elseif($num_hr == 0 and $num == 0 and $num_s==0): 
				$level[] = "no"; // lavoratore generico
			endif;
			$level[] = $is_god_admin;
			//////////////////////////////////////////////////////
			$_SESSION["is_admin"] = $level;

			$myip = $_SERVER['REMOTE_ADDR'];
			$sql_log = mysqli_query($GLOBALS["___mysqli_ston"], "insert into track_session set idst=".$_SESSION['sito'].", data_in='".date('Y-m-d H:i:s')."', op='_login_mail', ip='".$myip."'");
			
			header("Location: index.php?page=home");
		endif;

?>