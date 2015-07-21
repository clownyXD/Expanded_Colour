<?php
	$file = "../ResearchUploads/$user/Stitched/$type/$layout/year.xml";
	if(file_exists($file)){
		unlink($file);
	}	
	//creates file
	$doc = new DOMDocument('1.0',"UTF-8");
	// we want a nice output
	
	$root = $doc->createElement('years');
	$root = $doc->appendChild($root);
	
	$year = $doc->createElement('year', $smallest_year);
	$year = $root->appendChild($year);
	
	$doc->FormatOutput = true;
	$string_value = $doc->saveXML();
	$doc->save($file);

?>