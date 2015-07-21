<?php
/***************************************************************
*   hilbert curve
*   Version 0.3
*   Copyright (c) 2010-2013 Chi Hoang 
*   All rights reserved
*
*   Permission is hereby granted, free of charge, to any person obtaining a
*	copy of this software and associated documentation files (the "Software"),
*	to deal in the Software without restriction, including without limitation
*	the rights to use, copy, modify, merge, publish, distribute, sublicense,
*	and/or sell copies of the Software, and to permit persons to whom the
*	Software is furnished to do so, subject to the following conditions:
*
*   Free for non-commercial use
*
*	The above copyright notice and this permission notice shall be included
*	in all copies or substantial portions of the Software.
*	
*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
*	OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
*	THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
*	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
*	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
*	DEALINGS IN THE SOFTWARE.
*
***************************************************************/
/**************************************************************
*  hilbert_map_1  =  N
*  hilbert_map_9  =  S
*  hilbert_map_3  = W
*  hilbert_map_7  =  E
***************************************************************/

class hilbert {
	public $amount = 0;
	var $hilbert_map_1 = array (	'a' => array (
										"0, 0" => array (0, 'd'),
										"0, 1" => array (1, 'a'), 
										"1, 0" => array (3, 'b'),
										"1, 1" => array (2, 'a')
								), 
								 'b' => array ( 
										 "0, 0" => array (2, 'b'), 
										 "0, 1" => array (1, 'b'), 
										 "1, 0" => array (3, 'a'),
										 "1, 1" => array (0, 'c')
								), 
								'c' => array ( 
										"0, 0" => array (2, 'c'),
										"0, 1" => array (3, 'd'),
										"1, 0" => array (1, 'c'),
										"1, 1" => array (0, 'b')
									), 
								'd' => array (
										"0, 0" => array (0, 'a'), 
										"0, 1" => array (3, 'c'), 
										"1, 0" => array (1, 'd'), 
										"1, 1" => array (2, 'd')
								),
							);
							
	public function set_amount($amount_no){
		$this ->amount = $amount_no;
	}
	function point_to_hilbert($x, $y, $order=16, $map="hilbert_map_1", $mode = "hilbert")
	{		
		$current_square = 'a' ;
		$position = 0; 
		foreach (range($order-1, 0, -1) as $i)
		{ 
			$position <<= 2; 
			$quad_x = $x & (1 << $i) ? 1 : 0;
			$quad_y = $y & (1 << $i) ? 1 : 0;
			list($quad_position, $current_square) = $this->{$map}[$current_square]["$quad_x, $quad_y"];
			$position |= $quad_position;
		}
		return $position;
	}
		
	function test_pth() {
		$array = array();
		foreach (range($this ->amount -1,0,-1) as $x)
		{
			foreach (range($this ->amount -1,0,-1) as $y)
			{
				$sort[] = $points["$x,$y"] = $this->point_to_hilbert($x, $y, log($this ->amount, 2));
			}
		}
		array_multisort($points, $sort);
		$sort = array();
		$counter = 0;
		foreach ($points as $k => $v)
		{
			$counter ++;
			list($x,$y) = explode(',',$k);
			//echo $counter . ": (" . $x . "," . $y . ")<br>";
			$sort[] = array( $x , $y );
			$temp_array = array( $x,  $y, $counter);
			array_push($array, $temp_array);
		}
		//sort($sort_x);
		//sort($sort_y);
		//array_multisort($sort['y'],SORT_ASC, $array);
		//sort($temp_array);
		array_multisort($sort,SORT_ASC, $array);
		//$temp = json_encode($array);
		//echo "<script>console.log($temp)</script>";
		//$temp = json_encode($sort);
		//echo "<script>console.log($temp)</script>";
		$rows = array();
		//slow and currently not efficient. 
		foreach (range(0,$this ->amount-1,1) as $row)
		{
			$temp = array();
			for ($entry = 0; $entry < count($array); $entry++)
			{
				if($array[$entry][0]==$row){
					array_push($temp, $array[$entry][2]);
				}
			}
			array_push($rows, $temp);
		}
		//$temp = json_encode($rows);
		//echo "<script>console.log($temp)</script>";
		return $rows;
	}
	
}
?>