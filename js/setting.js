	function setMapTypeControl(){
		var val = $("#settingMapControlType").val().toUpperCase();
		var position = $('#settingMapControlTypePosition').val();
		map.setOptions("NONE"==val?{mapTypeControl:false}:{mapTypeControl:true,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle[val], position: google.maps.ControlPosition[position]}})
	}
	
	function mapSizeWidthValidation(){
		var val = $("#mapWidth").val();
		val = parseInt(val);
		if (isNaN(val)){
			a = 100;	
		}
		$("#mapWidth").val(val);
		setMapSize();
	}
	
	function mapSizeHeightValidation(){
		var height = $("#mapHeight").val();
		height = parseInt(height);
		if (isNaN(height)){
			height = 100;	
		}
		if (height > 1000){
			height = 1000;
		}
		$("#mapHeight").val(height);
		setMapSize();
	}
	
	function setMapSize(){
		var mapWidthUnit = $('input[name="mapWidthUnit"]:checked').val();
		var width = $("#mapWidth").val();
		if ((mapWidthUnit == '%') && (width > 100)){
			width = 100;
			$("#mapWidth").val(width);
		}else if ((mapWidthUnit == 'px') && (width > 1921)){
			width = 1920;
			$("#mapWidth").val(width);
		}else if ((mapWidthUnit == 'px') && (width < 100)){
			width = 100;
			$("#mapWidth").val(width);
		}else if ((mapWidthUnit == '%') && (width < 10)){
			width = 10;
			$("#mapWidth").val(width);
		}

		var mapHeightUnit = $('input[name="mapHeightUnit"]:checked').val();
		var height = $("#mapHeight").val();

		$("#map").css({width:width+mapWidthUnit, height:height+'px'})
		google.maps.event.trigger(map, 'resize');

	}

	function changeMapType(id){
		selectedMapType = id;
		var settingMapType = {
			'SATELLITE':google.maps.MapTypeId.SATELLITE,
			'HYBRID':google.maps.MapTypeId.HYBRID,
			'TERRAIN':google.maps.MapTypeId.TERRAIN,
			'ROADMAP':google.maps.MapTypeId.ROADMAP
		};
		map.setMapTypeId(settingMapType[id]);
	}
	
	function changeZoom(zoomValue){
		map.setZoom(parseInt (zoomValue));
	}

	function setPanControl(){
		var val = $("#settingPanControl").val();
		map.setOptions("none"===val?{panControl:!1}:{panControl:!0})
	}

	function setDoubleClickZoom(){
		var val = $("#settingDoubleClickZoom").val();
		map.setOptions("none"===val?{disableDoubleClickZoom:!0}:{disableDoubleClickZoom:!1})
	}
	
	function setMouseScroll(){
		var val = $("#settingDoubleClickZoom").val();
		map.setOptions("none"===val?{disableDoubleClickZoom:!0}:{disableDoubleClickZoom:!1})
	}
	
	function setMouseScroll(){
		var val = $("#settingMouseScroll").val();
		map.setOptions("none"===val?{scrollwheel:!1}:{scrollwheel:!0})
	}
	
	function updateStreetView(){
		var val = $("#settingStreetView").val();
		var position = $('#settingStreetViewPosition').val();
		map.setOptions("NONE"==val?{streetViewControl:!1}:{streetViewControl:!0,streetViewControlOptions:{position: google.maps.ControlPosition[position]}})
	}
	
	function setDraggable(){
		var val = $("#settingDraggableMap").val();
		map.setOptions("none"==val?{draggable:!1}:{draggable:!0});
	}

	function set_map_size(){
		var mapWidth = $("#mapWidth").val()
		var unitPxPercent = $('input[name="mapWidthUnit"]:checked').val();
		var mapHeight = $("#mapHeight").val();
		$("#map").css({width:mapWidth+unitPxPercent,height:mapHeight})
	}
	
	function changeZoomChange(){
		
		var val = $("#settingZoomControl").val().toUpperCase();
		var position = $('#settingZoomControlPosition').val();
		map.setOptions("NONE"==val?{zoomControl:!1}:{zoomControl:!0,zoomControlOptions:{style:google.maps.ZoomControlStyle[val],position: google.maps.ControlPosition[position]}})
	}
	
	function set_map_type_control(){
		var val = $("#gmb_map_type_control").val().toUpperCase();
		map.setOptions("NONE"==val?{mapTypeControl:!1}:{mapTypeControl:!0,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle[val]}})
	}