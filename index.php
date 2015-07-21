<!DOCTYPE html>
<html>
	<head>
		<title>Personal Image Collection</title>
	</head>
	<link rel="stylesheet" type="text/css" href="Style.css">
	<body>
		
		<div>
			<div id="buttondiv" class="headers" style="-webkit-border-top-left-radius: 15px;-moz-border-radius-topleft: 15px;">
				
				<?php 
					
					if (isset($_GET['type'])) {
						$type = $_GET['type'];
					}else{
						$type="Time";
					}
					if (isset($_GET['layout'])) {
						$layout = $_GET['layout'];
					}else{
						$layout="Linear";
					}
					if (isset($_GET['user'])) {
						$user = $_GET['user'];
					}else{
						$user=1;
					}
					if (isset($_GET['selectedImage'])) {
						$selectedImage = $_GET['selectedImage'];
					}else{
						$selectedImage=-1;
					}
					include "XML-Handlers/Load_imageinfo2.php";
					echo "<script>console.log(".$image_info.")</script>";
					echo"<input type=\"hidden\" id= \"hiddentype\" name=\"type\" value=\"$type\">";
					echo"<input type=\"hidden\" id= \"hiddenlayout\" name=\"type\" value=\"$layout\">";
					echo"<input type=\"hidden\" id= \"hiddenuser\" name=\"type\" value=\"$user\">";
					$types = array("Time","Colour");
					$layouts = array("Hilberts","Linear","ZigZag");
					//top level buttons
					for($i = 0; $i <2; $i++){
						echo"<form  action=\"http://localhost/Research/?\" >";
						echo"<input type=\"hidden\" name=\"type\" value=\"".$types[$i]."\">";
						echo"<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
						echo"<input type=\"hidden\" name=\"user\" value=\"$user\">";
						echo"<input type=\"hidden\" name=\"selectedImage\" id=\"selectedImage_".$i."\" value=\"$selectedImage\">";
						if($type != $types[$i]){
							echo"<input id=\"".$types[$i]."_Button\" type=\"submit\" value=\"".$types[$i]."\">";
						}else{
							echo"<input id=\"".$types[$i]."_Button\" type=\"submit\" value=\"".$types[$i]."\" disabled>";
						}
						echo"</form>";
					}
					include "XML-Handlers/UploadStatus_load-XML.php";
					$typeNo = 0;
					if ($type != "Time"){$typeNo = 1;}
					for($i = 0; $i < 3; $i++){
						if($vals[$typeNo][$i] ==  1){
							echo"<form  action=\"http://localhost/Research/?\" >";
							echo"<input type=\"hidden\" name=\"type\" value=\"".$types[$typeNo]."\">";
							echo"<input type=\"hidden\" name=\"layout\" value=\"".$layouts[$i]."\">";
							echo"<input type=\"hidden\" name=\"user\" value=\"$user\">";
							echo"<input type=\"hidden\" name=\"selectedImage\" id=\"selectedImage_".($i+2)."\" value=\"$selectedImage\">";
							if($layout != $layouts[$i]){
								echo"<input id=\"".$layouts[$i]."_Button\" type=\"submit\" value=\"".$layouts[$i]."\">";
							}else{
								echo"<input id=\"".$layouts[$i]."_Button\" type=\"submit\" value=\"".$layouts[$i]."\" disabled>";
							}
							echo"</form>";
						}else{
							echo"<form  action=\"http://localhost/Research/DeepZoomCreation/Stitch.php?\">";
							echo"<input type=\"hidden\" name=\"type\" value=\"".$types[$typeNo]."\">";
							echo"<input type=\"hidden\" name=\"layout\" value=\"".$layouts[$i]."\">";
							echo"<input type=\"hidden\" name=\"user\" value=\"$user\">";
							echo"<input id=\"".$layouts[$i]."_Button\" type=\"submit\" value=\"Stitch Images - ".$layouts[$i]."\">";
							echo"</form>";
						}
					}
					
				?>					
				<!--<form  action="http://co-project.lboro.ac.uk/cocbl/Research/UploadAndCrop/getXML.php" width = "200px">
					<input type="submit" value="XML file" >
				</form>-->
				<div id = "stripDiv"></div>
			</div>
		</div>
		<div>
			<div style="float:left;width:800px; height:800px;" id="divcanvas">
				<canvas id="zoomCanvas" style="cursor:move;"  width="800px" height="800px" oncontextmenu="return false;">You need a browser with the HTML5 Canvas ability.</canvas><br/>
			</div>
			
			
			
			<div id="divinfo" class="divinfo">
				<div style='height:30px; width:inherit; overflow:hidden;' id='TitleDiv'> 
				</div>
				<div id='imagediv'>
					<img id="image" src="camera-icon.png">
				</div>
				<!--<div id='EnlargeDiv' onclick="openWindow()" >Enlarge</div> -->
				<div id="infoDIV" style="position:absolute;">
					<div class='info' id='TimeDiv'> Time Taken: 
					</div>
					<div class='info' id='DateDiv'>Date Taken: 
					</div>
					<div class='info' id='MakeDiv'>Make: 
					</div>
					<div class='info' id='ModelDiv'>Model: 
					</div>
					</div>
				</div>
			</div>
			<div id="canvasRoot" style="background: rgb(120, 120, 120); position:absolute">
				<canvas id="rootCanvas" style="background: rgb(120, 120, 120);"  width="800px" height="800px" oncontextmenu="return false;">You need a browser with the HTML5 Canvas ability.</canvas><br/>
				<?php
					if($type == "Time"){
						echo '<div id="colourdivRoot" class="headers" style="height:50px!important; background-color:transparent; color">';
						echo 'Colour Representation';
						echo '</div>';
					}
				?>
			</div>
			<div id="canvasLast" style="background: rgb(140, 140, 140); position:absolute">
				<canvas id="lastCanvas" style="background: rgb(140, 140, 140);"  width="800px" height="800px" oncontextmenu="return false;">You need a browser with the HTML5 Canvas ability.</canvas><br/>
				<?php
					if($type == "Time"){
						echo '<div id="colourdivLast" class="headers" style="height:50px!important; background-color:transparent;">';
						echo 'Colour Representation';
						echo '</div>';
					}
				?>
			</div>
			<div id="canvasNext" style="background: rgb(80, 80, 80); position:absolute">
				<canvas id="nextCanvas" style="background: rgb(80, 80, 80);"  width="800px" height="800px" oncontextmenu="return false;">You need a browser with the HTML5 Canvas ability.</canvas><br/>
				<?php
					if($type == "Time"){
						echo '<div id="colourdivNext" class="headers" style="height:50px!important; background-color:transparent;">';
						echo 'Colour Representation';
						echo '</div>';
					}
				?>
			</div>
		</div>
		
		<?php
			if($type == "Time"){
				echo '<div id="colourdiv" class="headers" style="height:50px!important; background-color:rgb(75, 75, 75);">';
				echo 'Colour Representation';
				echo '</div>';
			}
		?>
	</body>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="DeepZoomView/imageloader.js"></script>
	<script type="text/javascript" src="DeepZoomView/canvaszoom.js"></script>
	<script type="text/javascript" src="DeepZoomView/RootCanvas.js"></script>
	<script type="text/javascript" src="DeepZoomView/LastView.js"></script>
	<script type="text/javascript" src="DeepZoomView/NextView.js"></script>
	<script type="text/javascript">
		
		var hsize= window.innerHeight - $("#colourdiv").height() - 16;
		var hsize2= window.innerHeight - $("#buttondiv").height() - 16;
		var wsize= window.innerWidth-hsize-18;
		var minihsize = hsize2/2;
		var miniwsize = wsize/2;
		var minicanvas = minihsize - $("#colourdivNext").height();
		var imageheight = ((hsize/2)*0.65)
		var imagewidth = (wsize/2);
		$("#canvasRoot").css("width",miniwsize).css("height",minihsize).css("left",8).css("top",8+$("#buttondiv").height());
		$("#canvasLast").css("width",miniwsize).css("height",minihsize).css("left",8+miniwsize).css("top",8+$("#buttondiv").height());
		$("#canvasNext").css("width",miniwsize).css("height",minihsize).css("left",8).css("top",8+minihsize+$("#buttondiv").height());
		$("#divinfo").css("width",miniwsize).css("height",minihsize).css("left",8+miniwsize).css("top",8+minihsize+$("#buttondiv").height());
		//$("#EnlargeDiv").css("top",imageheight+28);
		$("#infoDIV").css("top",imageheight+48)
		$("#zoomCanvas").attr("width",hsize).attr("height",hsize).attr("left",8+wsize).attr("top",8).css("width",hsize).css("height",hsize).css("left",8+wsize).css("top",8);
		$("#rootCanvas").attr("width",miniwsize).attr("height",minicanvas).css("width",miniwsize).css("height",(minicanvas)).attr("left",0).css("left",0);;
		$("#lastCanvas").attr("width",miniwsize-1).attr("height",minicanvas).css("width",miniwsize-1).css("height",minicanvas).attr("left",0).css("left",0);;
		$("#nextCanvas").attr("width",miniwsize).attr("height",minicanvas).css("width",miniwsize).css("height",minicanvas).attr("left",0).css("left",0);;
		$("#buttondiv").css("width",wsize).css("left",8);
		$("#imagediv").css("height",imageheight);
		$("#image").css("max-height",imageheight-30).css("max-width",imagewidth-20).css("margin-top",10);
		if(document.getElementById("hiddentype").value == "Time"){
			$("#colourdiv").css("width",hsize).css("left",8+wsize).css("top", hsize +8);
			$("#colourdivRoot").css("width",miniwsize).css("top", $("#rootCanvas").height());
			$("#colourdivLast").css("width",miniwsize).css("top", $("#rootCanvas").height());
			$("#colourdivNext").css("width",miniwsize).css("top", $("#rootCanvas").height());
		}
		var cavash =  $("#zoomCanvas").height();
		var cavasw =  $("#zoomCanvas").width();
		
		var image_canvas = new CanvasZoom( {
			'canvas' : document.getElementById('zoomCanvas'),
			'tilesFolder' : document.getElementById("hiddenuser").value+"/Stitched",
			'imageWidth' : 0,
			'imageHeight' : 0,
			'defaultZoom' : 0,
			'minZoom' : 0,
			'maxZoom' : 40,
			'type'	:	document.getElementById("hiddentype").value+"/"+document.getElementById("hiddenlayout").value,
			'user'	:	document.getElementById("hiddenuser").value
		} );
		var Root_canvas = new CanvasRoot ({
			'canvas' : document.getElementById('rootCanvas'),
			'tilesFolder' : document.getElementById("hiddenuser").value+"/Stitched",
			'imageWidth' : 0,
			'imageHeight' : 0,
			'defaultZoom' : 0,
			'minZoom' : 0,
			'maxZoom' : 40,
			'type'	:	document.getElementById("hiddentype").value+"/"+document.getElementById("hiddenlayout").value,
			'user'	:	document.getElementById("hiddenuser").value
		} );
		var Last_canvas = new CanvasLast ({
			'canvas' : document.getElementById('lastCanvas'),
			'tilesFolder' : document.getElementById("hiddenuser").value+"/Stitched",
			'imageWidth' : 0,
			'imageHeight' : 0,
			'defaultZoom' : 0,
			'minZoom' : 0,
			'maxZoom' : 40,
			'type'	:	document.getElementById("hiddentype").value+"/"+document.getElementById("hiddenlayout").value,
			'user'	:	document.getElementById("hiddenuser").value
		} );
		var Next_canvas = new CanvasNext ({
			'canvas' : document.getElementById('nextCanvas'),
			'tilesFolder' : document.getElementById("hiddenuser").value+"/Stitched",
			'imageWidth' : 0,
			'imageHeight' : 0,
			'defaultZoom' : 0,
			'minZoom' : 0,
			'maxZoom' : 40,
			'type'	:	document.getElementById("hiddentype").value+"/"+document.getElementById("hiddenlayout").value,
			'user'	:	document.getElementById("hiddenuser").value
		} );
		var info = image_canvas.getImageInfo;
		var name = document.getElementById("selectedImage_2").value;
		var counter = -1;
		for(var i = 0; i < info.length; i++){ //find position
			if (info[i].name+"."+info[i].extension == name){
				counter  =i;
			}
		}
		
		function openWindow(){
			window.open("HTMLDisplayImage.php?image_name=Source/"+$('#TitleDiv').html());
		}
		
		checkCanvasExistsRoot();
		checkCanvasExistsNext();
		checkCanvasExistsLast();
		
		
		function checkCanvasExistsRoot() {
			if(Root_canvas.getCtx() === null) {
				setTimeout(function() { checkCanvasExistsRoot() }, 50);
			} else {
				// _ctx exists		
				Root_canvas.moveNext();
			}
		}
		function checkCanvasExistsLast() {
			if(Last_canvas.getCtx() === null) {
				setTimeout(function() { checkCanvasExistsLast() }, 50);
			} else {
				// _ctx exists
				Last_canvas.moveLast();			
			}
		}
		function checkCanvasExistsNext() {
			if(Next_canvas.getCtx() === null) {
				setTimeout(function() { checkCanvasExistsNext() }, 50);
			} else {
				// _ctx exists
				Next_canvas.selectedimage(counter);
			}
		}
		
	</script>
</html>