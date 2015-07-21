
	function touchDown( event ) {
		
		event.preventDefault();
		_mouseIsDown = TRUE;
		_mouseLeftWhileDown = FALSE;

		_mouseDownX = touchPosX(event);
		_mouseDownY = touchPosY(event);

		_mouseMoveX = _mouseDownX;
		_mouseMoveY = _mouseDownY;
	}

	function touchUp( event ) {
		
		var tolerence = 50;
		_mouseIsDown = FALSE;
		_mouseLeftWhileDown = FALSE;

		_mouseX = touchPosX(event);
		_mouseY = touchPosY(event);

		if( _mouseX >= _mouseDownX - tolerence && _mouseX <= _mouseDownX + tolerence &&
				_mouseY >= _mouseDownY - tolerence && _mouseY <= _mouseDownY + tolerence )
				//_mouseY === _mouseDownY )
		{
			// Didn't drag so assume a click.
			zoomInMouse();
		}
	}

	function touchMove(event) {
		event.preventDefault();
		event.stopPropagation();
		_mouseX = touchPosX(event);
		_mouseY = touchPosY(event);

		if( _mouseIsDown )
		{
			var newOffsetX = _offsetX + (_mouseX - _mouseMoveX),
				newOffsetY = _offsetY + (_mouseY - _mouseMoveY);

			calculateNeededTiles( _zoomLevel, newOffsetX, newOffsetY );

			_mouseMoveX = _mouseX;
			_mouseMoveY = _mouseY;

			_offsetX = newOffsetX;
			_offsetY = newOffsetY;

			requestPaint();
		}
	}

	function gestureEnd(event) {
		_lastscale = 1.0;
	}

	function gestureChange(event) {
		var scale = event.scale;
		event.preventDefault();
		
		if (scale < 0.75*_lastscale) {
			zoomOutMouse();
			_lastscale = scale;
		}
		
		if (scale > 1.25*_lastscale) {
			zoomInMouse();
			_lastscale = scale;
		}
	}
	
		// touchend populates changedTouches instead of targetTouches
	function touchPosX( event ) {
		// Get the mouse position relative to the canvas element.
		var x = 0;
		if (event.targetTouches[0]) {
			x = event.targetTouches[0].pageX - _canvas.offsetLeft;
		} else {
			x = event.changedTouches[0].pageX - _canvas.offsetLeft;
		}
		return x;
	}

	function touchPosY( event ) {
		var y = 0;
		if (event.targetTouches[0]) {
			y = event.targetTouches[0].pageY - _canvas.offsetTop;
		} else {
			y = event.changedTouches[0].pageY - _canvas.offsetTop;
		}
		return y;
	}