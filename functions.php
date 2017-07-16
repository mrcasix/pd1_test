<?php
function get_result_to_array($st,$single_element=false){
	global $db_conn;
	$result_data = array();	
	$array_temp = array();	
	
	while($result = mysqli_fetch_assoc($st)){
		unset($array_temp);
		$array_temp=array();
		foreach($result as $key=>$value){
			$array_temp[$key] = $value;
		}
		$result_data[] = $array_temp;
		
	}
	if($single_element)
		return $array_temp;
	else 
		return $result_data;
}
?>