<?php
	//http://pastebin.com/YSjX7HCq
	function hue($h, $s, $l) {

			if ($s == 0) {
					$r = $g = $b = round($l * 255);
			}
			else {
					if ($l <= 0.5) {
							$m2 = $l * ($s + 1);
					}
					else
					{
							$m2 = $l + $s - $l * $s;
					}
					$m1 = $l * 2 - $m2;
					$hue = $h / 360;
				   
					$r = hsl2rgb_hue2rgb($m1, $m2, $hue + 1/3);
					$g = hsl2rgb_hue2rgb($m1, $m2, $hue);
					$b = hsl2rgb_hue2rgb($m1, $m2, $hue - 1/3);
			}
			return array($r, $g, $b);
	}
	 
	function hsl2rgb_hue2rgb($m1, $m2, $hue) {
			if ($hue < 0) $hue += 1;
			else if ($hue > 1) $hue -= 1;
	 
			if (6 * $hue < 1)
			$v = $m1 + ($m2 - $m1) * $hue * 6;
			else if (2 * $hue < 1)
			$v = $m2;
			else if (3 * $hue < 2)
			$v = $m1 + ($m2 - $m1) * (2/3 - $hue) * 6;
			else
			$v = $m1;
	 
			return round(255 * $v);
	}
?>