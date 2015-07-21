<?php
	$trueVal = (($currentFullWidth < ($array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_x_end() + ($padding*2)))&&($currentFullWidth > $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_x_end()));
	$trueValH = (($currentFullHeight < ($array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_end() + ($padding*2)))&&($currentFullHeight > $array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_end()));
	if ($A && $B && $C && $D){
		//echo "All ";
		if($trueVal && $trueValH){
			$count_ref_y = $temp_ref_y;
			$count_ref_x++;
			$while_boolean = FALSE;
			$counter++;
		}elseif((!$end_height&&$end_width)){
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}elseif(($end_height&&$end_width)){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}elseif(($end_height&&!$end_width)){
			//add one to x value
			$count_ref_x ++;
		
		}elseif(!$trueVal && $trueValH){
			$count_ref_x ++;
		}elseif($trueVal && !$trueValH){
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		
		}else{
			//add one to x value
			$count_ref_x ++;
		}
	$Add_Image = TRUE;
	//if AB are inside, then the image is not inside but is vertically on the same lines
	}elseif($A && $B && !$C && !$D){
		//echo "Vertically ";
		// use known sides to move to next tile
		if($Ds < $Gs){
			//add one to y
			$count_ref_y ++;
		}else{
			$while_boolean = FALSE;
			$counter++;
		}
	//if CD are inside, then the image is not inside but is horizontally on the same lin.es
	}elseif(!$A && !$B && $C && $D){
		//echo "Horizontally ";
		// use known sides to move to next tile
		if($Bs < $Es){
			//add on to x value
			$count_ref_x ++;
		}else{
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}
	//if AC/BC/AD/BD are in, then a corner of the image is inside
	}elseif($A && !$B && $C && !$D){
		//echo "AC ";
		//at the bottom - end of cropping zone
		//end sequence
		$while_boolean = FALSE;
		$counter++;
		$Add_Image = TRUE;
	}elseif(!$A && $B && $C && !$D){
		//echo "BC ";
		//at the bottom - start of cropping zone
		if($end_width){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}else{
			//add one to x value
			$count_ref_x ++;
		}
		$Add_Image = TRUE;
	}elseif($A && !$B && !$C && $D){
		//echo "<br>AD<br>";
		//at the top - end of cropping zone
		
		//echo "current height: $currentFullHeight";
		//echo "<br> noPadding: ".$array_of_images[$reference_array[$count_ref_y][$count_ref_x]]->get_y_end()."<br>";
		//echo  "trueVal : $trueVal <br>";
		if($trueValH){//NED TO FIX HERE.... IMAGE IN - PADDING PARTIALLY IN - Courses isses.
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}elseif($trueVal){
			//echo "<br>Made it....";
			$while_boolean = FALSE;
			$counter++;
		}else{
			//add one to y value
			//take x back to appropriate place
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}
		$Add_Image = TRUE;
	}elseif(!$A && $B && !$C && $D){
		//echo "BD ";
		//at the top - start of cropping zone
		if(($end_height&&$end_width)){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}elseif(($trueVal && $trueValH)){
			//add one to x value
			$while_boolean = FALSE;
			$counter++;
			if(!$end_width){
				$count_ref_x++;
			}
		}elseif(($trueVal && !$trueValH)){
			//add one to x value
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}elseif(($end_height&&!$end_width)){
			//add one to x value
			$count_ref_x ++;
		}elseif((!$end_height&&$end_width)){
			//add one to y value
			$count_ref_y ++;
		/*}elseif($trueVal){
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;*/
		}else{
			//add one to x value
			$count_ref_x ++;
		}
		$Add_Image = TRUE;
	//if ABC/ABD/ACD/BCD are in then part of the image is inside
	}elseif($A && $B && $C && !$D){
		//echo "ABC ";
		//at the bottom of cropping zone - but in the middle.
		if($end_width){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}else{
			//add one to x value
			$count_ref_x ++;
		}
		$Add_Image = TRUE;
	}elseif($A && $B && !$C && $D){
		//echo "ABD ";
		//at the top of cropping zone - but in the middle.
		if(($trueValH&&$trueVal)){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}elseif(($trueValH&&!$trueVal)){
			//add one to x value
			$count_ref_x ++;
		}elseif((!$trueValH&&$trueVal)){
			//add one to y value
			//take x back to appropriate place
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}else{
			//add one to x value
			$count_ref_x ++;
		}
		$Add_Image = TRUE;
	}elseif($A && !$B && $C && $D){
		//echo "ACD ";
		//at the end of cropping zone - floating in the middle.
		if($end_height){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}elseif ($trueValH){
			$count_ref_y = $temp_ref_y;
			$while_boolean = FALSE;
			$counter++;
		}else{
			//add one to y value
			//take x back to appropriate place
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}
		
		
		
		
		
		$Add_Image = TRUE;
	}elseif(!$A && $B && $C && $D){
		//echo "BCD ";
		//at the start of cropping zone - floating in the middle.
		if(($trueValH&&$trueVal)){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
		}elseif(($trueValH&&!$trueVal)){
			//add one to x value
			$count_ref_x ++;
		}elseif((!$trueValH&&$trueVal)){
			//add one to y value
			//take x to appropriate place
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		}else{
			//add one to x value
			$count_ref_x ++;
		}
		$Add_Image = TRUE;

	//if singular line is inside - and nothing else
	}elseif($A && !$B && !$C && !$D){
		//echo "A ";
		//use to judge where to next move to
		if($Ds <= $Gs){
			//add one to y
			$count_ref_y ++;
		//if image is bigger than crop plane
		}elseif($Cs <= $Gs && $Ds>=$Hs){
			//end sequence
			
			$while_boolean = FALSE;
			$counter++;
			$Add_Image = TRUE;
		}
		else{
			$Add_Image = TRUE;
			$counter++;
			$while_boolean = FALSE;
		}
	}elseif(!$A && $B && !$C && !$D){
		//echo "B ";
		//use to judge where to next move to
		if($Ds <= $Gs){
			//add one to y
			$count_ref_y ++;
		//if image is bigger than crop plane
		}else{
			if($Cs <= $Gs && $Ds>=$Hs &&!$end_width){
				$count_ref_x++;
				$counter++;
				$Add_Image = TRUE;
			}else{
				$while_boolean = FALSE;
				$counter++;
				$Add_Image = TRUE;
			}
		}
	}elseif(!$A && !$B && $C && !$D){
		//echo "C ";
		//use to judge where to next move to
		if($Bs <= $Es){
			//add on to x value
			$count_ref_x ++;
		//if image is bigger than crop plane
		}else{
			//end sequence
			$while_boolean = FALSE;
			$counter++;
			$Add_Image = TRUE;
		}
	}elseif(!$A && !$B && !$C && $D){
		//echo "D ";
		//use to judge where to next move to
		if(($Bs <= $Es)&&!$end_width){
			//add on to x value
			$count_ref_x ++;
		}elseif(($As >= $Fs)&&!$end_height){
			$count_ref_y ++;
			$count_ref_x = $temp_ref_x;
		//if image is bigger than crop plane
		}elseif(($Bs >= $Fs)&&($As <= $Es)&&!$end_height){
			$count_ref_y ++;
			$counter++;
			$Add_Image = TRUE;
		}elseif(($Bs >= $Fs)&&($As <= $Es)){
			$while_boolean = FALSE;
			$counter++;
			$Add_Image = TRUE;
		}else{
			//end sequence
			$while_boolean = FALSE;
			$counter++;
			$Add_Image = TRUE;
		}
	//if all are outside
	}elseif(!$A && !$B && !$C && !$D){
		//echo "None ";
		//if image is bigger than plane
		if ($As <= $Es && $Bs>=$Fs && $Cs <= $Gs && $Ds>=$Hs){
			//end sequence
			$while_boolean = FALSE;
			$counter++;
			$Add_Image = TRUE;
		}elseif(!($As <= $Es) && !($Bs>=$Fs) && !($Cs <= $Gs) && !($Ds>=$Hs)){
			if($Es - $As >= $Gs - $Cs){
				$count_ref_x ++;
			}else{
				$count_ref_y ++;
			}
		}else{
			$while_boolean = FALSE;
			//$counter++;
			//$Add_Image = TRUE;
		}
	}else{
		//echo "clause not met -not";
	}
?>