<?php
	//http://pastebin.com/YSjX7HCq
	function rgb2hsl($rgb,$g=null,$b=null){
       
        if (is_array($rgb) && sizeof($rgb) == 3) list($r, $g, $b) = $rgb;
        else $r=$rgb;
       
        $clrMin = min($r, $g, $b);
        $clrMax = max($r, $g, $b);
        $deltaMax = $clrMax - $clrMin;
               
        $L = ($clrMax + $clrMin) / 510;
               
        if (0 == $deltaMax){
                $H = 0;
                $S = 0;
        }
        else{
                if (0.5 > $L){
                        $S = $deltaMax / ($clrMax + $clrMin);
                }
                else{
                        $S = $deltaMax / (510 - $clrMax - $clrMin);
                }
 
                if ($clrMax == $r) {
                        $H = ($g - $b) / (6.0 * $deltaMax);
                }
                else if ($clrMax == $g) {
                        $H = 1/3 + ($b - $r) / (6.0 * $deltaMax);
                }
                else {
                        $H = 2 / 3 + ($r - $g) / (6.0 * $deltaMax);
                }
 
                if (0 > $H) $H += 1;
                if (1 < $H) $H -= 1;
        }
       
        $H=round($H*360);              
        return array($H, $S,$L);
	}
?>