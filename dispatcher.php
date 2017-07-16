<?php
	include_once "defines.php";
	include_once "query.php";
	include_once "functions.php";
	
	$message="";
	if(isset($_POST['action']) && isset($_SESSION['logged'])){
		
		$function_name = $_POST['action'];
		if (function_exists($function_name)){
			$ret = $function_name();
			if($ret==false){
				$message="ERROR";
			}
			else {
				$message="OK";
			}
		}
		
	}
	
	
	
	
?>
