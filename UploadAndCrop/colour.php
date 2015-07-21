<?php
	include_once "rgb2hsl.php";
	$im = ImageCreateFromJpeg($target_file); 

	$imgw = imagesx($im);
	$imgh = imagesy($im);
	$n = $imgw*$imgh;
	$histoHue = array();
	$histoGrey = array();
	for($m=0; $m<=360; $m++){
		$histoHue[$m] = 0;
	}
	for($m = 0; $m <= 100; $m++){
		$histoGrey[$m] = 0;		
	}
	$greyCount = 0;
	$count = 0;
	for ($m=0; $m<$imgw; $m++){
		for ($n=0; $n<$imgh; $n++){
		$hsl = array();
		//Hue
			// get the rgb value for current pixel
			$rgb = ImageColorAt($im, $m, $n); 
			// extract each value for r, g, b
			
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			$hsl = rgb2hsl(array($r,$g,$b));
			// extract each value for r, g, b
			if($hsl[1] > 0.15){
				$histoHue[$hsl[0]]++;
			}else{
				$histoGrey[($hsl[2]*100)]++;
				$greyCount ++;
			}
		}
	}
//Hue
	//most common
	if($greyCount != $imgh*$imgw){
		$xml_hueMode = array_search(max($histoHue),$histoHue);
		$xml_greyMode = -1;
	}else{
		$xml_greyMode = array_search(max($histoGrey),$histoGrey);
		$xml_hueMode = -1;
	}
?>