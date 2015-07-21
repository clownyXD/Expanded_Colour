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

function CanvasNext( _settings, _tilesFolderDeprecated, _imageWidthDeprecated, _imageHeightDeprecated ) {
	var changeImage = false;
	var boolval = false;
	var currentImage = -1;
	var horizontal_Level_Next = 0;
	var prehorizontal =0;
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
			_zoomLevelLast = -1,

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
	this.getCtx = function(){
		return _ctx;
	}

	function getTileFile( zoom, column, row ) {
		//console.log(horizontal_Level_Next)
		//console.log(zoom_levels[horizontal_Level_Next])
		//console.log(zoom)
		var totalNumber, zooms = 0, _tiles = NULL, tileGroupNumber;
		_tilesFolder = _settings.tilesFolder+"/"+_type+"/"+zoom_levels[horizontal_Level_Next];
		return _tilesFolder + "/" + zoom + "/" + column + "_" + row + "." + _fileType;
		//alert("Here");
	}

	function initialTilesLoaded() {
		//console.log(horizontal_Level_Next);
		var tileZoomLevel = _tileZoomArray[horizontal_Level_Next][_zoomLevelLast],
			columns = tileZoomLevel.length,
			rows = tileZoomLevel[0].length,
			mouse='mouse', touch='touch', gesture='gesture', // extreme minify!
			iColumn = 0, iRow = 0, imageId = 0;
			
		for( iColumn = 0; iColumn < columns; iColumn++ ) {

			for( iRow = 0; iRow < rows; iRow++ ) {

				tileZoomLevel[iColumn][iRow][_aGetTile] = _imageLoader.getImageById( imageId++ );
			}
		}
		
		_tileZoomArray[horizontal_Level_Next][0][0][0][_aGetTile] = _imageLoader.getImageById( imageId );
		
		//
		// Centre image
		//
		_offsetX = ((_canvasWidth - tileZoomLevel[_aGetWidth]) / 2) ;
		_offsetY = ((_canvasHeight - tileZoomLevel[_aGetHeight]) / 2);
		
		addEvent( mouse+'move', mouseMove, TRUE );
		
		_ctx = _canvas.getContext('2d');
		/*requestPaint();*/
	}
	function addEvent( event, func, ret, obj ) {
		obj = obj || _canvas;
		obj.addEventListener( event, function(e){ func( e || window.event ); }, ret || FALSE );
	}
	// Work out which of the tiles we need to download 
	function mouseMove(event) {
		/*_mouseX = mousePosX(event);
		_mouseY = mousePosY(event); 
		for(var i = 0; i < images.length; i++){
			var true1 = _mouseX >= parseInt(images[i].Xstart)+Math.round(_offsetX);
			var true2 = _mouseX <= parseInt(images[i].Xend)+Math.round(_offsetX);
			var true3 = _mouseY >= parseInt(images[i].Ystart)+Math.round(_offsetY);
			var true4 = _mouseY <= parseInt(images[i].Yend)+Math.round(_offsetY);
			if( true1 && true2 && true3 && true4){
				var innerhtml = "";
				var current = 0;
				var checker = true;
				while(checker){
					if(imageInfo[current].name == images[i].name.substring(0, images[i].name.indexOf('.'))){
						checker = false;
						if (currentImage != current){
							currentImage = current;
							changeImage = true;
						}
					}
					current ++ ;
				}
				if(changeImage){
					document.getElementById('TitleDiv').innerHTML = imageInfo[currentImage].name + "." + imageInfo[currentImage].extension;
					$('#image').attr('src',"JavascriptGet/DisplayImage.php?image_name=Resized/"+images[i].name);
					document.getElementById('TimeDiv').innerHTML = "Time Taken: "+imageInfo[currentImage].time;
					document.getElementById('DateDiv').innerHTML = "Date Taken: "+imageInfo[currentImage].day + "/"+imageInfo[currentImage].month + "/"+imageInfo[currentImage].year;
					document.getElementById('MakeDiv').innerHTML = "Make: "+imageInfo[currentImage].make;
					document.getElementById('ModelDiv').innerHTML = "Model: "+imageInfo[currentImage].model;
					document.getElementById('EnlargeDiv').Enabled = true;
					
					changeImage = false;
					
				}
			}
		}	*/
		
	}
	function mousePosX( event ) {
		// Get the mouse position relative to the canvas element.
		var x = 0;
		
		if (event.layerX || event.layerX === 0) { // Firefox
			x = event.layerX - _canvas.offsetLeft;
		} else if (event.offsetX || event.offsetX === 0) { // Opera
			x = event.offsetX;
		}
		return x;
	}
	/*this.moveNext = function(OffsetX, OffsetY, width){
		var scale = ( _tileZoomArray[horizontal_Level_Next][_zoomLevelLast][_aGetWidth])/(width)
		var padding = (minihsize - miniwsize)/2
		if (scale < 0){ scale *= -1;}
		_offsetX = (OffsetX * scale)
		_offsetY = (OffsetY  * scale) + padding
		calculateNeededTiles( _zoomLevelLast, _offsetX, _offsetY );
		requestPaint();	
	}*/
	this.selectedimage = function(imageNo){
		//console.log(horizontal_Level_Next);
		tileZoomLevel = _tileZoomArray[horizontal_Level_Next][_zoomLevelLast];
	//defines the size...
		/* var tempval = -1
		for (var i =0; i < _tileZoomArray.length; i++){
			if(width_array[i] < (_canvasWidth/3)){
				tempval = i;
			}
		}
		if (tempval == -1){
			horizontal_Level_Next = 0
		}else{
			horizontal_Level_Next = tempval;
		} */
		
		//horizontal_Level_Next = 2;
	//defines zoom level
		if(_zoomLevel < width_array[horizontal_Level_Next].length -1){
			_zoomLevelLast = _zoomLevel + 1;
		}else{
			_zoomLevelLast = _zoomLevel;
		}
	//gets correct information
		var image = [];
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_LayerXML.php",
			async: false,
			data: {'Vzoom': _zoomLevelLast, 'type':_type, 'Hzoom': zoom_levels[horizontal_Level_Next],'user':_user},
			success: function(JSON){
				images = JSON;
			}
		});
		//search through images....
		var indexofimages = 0;
		
		//console.log(images);
		//console.log(imageNo);
		//console.log(imageInfo);
		var Iname = (imageInfo[imageNo].name+"."+imageInfo[imageNo].extension);
		while (Iname != images[indexofimages].name){
			indexofimages ++;
			
		}
		//console(indexofimages);
		//
		_offsetX = (miniwsize/ 2) - images[indexofimages].Xstart - (images[indexofimages].Xend - images[indexofimages].Xstart)/2;
		_offsetY = (minicanvas/ 2) - images[indexofimages].Ystart - (images[indexofimages].Yend - images[indexofimages].Ystart)/2;
		//console(_offsetX);
		//console(_offsetY);
		//console(horizontal_Level_Next);
		
		calculateNeededTiles( _zoomLevelLast, _offsetX, _offsetY );
		requestPaint();
	}

	/*this.changeZoom = function(OffsetX, OffsetY, width){
		var prehorizontal = horizontal_Level_Next;
		var preZoom = _zoomLevelLast;
		//console.log(width_array);
		if(_zoomLevel < width_array[horizontal_Level_Next].length -1){
			_zoomLevelLast = _zoomLevel + 1;
		}else{
			_zoomLevelLast = _zoomLevel;
		}
		//console.log(_zoomLevelLast);
		var tempval = -1
		for (var i =0; i < _tileZoomArray.length; i++){
			if(_tileZoomArray[i][_zoomLevelLast][_aGetWidth] < _canvasWidth){
				tempval = i;
				
			}
		}
		if (tempval == -1){
			horizontal_Level_Next = 0
		}else{
			horizontal_Level_Next = tempval;
		}
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_LayerXML.php",
			async: false,
			data: {'Vzoom': _zoomLevelLast, 'type':_type, 'Hzoom': zoom_levels[horizontal_Level_Next]},
			success: function(JSON){
				images = JSON;
				
			}
		});
				
		var scale = ( _tileZoomArray[horizontal_Level_Next][_zoomLevelLast][_aGetWidth])/(width)
		var padding = (minihsize - miniwsize)/2
		if (scale < 0){ scale *= -1;}
		_offsetX = (OffsetX * scale)
		_offsetY = (OffsetY  * scale) + padding
		calculateNeededTiles( _zoomLevelLast, _offsetX, _offsetY );
		requestPaint();	
	}*/
	function mousePosY( event ) {
		var y = 0;
		
		if (event.layerY || event.layerY === 0) { // Firefox
			y = event.layerY - _canvas.offsetTop + 8;
		} else if (event.offsetY || event.offsetY === 0) { // Opera
			y = event.offsetY + 8;
		}
		return y;
	}
	
	function calculateNeededTiles( zoom, offsetX, offsetY ) {

		//
		// Calculate needed tiles
		//
		
		// TODO: This needs to be threaded, particularly when we are rotated.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		var tileZoomLevelArray = _tileZoomArray[horizontal_Level_Next][zoom],
		
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
			tileList = [],
			
			tileOverlapX = 0,
			tileOverlapY = 0,
			tileSizeX = tileSize - tileOverlapX,
			tileSizeY = tileSize - tileOverlapY,
			tileWidth, tileHeight,
			
			topleft, topright, bottomright, bottomleft,
			
			x1,y1,x2,y2,corners;
			
			//console.log(zoomWidth);
			
		for( iColumn = 0; iColumn < columns; iColumn++ ) {

			for( iRow = 0; iRow < rows; iRow++ ) {

				tile = tileZoomLevelArray[iColumn][iRow];
				
				//if( tile[_aGetTile] === NULL && tile[_aGetWaiting] === FALSE ) { // If not loaded or not loading
				
					x1 = (iColumn * tileSizeX) + offsetX;
					y1 = (iRow * tileSizeY) + offsetY;
					
					x2 = x1 + tileSizeX;
					y2 = y1 + tileSizeY;
					
					if( !( x1 > canvasRight || y1 > canvasBottom || x2 < canvasLeft || y2 < canvasTop) ) {
						// request tile!
						tile[_aGetWaiting] = TRUE;
						tileList.push( { "name" : zoom + "_" + iColumn + "_" + iRow, "file" : getTileFile( zoom, iColumn, iRow ) } );
					}
					//alert(getTileFile( zoom, iColumn, iRow ));
				//}
			}
		}
		
		
		getTiles( tileList );
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

			var tileInfo = _tileZoomArray[horizontal_Level_Next][tileDetails[0]][tileDetails[1]][tileDetails[2]];
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
	
	function paint() {
		
		var tileZoomLevelArray = _tileZoomArray[horizontal_Level_Next][_zoomLevelLast],
				
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
						try{
							_ctx.drawImage( tile, x1, y1 );
						}
						catch(err){}
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
		if(document.getElementById("hiddentype").value=="Time"){displayColours()};
		debugPaint( _ctx, offsetX, offsetY, zoomWidth, zoomHeight, tileCount );

	}
	function displayColours(){			
		var htmltable = "<center><table>";
		if(_zoomLevelLast < colourLevels[1]){
		//years
		//half years (e.g. 12 instead of 2012)
			if(colourYears.length < 10){
				htmltable+= "<tr><th colspan = " + (colourYears.length)*2 + "> Years </th></tr><tr>";
				for (var i = 0; i < colourYears.length; i++){
					htmltable+= "<td>"+((startyear+i).toString()).substr(2,4)+"</td><td style='background-color: rgb("+colourYears[i][0]+","+colourYears[i][1]+","+colourYears[i][2]+"); width:10px;'></td> "
				}
			}else{
				htmltable+= "<tr>";
				for (var i = 0; i < Math.ceil(colourYears.length/2); i++){
					htmltable+= "<td>"+((startyear+i).toString()).substr(2,4)+"</td><td style='background-color: rgb("+colourYears[i][0]+","+colourYears[i][1]+","+colourYears[i][2]+"); width:10px;'></td> "
				}
				htmltable+= "</tr><tr>";
				for (var i = Math.ceil(colourYears.length/2); i < colourYears.length; i++){
					htmltable+= "<td>"+((startyear+i).toString()).substr(2,4)+"</td><td style='background-color: rgb("+colourYears[i][0]+","+colourYears[i][1]+","+colourYears[i][2]+"); width:10px;'></td> "
				}
			}
			htmltable+= "</tr></table>";
		}else if(_zoomLevelLast < colourLevels[2]){
		//months
			htmltable+= "<tr><th colspan = " + (colourMonths.length)*2 + "> Months </th></tr><tr>";
			for (var i = 0; i < colourMonths.length; i++){
				htmltable+= "<td>"+mmonths[i]+"</td><td style='background-color: rgb("+colourMonths[i][0]+","+colourMonths[i][1]+","+colourMonths[i][2]+"); width:10px;'></td> ";
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
		document.getElementById("colourdivNext").innerHTML = htmltable;
	
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
	var counter = 0;
	(function() { // setup
		//my changes
		_zoomLevelMin = 0;
		_tileZoomArray = [];
		horizontal_Level_Next = 3;
		//console.log(_tileZoomArray);
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/LoadWidth.php",
			async: false,
			data: {'type':_type,'user':_user},
			success: function(JSON){
				width_array = JSON[1];
				zoom_levels = JSON[0];				
			}
		});
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_imageinfo.php",
			async: false,
			data: {"user":_user},
			success: function(JSON){
				imageInfo = JSON;
			}
		});
		_maxZoom = width_array[0].length-1;
		_zoomLevelMax = width_array[0].length-1;
		//console.log(_zoomLevelMax);
		_minZoom=0;
		_defaultZoom =0;
		
		var zoomLevelStart = -1,
			iZoom = 0, iColumn = 0, iRow = 0,
				columns = -1, rows = -1;
        for(var hzoom = 0; hzoom < width_array.length; hzoom++){
			_tileZoomArray[hzoom] =[];
			for( iZoom = _zoomLevelMax;  iZoom >= _zoomLevelMin; iZoom-- ) {
				_tileZoomArray[hzoom][iZoom] = [];
			}
			for( iZoom = _zoomLevelMax;  iZoom >= _zoomLevelMin; iZoom-- ) {
				columns = mathCeil( width_array[hzoom][iZoom] / _tileSize );
				rows = mathCeil( width_array[hzoom][iZoom] / _tileSize );
				//console.log(width_array[hzoom][iZoom]);
				//console.log(_tileSize);
				//console.log(columns);
				//console.log(rows);
				if( zoomLevelStart === -1 && 
						width_array[hzoom][iZoom] <= _canvasWidth && width_array[horizontal_Level_Next][iZoom] <= _canvasHeight ) {
					// Largest image that fits inside canvas.
					zoomLevelStart = iZoom;
				}

				// Create array for tiles
				for( iColumn = 0; iColumn < columns; iColumn++ ) {
					_tileZoomArray[hzoom][iZoom][iColumn] = []; 
				}
				// Set defaults
				// TODO: Test width - possibly to short, maybe not including last tile width...
				_tileZoomArray[hzoom][iZoom][_aGetWidth] = width_array[hzoom][iZoom];
				//console.log(_tileZoomArray[hzoom][iZoom][_aGetWidth]);
				_tileZoomArray[hzoom][iZoom][_aGetHeight] = width_array[hzoom][iZoom];
				//console(_tileZoomArray[hzoom][iZoom][_aGetHeight]);
				for( iColumn = 0; iColumn < columns; iColumn++ ) {
				
					for( iRow = 0; iRow < rows; iRow++ ) {
					
						_tileZoomArray[hzoom][iZoom][iColumn][iRow] = [];
						
						_tileZoomArray[hzoom][iZoom][iColumn][iRow][_aGetTile] = NULL;
						_tileZoomArray[hzoom][iZoom][iColumn][iRow][_aGetWaiting] = FALSE;
					}
				}
				
				//width_array[3][iZoom] /= 2;
				//width_array[3][iZoom] /= 2;
				//alert(width_array[3]);
				//alert(width_array[3][iZoom]);
			}
			//console.log(_tileZoomArray);
		}
		//console.log(_tileZoomArray);
		if( _defaultZoom === UNDEFINED ) {
			_defaultZoom = zoomLevelStart;
		}
		//_zoomLevelLast = _defaultZoom;

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
		if(_zoomLevel < width_array[horizontal_Level_Next].length -1){
			_zoomLevelLast = _zoomLevel +1;
			//console.log('breaks here')
		}else{
			_zoomLevelLast = width_array[horizontal_Level_Next].length -1;
		}
		var tempval = -1;
		//console.log(_tileZoomArray.length);
		/* for (var i =0; i < _tileZoomArray.length; i++){
			if(_tileZoomArray[i][0][_aGetWidth] < _canvasWidth){
				tempval = i;
				
			}
			//console.log(i);
			//console.log(_tileZoomArray[i][0][_aGetWidth]);
		}
		if (tempval == -1){
			horizontal_Level_Next = 0
		}else{
			horizontal_Level_Next = tempval;
		}*/
		//cons ole.log(_zoomLevelLast);
		//console.log(horizontal_Level_Next);
		//
		// Initial tile load
		//
		var imageList = [],imageId = 0;
		columns = _tileZoomArray[horizontal_Level_Next][0].length;
		rows = columns;
		for( iColumn = 0; iColumn < columns; iColumn++ ) {
		
			for( iRow = 0; iRow < rows; iRow++ ) {
				imageList.push( { "id" : imageId++, "file": getTileFile( _zoomLevelLast, iColumn, iRow  ) } );
			}
		}
		//console.log(imageList);
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "XML-Handlers/Load_LayerXML.php",
			async: false,
			data: {'Vzoom': _zoomLevelLast, 'type':_type, 'Hzoom': zoom_levels[horizontal_Level_Next], 'users':_user},
			success: function(JSON){
				images = JSON;
				
			}
		});
		
		
		imageList.push( { "id" : imageId, "file": getTileFile( 0, 0, 0  ) } );
		_imageLoader = new ImageLoader( {
			"images": imageList,
			"onAllLoaded":function() { initialTilesLoaded(); },
		} );
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
				//console(JSON);
			}
		});
		if(document.getElementById("hiddentype").value=="Time"){displayColours()};
		
        counter++;
	}());
}
				
