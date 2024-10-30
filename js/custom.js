jQuery(window).load(function(){
	jQuery('#status,#preloader').fadeOut();
})

jQuery(document).ready(function(e) {
	init();
	jQuery(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});


	jQuery(".show-map").show();
	jQuery('body').css('height', jQuery(window).height());

	jQuery('.search-btn a').click(function(e) {
		jQuery('.sreach-wrp').toggleClass('active');
	});

	var placeGeoSearch = new google.maps.places.Autocomplete(document.getElementById('geoSearch'));
	google.maps.event.addListener(placeGeoSearch, 'place_changed', function (){
		var place = placeGeoSearch.getPlace();
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);
		}
		jQuery('.sreach-wrp').toggleClass('active');
	});
	
	google.maps.event.addListener(placeGeoSearch, 'place_changed', function (){
		var place = placeGeoSearch.getPlace();
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);
		}
		jQuery('.sreach-wrp').toggleClass('active');
	});
	
	jQuery(".delete-all-styler").click(function(e) {
		if (confirm("Do you wish to remove all the styler's layers ?")){
			selectedMapType = 'ROADMAP';
			jQuery("#settingMapType").val(selectedMapType);
			jQuery('#styler-section').find('.clone-container').html('');
			jQuery('#styler-section .option-bar').addClass('display-none');
			jQuery('#styler-section .getting-started-outer').removeClass('display-none');
			loadStyler();
		}
    });
	
	jQuery('.showSearchField').click(function(e) {
  		jQuery(this).next().slideToggle('slow');
    });
	
	jQuery('.show-hide-buttons').click(function(e) {
		e.preventDefault();
		if (jQuery(this).parents('.styler-container').find('.features-outer').css('display') == 'none') {
			jQuery(this).parents('.clone-container').find('.show-hide-buttons').addClass('closed');
			jQuery(this).parents('.clone-container').find('.features-outer').hide();
			jQuery(this).removeClass('closed');
		}else{
			jQuery(this).addClass('closed');
		}		
  		jQuery(this).parents('.styler-container').find('.features-outer').slideToggle('slow');
    });
	
	jQuery('.delete-styler').click(function(e) {
		if (confirm("Do you wish to remove styler layer ?")){
			e.preventDefault();
			jQuery(this).parents('.styler-container').fadeOut('slow', function(){
				jQuery(this).remove();
	
				if (jQuery('#styler-section').find('.styler-container').length > 1){
					jQuery("#styler-section .add-style-arrow").hide();
				}else{
					jQuery("#styler-section .add-style-arrow").show();
				}
	
				jQuery('#styler-section').find('.styler-container').each(function(index, element) {
					var startingzero = '';
					index = index + 1;
					if(index <= 9)
						startingzero = '0';
					jQuery(this).find('.number').text(startingzero + (index));
					jQuery(this).attr('data-correspondingid', '#mapStylePanel'+index);
				});
				loadStyler();
			});
		}
	});
		
	jQuery('.delet').click(function(e) {
		if (confirm("Do you wish to remove marker ?")){
			e.preventDefault();
			var markerId = parseInt(jQuery(this).parents('.styler-container').find('.number').html());
			if ((typeof allMarkers[markerId]) !== 'undefined'){
				allMarkers[markerId]['marker'].setMap(null);
			}
			jQuery(this).parents('.styler-container').fadeOut('slow', function(){
				jQuery(this).remove();
				arrowAddMarker();
			});
			checkAllMarkersDeleted();
		}else{
			return false;	
		}
    });
	
	jQuery('.delete-all-markers').click(function(e) {
		if (confirm("Do you wish to remove all the markers ?")){
			e.preventDefault();
			jQuery(this).parent().parent().parent().parent().find('.number').each(function( index ) {
				if (index != 0){
					var markerId = jQuery(this).html();
					if (markerId != ''){
						markerId = parseInt(markerId);
						if ((typeof allMarkers[markerId]) !== 'undefined'){
							allMarkers[markerId]['marker'].setMap(null);
						}
					}
					jQuery(this).parents('.styler-container').fadeOut('slow', function(){
						jQuery(this).remove();
						checkAllMarkersDeleted();
						arrowAddMarker();
					});				
				}
			});
		}
    });	

	function checkAllMarkersDeleted(){
		var totalMarker = jQuery("#clone-container-marker").find(".styler-container").length;
		if (totalMarker == 1){
			jQuery('#marker-section').find('.option-bar').addClass('display-none');
			jQuery('#marker-section').find('.getting-started-outer').removeClass('display-none');			
		}
	}
	
	function arrowAddMarker(){
		if (jQuery('#marker-section').find('.styler-container').length > 2){
			jQuery("#marker-section .add-style-arrow").hide();
		}else{
			jQuery("#marker-section .add-style-arrow").show();
		}
	}
	
	function closeAllMarkerPanel(){
		jQuery("#marker-section .clone-container").find('.show-hide-buttons').addClass('closed');
		jQuery("#marker-section .clone-container").find('.features-outer').hide();
	}

	jQuery('#marker-section .make-clone').click(function(e) {
		if (jQuery("#marker-section .clone-container").find('.number').length == 0){
			var count = 1;
			jQuery('#marker-section').animate({scrollTop:jQuery(document).height()}, 'slow');
			if (jQuery("#marker-section .clone-container").find('.number').length > 0){
				count = parseInt(jQuery("#marker-section .clone-container").find('.number:last').text());
				count = count+1;
				closeAllMarkerPanel();
			}
			var startingzero = '';
			if(count <= 9){
				startingzero = '0';
			}
			var searchLocaTion = count;
			jQuery(this).parents('.sections').find('.clone .styler-container').clone(true).appendTo(jQuery(this).parents('.sections').find('.clone-container')).find('.searchlocation').attr('id', 'searchlocation'+count).parent().closest('.styler-container').find('.number:last').text(startingzero+(count)).closest('.styler-container').find('.longitude:first').attr('id', 'longitude'+count).closest('.styler-container').find('.latitude:first').attr('id', 'latitude'+count).closest('.styler-container').find('.primary-marker:first').attr('value', count).closest('.styler-container').find('.section-link').attr('data-label-class', count).closest('.styler-container').attr('id', 'marker_'+count);
	
			if (jQuery("#marker-section .clone-container").find('.styler-container').length == 1){
				var count = parseInt(jQuery("#marker-section .clone-container .styler-container:first").find('.primary-marker').attr('checked', 'checked'));
			}
			
			var objDiv = document.getElementById("outerScroll2");
			objDiv.scrollTop = objDiv.scrollHeight;
			jQuery('#marker-section .option-bar').removeClass('display-none');
			jQuery('#marker-section .getting-started-outer').addClass('display-none');
			arrowAddMarker();
			var places = new google.maps.places.Autocomplete(document.getElementById('searchlocation'+searchLocaTion));
			google.maps.event.addListener(places, 'place_changed', function () {
				var place = places.getPlace();
				var address = place.adr_address;
				var latitude = place.geometry.location.lat();
				var longitude = place.geometry.location.lng();
				
				var myLatlng = new google.maps.LatLng(latitude, longitude);
				jQuery("#latitude"+searchLocaTion).val(latitude.toFixed(4));
				jQuery("#longitude"+searchLocaTion).val(longitude.toFixed(4));
			});
		}else{
			buyMsg();	
		}
    });
	
	jQuery('#styler-section .make-clone').click(function(e) {
		jQuery('#styler-section').animate({scrollTop:jQuery(document).height()}, 'slow');
		var not_styler = jQuery(this).parents('.sections').find('.clone').hasClass('not-styler');
		jQuery(this).parents('.sections').find('.getting-started-outer').addClass('display-none');
		var count = parseInt(jQuery('#styler-section .styler-container').length) + 1;
		var startingzero = '';
		if (count){
			jQuery("#styler-section .clone-container").find('.show-hide-buttons').addClass('closed');
			jQuery("#styler-section .clone-container").find('.features-outer').hide();
		}
		if(count <= 9){
			startingzero = '0';
		}
		jQuery('#clone').find('.styler-container').attr('data-correspondingid', '#mapStylePanel'+(count-1)).clone(true).appendTo('#clone-container').find('.number:last').text(startingzero+count);
		if (jQuery('#styler-section').find('.styler-container').length > 1){
			jQuery("#styler-section .add-style-arrow").hide();
		}	
		jQuery('#styler-section .option-bar').removeClass('display-none');
		jQuery('#styler-section .getting-started-outer').addClass('display-none');
    });
		
	jQuery('.range').change(function(e) {
		if (jQuery(this).hasClass('weight-range')){
			var weight = parseInt(jQuery(this).val())/10;
			jQuery(this).next().html(weight.toFixed(1))
		}else{
	        jQuery(this).next().html(jQuery(this).val())
		}
		var correspondingid = jQuery(this).data('correspondingid');
		jQuery(correspondingid).val(jQuery(this).val()).trigger('change');
    });
	
	jQuery('.range').mouseover(function(e) {
        jQuery(this).parents('.styling-inner').find('.colorpicker').removeClass('colorchanged');
    });
	
	jQuery('.colorpicker').change(function(e) {
		jQuery(this).addClass('colorchanged');
        jQuery(this).prev().html(jQuery(this).val());
		var elem = jQuery(this).prev();
		var correspondingid = jQuery(this).data('correspondingid');
		
		if(jQuery(this).hasClass('changehex')) {
			jQuery(correspondingid).next().val(jQuery(this).val()).trigger('onkeyup');
			var hex = jQuery(this).val();
			if (hexre.test(hex)) {
			  
			  if (hex.length == 6) {
				hex = '#' + hex;
			  }
		  
			  var rgb = getRGBFromColorCode(hex);
			  jQuery(this).parents('.styling-inner').find('.redSlider').val(rgb[0]).trigger('change');
			  jQuery(this).parents('.styling-inner').find('.greenSlider').val(rgb[1]).trigger('change');
			  jQuery(this).parents('.styling-inner').find('.blueSlider').val(rgb[2]).trigger('change');
			}
		} else {
			jQuery(correspondingid).next().val(jQuery(this).val());
		}
    });
	
	jQuery('.outerScroll').enscroll({
		showOnHover: true,
		verticalTrackClass: 'track',
		verticalHandleClass: 'handle'
	});
	
	jQuery('.visibilities').change(function(e) {
        jQuery(this).parents('.styling-head').find('input:checkbox').prop('checked', true);
    });
	
	jQuery('.panel-tabs').find('li').click(function(e) {
		var related_section = jQuery(this).data('related-section');
		if(!jQuery('.rhs-pannel-outer').hasClass('opened')){
			jQuery('.rhs-pannel-outer').animate({right: 0},'slow').addClass('opened');
			jQuery('.menu-icon').addClass('transformed');
		}
		jQuery('.menu-bar').find('li').removeClass('menu-bar-li-current');
		jQuery(this).addClass('menu-bar-li-current');
		jQuery('.sections').fadeOut('slow');
		jQuery(related_section).fadeIn('slow');
		jQuery('.sreach-wrp').removeClass('active');
    });
	
	jQuery('.panel-openClose li.menu-btn').click(function(e) {
		jQuery('.panel-tabs').find('li').removeClass('menu-bar-li-current');	
		if(!jQuery('.rhs-pannel-outer').hasClass('opened')){
			jQuery('.rhs-pannel-outer').animate({right: 0},'slow').addClass('opened');
			jQuery('.menu-icon').addClass('transformed');
		}else if(jQuery('.rhs-pannel-outer').hasClass('opened')){
			jQuery('.rhs-pannel-outer').animate({right: '-'+298+'px'},'slow').removeClass('opened');
			jQuery('.menu-icon').removeClass('transformed');
		}	
    });
	
	jQuery('.tabs').find('li').click(function(e) {
		changeTab(this);
    });

	jQuery('.info-window-tabs').find('li').click(function(e) {
		changeInfoWindowTab(this);
    });

	function changeInfoWindowTab(elem) {
		var divIndex = jQuery(elem).index();
		jQuery(elem).parents('ul').find('li').removeClass('current-tab');
		jQuery(elem).addClass('current-tab');	
		jQuery(elem).parents('ul').next('.tabs-set').find(".tab-content").hide('slow');
		jQuery(elem).parents('ul').next('.tabs-set').find(".tab-content:eq("+divIndex+")").show('slow');
	}	
	
	var min_val = 1;
	var max_val = 99;
	jQuery('.inc').click(function(e) {
		var input = jQuery(this).parents('.inc-dec-wrap').find('input[type="text"]');
		if(parseInt(input.val()) < max_val)
        	input.val(parseInt(input.val())+1);
    });
	
	jQuery('.dec').click(function(e) {
		var input = jQuery(this).parents('.inc-dec-wrap').find('input[type="text"]');
        if(parseInt(input.val()) > min_val)
			input.val(parseInt(input.val())-1);
    });
	
	jQuery('.status').click(function(e) {
		if(jQuery(this).is(':checked')){
			jQuery(this).next().val(1);
		}else{ 
			jQuery(this).next().val(0);
		}
        jQuery(this).parents('.status-parent').next().slideToggle('slow');
    });
	
	jQuery('.font-icons').find('i').click(function(e) {
        jQuery(this).addClass('selected-icon');
    });
	
	jQuery('.marker-type-svg i').click(function(e) {
		jQuery(this).parent().find('i').removeClass('selected-icon');
		var str = jQuery(this).attr('class');
		jQuery(this).closest('.features-inner').find(".marker-ICON").val(str);
		jQuery(this).addClass('selected-icon');
    });
	
	jQuery(".marker-png i").click(function(e) {
		buyMsg();
	//	jQuery(this).parent().find('i').removeClass('selected-icon');
	//	jQuery(this).addClass('selected-icon');
	//	jQuery(this).closest('.features-inner').find(".marker-ICON").val(jQuery(this).find('img:first').attr('alt'));
    });

	jQuery('.styler-color').minicolors({
		control: jQuery(this).attr('data-control') || 'hue',
		inline: jQuery(this).attr('data-inline') === 'true',
		letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
		opacity: jQuery(this).attr('data-opacity'),
		position: jQuery(this).attr('data-position') || 'bottom right',
		change: function(hex, opacity) {
			jQuery(this).parent().parent().prev().find('.ch-style-color').val(1);
			jQuery(this).parent().parent().prev().find('.ch-styler-color').attr("checked", "checked");
			if (jQuery('#loadingFeatureMap').val() == ''){
				setCheckbox(this, 'parents', '.styling-head');
			}
		},
		theme: 'bootstrap'
	});
	
	jQuery('.hue-color').minicolors({
		control: jQuery(this).attr('data-control') || 'hue',
		inline: jQuery(this).attr('data-inline') === 'true',
		letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
		opacity: jQuery(this).attr('data-opacity'),
		position: jQuery(this).attr('data-position') || 'bottom right',
		change: function(hex, opacity) {
			if (jQuery('#loadingFeatureMap').val() == ''){
				setCheckbox(this, 'parents', '.styling-head');
			}
		},
		theme: 'bootstrap'
	});
	
	jQuery('.demo').minicolors({
		control: jQuery(this).attr('data-control') || 'hue',
		defaultValue: '#7e00ff',
		inline: jQuery(this).attr('data-inline') === 'true',
		letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
		opacity: jQuery(this).attr('data-opacity'),
		position: jQuery(this).attr('data-position') || 'bottom left',
		change: function(hex, opacity) {
			if( !hex ) return;
			if( opacity ) hex += ', ' + opacity;
			if( typeof console === 'object' ) {
			}
		},
		theme: 'bootstrap'
	});
	

		/* Restricted Keyboard Enter */
		jQuery(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
		
		/* Executing Marker Parameters*/
		jQuery('.droppin').click(function(e) {
			
			/* Initializing the Updated Parameters of Markers */
			var parent = jQuery(this).parents('.styler-container');
			var lat = parseFloat(parent.find('.latitude').val());
			var long = parseFloat(parent.find('.longitude').val());
			var getLatLong = 0;
			var scale = parseFloat('0.'+parent.find('.fill-color input').val());
			var markerType = 'svg';//parent.find('.marker-type').first().val();
			var markerSharp = parent.find('.marker-ICON').first().val();
			var label = parent.find('.font-icons i.selected-icon').data('label-class');
			var showInfo = parent.find('.show-info-window').first().val();
			var makerTitle = parent.find('.marker-title').val();
			var location = new google.maps.LatLng(lat,long);
			
			var markerStrokeColor = parent.find('.marker-stroke-color').val();
			var markerStrokeWeight = parent.find('.marker-stroke-weight').val();			
			var fillColor = parent.find('.input-color').val();
			var imageTitle2 = parent.find('.image-title').val();
			var imageUrl2 = parent.find('.image-url').val();
			var bodyTextH1 = parent.find('.body-text-h1').val();
			var bodyTextP1 = parent.find('.body-text-p1').val();
			var invalidPhone = parent.find('.body-text-p1').hasClass('invalid-phone');
			var bodyTextP2 = parent.find('.body-text-p2').val();
			var invalidEmail = parent.find('.body-text-p2').hasClass('invalid-email');
			var bodyTextURL = parent.find('.body-text-url').val();
			var markerVisible = parent.find('.marker-visible:checked').length;
			var primaryMarker = parent.find('.primary-marker:checked').length;
			
			/* Combining Parameters with Data Object */
			var data = {};
			data.title = makerTitle;
			data.fillColor = fillColor;
			data.fillOpacity = 1;
			data.strokeColor = markerStrokeColor;
			data.strokeWeight = parseInt(markerStrokeWeight);
			data.scale = scale;
			data.label = label;
			data.imageTitle = imageTitle2;
			data.imageUrl = imageUrl2;
			data.showInfoWindow = showInfo;
			data.markerObj = parent;
			data.bodyTextH1 = bodyTextH1;
			data.bodyTextP1 = bodyTextP1;
			data.invalidPhone = invalidPhone;
			data.bodyTextP2 = bodyTextP2;
			data.invalidEmail = invalidEmail;
			data.bodyTextURL = bodyTextURL;
			data.markerSharp = markerSharp;
			data.markerType = markerType;
			data.markerVisible = markerVisible;
			data.primaryMarker = primaryMarker;
			var markerStrokeColor = parent.find('.marker-stroke-color').val();
			var markerStrokeWeight = parent.find('.marker-stroke-weight').val();			

			data.labelTextolor = parent.find('.label-text-color').val();
			data.labelStrokeColor = parent.find('.label-stroke-color').val();
			data.labelStrokeWeight = parseInt(parent.find('.label-stroke-weight').val());
			data.latitude = parseFloat(parent.find('.latitude').val());
			data.longitude = parseFloat(parent.find('.longitude').val());
			
			/* Saving or Updating */
			if (jQuery(this).html() == 'Drop Pin'){
				data.markerNumber = parseInt(parent.find('.number').html());
				parent.find('.droppin').text('Update');
				placeMarker(location, data);
			}else{
				var markerIdd = parseInt(jQuery(this).parents('.styler-container').find('.number').html());		
				parent.find('.droppin').text('Update');
				data.markerId = markerIdd;
				var markerObj = editMarker(location, data);
			}
		});
		
		/*Loading the Selected Feature Map*/	
		jQuery('.featured-map-outer').find('li').click(function(e) {
			showBuyMsg = 0;
			var styles = jQuery(this).data('featured-style');
			if (jQuery(".featured-map-outer ul.clearfix li:first").data('featured-style') === styles){
				jQuery('#styler-section .option-bar').addClass('display-none');
				jQuery('#styler-section .getting-started-outer').removeClass('display-none');				
				jQuery('#styler-section').find('.clone-container').html('');
				map.set('styles', []);
			}else{
				jQuery("#styler-section .add-style-arrow").hide();
				jQuery('#styler-section .option-bar').removeClass('display-none');
				jQuery('#styler-section .getting-started-outer').addClass('display-none');
				map.set('styles', styles);
				jQuery("#featuredMap").val(jQuery(this).find('.featured-map').val());
				var styles = jQuery(this).data('featured-style');
				jQuery("#clone-container").html('');
				for(var i=0; i<styles.length ; i++){
					count = i+1;
					var startingzero = '';					
					if(count <= 9){
						startingzero = '0';
					}
					jQuery('#clone').find('.styler-container').attr('data-correspondingid', '#mapStylePanel'+(count-1)).clone(true).appendTo('#clone-container').find('.number:last').text(startingzero+(count));
					var last = jQuery('#clone-container').find('.number:last');				
				}
				var counting = 0;
			
				jQuery('#loadingFeatureMap').val(1);
				jQuery("#clone-container .styler-container").each(function() {
					var number = parseInt(jQuery(this).find(".number:first").text())-1;
					if (typeof styles[number].featureType !== 'undefined'){
						
						jQuery(this).find(".feature").val(styles[number].featureType);
						
						option = styles[number].featureType;
						if ((option !== 'all') && (option !== 'All')){
							option = jQuery(this).find("option[value='"+styles[number].featureType+"']").text();
							option = option.split("->")
							if (typeof option[0] !== 'undefined'){
								option = option[0];
							}else{
								option = '';
							}
						}else{
							option = 'All';
						}
						jQuery(this).find(".styler-title").html(option);
					}
					if (typeof (styles[number].elementType) !== 'undefined'){
						jQuery(this).find(".element").val(styles[number].elementType);
					}
					jQuery(this).find(".features-outer").css({'display':'none'});
					var visibility = '';
					var invertLightness = '';
					var stylerColor = '';
					var weight = 0;
					var hue = '';
					var saturation = 0;
					var lightness = 0;
					var gamma = 0;
					jQuery.each(styles[number].stylers, function( index, objStyler ) {
						if (typeof (objStyler.visibility) !== 'undefined'){
							visibility = objStyler.visibility;
						}
						if (typeof (objStyler.invert_lightness) !== 'undefined'){
							invertLightness = objStyler.invert_lightness;
						}
						if (typeof (objStyler.color) !== 'undefined'){
							stylerColor = objStyler.color;
						}
						if (typeof (objStyler.weight) !== 'undefined'){
							weight = parseFloat(objStyler.weight)*10;
						}
						if (typeof (objStyler.hue) !== 'undefined'){
							hue = objStyler.hue;
						}
						if (typeof (objStyler.saturation) !== 'undefined'){
							saturation = parseInt(objStyler.saturation);
						}
						if (typeof (objStyler.lightness) !== 'undefined'){
							lightness = parseInt(objStyler.lightness);
						}
						if (typeof (objStyler.gamma) !== 'undefined'){
							gamma = parseInt(objStyler.gamma);
						}
					});
	
					jQuery(this).find(".visibilities").val(visibility);				
					jQuery(this).find(".invert-lightness").val(invertLightness);				
					jQuery(this).find(".show-hide-buttons").addClass('closed');
					if (stylerColor){
						jQuery(this).find(".styler-color:first").minicolors('value',stylerColor);
						jQuery(this).find(".styler-color").prev().html(stylerColor);
						jQuery(this).find(".chkbx-color").attr('checked', true);
						jQuery(this).find(".chkbx-color").next().val(1);
					}else{
						jQuery(this).find(".chkbx-color").next().val(0);
					}
									
					if (weight != 0){			
						jQuery(this).find(".weight-range").val(weight);
						jQuery(this).find(".weight-range").next().html(weight);				
						jQuery(this).find(".chkbx-weight").attr('checked', true);
						
					}
					if (hue != ''){
						jQuery(this).find(".hue-color:first").minicolors('value',hue);
						jQuery(this).find(".hue-color").prev().html(hue);
						jQuery(this).find(".chkbx-hue").attr('checked', true);
						jQuery(this).find(".chkbx-hue").next().val(1);
					}else{
						jQuery(this).find(".chkbx-hue").next().val(0);
					}
					
					if (saturation != 0){
						jQuery(this).find(".saturation-range").val(saturation);				
						jQuery(this).find(".saturation-range").next().html(saturation);				
						jQuery(this).find(".chkbx-sat").attr('checked', true);
					}
					if (lightness != 0){
						jQuery(this).find(".lightness-range").val(lightness);				
						jQuery(this).find(".lightness-range").next().html(lightness);				
						jQuery(this).find(".chkbx-lightness").attr('checked', true);
					}
					if (gamma){
						jQuery(this).find(".gamma-range").val(gamma);				
						jQuery(this).find(".gamma-range").next().html(gamma);				
						jQuery(this).find(".chkbx-gamma").attr('checked', true);
					}
	
				});
			}
			jQuery('#loadingFeatureMap').val('');
			loadStyler();
			showBuyMsg = 1;
		});
	
	
});

var showBuyMsg = 1;

function buyMsg(){
	if (showBuyMsg == 1){
		showBuyMsg = 0;
		jQuery.msgBox({
			title:"You are using limited plugin version",
			content:"<h3>Upgrade To Full Features</h3><h4>Mapwiz Premium WP Plug-In</h4><div class='msgBoxButtons' id='msgBox1458678344038Buttons'><a href='http://www.mapwiz.io' style='color:#ffffff' target='_blank'>Buy Now</a></div>",
			type: "confirm",
			buttons: [ { value: "Cancel"}],
			timeOut:70,
			success: function (result) {
				showBuyMsg = 1
			},
			afterClose: function () {
				showBuyMsg = 1
			}
		});
	}
}

/* Post Form */
function saveMap(){alert('coming');
	jQuery("#frmMAP").submit();
}

function validateEmail(elem) {

	var emailReg = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?jQuery/);
	if( !emailReg.test( elem.val() ) ) {
		elem.addClass('invalid-email');
	}else{
		elem.removeClass('invalid-email');
	}
}

function validatePhone(elem) {

	var regEx = new RegExp(/^[0-9-+]+jQuery/);
  	if(!regEx.test(elem.val())) {
		elem.addClass('invalid-phone');
	}else{
		elem.removeClass('invalid-phone');
	}
}

function featureType(elem){
	var option = elem.val();
	option = elem.find("option[value='"+option+"']").text();
	option = option.split("->")
	
	if (typeof option[0] !== 'undefined'){
		option = option[0];
	}else{
		option = '';
	}
	elem.parents('.styler-container').find('.styler-title').html(option);	
	loadStyler();
}

/* Change Menu Tab */
function changeTab(elem) {
	var divIndex = jQuery(elem).index();
	jQuery(elem).parents('ul').find('li').removeClass('current-tab');
    jQuery(elem).addClass('current-tab');	
	jQuery(elem).parents('ul').next('.tabs-set').find(".main-tab-content").hide();
	jQuery(elem).parents('ul').next('.tabs-set').find(".main-tab-content:eq("+divIndex+")").show();
}

/* Auto Check the Checkbox*/
function setCheckbox(elem, type, selector) {
	var getSelector = '';
	if(type == 'parents')
		getSelector = jQuery(elem).parents(selector).find('input:checkbox');
	else 
		getSelector = jQuery(elem).parents(selector).prev().find('input:checkbox');
	
	getSelector.prop('checked', true);
	loadStyler();
}

function resetCorrespondingElement(elem, type, selector, val, elemType) {
	if ((elemType == 'range') && (jQuery(elem).parent().parent().find('.output-range').length > 1)){
		jQuery(elem).parent().parent().find('.output-range').html('');
	}
	var getSelector = '';
	if(type == 'parents')
		getSelector = jQuery(elem).parents(selector).find('input[type="'+elemType+'"]');
	else 
		getSelector = jQuery(elem).parents(selector).next().find('input[type="'+elemType+'"]');
	
	if(!jQuery(elem).is(':checked')) {
		getSelector.val(val);
		if(elemType == 'color')
			jQuery(elem).parents(selector).next().find('.output-color').text('');
			
	}
	loadStyler();
}

function triggerOperation(elem, event) {
	if(event == 'change') {
		var correspondingid = jQuery(elem).find('option:selected').data('correspondingid');
		if(jQuery(correspondingid).attr('type') == 'radio' || jQuery(correspondingid).attr('type') == 'radio') {
			jQuery(correspondingid).attr('checked', true).trigger('click');
		}
	} else {
		var correspondingid = jQuery(elem).data('correspondingid');
		jQuery(correspondingid).trigger('click');
	}
	loadStyler();
}

function triggerOperationSelect(elem) {
	var correspondingid = jQuery(elem).data('correspondingid');
	jQuery(correspondingid).val(jQuery(elem).val());
	jQuery(correspondingid).find('option:selected').attr('selected','selected').trigger('click');
	
	if(jQuery(elem).is('input[type="radio"]') && jQuery(elem).hasClass('radio'))
		switchLevel(elem);
	
}

function restoreToDefault() {
	jQuery('.styler-container').each(function(index, element) {
		if(jQuery(this).hasClass('selected-styler')) {
			resetFormElements(this);
			hideAllElements(this);
		}
	});
}

function resetFormElements(parent) {
	jQuery(parent).find('.feature-all').prop('checked', true).trigger('click');
	jQuery(parent).find('.set_invert_lightness').prop('checked', false);
	jQuery(parent).find('.chkbx-color').prop('checked', false);
	jQuery(parent).find('.redSlider, .greenSlider, .blueSlider').val('128');
	jQuery(parent).find('.chkbx-weight').prop('checked', false);
	jQuery(parent).find('.weightSlider, .satSlider, .lightSlider').val('0');
	jQuery(parent).find('.chkbx-hue').prop('checked', false);
	jQuery(parent).find('.chkbx-sat').prop('checked', false);
	jQuery(parent).find('.chkbx-lightness').prop('checked', false);
	jQuery(parent).find('.output-range').html('');
	jQuery(parent).find('.colorpicker').val('#ff0000');
	jQuery(parent).find('.output-color').text('');
	jQuery(parent).find('.chkbx-visibility').prop('checked', false);
	jQuery(parent).find('.visibilities').val(jQuery(".visibilities option:first").val());
}

function hideAllElements(parent) {
	if(parent == 'resetAll')
		parent = jQuery('#clone-container .styler-container');
	
	jQuery(parent).find('.styling-outer').slideUp('slow');
	jQuery(parent).find('.sub-features').slideUp('slow');
	jQuery(parent).find('.show-sub-features').slideUp('slow');
	jQuery(parent).find('.adv-show').text('Show');
	jQuery(parent).find('.advace-settings').slideUp('slow');
}

function styleRadioAndCheckbox() {
	jQuery('input:checkbox, input[type="radio"]').change(function(e) {
		if(jQuery(this).is('input[type="radio"]')) {
			if(jQuery(this).is(':checked')) {
				jQuery(this).parents('ul').find('.checkbox-wrap').css('background-position', 'left');
				jQuery(this).parent().css('background-position', 'right');
			}
			else 
				jQuery(this).parent().css('background-position', 'left');
		} else {
			if(jQuery(this).is(':checked'))
				jQuery(this).parent().css('background-position', 'right');
			else 
				jQuery(this).parent().css('background-position', 'left');
		}
	});
}

/* Marker Functions */

	/* Updating Marker Type PNG Shapes */
	function markerType(elem){
		var mainTab = elem.closest('.features-inner');
		mainTab.find("div[class*='marker-type-']").addClass('display-no-imp');
		mainTab.find('.marker-type-'+elem.val()).removeClass('display-no-imp');
		var markerTabe = mainTab.find('.marker-type-'+elem.val());
		markerTabe.removeClass('display-no-imp');		
		if (markerTabe.find('.selected-icon').length > 0){
			var selectedMarker = markerTabe.find('i.selected-icon');
			elem.closest('.features-inner').find(".marker-ICON").val(selectedMarker.data('label-class'));
		}else{
		//	var firstMarker = markerTabe.find('i:first');
		//	firstMarker.addClass('selected-icon');
		//	elem.closest('.features-inner').find(".marker-ICON").val(firstMarker.data('label-class'));
		}
	}

	/* Validation for Latitude and Longitude value */
	function validationLatitudeLongitude(elem){

		var val = elem.val();
		val = jQuery.trim(val)
		if ((val == 0) || (val == '') || (val == '0') || (val == null)){
			val = 0;
		}else{
			val = parseFloat(val);
			if (isNaN(val)){
				val = 0;
			}
		}
		elem.val(val);

		/* Showing default place holder in search field */
		elem.closest('.styler-container').find('.searchlocation').val('');
	}	
	
	/* Loading Updated Styler Changes*/
	function loadStyler(){
		buyMsg();
	}

	/*Initializing the Default Map*/
	function initialize() {
		
		/* Zoom Functions */		
		google.maps.event.addListener(map, 'maptypeid_changed', function() {
			var map_type_id = map.getMapTypeId();
			jQuery('#settingMapType').val(map_type_id.toUpperCase());
		});
		
		google.maps.event.addListener(map, 'zoom_changed', function() {
			jQuery('#settingZoom').val(map.getZoom());
		});
		/* End Zoom Functions */
	}
	
	/* Get Geo Location */
	function getGeoLocation(getcoordsObj) {
	//	getcoordsObj.append("<img src='"+pathICON+"images/loader2.gif' />");
		getcoords = parseInt(getcoordsObj.data('label-class'));
		// Try HTML5 geolocation
		if(navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(function(position) {
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			jQuery("#latitude"+getcoords).val(position.coords.latitude.toFixed(7));
			jQuery("#longitude"+getcoords).val(position.coords.longitude.toFixed(7));
		//	getcoordsObj.find('img').remove();
		  }, function() {
			handleNoGeolocation(true);
		  });
		} else {
		  handleNoGeolocation(false);
		}
	}
	
	function gotoLocation(){
			
		var lat = parseFloat(jQuery("#lat").val());
		var lng = parseFloat(jQuery("#lng").val());

		var pos = new google.maps.LatLng(lat, lng);
		
		var infowindow = new google.maps.InfoWindow({
			map: map,
			position: pos,
			content: '<span style="color:#000000">Latitude : '+lat.toFixed(5)+', Longitude : '+lng.toFixed(5)+'</span>'
		});
  
		map.setCenter(pos);
		map.setZoom(15);
		jQuery('#zoomlevel').text(map.getZoom(15));			
	}
	
    function getCoords(ObjId) {
		if (ObjId !== ''){
			ObjId.find('img').remove();
			ObjId.append("<img src='"+pathICON+"images/loader2.gif' />");
		}
		 if(navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
			  var pos = new google.maps.LatLng(position.coords.latitude,
											   position.coords.longitude);
				if (ObjId == ''){
			  		jQuery('#lat').val(position.coords.latitude.toFixed(5));
			  		jQuery('#lng').val(position.coords.longitude.toFixed(5));
				}else{
					ObjId.parent().next().find(".latitude").val(position.coords.latitude.toFixed(5))
					ObjId.parent().next().find(".longitude").val(position.coords.longitude.toFixed(5))
					ObjId.find('img').remove();
				}
			  
			}, function() {
			  handleNoGeolocation(true);
			});
		 } else {
			// Browser doesn't support Geolocation
			handleNoGeolocation(false);
		 }
	}
	
	/* handle no geo location */
	function handleNoGeolocation(errorFlag) {}
	/* End handle no geo location */
	
	/* get lat and long current geo location (address) */
	function codeAddress(cAddress) {
	  var address = cAddress;
	  geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
		  map.setCenter(results[0].geometry.location);
		  var marker = new google.maps.Marker({
			  map: map,
			  position: results[0].geometry.location
		  });
		} else {
		  alert('Geocode was not successful for the following reason: ' + status);
		}
	  });
	}
	
	function placeMarker(location, data) {	
		var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
		var myOptions = {
          zoom: 10,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
   		var labelTitle = data.title;
        if (!data.markerVisible){
			labelTitle = '';
		} else if (labelTitle == ''){
			labelTitle = 'Label Title';
		}

        var mapLabel = new MapLabel({
          text: labelTitle,
          position: new google.maps.LatLng(data.latitude, data.longitude),
          map: map,
		  fontFamily: 'arial',
		  title: ''		  
        });
        mapLabel.set('position', myLatlng);
		mapLabel.set('fontSize', '18');
		mapLabel.set('fontColor', data.labelTextolor);
		mapLabel.set('strokeWeight', data.labelStrokeWeight);
		mapLabel.set('strokeColor', data.labelStrokeColor);
		mapLabel.set('fontFamily', 'sans-serif');

        var marker = new google.maps.Marker();
        marker.bindTo('map', mapLabel);
        marker.bindTo('position', mapLabel);
        marker.setDraggable(true);
        marker.setAnimation(google.maps.Animation.DROP);
		if (data.markerType == 'svg'){
			marker.setIcon({
				path: markerSharps[data.markerSharp].marker,
				fillColor: data.fillColor,
				fillOpacity: data.fillOpacity,
				strokeColor: data.strokeColor,
				strokeWeight: data.strokeWeight,
				scale: markerSharps[data.markerSharp].scale
			});
		}else{
			marker.setIcon(plungInPath+'markers/'+data.markerType+'/'+data.markerSharp);
		}
				
		var contentText = '';
		if (data.showInfoWindow == true){
			contentText = infoWindow(data);
		}
		var infowindow = new google.maps.InfoWindow({
            content: contentText
		});
		if ((data.showInfoWindow == true) && (contentText != '')){
			infowindow.open(map,marker);
		}
		loadInfoWindow(marker, infowindow, data.markerNumber);
		allMarkers[data.markerNumber] = [];
		allMarkers[data.markerNumber]['marker'] = marker;
		allMarkers[data.markerNumber]['info_window'] = infowindow;
		allMarkers[data.markerNumber]['label'] = mapLabel;		
		allMarkers[data.markerNumber]['showInfoWindow'] = data.showInfoWindow;
		
		if (parseInt(data.primaryMarker)){
			map.setCenter(myLatlng);
		}
	}
	
	function loadInfoWindow(marker, infowindow, markerNumber) {
		
		marker.addListener('click', function() {
			if (parseInt(allMarkers[markerNumber]['showInfoWindow']) == 1){
				infowindow.open(marker.get('map'), marker);
			}	
		});
		
		google.maps.event.addListener(marker, 'dragend', function() {
			var lat = marker.getPosition().lat();
			var lng = marker.getPosition().lng();			
			jQuery("#clone-container-marker").find(".styler-container").each(function(index, element) {
                
				if(parseInt(jQuery(this).find(".number:first").text()) == parseInt(markerNumber)){
					jQuery(this).find(".latitude:first").val(lat.toFixed(7));
					jQuery(this).find(".longitude:first").val(lng.toFixed(7));
					jQuery(this).find(".searchlocation:first").val('');
				}
            });	 
		});
		
		google.maps.event.addListener(infowindow, 'domready', function() {
			
			// Reference to the DIV which receives the contents of the infowindow using jQuery
			var iwOuter = jQuery('.gm-style-iw');
			
			var iwBackground = iwOuter.prev();
			
			// Remove the background shadow DIV
			iwBackground.children(':nth-child(2)').css({'display' : 'none'});
			
			// Remove the white background DIV
			iwBackground.children(':nth-child(4)').css({'display' : 'none'});
							
			iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'display: none !important;'});
						
			iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'display:none !important;'});	
			
			iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': '#000000 20px 20px 20px', 'z-index' : '1'});
			
			// Is this div that groups the close button elements.
			var iwCloseBtn = iwOuter.next();
			
			// Apply the desired effect to the close button
			iwCloseBtn.css({
				opacity: '1', // by default the close button has an opacity of 0.7
				right: '20px',
				top: '40px', // button repositioning
				background : 'none'
			});
			
			iwCloseBtn.mouseout(function(){
				jQuery(this).css({opacity: '1'});
			});
		});
		return infowindow;
	}	
	
	
	function editMarker(location, data) {
		allMarkers[data.markerId]['showInfoWindow'] = data.showInfoWindow;
		if (data.markerType == 'svg'){
			allMarkers[data.markerId]['marker'].setIcon({
				path: markerSharps[data.markerSharp].marker,
				fillColor: data.fillColor,
				fillOpacity: data.fillOpacity,
				strokeColor: data.strokeColor,
				strokeWeight: data.strokeWeight,
				scale: markerSharps[data.markerSharp].scale
			});
		}else{
			allMarkers[data.markerId]['marker'].setIcon(plungInPath+'markers/'+data.markerType+'/'+data.markerSharp);
		}
		var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
		allMarkers[data.markerId]['marker'].setPosition(myLatlng);
        var myOptions = {
          zoom: 10,
          center: myLatlng
        };
        allMarkers[data.markerId]['marker'].setDraggable(true);
       if (data.markerVisible){
			if (data.title == ''){
				allMarkers[data.markerId]['label'].set('text', 'Label Title');
			}else{
				allMarkers[data.markerId]['label'].set('text', data.title);
			}
		}else{
			allMarkers[data.markerId]['label'].set('text', '');
		}

		allMarkers[data.markerId]['label'].set('strokeColor',data.labelStrokeColor);
		allMarkers[data.markerId]['label'].set('fontColor', data.labelTextolor);
		allMarkers[data.markerId]['label'].set('strokeWeight', data.labelStrokeWeight);
		allMarkers[data.markerId]['label'].set('position', myLatlng);
		
		var mapLabel = new MapLabel({
          text: data.title,
          position: myLatlng,
		  fontFamily: 'arial'
        });
		if (parseInt(data.showInfoWindow)){
			var contentText =  infoWindow(data);
			if (contentText != ''){
				allMarkers[data.markerId]['info_window'].open(map, allMarkers[data.markerId]['marker']);
				allMarkers[data.markerId]['info_window'].setContent(contentText);
			}else{
				allMarkers[data.markerId]['showInfoWindow'] = 0;
				allMarkers[data.markerId]['info_window'].close();
			}
		}else{
			allMarkers[data.markerId]['showInfoWindow'] = 0;
			allMarkers[data.markerId]['info_window'].close();
		}
		if (parseInt(data.primaryMarker)){
			map.setCenter(myLatlng);
		}
	}
	
	function infoWindow(data){
		
		var infoWindowContentText = '';
		var imageUrl_IW = data.imageUrl.trim();
		var imageTitle_IW = data.imageTitle.trim();
		var bodyTextH1_IW = data.bodyTextH1.trim();
		var bodyTextP1_IW = data.bodyTextP1.trim();
		var invalidPhone_IW = data.invalidPhone;
		var bodyTextP2_IW = data.bodyTextP2.trim();
		var invalidEmail_IW = data.invalidEmail;
		var bodyTextURL_IW = data.bodyTextURL.trim();
		if ((imageUrl_IW != '') || (imageTitle_IW != '') || (bodyTextH1_IW != '') || ((bodyTextP1_IW != '') && !(invalidPhone_IW)) || ((bodyTextP2_IW != '') && !(invalidEmail_IW)) || (bodyTextURL_IW != '')){
			infoWindowContentText = '<div class="info-window-outer'; if (imageUrl_IW == ''){infoWindowContentText += ' without-img';} infoWindowContentText += '">';
				infoWindowContentText += '<div class="info-window-inner">';
						if (imageUrl_IW != ''){
							infoWindowContentText += '<div class="info-window-img-container">';
								infoWindowContentText += '<img src="'+imageUrl_IW+'" alt="">';
								infoWindowContentText += '<div class="info-window-heading">';
									infoWindowContentText += '<div class="info-window-heading-inner">';
										infoWindowContentText += '<h4>'+imageTitle_IW+'</h4>';
									infoWindowContentText += '</div>';
								infoWindowContentText += '</div>';
							infoWindowContentText += '</div>';
						}else if (imageTitle_IW != '') {
							infoWindowContentText += '<div class="info-window-heading">';
								infoWindowContentText += '<div class="info-window-heading-inner-woi">';
									infoWindowContentText += '<h4>'+imageTitle_IW+'</h4>';
								infoWindowContentText += '</div>';
							infoWindowContentText += '</div>';
						}
						infoWindowContentText += '<div class="info-window-detail">';
						if (bodyTextH1_IW != ''){
							infoWindowContentText += '<h5>'+bodyTextH1_IW+'</h5>';
						}
						if ((bodyTextP1_IW != '') && !(invalidPhone_IW)){
							infoWindowContentText += '<p>'+bodyTextP1_IW+'</p>';
						}
						if ((bodyTextP2_IW != '') && !(invalidEmail_IW)){
							infoWindowContentText += '<p>'+bodyTextP2_IW+'</p>';
						}
						if (bodyTextURL_IW != ''){
							bodyTextURL_IW = bodyTextURL_IW.replace(/.*?:\/\//g, "");
							infoWindowContentText += '<p><a target="_blank" href="http://'+bodyTextURL_IW+'">'+bodyTextURL_IW+'</a></p>';
						}
					infoWindowContentText += '</div>';
				infoWindowContentText += '</div>';
			infoWindowContentText += '</div>';	
		}
		return infoWindowContentText;
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	