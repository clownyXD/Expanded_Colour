<?php
	//open file
	//go through 
	//if one value = the same add a '_copy' one
	//before extention
	$file = "../ResearchUploads/file_data.xml";
	if(!is_file($file)){
		//exit('Failed to open $file.');
	} else {
		$xml = simplexml_load_file($file);
		$noDup = TRUE;
		//remove spaces
		$temp_name = "";
		for($p = 0; $p < strlen($file_name); $p++){
			if (substr($file_name, $p, 1) == " "){
				$temp_name .= "_";
			}else{
				$temp_name .= substr($file_name, $p, 1);
			}
		}
		$file_name = $temp_name;
		while($noDup){
			$dupTest = FALSE;
			for($dupCounter = 0; $dupCounter < count($xml->image); $dupCounter++){
				$Current_name_on_dup = (string)$xml->image[$dupCounter]->name.".".(string)$xml->image[$dupCounter]->extention;
				if($Current_name_on_dup == $file_name){
					$dupTest = TRUE;
					list($first, $last) = explode(".", $file_name);
					$file_name = $first."_dup.".$last;
				}
			}
			if(!$dupTest){
				$noDup = FALSE;
			}
		}
	}
	//echo $file_name;
?>