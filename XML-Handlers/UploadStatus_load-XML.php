<?php
	$file = "ResearchUploads/$user/UploadStatus.xml";	
	if(!is_file($file)){
		//exit('Failed to open $file.');
		$vals = array(array(0,0,0),array(0,0,0),array(0,0,0));
	} else {
		$xml = simplexml_load_file($file);
		$vals[0][0] = (string)$xml->Time->Hilberts;
		$vals[0][1] = (string)$xml->Time->Linear;
		$vals[0][2] = (string)$xml->Time->ZigZag;
		$vals[1][0] = (string)$xml->Colour->Hilberts;
		$vals[1][1] = (string)$xml->Colour->Linear;
		$vals[1][2] = (string)$xml->Colour->ZigZag;
		$vals[2][0] = (string)$xml->ColourMed->Hilberts;
		$vals[2][1] = (string)$xml->ColourMed->Linear;
		$vals[2][2] = (string)$xml->ColourMed->ZigZag;
	}
?>