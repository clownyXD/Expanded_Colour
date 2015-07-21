<?php
	$type = $_GET['type'];
	$user = $_GET['user'];
	$file = "../ResearchUploads/$user/Stitched/$type/width.xml";
	if(!is_file($file)){
		//exit('Failed to open $file.');
	} else {
		$xml = simplexml_load_file($file);
		$full_array = array();
		$name_array = array();
		for($i = 0; $i < count($xml->widths); $i++){
			$temp_array = array();
			array_push($name_array, (string)$xml->widths[$i]->name);
			for($j = 0; $j < count($xml->widths[$i]->width); $j++){
				array_push($temp_array, intval((string)$xml->widths[$i]->width[$j]));
			}
			array_push($full_array, $temp_array);
		}
		$finalarray = array( 	
						$name_array,
						$full_array
					);
		echo json_encode($finalarray);
	}
?>