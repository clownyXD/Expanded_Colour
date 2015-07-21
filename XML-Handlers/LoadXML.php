<?php
	$file = "../ResearchUploads/$user/file_data.xml";
	if(!is_file($file)){
		//exit('Failed to open $file.');
		echo "not a file";
		//disks/diskh/zco/cocbl/ResearchUploads/file_data.xml
	} else {
		$xml = simplexml_load_file($file);
		$images = array();
		for($i = 0; $i < count($xml->image); $i++){
			$images[] = array(
				'name'		=>	(string)$xml->image[$i]->name,
				'extension'	=>	(string)$xml->image[$i]->extention,
				'time' 		=>	(string)$xml->image[$i]->time,
				'day' 		=>	(string)$xml->image[$i]->day,
				'month'		=>	(string)$xml->image[$i]->month,
				'year'		=>	(string)$xml->image[$i]->year,
				'hueMode'	=>	(string)$xml->image[$i]->hueMode,
				'greyMode'	=>	(string)$xml->image[$i]->greyMode,
				/* 'hueMed'	=>	(string)$xml->image[$i]->hueMed,
				'colourMode'=>	(string)$xml->image[$i]->colourMode,
				'colourMed'	=>	(string)$xml->image[$i]->colourMed, */
				'number' =>		$i
			);
		}
	
		$sort = array();
		foreach($images as $k=>$v){
			$sort['name'][$k] = $v['name'];
			$sort['time'][$k] = $v['time'];
			$sort['day'][$k] = $v['day'];
			$sort['month'][$k] = $v['month'];
			$sort['year'][$k] = $v['year'];
			$sort['hueMode'][$k] = $v['hueMode'];
			$sort['greyMode'][$k] = $v['greyMode'];
/* 			$sort['hueMed'][$k] = $v['hueMed'];
			$sort['colourMode'][$k] = $v['colourMode'];
			$sort['colourMed'][$k] = $v['colourMed']; */
		}
		
		//$sort['greyMode'] = sort($sort['greyMode']); // inverts so we start at white to black - then colours
		
		if($type== "Time"){
			//sorts for time
			array_multisort($sort['year'],SORT_ASC, $sort['month'],SORT_ASC,$sort['day'],SORT_ASC,$sort['time'],SORT_ASC,$images);
		}elseif ($type == "Colour"){
			//sorts for colour
			array_multisort($sort['greyMode'],SORT_DESC,$sort['hueMode'],SORT_ASC,$images,$images);
		}/* elseif ($type == "Colour"){ //no wonder this didn't work.... -.-
			array_multisort($sort['hueMed'],SORT_ASC,$images);
		} */
		for($i = 0; $i < count($images); $i++){
			$images[$i]['number'] = $i;
		}
		//echo var_dump($images);
	}
	
	//$array = json_encode($images);
	//echo "<script>console.log($array)</script>";
/* 	for($i = 0; $i < count($xml->image); $i++){
		echo $images[$i]['name'].".".$images[$i]['extension']." - ".$images[$i]['time']." - ".$images[$i]['day']."/".$images[$i]['month']."/".$images[$i]['year']. "<br>";
	} */
?>