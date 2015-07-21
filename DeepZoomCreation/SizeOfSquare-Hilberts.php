<?php
	//hilbert square
	$double = 1;
	$bool = TRUE;
	while ($bool){
		$double = $double *2;
		if (count($results)>$double*$double && count($results)<=$double*$double*4){
			$bool = FALSE;
		}	
	}
	$max_size_of_square=$double*2;
?>