<?php	
	$user = $_GET['user'];
	$file = "../ResearchUploads/$user/file_data.xml";
	if(!is_file($file)){
		//exit('Failed to open $file.');
	} else {
		$xml = simplexml_load_file($file);
		$images = array();
		for($i = 0; $i < count($xml->image); $i++){
			$images[] = array(
				'name'	=>	(string)$xml->image[$i]->name,
				'extension'	=>	(string)$xml->image[$i]->extention,
				'time' 	=>	(string)$xml->image[$i]->time,
				'day' 	=>	(string)$xml->image[$i]->day,
				'month'	=>	(string)$xml->image[$i]->month,
				'year'	=>	(string)$xml->image[$i]->year,
				'number' =>		$i,
				'make'	=>	(string)$xml ->image[$i]->make,
				'model'	=>	(string)$xml ->image[$i]->model,
				'orientation'	=>	(string)$xml ->image[$i]->orientation,
				'XResolution'	=>	(string)$xml ->image[$i]->XResolution,
				'YResolution'	=>	(string)$xml ->image[$i]->YResolution,
				'resolutionUnit'	=>	(string)$xml ->image[$i]->resolutionUnit,
				'software'	=>	(string)$xml ->image[$i]->software,
				'exif_IFD_Pointer'	=>	(string)$xml ->image[$i]->exif_IFD_Pointer,
				'hueMode'	=>	(string)$xml ->image[$i]->hueMode,
				'greyMode'	=>	(string)$xml ->image[$i]->greyMode
			);
		}
		$checkBool = TRUE;
		$counter = 0;
		echo json_encode($images);
	}
	
?>