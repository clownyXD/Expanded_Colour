<?php
	$file = "../ResearchUploads/$user/file_data.xml";
	
	$XMLname = $file_name;
	list($XMLdate, $XMLtime) = explode(" ",$date_taken);
	$XMLext = $file_ext;
	list($XMLyear, $XMLmonth, $XMLday) = explode(":",$XMLdate);
	
	if(file_exists($file)){
		// open and load a XML file
		$doc = new DomDocument();
		$doc->load($file);
		// Apply some modification
		
		$root = $doc->getElementsByTagName('images')->item(0);
		
		$image = $doc->createElement('image');
		//$image = $root->appendChild($image);
	}else{	
		//creates file
		$doc = new DOMDocument('1.0',"UTF-8");
		// we want a nice output
		
		$root = $doc->createElement('images');
		$root = $doc->appendChild($root);

		$image = $doc->createElement('image');
		$image = $root->appendChild($image);
	}
	$name = $doc->createElement('name',$XMLname);
	$name = $image->appendChild($name);
	
	$extention = $doc->createElement('extention',$XMLext);
	$extention = $image->appendChild($extention);
	
	$time = $doc->createElement('time',$XMLtime);
	$time = $image->appendChild($time);
	
	$day = $doc->createElement('day',$XMLday);
	$day = $image->appendChild($day);
	
	$month = $doc->createElement('month',$XMLmonth);
	$month = $image->appendChild($month);
	
	$year = $doc->createElement('year',$XMLyear);
	$year = $image->appendChild($year);

	
	if(isset($exif_data['Make'])){$xml_make = $exif_data['Make'];}else{$xml_make= "N/A";}
	if(isset($exif_data['Model'])){$xml_model = $exif_data['Model'];}else{$xml_model= "N/A";}
	if(isset($exif_data['Orientation'])){$xml_orientation = $exif_data['Orientation'];}else{$xml_orientation= "N/A";}
	if(isset($exif_data['XResolution'])){$xml_xResolution = $exif_data['XResolution'];}else{$xml_xResolution= "N/A";}
	if(isset($exif_data['YResolution'])){$xml_yResolution = $exif_data['YResolution'];}else{$xml_yResolution= "N/A";}
	if(isset($exif_data['ResolutionUnit'])){$xml_resolutionUnit = $exif_data['ResolutionUnit'];}else{$xml_resolutionUnit= "N/A";}
	if(isset($exif_data['Software'])){$xml_Software = $exif_data['Software'];}else{$xml_Software= "N/A";}
	if(isset($exif_data['Exif_IFD_Pointer'])){$xml_exif_IFD_Pointer = $exif_data['Exif_IFD_Pointer'];}else{$xml_exif_IFD_Pointer= "N/A";}
	
	echo $xml_orientation . " " . $xml_xResolution . " " . $xml_yResolution . " " . $xml_resolutionUnit . " " . $xml_Software . " " . $xml_exif_IFD_Pointer . "<br>";
	
	$make = $doc->createElement('make',$xml_make);
	$make = $image->appendChild($make);
	
	$model = $doc->createElement('model',$xml_model);
	$model = $image->appendChild($model);
	
	$orientation = $doc->createElement('orientation',$xml_orientation);
	$orientation = $image->appendChild($orientation);
	
	$XResolution = $doc->createElement('XResolution',$xml_xResolution);
	$XResolution = $image->appendChild($XResolution);
	
	$YResolution = $doc->createElement('YResolution',$xml_yResolution);
	$YResolution = $image->appendChild($YResolution);
	
	$resolutionUnit = $doc->createElement('resolutionUnit',$xml_resolutionUnit);
	$resolutionUnit = $image->appendChild($resolutionUnit);
	
	$software = $doc->createElement('software',$xml_Software);
	$software = $image->appendChild($software);
	
	$exif_IFD_Pointer = $doc->createElement('exif_IFD_Pointer',$xml_exif_IFD_Pointer);
	$exif_IFD_Pointer = $image->appendChild($exif_IFD_Pointer);
	
	/* $hueHist = $doc->createElement('hueHist',json_encode($histoHue));
	$hueHist = $image->appendChild($hueHist); */
	
	$hueMode = $doc->createElement('hueMode',$xml_hueMode);
	$hueMode = $image->appendChild($hueMode);
	
	/* $hueMed = $doc->createElement('hueMed',$xml_hueMed);
	$hueMed = $image->appendChild($hueMed); */
	
/* 	$colourHist = $doc->createElement('colourHist',json_encode($histogram));
	$colourHist = $image->appendChild($colourHist); */
	
	/* $colourMode = $doc->createElement('colourMode',json_encode($xml_colourMode));
	$colourMode = $image->appendChild($colourMode);
	
	$colourMed = $doc->createElement('colourMed',json_encode($xml_colourMed));
	$colourMed = $image->appendChild($colourMed); */
	
	$greyMode = $doc->createElement('greyMode',$xml_greyMode);
	$greyMode = $image->appendChild($greyMode);
	
	if(file_exists($file)){
		$root->appendChild($image);
		// Save the new version of the file
		$doc->save($file);
	}else{
		$doc->FormatOutput = true;
		$string_value = $doc->saveXML();
		$doc->save($file);
	}
?>