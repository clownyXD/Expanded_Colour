<?php
	$file = "../ResearchUploads/$user/UploadStatus.xml";
	$vals = array(
				array(0,0,0),
				array(0,0,0),
				array(0,0,0)
				);
	if(file_exists($file)){
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
		unlink($file);
	}
	if (isset($type) && isset($layout)){
		$t = 0;
		if($type == "Colour"){
			$t = 1;
		}elseif($type == "ColourMed"){
			$t = 2;
		}
		if($layout == "Hilberts"){
			$vals[$t][0]=1;
		}
		if($layout == "Linear"){
			$vals[$t][1]=1;
		}
		if($layout == "ZigZag"){
			$vals[$t][2]=1;
		}
	}
	
		
	//creates file
	$doc = new DOMDocument('1.0',"UTF-8");
	// we want a nice output
	
	$root = $doc->createElement('status');
	$root = $doc->appendChild($root);
	
	$time = $doc->createElement('Time');
	$time = $root->appendChild($time);
	
	$Hilberts = $doc->createElement('Hilberts',$vals[0][0]);
	$Hilberts = $time->appendChild($Hilberts);
	
	$linear = $doc->createElement('Linear',$vals[0][1]);
	$linear = $time->appendChild($linear);
	
	$zigzag = $doc->createElement('ZigZag',$vals[0][2]);
	$zigzag = $time->appendChild($zigzag);
	
	$colour = $doc->createElement('Colour');
	$colour = $root->appendChild($colour);
	
	$Hilberts = $doc->createElement('Hilberts',$vals[1][0]);
	$Hilberts = $colour->appendChild($Hilberts);
	
	$linear = $doc->createElement('Linear',$vals[1][1]);
	$linear = $colour->appendChild($linear);
	
	$zigzag = $doc->createElement('ZigZag',$vals[1][2]);
	$zigzag = $colour->appendChild($zigzag);
	
	$colourMed = $doc->createElement('ColourMed');
	$colourMed = $root->appendChild($colourMed);
	
	$Hilberts = $doc->createElement('Hilberts',$vals[2][0]);
	$Hilberts = $colourMed->appendChild($Hilberts);
	
	$linear = $doc->createElement('Linear',$vals[2][1]);
	$linear = $colourMed->appendChild($linear);
	
	$zigzag = $doc->createElement('ZigZag',$vals[2][2]);
	$zigzag = $colourMed->appendChild($zigzag);
	
	$doc->FormatOutput = true;
	$string_value = $doc->saveXML();
	$doc->save($file);
?>