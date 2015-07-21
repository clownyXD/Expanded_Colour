<?php
	$file = "../ResearchUploads/$user/Stitched/$type/$layout/".$size_per_image[$currentZoom]."/$i/info.xml";
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
	
	$xstart = $doc->createElement('Xstart',$XMLXstart);
	$xstart = $image->appendChild($xstart);
	
	$xend = $doc->createElement('Xend',$XMLXend);
	$xend = $image->appendChild($xend);
	
	$ystart = $doc->createElement('Ystart',$XMLYstart);
	$ystart = $image->appendChild($ystart);
	
	$yend = $doc->createElement('Yend',$XMLYend);
	$yend = $image->appendChild($yend);
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