<?php
	$file = "../ResearchUploads/$user/Stitched/$type/year.xml";
	if(is_file($file)){
		$xml2 = simplexml_load_file($file);
		$smallest_year = intval((string)$xml2->year);

	}
?>