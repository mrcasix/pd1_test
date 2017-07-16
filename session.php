<?php
	include_once 'defines.php';
	session_start();
	/*
	if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
		header ("Location: https://".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']."");
		die();
	}
	*/
	if(isset($_GET['controlla_cookies'])) {
		if(!isset($_COOKIE[session_name()]))
			die('Bisogna abilitare i cookie per accedere al sito.');
	} else {
		$url_string="?";
		if(!empty($_SERVER['QUERY_STRING']))
			$url_string .= $_SERVER['QUERY_STRING']."&";	
		$url_string .="controlla_cookies";	
		header('Location: '.$_SERVER['PHP_SELF'].$url_string);
	}
	if(isset($_SESSION['ultima_attivita'])){
		if($_SESSION['ultima_attivita']<time()-__DURATA_SESSIONE__*60){
			$_SESSION=array();
			session_unset();
			session_destroy();
		} else {
			$_SESSION['ultima_attivita']=time();
		}
	}
?>
