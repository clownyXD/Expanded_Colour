<?php
	$type = ($_GET["type"]);
	$user = ($_GET["user"]);
	include "../XML-Handlers/getEarlyYear.php";
	include "../DeepZoomCreation/ColourCreation/ColourDeclaration.php";
	//number of folders - for colour difference
	$folder = "../ResearchUploads/$user/Stitched/$type/50";
	$foldercount = (count(glob("$folder/*",GLOB_ONLYDIR))); 
	
	$years = 0;
	$months = round(($foldercount)*(1/3));
	$weeks = round(($foldercount)*(2/3));
	$Yeararrayofcolours = array_reverse($Yeararrayofcolours);
	echo json_encode(array(array($years,$months,$weeks),$Yeararrayofcolours,$Montharrayofcolours,$Daysarrayofcolours,$smallest_year));
?>