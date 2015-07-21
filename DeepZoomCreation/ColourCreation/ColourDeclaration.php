<?php
/////////////////////YEARS///////////////////////////
	//colouring system
	//6 main iterations:
	/*
		$r = 255 	$g = 0		$b = 0
		$r = 255 	$g = 255	$b = 0
		$r = 0	 	$g = 255	$b = 0
		$r = 0	 	$g = 255	$b = 255
		$r = 0	 	$g = 0		$b = 255
		$r = 255	$g = 0		$b = 255
	*/
	$range_years = date("Y") - $smallest_year +1; //range of years		- for colouring
	//echo "Current = ". date("Y").", Smallest = $smallest_year, Range = $range_years . <br>";
	$Yeararrayofcolours = array();

	//	divide range by 6,
	//		as 6 modes (255,0,0) (255,255,0) (0,255,0) (0,255,255) (0,0,255) (255,0,255)
	//	
	if($range_years <4){ //defaults colours
		$Yeararrayofcolours[] = array(255,0,0);
		$Yeararrayofcolours[] = array(0,255,0);
		if($range_years==3){
			$Yeararrayofcolours[] = array(0,0,255);
		}
	}else{
		$rngDiv = $range_years/6;	//to calculate the amount of times to loop.
		$colDiv = 0.5*255/($rngDiv);		//to add or take away.	
		$rngDiv = round($rngDiv);
		
		$R=0;$B=0;$G=0;
		for($q = 0; $q < $range_years; $q++){
			$p = $q *0.5;
			if($p < $rngDiv){
				$R += $colDiv;
			}elseif($p < 2*$rngDiv){
				$G += $colDiv;
			}elseif($p < 3*$rngDiv){
				$R -= $colDiv;
			}elseif($p < 4*$rngDiv){
				$B += $colDiv;
			}elseif($p < 5*$rngDiv){
				$G -= $colDiv;
			}elseif($p < 6*$rngDiv){
				$R += $colDiv;
			}elseif($p < 7*$rngDiv){
				$G += $colDiv;
			}
			$Yeararrayofcolours[] = array(round($R),round($G),round($B));
			
		}
	}
	//echo var_dump($Yeararrayofcolours);
	$Yeararrayofcolours = array_reverse($Yeararrayofcolours);
/////////////////////MONTHS///////////////////////////
	$Montharrayofcolours = array(
								array(255,0,0),
								array(255,125,0),
								array(255,255,0),
								array(125,255,0),
								array(0,255,0),
								array(0,255,125),
								array(0,255,255),
								array(0,125,255),
								array(0,0,255),
								array(125,0,255),
								array(255,0,255),
								array(255,0,125)
								);
	
/////////////////////WEEKS///////////////////////////
	$Daysarrayofcolours = array(
								array(255,0,0),
								array(255,43,0),
								array(255,85,0),
								array(255,128,0),
								array(255,170,0),
								array(255,213,0),
								array(255,255,0),
								array(213,255,0),
								array(170,255,0),
								array(128,255,0),
								array(85,255,0),
								array(43,255,0),
								array(0,255,0),
								array(0,255,43),
								array(0,255,85),
								array(0,255,128),
								array(0,255,170),
								array(0,255,213),
								array(0,255,255),
								array(0,213,255),
								array(0,170,255),
								array(0,128,255),
								array(0,85,255),
								array(0,43,255),
								array(0,0,255),
								array(43,0,255),
								array(85,0,255),
								array(128,0,255),
								array(170,0,255),
								array(213,0,255),
								array(255,0,213),
								array(255,0,170)
								);
	
?>