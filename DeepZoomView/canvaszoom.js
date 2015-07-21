/*
The MIT License

Copyright (c) 2012 Matthew Wilcoxson (www.akademy.co.uk)
Copyright (c) 2011 Peter Tribble (www.petertribble.co.uk) - Touch / gesture controls.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
/*
CanvasZoom
By Matthew Wilcoxson

Description:    Zooming of very large images with Javascript, HTML5 and the canvas element (based on DeepZoom format and now the Zoomify format).
Website:        http://www.akademy.co.uk/software/canvaszoom/canvaszoom.php
Like it?:       http://www.akademy.co.uk/me/donate.php
Version:        1.1.4
*/
/* global ImageLoader, window */
var _zoomLevel = -1;
var horizontal_Level = 0;
var months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
var mmonths = ['J','F','M','A','M','J','J','A','S','O','N','D'];
var start_year = 0;
var square = [0,0,0,0];
var squareLocked = [0,0,0,0];
var lockedImage = "";
var tmplockedImage = "";
var permlock = "";
var selectedImage_ini = -1;
var tilesurrounds = new Array();
var tileList = new Array();
function CanvasZoom( _settings, _tilesFolderDeprecated, _imageWidthDeprecated, _imageHeightDeprecated ) {
	var changeImage = false;
	var boolval = false;
	var currentImage = -1;
	var prehorizontal =0;
	var colourLevels = [];
	var colourYears = [];
	var colourMonths= [];
	var colourWeeks = [];
	"use strict";
	var width_array = []; //for custom widths
	var zoom_levels = []; //for custom zoom levels
	var images = []; //contains many images 
	var imageInfo = []; //contains info on images
	//var t = this; // make "this" accessible when out of "this" scope and minify
	var NULL=null, UNDEFINED, FALSE=false, TRUE=true, MATH=Math; // To minify
	//to get intial settings

	var _debug = FALSE,
		_debugShowRectangle = FALSE; // Paint a rectangle rather than an image, adjust as needed!

	var _tileOverlap = 0, 
			_tileSize = 256,
			_fileType = "jpg",
			_tilesSystem = "deepzoom", // or "zoomify"
			_canvas,_drawBorder,_defaultZoom, _minZoom, _maxZoom, _tilesFolder, _imageWidth, _imageHeight, _type,_user;
			
	if( _settings.getContext === UNDEFINED ) {

		// settings
		_type = (_settings.type === UNDEFINED) ? _type : _settings.type;
		_canvas = _settings.canvas;
		
		_imageWidth = _settings.imageWidth;
		_imageHeight = _settings.imageHeight;
		
		_drawBorder = (_settings.drawBorder === UNDEFINED) ? TRUE : _settings.drawBorder;

		_defaultZoom = _settings.defaultZoom;//(_settings.defaultZoom === UNDEFINED) ? UNDEFINED : _settings.defaultZoom;
		_minZoom = _settings.minZoom;//(_settings.minZoom === UNDEFINED) ? UNDEFINED : _settings.minZoom;
		_maxZoom = _settings.maxZoom;//(_settings.maxZoom === UNDEFINED) ? UNDEFINED : _settings.maxZoom;
		
		_tilesSystem = (_settings.tilesSystem === UNDEFINED) ? _tilesSystem : _settings.tilesSystem;
		_tileOverlap = (_settings.tileOverlap === UNDEFINED) ? _tileOverlap : _settings.tileOverlap;
		_tileSize = (_settings.tileSize === UNDEFINED) ? _tileSize : _settings.tileSize;
		_fileType = (_settings.fileType === UNDEFINED) ? _fileType : _settings.fileType;
		_user = (_settings.user === UNDEFINED) ? _user : _settings.user;
		
		
	}
	else {
		// canvas, old deprecated way for backward compatibility with tiles, width, height.
		_canvas = _settings;
		_tilesFolder = _tilesFolderDeprecated;
		_imageWidth = _imageWidthDeprecated;
		_imageHeight = _imageHeightDeprecated;
	}

	var _zoomLevelMin = 0,
			_zoomLevelMax = 16,
			_zoomLevelFull = 16, // For painting a background image for all missing tiles.	
			

		_lastscale = 1.0,
		
		_rotate = 0,
	
		_mouseX = 0,
			_mouseY = 0,
			_mouseDownX = 0,
			_mouseDownY = 0,
			_mouseMoveX = 0,
			_mouseMoveY = 0,

		_mouseIsDown = FALSE,
			_mouseLeftWhileDown = FALSE,
			_moved = FALSE;

		_offsetX = 0,
			_offsetY = 0,

		_aGetWidth = 'w',
			_aGetHeight = 'h',
			_aGetTile = 't',
			_aGetWaiting = 'wt',
	
		_tileZoomArray = NULL,
			_imageLoader = NULL,

		_ctx = NULL,
		
		_canvasWidth = _canvas.width,
		_canvasHeight = _canvas.height,
		
		_canvasLeft = 0, 
		_canvasTop = 0,
		_canvasRight = _canvasLeft + _canvasWidth,
		_canvasBottom = _canvasTop + _canvasHeight,
		
		PI = MATH.PI,
		TWOPI = MATH.PI * 2,
		LN2 = MATH.LN2,
		
		mathMin = MATH.min,
		mathMax = MATH.max,
		mathSqrt = MATH.sqrt,
		mathCos = MATH.cos,
		mathSin = MATH.sin,
		mathFloor = MATH.floor,
		mathCeil = MATH.ceil,
		mathLog = MATH.log,
		mathATan2 = MATH.atan2,
		mathATan = MATH.atan
		;


	function getTileFile( zoom, column, row ) {
		
		var totalNumber, zooms = 0, _tiles = NULL, tileGroupNumber;
		_tilesFolder = _settings.tilesFolder+"/"+_type+"/"+zoom_levels[horizontal_Level];
		return _tilesFolder + "/" + zoom + "/" + column + "_" + row + "." + _fileType;
		//console.log("Here");
	}

	function initialTilesLoaded() {
		//console.log(horizontal_Level);
		var tileZoomLevel = _tileZoomArray[horizontal_Level][_zoomLevel],
			columns = tileZoomLevel.length,
			rows = tileZoomLevel[0].length,
			mouse='mouse', touch='touch', gesture='gesture', // extreme minify!
			iColumn = 0, iRow = 0, imageId = 0;
			
		for( iColumn = 0; iColumn < columns; iColumn++ ) {

			for( iRow = 0; iRow < rows; iRow++ ) {
				//console.log(_imageLoader.getImageById( imageId ));
				tileZoomLevel[iColumn][iRow][_aGetTile] = _imageLoader.getImageById( imageId++ );
			}
		} 
		
		//
		// Centre image
		//
		_offsetX = (_canvasWidth - tileZoomLevel[_aGetWidth]) / 2;
		_offsetY = (_canvasHeight - tileZoomLevel[_aGetHeight]) / 2;
		
		var zoomWidth = tileZoomLevel[_aGetWidth],
			tileSizeX = _tileSize, tileSizeY = _tileSize,
			x1,y1,x2,y2
			zoomHeight = tileZoomLevel[_aGetHeight]
			tileList = new Array();
		for( iColumn = 0; iColumn < columns; iColumn++ ) {

			for( iRow = 0; iRow < rows; iRow++ ) {
				var smallerX =0;
				var smallerY =0;
				if(tileSizeX * (iColumn+1) > zoomWidth){
					smallerX = zoomWidth - tileSizeX * (iColumn+1);
				}
				if(tileSizeY * (iRow+1) > zoomHeight){
					smallerY = zoomHeight - tileSizeY * (iRow+1);
				}
				x1 = (iColumn * tileSizeX) + _offsetX;
				y1 = (iRow * tileSizeY) + _offsetY;
				
				x2 = x1 + tileSizeX;
				y2 = y1 + tileSizeY;
				tilesurrounds.push(Array(x1,y1,tileSizeX + smallerX,tileSizeY + smallerY));
				tileList.push( { "name" : _zoomLevel + "_" + iColumn + "_" + iRow, "file" : getTileFile( _zoomLevel, iColumn, iRow ), "x1" : x1, "x2" : x2, "y1" : y1, "y2" : y2, "posy" : iColumn, "posx" : iRow } );
			}
		}
	
		_tileZoomArray[horizontal_Level][0][0][0][_aGetTile] = _imageLoader.getImageById( imageId );
		
		
		//console.log("in " + _offsetX);
		//console.log("canvas " + _canvasWidth);
		//console.log(tileZoomLevel[_aGetWidth]);
		// 
		// Add mouse listener events
		//
		addEvent( mouse+'move', mouseMove, TRUE );
		//console.log("mouse move")
		addEvent( mouse+'down', mouseDown, TRUE );
		//console.log("mouse down")
		//addEvent( mouse+'up', mouseUp, TRUE );
		
		addEvent( mouse+'out', mouseOut, TRUE );
		//console.log("mouse out")
		addEvent( mouse+'over', mouseOver, TRUE );
		//console.log("mouse over")
		addEvent( 'DOMMouseScroll', mouseWheel, TRUE );
		//console.log("mouse wheel")
		addEvent( mouse+'wheel', mouseWheel, TRUE );
		
		/*addEvent(touch+'start', touchDown );
		addEvent(touch+'end', touchDown );
		addEvent(touch+'move', touchDown );
				
		addEvent( gesture+'end', gestureEnd ); // gestures to handle pinch
		addEvent( gesture+'change', gestureChange, TRUE ); // don't let a gesturechange event propagate
		*/
		addEvent( mouse+'up', mouseUpWindow, FALSE, window );
		addEvent( mouse+'move', mouseMoveWindow, FALSE, window );
		
		_ctx = _canvas.getContext('2d');
		
		//place selected image here.
		
		if(parseInt(document.getElementById("selectedImage_1").value)!= -1){	//initail selected square
			//console.log(selectedImage_ini);
			//console.log(document.getElementById("selectedImage_1").value);
			
			//console.log("x: "+_offsetX);
			//console.log("y: "+_offsetY);
			
			squareLocked = imageSelectInitial(images);

		}		
		requestPaint();
	}
    
	function addEvent( event, func, ret, obj ) {
		obj = obj || _canvas;
		obj.addEventListener( event, function(e){ func( e || window.event ); }, ret || FALSE );
	}
    
	function mouseDown( event ) {
		_mouseIsDown = TRUE;
		_mouseLeftWhileDown = FALSE;
		var temp_x = _mouseMoveX;
		var temp_y = _mouseMoveY;
		_mouseMoveX = _mouseDownX = mousePosX(event);
		_mouseMoveY = _mouseDownY = mousePosY(event); 
		
		_mouseX = mousePosX(event);
		_mouseY = mousePosY(event); 
		var leeway = 1;
		if( temp_x <= _mouseMoveX + leeway && temp_x >= _mouseMoveX - leeway && temp_y <= _mouseMoveY + leeway && temp_y >= _mouseMoveY - leeway ) {
			boolval = true;
		}else{
			boolval = false;
		}
	}
	var clickR = false;
	var clickL = false;
	var timeoutL = 0;
	var timeoutR = 0;
	$('#zoomCanvas').mouseup(function(event) { //mouse up events, including double clicks
		event.preventDefault()
		_mouseIsDown = FALSE;
		_mouseLeftWhileDown = FALSE;
		var click = FALSE;
		
		switch (event.which) {
			case 1:
				if(!_moved){
					clickR=false;
					clearTimeout(timeoutR);
					if(clickL){
						tmplockedImage = "";
						clearTimeout(timeoutL);
						clickL = false;
						//double click
						zoomIn( _mouseX, _mouseY );
					}else{
						clickL = true;
						lockedImage = tmplockedImage;
						timeoutL = setTimeout( function() {
							clickL = false;
							//single click
							//select an image
							//console.log(_moved);
							squareLocked = changeLockedImage(images);
							requestPaint();
						}, 300 );
					}
				}
				//if(boolval){
					//zoomIn( _mouseX, _mouseY );
				//}
				//console.log('Left Mouse button.');
				break;
			case 2:
				//console.log('Middle Mouse button.');
				break;
			case 3:
				clickL=false;
				clearTimeout(timeoutL);
				if(clickR){
					clearTimeout(timeoutR);
					clickR = false;
					//double click
					zoomOut( _mouseX, _mouseY );
				}else{
					clickR = true;
					timeoutR = setTimeout( function() {
						clickR = false;
						
						//single click
					}, 300 );
				}
			
			
				//if(boolval){
				//	zoomOut( _mouseX, _mouseY );
				//}
				click = true;
				//console.log('Right Mouse button pressed.');
				break;
		}
		Last_canvas.changeZoom(_offsetX,_offsetY, _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth],click);
		//Next_canvas.changeZoom(_offsetX,_offsetY, _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth]);
		Root_canvas.moveNext();
		Last_canvas.moveLast();
		_moved=false;
	});
	this.imagewidth = function(){
		return  _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth];
	}	
	this.Oy = function(){
		return  _offsetY;
	}	
	this.Ox = function(){
		return  _offsetX;
	}
	function mouseMove(event) {
		//console.log(tileList);
		var newOffsetX = 0, newOffsetY = 0;
		var true1 = false, true2 = false;
		_mouseX = mousePosX(event);
		_mouseY = mousePosY(event); 
		/* console.log("mouse x: "+_mouseX);
		console.log("mouse y: "+_mouseY); */
		if( _mouseIsDown ) {
			
			var a = _offsetX + (_mouseX - _mouseMoveX),
				b = _offsetY + (_mouseY - _mouseMoveY),
				image_width = _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth];
				buffer = hsize*0.3;
			if(a +image_width  > _canvasLeft+buffer && a < _canvasRight- buffer ){ 
				newOffsetX = a;
			}else{
				newOffsetX = _offsetX;
			}
			if(b+image_width  >_canvasTop+buffer  && b < _canvasBottom-buffer ){
				newOffsetY = b;
			}else{
				newOffsetY = _offsetY;
			}//for locked image
			
			changeLockedImagePos(newOffsetX, newOffsetY)
			
			if(_offsetX != newOffsetX && _offsetY != newOffsetY){
				_moved = true;
			}
			
				calculateNeededTiles( _zoomLevel, newOffsetX, newOffsetY );
				
				_mouseMoveX = _mouseX;
				_mouseMoveY = _mouseY;
				
				_offsetX = newOffsetX;
				_offsetY = newOffsetY;
				
				requestPaint();		
				//Next_canvas.moveNext(_offsetX,_offsetY, _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth]);			
				Last_canvas.moveLast();			
				Root_canvas.moveNext();
				square = [0,0,0,0];
				
				
				//need to follow locked image....
				
				
		}else{
			for(var m =0; m < tileList.length; m++){
				var true1 = _mouseX >= tileList[m].x1;
				var true2 = _mouseX < tileList[m].x2;
				var true3 = _mouseY >= tileList[m].y1;
				var true4 = _mouseY < tileList[m].y2;
				if(true1 && true2 && true3 && true4){
					console.log("file: "+tileList[m].file);
					/* console.log("x1: "+tileList[m].x1);
					console.log("y1: "+tileList[m].x2);
					console.log("offsetX_: "+_offsetX); */
				}
			}
			
			
			
			
			
			
			
			for(var m = 0; m < images.length; m++){
				var true1 = _mouseX >= parseInt(images[m].Xstart)+Math.round(_offsetX);
				var true2 = _mouseX <= parseInt(images[m].Xend)+Math.round(_offsetX);
				var true3 = _mouseY >= parseInt(images[m].Ystart)+Math.round(_offsetY);
				var true4 = _mouseY <= parseInt(images[m].Yend)+Math.round(_offsetY);
				if( true1 && true2 && true3 && true4){
					var innerhtml = "";
					var current = 0;
					var checker = true;
					//console.log("m " + m);
					while(checker){
						//console.log("current " + current);
						//images[m].name.substring(0, images[m].name.indexOf('.'))
						//console.log(images[m].name.lastIndexOf('.'));
						//console.log(imageInfo[current].name);
						//console.log(images[m].name.substring(0, images[m].name.lastIndexOf('.')));
						
						if(imageInfo[current].name == images[m].name.substring(0, images[m].name.lastIndexOf('.'))){
							checker = false;
							if (currentImage != current){
								currentImage = current;
								changeImage = true;
							}
						}
						current ++ ;
					}
					if(changeImage){				
						changeImage = false;
						//event to change image..					
						Next_canvas.selectedimage(currentImage);
						
						//console.log(parseInt(images[m].Xstart)+Math.round(_offsetX));
						//console.log(parseInt(images[m].Xend)+Math.round(_offsetX));
						//console.log(parseInt(images[m].Ystart)+Math.round(_offsetY));
						//console.log(parseInt(images[m].Yend)+Math.round(_offsetY));
						
						
					}
					//draw box over selected images
						//var scale = _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth]/image_canvas.imagewidth();
						var a = parseInt(images[m].Xstart)+Math.round(_offsetX);
						var b = parseInt(images[m].Ystart)+Math.round(_offsetY);
						var c = parseInt(images[m].Xend)-parseInt(images[m].Xstart);
						var d = parseInt(images[m].Yend)-parseInt(images[m].Ystart);
						square = [a,b,c,d];
						tmplockedImage = images[m].name;
						requestPaint();
				}
			}
		}		
	}

	function changeLockedImagePos(newOffsetX, newOffsetY){
		squareLocked[0] -= _offsetX;
		squareLocked[1] -= _offsetY;
		squareLocked[0] += newOffsetX;
		squareLocked[1] += newOffsetY;
	}
	function changeLockedImage(JSON){
		var check = true;
		var counter = -1;		
		for(var i = 0; i < JSON.length; i++){ //find position
			if (JSON[i].name == lockedImage){
				counter  =i;
			}
		}
		if(counter == -1){
			tmplockedImage = "";
			return [0,0,0,0];
		}
		permlocked(lockedImage);
		displayImageBox(JSON,counter)
		var a = parseInt(JSON[counter].Xstart)+Math.round(_offsetX);
		var b = parseInt(JSON[counter].Ystart)+Math.round(_offsetY);
		var c = parseInt(JSON[counter].Xend)-parseInt(JSON[counter].Xstart);
		var d = parseInt(JSON[counter].Yend)-parseInt(JSON[counter].Ystart);
		return [a,b,c,d];
	}
	
	function displayImageBox(JSON,counter){
		document.getElementById('TitleDiv').innerHTML = JSON[counter].name;
		var count = 0;
		var check = false;
		while(!check){
			//console.log(count);
			//console.log(imageInfo[count].name+"."+imageInfo[count].extension + " == " + JSON[counter].name);
			if(JSON[counter].name == imageInfo[count].name+"."+imageInfo[count].extension){
				check = true;
			}else{
				count++;
			}
		}
		if(_type.indexOf("Colour")!=-1){//changes bg colour to match hue of image.
			if(imageInfo[count].hueMode != -1){
				$('#imagediv').css('background-color','hsl('+imageInfo[count].hueMode+',100%,50%)');
			}else{
				$('#imagediv').css('background-color','hsl(0,0%,'+imageInfo[count].greyMode+'%)');
			}
			//console.log(imageInfo[imageNo].greyMode);
		}
		
		$('#image').attr('src',"JavascriptGet/DisplayImage.php?image_name="+JSON[counter].name+"&user="+_user);
		document.getElementById('TimeDiv').innerHTML = "Time Taken: "+imageInfo[count].time;
		document.getElementById('DateDiv').innerHTML = "Date Taken: "+imageInfo[count].day + "/"+imageInfo[count].month + "/"+imageInfo[count].year;
		document.getElementById('MakeDiv').innerHTML = "Make: "+imageInfo[count].make;
		document.getElementById('ModelDiv').innerHTML = "Model: "+imageInfo[count].model;
		//document.getElementById('EnlargeDiv').Enabled = true;
		
	}
	function permlocked(lock){
		permlock = lock;
		for(var i =0; i<5; i++){ //sets selected image so can be compared in different modes.
			document.getElementById("selectedImage_"+i).value = permlock;
		}
	}
	function changeLockedImageZoom(JSON){
		var check = true;
		var counter = -1;		
		for(var i = 0; i < JSON.length; i++){ //find position
			if (JSON[i].name == permlock){
				counter  =i;
			}
		}
		if(counter == -1){//if doesn't exist, don't draw
			tmplockedImage = "";
			permlocked(-1);
			return [0,0,0,0];
		}
		var a = parseInt(JSON[counter].Xstart)+Math.round(_offsetX);
		var b = parseInt(JSON[counter].Ystart)+Math.round(_offsetY);
		var c = parseInt(JSON[counter].Xend)-parseInt(JSON[counter].Xstart);
		var d = parseInt(JSON[counter].Yend)-parseInt(JSON[counter].Ystart);
		return [a,b,c,d];
	}
	function imageSelectInitial(JSON){
		var check = true;
		var counter = -1;		
		for(var i = 0; i < JSON.length; i++){ //find position
			if (JSON[i].name == permlock){
				counter  =i;
			}
		}
		if(counter == -1){//if doesn't exist, don't draw
			tmplockedImage = "";
			permlocked(-1);
			return [0,0,0,0];
		}
		selectedImage_ini = counter;
		_offsetX = (_canvasWidth - parseInt(images[counter].Xend)-parseInt(images[counter].Xstart))/2;
		_offsetY = (_canvasWidth - parseInt(images[counter].Yend)-parseInt(images[counter].Ystart))/2;
		displayImageBox(JSON,counter);
		var a = parseInt(JSON[counter].Xstart)+Math.round(_offsetX);
		var b = parseInt(JSON[counter].Ystart)+Math.round(_offsetY);
		var c = parseInt(JSON[counter].Xend)-parseInt(JSON[counter].Xstart);
		var d = parseInt(JSON[counter].Yend)-parseInt(JSON[counter].Ystart);
		return [a,b,c,d];
	}
	function mousePosX( event ) {
		// Get the mouse position relative to the canvas element.
		var x = 0;
		
		if (event.layerX || event.layerX === 0) { // Firefox
			x = event.layerX - _canvas.offsetLeft + wsize +8; //78 = temp fix
		} else if (event.offsetX || event.offsetX === 0) { // Opera
			x = event.offsetX + wsize;
		}
		return x;
	}
	
	function mousePosY( event ) {
		var y = 0;
		
		if (event.layerY || event.layerY === 0) { // Firefox
			y = event.layerY - _canvas.offsetTop + 8;
		} else if (event.offsetY || event.offsetY === 0) { // Opera
			y = event.offsetY + 8;
		}
		return y;
	}


    
	function mouseOut( event ) {
		if( _mouseIsDown ) {
			_mouseLeftWhileDown = TRUE;
		}
	}
	
	function mouseOver( event ) {
		// (Should be called mouseEnter IMO...)
		_mouseLeftWhileDown = FALSE;
	}
	
	function mouseWheel( event ) {
		var delta = 0;
		square = [0,0,0,0];
		if (event.wheelDelta) { 
			delta = -(event.wheelDelta/120);
		} else if (event.detail) { 
			delta = event.detail/3;
		}

		if (delta)  {
			if (delta < 0) {
				zoomInMouse();
			}
			else {
				zoomOutMouse();
			}
		}
				 
		if (event.preventDefault) {
			event.preventDefault(); 
		}
		
		//look into a new function?
		//Next_canvas.changeZoom(_offsetX,_offsetY, _tileZoomArray[horizontal_Level][_zoomLevel][_aGetWidth]);		
		
		event.returnValue = FALSE;
		
	}
	
	// If mouseUp occurs outside of canvas while moving, cancel movement.
	function mouseUpWindow( event ) {
		if( _mouseIsDown && _mouseLeftWhileDown ) {
			mouseUp( event );
		}
	}
	
	// keep track of mouse outside of canvas so movement continues.
	function mouseMoveWindow(event) {
		if( _mouseIsDown && _mouseLeftWhileDown ) {
			mouseMove(event);
		}
	}
    
	// Zoom in a single level
	function zoomIn( x, y ) {
		prehorizontal = horizontal_Level;
		/*if(horizontal_Level - 2 >= 0){
			horizontal_Level -= 2;
		}else if(horizontal_Level - 1 >= 0){
			horizontal_Level --;
		}*/
		zoom( _zoomLevel + 1, x, y );
		requestPaint();
	}
	
	// Zoom out a single level
	function zoomOut( x, y ) {
		prehorizontal = horizontal_Level;
		/*if(horizontal_Level + 1 < width_array.length){
			horizontal_Level ++;
		}*/
		//console.log(width_array[horizontal_Level][_zoomLevel]);
		/* if(width_array[horizontal_Level][_zoomLevel-1]*1.1 > _canvasWidth){
			zoom( _zoomLevel - 1, x, y );
		}else if(width_array[horizontal_Level+1][_zoomLevel-1]*1.1 > _canvasWidth){
			if(_zoomLevel != 0){
				if(horizontal_Level + 1 < width_array.length){
					horizontal_Level ++;
					zoom( _zoomLevel - 1, x, y );
				}
			}
		} */
		var answered = false;
		var p = 1;
		var q = 0;
		var turn = true;
		if(_zoomLevel > 0){
			while(!answered){
				if(width_array[horizontal_Level+q][_zoomLevel-p]*1.2 > _canvasWidth){
					horizontal_Level += q;
					zoom( _zoomLevel - p, x, y );
					answered = true;
				}
				q++;
			}
			requestPaint();
		}
	}
	
    // Zoom in at mouse co-ordinates
	function zoomInMouse() {
		prehorizontal = horizontal_Level;
		//zoomIn( _mouseX, _mouseY );
		if(horizontal_Level + 1 < width_array.length){
			horizontal_Level ++;
		}
		zoom( _zoomLevel, _mouseX, _mouseY );
		//console.log(zoom_levels[horizontal_Level]);
		Root_canvas.moveNext();
		Last_canvas.moveLast();
		requestPaint();
		
	}
	
	// Zoom out at mouse co-ordinates
	function zoomOutMouse() {
		prehorizontal = horizontal_Level;
		//zoomOut( _mouseX, _mouseY );
		if(width_array[horizontal_Level][_zoomLevel]*1.1 > _canvasWidth){
			if(horizontal_Level - 1 >= 0){
				horizontal_Level --;
			}
		}
		zoom( _zoomLevel, _mouseX, _mouseY );
		//console.log(zoom_levels[horizontal_Level]);
		Root_canvas.moveNext();
		Last_canvas.moveLast();
		requestPaint();
		
	}
	
	function setRotate( radians ) {
		_rotate = radians % TWOPI;
		
		calculateNeededTiles( _zoomLevel, _offsetX, _offsetY );
		requestPaint();
	}

	// Change the zoom level and update.
	function zoom( zoomLevel, zoomX, zoomY ) {

		if( zoomLevel >= _zoomLevelMin && zoomLevel <= _zoomLevelMax ) {
			// TODO: restrict zoom position to within (close?) area of image.
            
			var newZoom = zoomLevel,
					currentZoom = _zoomLevel,										
					currentImageX = zoomX - _offsetX,
					currentImageY = zoomY - _offsetY,
			
				scale = _tileZoomArray[horizontal_Level][newZoom][_aGetWidth] / _tileZoomArray[prehorizontal][currentZoom][_aGetWidth],
			
				newImageX = currentImageX * scale,
					newImageY = currentImageY * scale,

				newOffsetX = _offsetX - (newImageX - currentImageX),
					newOffsetY = _offsetY - (newImageY - currentImageY);

				
				
			calculateNeededTiles( newZoom, newOffsetX, newOffsetY );
			
			_zoomLevel = newZoom;
			_offsetX = newOffsetX;
			_offsetY = newOffsetY;
		}
		
		//console.log(_zoomLevel);
		//console.log(_type);
		//console.log(zoom_levels[horizontal_Level]);
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_LayerXML.php",
			async: false,
			data: {'Vzoom': _zoomLevel, 'type':_type, 'Hzoom': zoom_levels[horizontal_Level],'user':_user},
			success: function(JSON){
				images = JSON;
				squareLocked = changeLockedImageZoom(JSON);
				//requestPaint();
			}
		});
		//console.log("images:");
		//console.log(images);
	}
	
	// Work out which of the tiles we need to download 
	function calculateNeededTiles( zoom, offsetX, offsetY ) {

		//
		// Calculate needed tiles
		//
		
		// TODO: This needs to be threaded, particularly when we are rotated.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		var tileZoomLevelArray = _tileZoomArray[horizontal_Level][zoom],
		
			canvasLeft = _canvasLeft, 
			canvasTop = _canvasTop,
			canvasRight = _canvasRight,
			canvasBottom = _canvasBottom,
	
			tile = NULL,
			
			tileSize = _tileSize,
				tileOverlap = _tileOverlap,
				rotate = _rotate,
	
			zoomWidth = tileZoomLevelArray[_aGetWidth],
			zoomHeight = tileZoomLevelArray[_aGetHeight],
			
			imageMidX = offsetX + zoomWidth / 2,
			imageMidY = offsetY + zoomHeight / 2,
		
			columns = tileZoomLevelArray.length,
			rows = tileZoomLevelArray[0].length,
		
			iColumn, iRow,
			
			
			tileOverlapX = 0,
			tileOverlapY = 0,
			tileSizeX = tileSize - tileOverlapX,
			tileSizeY = tileSize - tileOverlapY,
			tileWidth, tileHeight,
			
			topleft, topright, bottomright, bottomleft,
			
			x1,y1,x2,y2,corners;
			
			//console.log(zoomWidth);
		tileList = new Array
		tilesurrounds = new Array();		
		for( iColumn = 0; iColumn < columns; iColumn++ ) {
			for( iRow = 0; iRow < rows; iRow++ ) {
				var smallerX =0;
				var smallerY =0;
				tile = tileZoomLevelArray[iColumn][iRow];
				
				//if( tile[_aGetTile] === NULL && tile[_aGetWaiting] === FALSE ) { // If not loaded or not loading
					//for testing sizes of tiles

					if(tileSizeX * (iColumn+1) > zoomWidth){
						smallerX = zoomWidth - tileSizeX * (iColumn+1);
					}
					if(tileSizeY * (iRow+1) > zoomHeight){
						smallerY = zoomHeight - tileSizeY * (iRow+1);
					}

					//////original....    \/
					x1 = (iColumn * tileSizeX) + offsetX;
					y1 = (iRow * tileSizeY) + offsetY;
					
					x2 = x1 + tileSizeX;
					y2 = y1 + tileSizeY;
					//////////////       /\
					
					tilesurrounds.push(Array(x1,y1,tileSizeX + smallerX,tileSizeY + smallerY));
					
					
					if( !( x1 > canvasRight || y1 > canvasBottom || x2 < canvasLeft || y2 < canvasTop) ) {
						// request tile!
						tile[_aGetWaiting] = TRUE;
						tileList.push( { "name" : zoom + "_" + iColumn + "_" + iRow, "file" : getTileFile( zoom, iColumn, iRow ), "x1" : x1, "x2" : x2, "y1" : y1, "y2" : y2, "posy" : iColumn, "posx" : iRow } );
					}
					
					//console.log(getTileFile( zoom, iColumn, iRow ));
				//}
			}

		}
		//console.log(tileLocationRow);
		getTiles( tileList );
		//console.log(tileList)
	}

	// Load the tiles we need with ImageLoader
	function getTiles( tileList ) {
		if( tileList.length > 0 ) {

			/*_imageLoader = */new ImageLoader( {
				"images": tileList,
				"onImageLoaded":function( name, tile ) { tileLoaded( name, tile ); }
			} );
		}
	}
	
	// Tile loaded, save it.
	function tileLoaded( name, tile ) {

		var tileDetails = name.split("_");
		
		if( tileDetails.length === 3 ) {

			var tileInfo = _tileZoomArray[horizontal_Level][tileDetails[0]][tileDetails[1]][tileDetails[2]];
			tileInfo[_aGetTile] = tile;
			tileInfo[_aGetWaiting] = FALSE;
			
			requestPaint();
		}
	}
	

	function requestPaint() {
		
		var animRequest = window.requestAnimationFrame;
		if( animRequest ) {
			animRequest( paint );
		} else {
			window.setTimeout( paint, 1000 / 60 );	
		}
	}

	function paintBorder( ctx ) {
		//ctx.strokeStyle = "#000";
		//ctx.strokeRect( 0, 0, _canvasWidth, _canvasHeight );
	}
	
	function paint() {
		
		var tileZoomLevelArray = _tileZoomArray[horizontal_Level][_zoomLevel],
				
			offsetX = _offsetX,
				offsetY = _offsetY,
			tileSize = _tileSize,
				tileOverlap = _tileOverlap,

			zoomWidth = tileZoomLevelArray[_aGetWidth],
				zoomHeight = tileZoomLevelArray[_aGetHeight],
        
			imageMidX = offsetX + zoomWidth / 2,
			imageMidY = offsetY + zoomHeight / 2,

			rotate = _rotate,

			columns = tileZoomLevelArray.length,
				rows = tileZoomLevelArray[0].length,

			canvasLeft = _canvasLeft,
				canvasTop = _canvasTop,
				canvasRight = _canvasRight,
				canvasBottom = _canvasBottom,

			x1, x2, y1, y2,
				corners,
			
			tileOverlapX = 0, tileOverlapY = 0,
				tileCount = 0, 
				tile = NULL,
				
			tileSizeX = tileSize - tileOverlapX,
			tileSizeY = tileSize - tileOverlapY,
			tileWidth, tileHeight,
			
			overlap = false;
		// Clear area
		//
		_ctx.clearRect( 0, 0, _canvasWidth, _canvasHeight );
			
		
		for( iColumn = 0; iColumn < columns; iColumn++ ) {
			for( iRow = 0; iRow < rows; iRow++ ) {
				x1 = (iColumn * tileSizeX) + offsetX;
				y1 = (iRow * tileSizeY) + offsetY;
				//console.log(iColumn);
				//console.log(tileSizeX);
				//console.log(offsetX);
				tileWidth =tileSizeX;
				tileHeight =tileSizeY;
				
				x2 = x1 + tileWidth;
				y2 = y1 + tileHeight;
					overlap = !( x1 > canvasRight || x2 < canvasLeft || y1 > canvasBottom || y2 < canvasTop );
				
				if( overlap ) {

					tile = tileZoomLevelArray[iColumn][iRow][_aGetTile];
					if( tile !== NULL ) {
						// Draw tile
						_ctx.drawImage( tile, x1, y1 );
					}					
					if( _debug ) {
						
						if( _debugShowRectangle && tile === NULL ) {
							
							_ctx.fillStyle = "#999";
							_ctx.fillRect( x1, y1, x2 - x1, y2 - y1 );
						}
						
						// Draw tile border.
						_ctx.strokeRect( x1, y1, tileSize, tileSize );
						tileCount++;
					}
				}
			}
		}
		
		debugPaint( _ctx, offsetX, offsetY, zoomWidth, zoomHeight, tileCount );
		if( _drawBorder ) {
			paintBorder( _ctx );
		}
		if(document.getElementById("hiddentype").value=="Time"){displayColours()};
		
		//locked square
		_ctx.strokeStyle = "#CC0066";
		
		_ctx.lineWidth=8;
		
		_ctx.strokeRect(squareLocked[0], squareLocked[1], squareLocked[2], squareLocked[3]);
		//moving square
		_ctx.strokeStyle = "#330066";
		
		_ctx.lineWidth=4;
		
		_ctx.strokeRect(square[0], square[1], square[2], square[3]);
		//tiles
		_ctx.strokeStyle = "#ffffff";
		
		_ctx.lineWidth=10;
		for(var i = 0; i <tilesurrounds.length;i++){
			_ctx.strokeRect(tilesurrounds[i][0], tilesurrounds[i][1], tilesurrounds[i][2], tilesurrounds[i][3]);
		}

	}
	
	function debugPaint( ctx, imageLeft, imageTop, zoomWidth, zoomHeight, tileCount ) {
		
		if( _debug ) {

			// 
			// DEBUG!
			//
			ctx.strokeStyle = "#ff0";
			ctx.strokeRect( _canvasLeft, _canvasTop, _canvasWidth, _canvasHeight );
			ctx.strokeStyle = "#f0f";
			ctx.strokeRect( imageLeft, imageTop, zoomWidth, zoomHeight );
		
			ctx.fillStyle = "#0f0";
			ctx.font = "normal 12px Arial";

			// Text
			ctx.fillText( _mouseX + "," + _mouseY + " | " + _offsetX + "," + _offsetY + " | " + tileCount, 0, 20 );

			// Grid
			ctx.strokeStyle = "#f00";
			var x,y;
			for( y = 0; y < _canvasHeight; y += _tileSize ) {
				for( x = 0; x < _canvasWidth; x += _tileSize ) {	
					ctx.strokeRect( x, y, _tileSize, _tileSize ); 
				}
			}
		}
	}
	
	//Zoom in at the centre of the canvas
	this.zoomInCentre = function () {
		zoomIn( _canvasWidth / 2, _canvasHeight / 2 );
	};
	
	//Zoom out at the centre of the canvas
	this.zoomOutCentre = function () {
		zoomOut( _canvasWidth / 2, _canvasHeight / 2);
	};
	
	this.rotateClockwise = function () {
		setRotate( _rotate + TWOPI/32 );
	};
	
	this.rotateAnticlockwise = function () {
		setRotate( _rotate - TWOPI/32 );
	};
	
function displayColours(){			
		var htmltable = "<center><table>";
		if(_zoomLevel < colourLevels[1]){
		//years
			htmltable+= "<tr><th colspan = " + (colourYears.length)*2 + "> Years </th></tr><tr>";
			for (var i = 0; i < colourYears.length; i++){
				htmltable+= "<td>"+(startyear+i)+"</td><td style='background-color: rgb("+colourYears[i][0]+","+colourYears[i][1]+","+colourYears[i][2]+"); width:10px;'></td> "
			}
			htmltable+= "</tr></table>";
		}else if(_zoomLevel < colourLevels[2]){
		//months
			htmltable+= "<tr><th colspan = " + (colourMonths.length)*2 + "> Months </th></tr><tr>";
			for (var i = 0; i < colourMonths.length; i++){
				htmltable+= "<td>"+months[i]+"</td><td style='background-color: rgb("+colourMonths[i][0]+","+colourMonths[i][1]+","+colourMonths[i][2]+"); width:10px;'></td> ";
			}
		}else{
		//days
			htmltable+= "<tr><th colspan = " + (colourWeeks.length)*2 + "> Days </th></tr><tr class='days'>";
			htmltable+= "<td> 1 </td>";
			for (var i = 0; i < colourWeeks.length; i++){
				htmltable+= "<td style='background-color: rgb("+colourWeeks[i][0]+","+colourWeeks[i][1]+","+colourWeeks[i][2]+"); width:10px;'></td> "
			}
			htmltable+= "<td> 31 </td>";
		}
		htmltable+= "</tr></table></center>";
		document.getElementById("colourdiv").innerHTML = htmltable;
	
	}
	
	
	var counter = 0;
	this.getImageInfo = function(){
		return imageInfo;
	}
	(function() { // setup
		//my changes
		_zoomLevelMax = 2;
		_zoomLevelMin = 0;
		_tileZoomArray = [];
		
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/LoadWidth.php",
			async: false,
			data: {'type':_type,'user':_user},
			success: function(JSON){
				width_array = JSON[1];
				zoom_levels = JSON[0];
				//console.log(width_array);
				//console.log(zoom_levels);
				
			}
		});
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_imageinfo.php",
			async: false,
			data: {'user':_user},
			success: function(JSON){
				imageInfo = JSON;
			}
		});
		_maxZoom = width_array[0].length-1;
		_zoomLevelMax = width_array[0].length-1;
		/////// initial load tile
		
		var CurrentZoomLevel = _defaultZoom;
	
		//perm lock set
		//console.log(document.getElementById("selectedImage_1").value);
		permlocked(document.getElementById("selectedImage_1").value);
		
		
		//find already selected image
		_zoomLevel = 0;	
		zoomLevelStart = -1;
		//console.log(permlock);
		
		if(permlock!=-1){
			//console.log('in');
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "XML-Handlers/Load_LayerXML-initial.php",
				async: false,
				data: {'Vzoom': _zoomLevelMax, 'type':_type, 'user': _user},
				success: function(JSON){
					locationSearch = JSON;
					//console.log(locationSearch);
					var found = false;
					var count = 0;
					while(!found){
						for(var i = 0; i < locationSearch[count].length; i++){
							//console.log(locationSearch[count][i].name + " == " + document.getElementById("selectedImage_1").value + "??? ...");
							//console.log(locationSearch[count][i].name  ==  document.getElementById("selectedImage_1").value);
							if(locationSearch[count][i].name==document.getElementById("selectedImage_1").value&& found == false){
								found = true;
							}
						}
						if (found != true){
							count ++;
						}
					}
					//image in layer count.
					
					CurrentZoomLevel = count;
					_zoomLevel = count;
					zoomLevelStart = count;

				}
			});
		}
		
		
		//console.log(zoomLevelStart);
		

		_minZoom=0;
		_defaultZoom =_zoomLevel;
		
		var 	iZoom = 0, iColumn = 0, iRow = 0,
				columns = -1, rows = -1;
        for(var hzoom = 0; hzoom < width_array.length; hzoom++){
			_tileZoomArray[hzoom] =[];
			for( iZoom = _zoomLevelMax;  iZoom >= _zoomLevelMin; iZoom-- ) {
				_tileZoomArray[hzoom][iZoom] = [];
			}
			for( iZoom = _zoomLevelMax;  iZoom >= _zoomLevelMin; iZoom-- ) {
				columns = mathCeil( width_array[hzoom][iZoom] / _tileSize );
				rows = mathCeil( width_array[hzoom][iZoom] / _tileSize );

				// Create array for tiles
				for( iColumn = 0; iColumn < columns; iColumn++ ) {
					_tileZoomArray[hzoom][iZoom][iColumn] = []; 
				}
				// Set defaults
				// TODO: Test width - possibly to short, maybe not including last tile width...
				_tileZoomArray[hzoom][iZoom][_aGetWidth] = width_array[hzoom][iZoom];
				_tileZoomArray[hzoom][iZoom][_aGetHeight] = width_array[hzoom][iZoom];
				for( iColumn = 0; iColumn < columns; iColumn++ ) {
				
					for( iRow = 0; iRow < rows; iRow++ ) {
					
						_tileZoomArray[hzoom][iZoom][iColumn][iRow] = [];
						
						_tileZoomArray[hzoom][iZoom][iColumn][iRow][_aGetTile] = NULL;
						_tileZoomArray[hzoom][iZoom][iColumn][iRow][_aGetWaiting] = FALSE;
					}
				}
			}
			//console.log(_tileZoomArray);
		}
		
		
		
		
		
		
		if( _defaultZoom === UNDEFINED ) {
			_defaultZoom = zoomLevelStart;
		}
		_zoomLevel = _defaultZoom;

		if( _minZoom > _zoomLevelMin ) {
			_zoomLevelMin = _minZoom;
		}
		if( _maxZoom < _zoomLevelMax ) {
			_zoomLevelMax = _maxZoom;
		}

		if( _zoomLevelMin > _zoomLevelMax ) {
			var zoomMinTemp = _zoomLevelMin;
			_zoomLevelMin = _zoomLevelMax;
			_zoomLevelMax = zoomMinTemp;
		}
		var tempval = -1
		//console.log(_canvasWidth);
		for (var i =0; i < _tileZoomArray.length; i++){
			if(_tileZoomArray[i][0][_aGetWidth] < _canvasWidth){
				tempval = i;
				
			}
			//console.log(i);
			//console.log(_tileZoomArray[i][0][_aGetWidth]);
		}
		if (tempval == -1){
			horizontal_Level = 1;
		}else{
			horizontal_Level = tempval;
		}
		//console.log(horizontal_Level);
		//
		// Initial tile load
		//
		var imageList = [],imageId = 0;
		columns = _tileZoomArray[horizontal_Level][_zoomLevel].length;
		rows = columns;
		for( iColumn = 0; iColumn < columns; iColumn++ ) {
		
			for( iRow = 0; iRow < rows; iRow++ ) {
				imageList.push( { "id" : imageId++, "file": getTileFile( _zoomLevel, iColumn, iRow  ) } );
			}
		} 
		
		
		
		
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_LayerXML.php",
			async: false,
			data: {'Vzoom': CurrentZoomLevel, 'type':_type, 'Hzoom': zoom_levels[horizontal_Level], 'user': _user},
			success: function(JSON){
				images = JSON;
				
			}
		});
		
		
		//console.log(images);
		//make years dynamic... (i.e get them properly)
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "JavascriptGet/GetColours.php",
			async: false,
			data: {'type':_type,'user':_user},
			success: function(JSON){
				colourLevels = JSON[0];
				colourYears = JSON[1];
				colourMonths= JSON[2];
				colourWeeks = JSON[3];
				startyear = JSON[4];
				
				//console.log(JSON);
			}
		});
		if(document.getElementById("hiddentype").value=="Time"){
			displayColours();// displays colour meanigns to time based browsing only.
		}
		imageList.push( { "id" : imageId, "file": getTileFile( 0, 0, 0  ) } );
		console.log(imageList);
		_imageLoader = new ImageLoader( {
			"images": imageList,
			"onAllLoaded":function() { initialTilesLoaded(); },
		} );
        counter++;
		
		//Next_canvas.selectedimage(selectedImage_ini);
		//Last_canvas.moveLast();			
		//Root_canvas.moveNext();
		
	}());
	

}
				
