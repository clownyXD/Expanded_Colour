<?php
//finish zigzag?
//redo hilberts?
//look at showing dates?
	$current = $array_of_images[$reference_array[$a][$b]]->getDateNumber();
	$lowest = $array_of_images[$reference_array[count($reference_array)-1][count($reference_array)-1]]->getDateNumber();
	$highest = 0;
	$lowestX = 0;
	$lowestY = 0;
	$highestX = 0;
	$highestY = 0;
	$temp_widthH =0;
	$temp_heightH =0;
	$changedL = FALSE;
	$changedH = FALSE;
	//echo "current: $current<br>";
	for($q = $a -1; $q <= $a+1; $q++){
		for($r = $b -1; $r <= $b+1; $r++){
			if($q >= 0 && $r >= 0 && $q < count($reference_array) && $r < count($reference_array)){
				$true1 = $array_of_images[$reference_array[$q][$r]]->getDateNumber() <= $lowest;
				$true2 = $array_of_images[$reference_array[$q][$r]]->getDateNumber() > $current;												
				if($true1 && $true2){
					$lowest = $array_of_images[$reference_array[$q][$r]]->getDateNumber();
					$lowestY = $q;
					$lowestX = $r;
					$changedL = TRUE;
				}
				//echo "T1: $true1 & T2: $true2 <br>";
				$true1 = $array_of_images[$reference_array[$q][$r]]->getDateNumber() >= $highest;
				$true2 = $array_of_images[$reference_array[$q][$r]]->getDateNumber() < $current;												
				if($true1 && $true2){
					$highest = $array_of_images[$reference_array[$q][$r]]->getDateNumber();
					$highestY = $q;
					$highestX = $r;
					$changedH = TRUE;
				}
			}
		}
	}
	//positions
	//	-1	0	1
	//	6	-1	2
	//	5	4	-1
	$arrowNo = -1;
	$Ax = 0;
	$Ay=0;
	if($changedL){
		$Ax = $lowestX - $b;
		$Ay = $lowestY - $a;
	}
	if($Ax == 0 && $Ay == -1 && !($type == "Linear")){
		$arrowNo = 0;
	}elseif($Ax == 1 && $Ay == -1 && !($type == "Linear")){
		$arrowNo = 1;
	}elseif($Ax == 1 && $Ay == 0){
		$arrowNo = 2;
	}elseif($Ax == 1 && $Ay == 1 && !($type == "Linear")){
		$arrowNo = -1;
	}elseif($Ax == 0 && $Ay == 1 && !($type == "Linear")){
		$arrowNo = 4; 
	}elseif($Ax == -1 && $Ay == 1 && !($type == "Linear")){
		$arrowNo = 5;
	}elseif($Ax == -1 && $Ay == 0){
		$arrowNo = 6;
	}elseif($Ax == -1 && $Ay == -1 && !($type == "Linear")){
		$arrowNo = -1;//7
	}elseif($Ax == 0 && $Ay == 0 && !($type == "Linear")){
		$arrowNo = -1;
	}
	$Bx = 0;
	$By = 0;
	if($changedH){
		$Bx = $highestX - $b;
		$By = $highestY - $a;
	}
	$arrowNoH = -1;
	if($Bx == 0 && $By == -1 && !($type == "Linear")){//ignores arrows on last image on a row - only on linear layout.
		$arrowNoH = 0;
	}elseif($Bx == 1 && $By == -1 && !($type == "Linear")){
		$arrowNoH = 1;
	}elseif($Bx == 1 && $By == 0){
		$arrowNoH = 2;
	}elseif($Bx == 1 && $By == 1 && !($type == "Linear")){
		$arrowNoH = -1;
	}elseif($Bx == 0 && $By == 1 && !($type == "Linear")){
		$arrowNoH = 4; 
	}elseif($Bx == -1 && $By == 1 && !($type == "Linear")){
		$arrowNoH = 5;
	}elseif($Bx == -1 && $By == 0){
		$arrowNoH = 6;
	}elseif($Bx == -1 && $By == -1 && !($type == "Linear")){
		$arrowNoH = -1;
	}elseif($Bx == 0 && $By == 0 && !($type == "Linear")){
		$arrowNoH = -1;
	}
	if($arrowNo == 0){
		$temp_width = $b*($scale+(($padding*2)))+$scale/2 ;
		$temp_height = $a*($scale+($padding*2))- $padding;
	}elseif($arrowNo == 2){
		$temp_width = $b*($scale+($padding*2))+$scale+($padding*2)- $padding;
		$temp_height = $a*($scale+($padding*2))+$scale/2;
	}elseif($arrowNo == 4){
		$temp_width = $b*($scale+($padding*2))+$scale/2;
		$temp_height = $a*($scale+($padding*2))+$scale+($padding*2)- $padding;
	}elseif($arrowNo == 6){
		$temp_width = $b*($scale+($padding*2))- $padding;
		$temp_height = $a*($scale+($padding*2))+$scale/2;
	}elseif($arrowNo == 1){
		$temp_width = $b*($scale+($padding*2))+$scale+($padding);
		$temp_height = $a*($scale+($padding*2))- $padding;
	}elseif($arrowNo == 5){
		$temp_width = $b*($scale+($padding*2))- $padding;
		$temp_height = $a*($scale+($padding*2))+$scale+$padding;
	}
	$arrowNoHt = $arrowNoH;
	
	if($arrowNoH == 0){
		$arrowNoHt = 4;
		$temp_widthH = $b*($scale+($padding*2))+$scale/2;
		$temp_heightH = $a*($scale+($padding*2))- $padding;
	}elseif($arrowNoH == 2){
		$arrowNoHt = 6;
		$temp_widthH = $b*($scale+($padding*2))+$scale+($padding*2)- $padding;
		$temp_heightH = $a*($scale+($padding*2))+$scale/2;
	}elseif($arrowNoH == 4){
		$arrowNoHt = 0;
		$temp_widthH = $b*($scale+($padding*2))+$scale/2;
		$temp_heightH = $a*($scale+($padding*2))+$scale+($padding*2)- $padding;
	}elseif($arrowNoH == 6){
		$arrowNoHt = 2;
		$temp_widthH = $b*($scale+($padding*2))- $padding;
		$temp_heightH = $a*($scale+($padding*2))+$scale/2;
	}elseif($arrowNoH == 1){
		$arrowNoHt = 5;
		$temp_widthH = $b*($scale+($padding*2))+$scale+($padding);
		$temp_heightH = $a*($scale+($padding*2))- $padding;
	}elseif($arrowNoH == 5){
		$arrowNoHt = 1;
		$temp_widthH = $b*($scale+($padding*2))- $padding;
		$temp_heightH = $a*($scale+($padding*2))+$scale+($padding);
	}
	$temp_width -= ($default_size*$x);
	$temp_height -= ($default_size*$y);
	$temp_widthH -= ($default_size*$x);
	$temp_heightH -= ($default_size*$y);
	$white = imagecolorallocate($output, 255, 255, 255);
	$black = imagecolorallocate($output, 0, 0, 0);
	$grey = imagecolorallocate($output, 60, 60, 60);
	if(!($layout =="Linear" && ($arrowNo ==5||$arrowNoHt ==5))){
		//lower value
		if($arrowNo == 0 ||$arrowNo == 2 ||$arrowNo == 4 ||$arrowNo == 6 || $arrowNo == 1|| $arrowNo == 5){
			
			$temparray = $arrowInner[$arrowNo];
			$temparray2 = $arrow[$arrowNo];
			for($p = 0; $p< count($temparray); $p+=2){
				$temparray[$p] = ($temparray[$p])/1.2 + $temp_width-3;
				$temparray2[$p] = ($temparray2[$p])/1.2 + $temp_width-3;
			}
			for($p = 1; $p< count($temparray); $p+=2){
				$temparray[$p] = ($temparray[$p])/1.2 + $temp_height-3;
				$temparray2[$p] = ($temparray2[$p])/1.2 + $temp_height-3;
			}
			imagefilledpolygon($output, $temparray2, 3, $grey);
			imagefilledpolygon($output, $temparray, 3, $white);
		}
		//higher value
		if( $arrowNoHt == 0 ||$arrowNoHt == 2 ||$arrowNoHt == 4 ||$arrowNoHt == 6 || $arrowNoHt == 1|| $arrowNoHt == 5){
			$temparray = $arrowInner[$arrowNoHt];
			$temparray2 = $arrow[$arrowNoHt];
			for($p = 0; $p< count($temparray); $p+=2){
				$temparray[$p] = ($temparray[$p])/1.2 + $temp_widthH-3;
				$temparray2[$p] = ($temparray2[$p])/1.2 + $temp_widthH-3;
			}
			for($p = 1; $p< count($temparray); $p+=2){
				$temparray[$p] = ($temparray[$p])/1.2+ $temp_heightH-3;
				$temparray2[$p] = ($temparray2[$p])/1.2+ $temp_heightH-3;
			}
			//
			imagefilledpolygon($output, $temparray2, 3, $grey);
			imagefilledpolygon($output, $temparray, 3, $white);
		}
	}
?>