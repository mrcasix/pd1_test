<?php
	include 'defines.php';

//	die("Prova");

	session_start();
	$_SESSION=array();
	session_unset();
	session_destroy();
	header("Location: http://".__LINK_INTERO_SITO__);
?>
