<?php
	$Vzoom = $_GET['Vzoom'];
	$type = $_GET['type'];
	$Hzoom = $_GET['Hzoom'];
	if(isset($_GET['user'])){
		$user = $_GET['user'];
	}else{
		$user =1;
	}
	$file = "../ResearchUploads/$user/Stitched/$type/$Hzoom/$Vzoom/info.xml";	
	if(!is_file($file)){
		exit("Failed to open $file");
	} else {
		$xml = simplexml_load_file($file);
		$images = array();
		for($i = 0; $i < count($xml->image); $i++){
			$images[] = array(
				'name'	=>	(string)$xml->image[$i]->name,
				'Xstart'	=>	(string)$xml->image[$i]->Xstart,
				'Xend' 	=>	(string)$xml->image[$i]->Xend,
				'Ystart' 	=>	(string)$xml->image[$i]->Ystart,
				'Yend'	=>	(string)$xml->image[$i]->Yend
			);
		}
	}
	echo json_encode($images);
?>