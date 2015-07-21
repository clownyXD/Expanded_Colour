<?php
	class Images
	{
		public $image_number = 0; 	// number in sequence of image - unnecessary 
		public $path = ""; 			// image name
		public $x_position = 0;		// position in overall sequence where the image will be placed
		public $y_position = 0;		// position in overall sequence where the image will be placed - look at: http://co-project.lboro.ac.uk/cocbl/Research/UploadAndCrop/image_generator.php?image_name=Stitched/Test.jpg
		public $real_x_position = 0;
		public $real_y_position = 0;
		public $size_image = 0;		// size of image - for references later
		public $image_date_number = 0;
		public $year = 0;
		public $month = 0;
		public $day = 0;
		public $hue = 0;
		public $grey = 0;
		private $fully_stored = TRUE; 	// whether an image has been stored in XML 
		public function construct($image_no, $image_path, $x, $y, $size, $padding, $number, $yr, $m, $d, $h, $g) {	// constructor
			$this->image_number = $image_no;
			$this->path = $image_path;
			$this->x_position = $x;
			$this->y_position = $y;
			$this->size_image = $size;
			$this->real_x_position = $x*($size+($padding*2))+$padding;	//as a square the x and y co-ordiantes are the same.
			$this->real_y_position = $y*($size+($padding*2))+$padding;	//as a square the x and y co-ordiantes are the same.
			$this->image_date_number = $number;	
			$this->year = $yr;	
			$this->month = $m;	
			$this->day = $d;	
			$this->hue = intval($h);
			$this->grey = intval($g);
			//$this->hueMed = $hM;
		}
		public function getImageNumber(){
			return $this->image_number ;
			//return $image_number;
		}
		public function getPath(){
			return $this->path;
		}		
		public function get_x_start(){
			return $this->real_x_position;
		}
		public function get_y_start(){
			return $this->real_y_position;
		}
		public function get_x_end(){
			return $this->real_x_position+$this->size_image;
		}
		public function get_y_end(){
			return $this->real_y_position+$this->size_image;
		}
		public function getxPosition(){
			return $this->x_position;
		}		
		public function getyPosition(){
			return $this->y_position;
		}
		public function getSizeImage(){
			return $this->size_image;
		}
		public function getDateNumber(){
			return $this->image_date_number;
		}		
		public function getFullyStored(){
			$temp = $this->fully_stored;
			if($this->fully_stored){
				$this->fully_stored = FALSE;
			}
			return $temp;
		}
		public function getYear(){
			return $this->year;
		}	
		public function getMonth(){
			return $this->month;
		}	
		public function getDay(){
			return $this->day;
		}	
		public function getHue(){
			return $this->hue;
		}	
		public function getGrey(){
			return $this->grey;
		}
	}
?>