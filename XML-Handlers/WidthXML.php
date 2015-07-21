<?php
	$file = "../ResearchUploads/$user/Stitched/$type/$layout/width.xml";
	if(file_exists($file)){
		unlink($file);
	}	
	//creates file
	$doc = new DOMDocument('1.0',"UTF-8");
	// we want a nice output
	$root = $doc->createElement('section');
	$root = $doc->appendChild($root);
	
	for($counter = 0; $counter < count($allWidths); $counter ++){
		$widths = $doc -> createElement('widths');
		$widths = $root -> appendChild($widths);
		
		$name = $doc->createElement('name',(string)$size_per_image[$counter]);
		$name = $widths->appendChild($name);
		for($innercounter = 0; $innercounter < count($allWidths[$counter]); $innercounter++){
			$XMLwidth = $doc->createElement('width', $allWidths[$counter][$innercounter]);
			$XMLwidth = $widths->appendChild($XMLwidth);
		}
	}	
	
	$doc->FormatOutput = true;
	$string_value = $doc->saveXML();
	$doc->save($file);

?>