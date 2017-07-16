<?php
	include 'defines.php';
	$_SESSION=array();
	session_unset();
	session_destroy();
	header("Location: http://".__LINK_INTERO_SITO__);
?>