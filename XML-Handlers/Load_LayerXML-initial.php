<?php
	$Vzoom = $_GET['Vzoom'];
	$type = $_GET['type'];
	if(isset($_GET['user'])){
		$user = $_GET['user'];
	}else{
		$user =1;
	}
	$concatImages = array();
	for($j = 0; $j <= $Vzoom; $j++){
		$file ="../ResearchUploads/$user/Stitched/$type/10/".$j."/info.xml";	//25 as we don't need to worry about horizontal.
		if(!is_file($file)){
			exit("Failed to open $file");
		} else {
			$xml = simplexml_load_file($file);
			$images = array();
			for($k = 0; $k < count($xml->image); $k++){
				$images[] = array(
					'name'	=>	(string)$xml->image[$k]->name,
					'Xstart'	=>	(string)$xml->image[$k]->Xstart,
					'Xend' 	=>	(string)$xml->image[$k]->Xend,
					'Ystart' 	=>	(string)$xml->image[$k]->Ystart,
					'Yend'	=>	(string)$xml->image[$k]->Yend
				);
			}
		}
		array_push($concatImages, $images);
	}
	
	echo json_encode($concatImages);
?>