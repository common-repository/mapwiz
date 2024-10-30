<?php
	class mapClass
	{
		public $id=0, $maps=array(), $map=false, $admin=0, $markers=array(), $stylers=array(), $primaryMarker=array(), $localization='en',  $features =   array(array("id"=>1,"name"=>"Administrative","value"=>"administrative","parent_id"=>0,"ordering"=>1),array("id"=>7,"name"=>"Administrative->Country","value"=>"administrative.country","parent_id"=>1,"ordering"=>2),array("id"=>8,"name"=>"Administrative->Province","value"=>"administrative.province","parent_id"=>1,"ordering"=>3),array("id"=>9,"name"=>"Administrative->Locality","value"=>"administrative.locality","parent_id"=>1,"ordering"=>4),array("id"=>10,"name"=>"Administrative->Neighborhood","value"=>"administrative.neighborhood","parent_id"=>1,"ordering"=>5),array("id"=>11,"name"=>"Administrative->Land parcel","value"=>"administrative.land_parcel","parent_id"=>1,"ordering"=>6),array("id"=>2,"name"=>"Landscape","value"=>"landscape","parent_id"=>0,"ordering"=>7),array("id"=>12,"name"=>"Landscape->Man made","value"=>"landscape.man_made","parent_id"=>2,"ordering"=>8),array("id"=>13,"name"=>"Landscape->Natural","value"=>"landscape.natural","parent_id"=>2,"ordering"=>9),array("id"=>29,"name"=>"Landscape->Natural->Land cover","value"=>"landscape.natural.landcover","parent_id"=>13,"ordering"=>10),array("id"=>30,"name"=>"Landscape->Natural->Terrain","value"=>"landscape.natural.terrain","parent_id"=>13,"ordering"=>11),array("id"=>3,"name"=>"Point of interest","value"=>"poi","parent_id"=>0,"ordering"=>12),array("id"=>14,"name"=>"Point of interest->Attraction","value"=>"poi.attraction","parent_id"=>3,"ordering"=>13),array("id"=>15,"name"=>"Point of interest->Business","value"=>"poi.business","parent_id"=>3,"ordering"=>14),array("id"=>17,"name"=>"Point of interest->Government","value"=>"poi.government","parent_id"=>3,"ordering"=>15),array("id"=>18,"name"=>"Point of interest->Medical","value"=>"poi.medical","parent_id"=>3,"ordering"=>16),array("id"=>19,"name"=>"Point of interest->Park","value"=>"poi.park","parent_id"=>3,"ordering"=>17),array("id"=>20,"name"=>"Point of interest->Place of worship","value"=>"poi.place_of_worship","parent_id"=>3,"ordering"=>18),array("id"=>21,"name"=>"Point of interest->School","value"=>"poi.school","parent_id"=>3,"ordering"=>19),array("id"=>22,"name"=>"Point of interest->Sports complex","value"=>"poi.sports_complex","parent_id"=>3,"ordering"=>20),array("id"=>4,"name"=>"Road","value"=>"road","parent_id"=>0,"ordering"=>21),array("id"=>24,"name"=>"Road->Highway","value"=>"road.highway","parent_id"=>4,"ordering"=>22),array("id"=>31,"name"=>"Road->Highway->Controlled access","value"=>"road.highway.controlled_access","parent_id"=>24,"ordering"=>23),array("id"=>25,"name"=>"Road->Arterialv","value"=>"road.arterial","parent_id"=>4,"ordering"=>24),array("id"=>26,"name"=>"Road->Local","value"=>"road.local","parent_id"=>4,"ordering"=>25),array("id"=>5,"name"=>"Transit","value"=>"transit","parent_id"=>0,"ordering"=>26),array("id"=>27,"name"=>"Transit->Line","value"=>"transit.line","parent_id"=>5,"ordering"=>27),array("id"=>28,"name"=>"Transit->Station","value"=>"transit.station","parent_id"=>5,"ordering"=>28),array("id"=>32,"name"=>"Transit->Station->Airport","value"=>"transit.station.airport","parent_id"=>28,"ordering"=>29),array("id"=>33,"name"=>"Transit->Station->Bus","value"=>"transit.station.bus","parent_id"=>28,"ordering"=>30),array("id"=>34,"name"=>"Transit->Station->Rail","value"=>"transit.station.rail","parent_id"=>28,"ordering"=>31),array("id"=>6,"name"=>"Water","value"=>"water","parent_id"=>0,"ordering"=>32)),$elements = array(array("id"=>1,"name"=>"Geometry","value"=>"geometry","parent_id"=>0,"ordering"=>1),array("id"=>3,"name"=>"Geometry->Fill","value"=>"geometry.fill","parent_id"=>1,"ordering"=>2),array("id"=>4,"name"=>"Geometry->Stroke","value"=>"geometry.stroke","parent_id"=>1,"ordering"=>3),array("id"=>2,"name"=>"Labels","value"=>"labels","parent_id"=>0,"ordering"=>4),array("id"=>5,"name"=>"Labels->Text","value"=>"labels.text","parent_id"=>2,"ordering"=>5),array("id"=>7,"name"=>"Labels->Text->Fill","value"=>"labels.text.fill","parent_id"=>3,"ordering"=>6),array("id"=>8,"name"=>"Labels->Text->Stroke","value"=>"labels.text.stroke","parent_id"=>3,"ordering"=>7),array("id"=>6,"name"=>"Labels->Icon","value"=>"labels.icon","parent_id"=>2,"ordering"=>8)),$visibilities = array(''=>'Default', 'on'=>'On', 'simplified'=>'Simplified', 'off'=>'Off'), $pluginDirPath = '', $pluginsURL = '', $pluginDirURL="";
		public function getMap($request){
		
			global $wpdb;
			if((isset($request["mid"])) && (intval($request["mid"]))){
				$mapId = intval($request["mid"]);
				$result = array();
				$sSQL="select * from ".$wpdb->prefix . "map where id=".$mapId;
				$row = $wpdb->get_row($sSQL);
				if($row){
					
					$result['map'] = $row;
					$id = $row->id;
					$this->map = $row;
					$this->id = $row->id;
			
					$sSQL="select * from ".$wpdb->prefix . "marker where map_id=".$mapId;
					$resultMarkers = $wpdb->get_results($sSQL);
					$result['markers'] = $resultMarkers;
					$this->markers = $resultMarkers;
			
					$sSQL="select latitude, longitude from ".$wpdb->prefix . "marker where map_id=".$mapId." order by primary_marker desc";
					$rowPrimaryMarker = $wpdb->get_row($sSQL);
					$result['primaryMarker'] = $rowPrimaryMarker;
					$this->primaryMarker = $rowPrimaryMarker;
					
					$sSQL="select * from ".$wpdb->prefix . "styler where map_id=".$mapId;
					$resultStylers = $wpdb->get_results($sSQL);
					$result['stylers'] = $resultStylers;
					$this->stylers = $resultStylers;
				}
			}
			if ($this->id == 0){
				$sSQL="select * from ".$wpdb->prefix . "map";
				$wpdb->get_results($sSQL);
  				if ($wpdb->num_rows != 0){
					$_SESSION['msg'] = "You are allowed only one map in limited version";
					?><script type="text/javascript">window.location.assign("admin.php?page=myplug/muyplg.php");</script><?php
				}
			}
		}
		
		public function save($post){
			
			global $wpdb;
			/* Saving Post Data*/
			if(isset($post["SaveMap"])){
				$mapPost = '';
				/* Query to save map*/
				$mapPost .= "featured_map = '".$post["featuredMap"]."', ";
				$mapPost .= "width = '".$post["mapWidth"]."', ";
				$mapPost .= "height = '".$post["mapHeight"]."', ";
				$mapPost .= "width_unit = '".$post["mapWidthUnit"]."', ";
				$mapTypeId = strtoupper($post["settingMapType"]);
				$mapPost .= "mapTypeId = '".$mapTypeId."', ";
				$mapPost .= "zoom = '".$post["settingZoom"]."', ";
				$mapPost .= "zoomControl = '".(($post['settingZoomControl']=='none')?0:1)."', ";
				$mapPost .= "zoomControlOptions = '".(($post['settingZoomControl']=='none')?'':$post['settingZoomControl'])."', ";
				$mapPost .= "zoom_control_position = '".$post['settingZoomControlPosition']."', ";
				$mapPost .= "mapTypeControl = '".(($post['settingMapControlType']=='none')?0:1)."', ";
				$mapPost .= "map_type_control_position = '".$post['settingMapControlTypePosition']."', ";        
				$mapPost .= "mapTypeControlOptions = '".(($post['settingMapControlType']=='none')?'':$post['settingMapControlType'])."', ";
				$mapPost .= "disableDoubleClickZoom = '".(($post['settingDoubleClickZoom']=='none')?1:0)."', ";
				$mapPost .= "draggable = '".(($post['settingDraggableMap']=='none')?0:1)."', ";
				$mapPost .= "scrollwheel = '".(($post['settingMouseScroll']=='none')?0:1)."', ";
				$mapPost .= "streetView = '".(($post['settingStreetView']=='none')?0:1)."', ";
				$mapPost .= "street_view_position = '".$post['settingStreetViewPosition']."' ";
				$action = "";
				
				if (intval($post["id"])){
					
					/* Update Map Properties */
					$action = "updated";
					$id = $post["id"];
					$mapQuery = "Update ".$wpdb->prefix."map SET " . $mapPost . "where id = '".$id."' ";
					$wpdb->query($mapQuery);
					$wpdb->query("delete from ".$wpdb->prefix."marker where map_id = ".$id);
					$wpdb->query("delete from ".$wpdb->prefix."styler where map_id = ".$id);
					$new = 0;
				}else{
					
					/* Save New Map */
					$action = "saved";
					$mapQuery = "INSERT INTO ".$wpdb->prefix."map SET " . $mapPost ;
					$wpdb->query($mapQuery);
					$id = $wpdb->insert_id;
					$new = 1;
				}
								
				/* Save Markers */
				if (count($post['markerStrokeWeight']) > 1){
					foreach($post['markerStrokeWeight'] as $key=>$marker){
						if ($key > 0){
							$markerQuery = 'INSERT INTO '.$wpdb->prefix.'marker SET ';
							$markerQuery .= "map_id = ".$id.", ";
							
							if (isset($post['primaryMarker']) && (intval($post['primaryMarker']) == $key)){
								$markerQuery .= "primary_marker = 1, ";
							}else{
								$markerQuery .= "primary_marker = 0, ";
							}
							
							$markerQuery .= "markerTitle = '".$post["markerTitle"][$key]."', ";
							$markerQuery .= "markerICON = '".$post["markerICON"][$key]."', ";
							$markerQuery .= "marker_type = 'svg', ";
							$markerQuery .= "latitude = ".floatval($post["latitude"][$key]).", ";
							$markerQuery .= "longitude = ".floatval($post["longitude"][$key]).", ";
							$markerQuery .= "fillColor = '".$post["fillColor"][$key]."', ";
							$markerQuery .= "markerFillOpacity = 1, ";
							$markerQuery .= "markerStrokeColor = '".$post["markerStrokeColor"][$key]."', ";
							$markerQuery .= "markerStrokeWeight = ".intval($post["markerStrokeWeight"][$key]).", ";
							$markerQuery .= "imageTitle = '".$post["imageTitle"][$key]."', ";
							$markerQuery .= "imageUrl = '".$post["imageUrl"][$key]."', ";
							if ($post["showInfoWindow"][$key] == 1){
								$showInfoWindow = 1;
							}else{
								$showInfoWindow = 0;
							}
							$markerQuery .= "showInfoWindow = ".$showInfoWindow.", ";
							$markerQuery .= "bodyTextH1 = '".$post["bodyTextH1"][$key]."', ";
							$markerQuery .= "bodyTextP1 = '".$post["bodyTextP1"][$key]."', ";
							$markerQuery .= "bodyTextP2 = '".$post["bodyTextP2"][$key]."', ";
							$markerQuery .= "bodyTextUrl = '".$post["bodyTextUrl"][$key]."', ";
							$markerLabelTitle = $post["labelTitle"][$key];
							if (empty($markerLabelTitle)){
								$markerLabelTitle = 'Label Title';
							}
							$markerQuery .= "labelTitle = '".$markerLabelTitle."', ";
							if (isset($post["markerVisible"][$key]))
								$markerQuery .= "markerVisible = 1, ";
							else
								$markerQuery .= "markerVisible = 0, ";
							$markerQuery .= "labelTextColor = '".$post["labelTextColor"][$key]."', ";
							$markerQuery .= "strokeColor = '".$post["strokeColor"][$key]."', ";
							$markerQuery .= "labelStrokeColor = '".$post["labelStrokeColor"][$key]."', ";
							$markerQuery .= "labelStrokeWeight = ".intval($post["labelStrokeWeight"][$key])." ";
							$wpdb->query($markerQuery);
						}
					}
				}
				/* End - Save Stylers */
		
				/* Returning After Save*/
				if ($post["SaveMap"] == 1){
                    $_SESSION['msg'] = "Map has been updated successfully";
					?><script type="text/javascript">window.location.assign("admin.php?page=myplug/muyplg.php&id=<?=$id?>");</script><?php
				}else if(($new == 1) && ($post["SaveMap"] != 1)){
                    $_SESSION['msg'] = "Map has been created successfully";
					?><script type="text/javascript">window.location.assign("admin.php?page=myplug/muyplg.php&mid=<?=$id?>");</script><?php
				}
			}
			/* End - Saving Post Data*/
		}
		
		public function delete($mapId){

			global $wpdb;
			$sql = "delete from ".$wpdb->prefix."map where id=".$mapId;
			$wpdb->query($sql);
			$sql = "delete from ".$wpdb->prefix."marker where map_id=".$mapId;
			$wpdb->query($sql);
			$sql = "delete from ".$wpdb->prefix."styler where map_id=".$mapId;
			$wpdb->query($sql);
			$_SESSION['msg'] = "Map has been deleted successfully";
		}
		
		public function getMaps(){
		
			global $wpdb;
			$sql = "select * from ".$wpdb->prefix."map order by id desc";
			$this->maps = $wpdb->get_row($sql);
		}
		
		public function getMapInfo($mapId=0){
		
			global $wpdb;
			if(intval($mapId)){
				$result = array();
				$sSQL="select * from ".$wpdb->prefix . "map where id=".$mapId;
				$row = $wpdb->get_row($sSQL);
				if($row){
					
					$result['map'] = $row;
					$id = $row->id;
					$this->map = $row;
					$this->id = $row->id;
			
					$sSQL="select * from ".$wpdb->prefix . "marker where map_id=".$mapId;
					$resultMarkers = $wpdb->get_results($sSQL);
					$result['markers'] = $resultMarkers;
					$this->markers = $resultMarkers;
			
					$sSQL="select latitude, longitude from ".$wpdb->prefix . "marker where map_id=".$mapId." order by primary_marker desc";
					$rowPrimaryMarker = $wpdb->get_row($sSQL);
					$result['primaryMarker'] = $rowPrimaryMarker;
					$this->primaryMarker = $rowPrimaryMarker;
					
					$sSQL="select * from ".$wpdb->prefix . "styler where map_id=".$mapId;
					$resultStylers = $wpdb->get_results($sSQL);
					$result['stylers'] = $resultStylers;
					$this->stylers = $resultStylers;
				}
			}
		}
		
		public function markerJS(){
			$id = $this->id;
			$markers = $this->markers;
			if (count($markers)){
				foreach($markers as $index=>$marker){?>
                	<?php if ($this->admin){?>
                    var places<?=($index+1)?> = new google.maps.places.Autocomplete(document.getElementById('searchlocation<?=($index+1)?>'));
                    google.maps.event.addListener(places<?=($index+1)?>, 'place_changed', function () {
                        var place = places<?=($index+1)?>.getPlace();
                        var address = place.adr_address;
                        var latitude = place.geometry.location.lat();
                        var longitude = place.geometry.location.lng();
                        
                        var myLatlng = new google.maps.LatLng(latitude, longitude);
                        $("#latitude<?=($index+1)?>").val(latitude.toFixed(7));
                        $("#longitude<?=($index+1)?>").val(longitude.toFixed(7));
                    });
                    <?php }?>
                    
					var mapLabel = new MapLabel({
					text: '<?=$marker->labelTitle?>',
					position: new google.maps.LatLng(<?=$marker->latitude?>, <?=$marker->longitude?>),
					map: map,
						fontFamily: 'arial'
					});
					mapLabel.set('fontSize', '18');
					mapLabel.set('fontColor', '<?=$marker->labelTextColor?>');
					mapLabel.set('strokeWeight', <?=$marker->labelStrokeWeight?>);
					mapLabel.set('strokeColor', '<?=$marker->labelStrokeColor?>');
					mapLabel.set('fontFamily', 'sans-serif');
					var myLatlng = new google.maps.LatLng(<?=$marker->latitude?>, <?=$marker->longitude?>);
					var marker = new google.maps.Marker({
						position: myLatlng,
						map: map
					});
                    <?php if ($this->admin == 1){ ?> marker.setDraggable(true);<?php }?>
					marker.bindTo('map', mapLabel);
					marker.bindTo('position', mapLabel);
					
					<?php if ($marker->marker_type == 'svg'){?>
						marker.setIcon({
							path: markerSharps['<?=$marker->markerICON?>'].marker,
							fillColor: '<?=$marker->fillColor?>',
							fillOpacity: 4,
							strokeColor: '<?=$marker->markerStrokeColor?>',
							strokeWeight: <?=$marker->markerStrokeWeight?>,
							scale: markerSharps['<?=$marker->markerICON?>'].scale
						});
					<?php }

					$infoWindowContentText = '';
					if (($marker->showInfoWindow == 1)){
						
						$isValidPhone = validPhone($marker->bodyTextP1);
						$isValidEmail = validEmail($marker->bodyTextP2);

						if ((!empty($marker->imageUrl)) || (!empty($marker->imageTitle)) || (!empty($marker->bodyTextH1)) || ((!empty($marker->bodyTextP1)) && !($isValidPhone)) || ((!empty($marker->bodyTextP2)) && !($isValidEmail)) || (!empty($marker->bodyTextUrl))){																
							$imageClass = '';
							if (empty($marker->imageUrl)){
								$imageClass = ' without-img';
							}
							
							$infoWindowContentText = '<div class="info-window-outer '.$imageClass.'">';
								$infoWindowContentText .= '<div class="info-window-inner">';
										if (!empty($marker->imageUrl)){
											$infoWindowContentText .= '<div class="info-window-img-container">';
												$infoWindowContentText .= '<img src="'.$marker->imageUrl.'" alt="">';
												$infoWindowContentText .= '<div class="info-window-heading">';
													$infoWindowContentText .= '<div class="info-window-heading-inner">';
														$infoWindowContentText .= '<h4>'.$marker->imageTitle.'</h4>';
													$infoWindowContentText .= '</div>';
												$infoWindowContentText .= '</div>';
											$infoWindowContentText .= '</div>';
										}else if (!empty($marker->imageTitle)) {
											$infoWindowContentText .= '<div class="info-window-heading">';
												$infoWindowContentText .= '<div class="info-window-heading-inner-woi">';
													$infoWindowContentText .= '<h4>'.$marker->imageTitle.'</h4>';
												$infoWindowContentText .= '</div>';
											$infoWindowContentText .= '</div>';
										}
										$infoWindowContentText .= '<div class="info-window-detail">';
											if (!empty($marker->bodyTextH1)){
												$infoWindowContentText .= '<h5>'.$marker->bodyTextH1.'</h5>';
											}
										
											if (!empty($marker->bodyTextP1) && $isValidPhone){
												$infoWindowContentText .= '<p>'.$marker->bodyTextP1.'</p>';
											}
										
											if (!empty($marker->bodyTextP2) && !($isValidEmail)){
												$infoWindowContentText .= '<p>'.$marker->bodyTextP2.'</p>';
											}
											if (!empty($marker->bodyTextUrl)){
												$marker->bodyTextUrl = preg_replace('#^https?://#', '', rtrim($marker->bodyTextUrl,'/'));
												$infoWindowContentText .= '<p><a target="_blank" href="http://'.$marker->bodyTextUrl.'">'.$marker->bodyTextUrl.'</a></p>';
											}
										$infoWindowContentText .= '</div>';
								$infoWindowContentText .= '</div>';
							$infoWindowContentText .= '</div>';
						}else{
							$marker->showInfoWindow = 0;
						}
					}else{$marker->showInfoWindow = 0;} ?>
					var infowindow = new google.maps.InfoWindow({
						content: '<?=$infoWindowContentText?>'
					});
					<?php if ($marker->showInfoWindow == 1){?>
						infowindow.close();
					<?php }?>
					
                    <?php if ($this->admin){?>
                    loadInfoWindow(marker, infowindow, <?=($index+1)?>);
					<?php }?>
                    allMarkers[<?=($index+1)?>] = [];
					allMarkers[<?=($index+1)?>]['marker'] = marker;
					allMarkers[<?=($index+1)?>]['info_window'] = infowindow;
					allMarkers[<?=($index+1)?>]['label'] = mapLabel;
					<?php if ($marker->showInfoWindow == 1){?>
						allMarkers[<?=($index+1)?>]['marker'].addListener('click', function() {
							allMarkers[<?=($index+1)?>]['info_window'].open(map, allMarkers[<?=($index+1)?>]['marker']);
						});
						google.maps.event.addListener(allMarkers[<?=($index+1)?>]['info_window'], 'domready', function() {
						
						// Reference to the DIV which receives the contents of the infowindow using jQuery
						var iwOuter = jQuery('.gm-style-iw');
						var iwBackground = iwOuter.prev();

						// Remove the background shadow DIV
						iwBackground.children(':nth-child(2)').css({'display' : 'none'});
						iwBackground.children(':nth-child(4)').css({'display' : 'none'});
						iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'display: none !important;'});
						iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'display:none !important;'});
						iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});						
						var iwCloseBtn = iwOuter.next();
						
						// Apply the desired effect to the close button
						iwCloseBtn.css({
							opacity: '1',
							right: '20px',
							top: '40px',
							background : 'none'
						});
						
						iwCloseBtn.mouseout(function(){
							jQuery(this).css({opacity: '1'});
						});
					
					});
				<?php } ?>
		<?php }}
		}

		public function markerHtml(){
			$markers = $this->markers;
			$id = $this->id;?>
            <!-- Editing Marker Block -->
            <div class="sections section-height" id="marker-section">
              <div class="display-none clone not-styler" id="marker-clone">
                <div class="styler-container">
                  <div class="styler-head clearfix">
                    <div class="number">01</div>
                    <div class="styler-buttons clearfix"> <span class="markertitle-style">
                      <input type="text" name="markerTitle[]" id="markerTitle" value="" class="style-input" maxlength="15"  />
                      </span> <a href="" class="show-hide-buttons fr" ></a> <a href="" class="delet fr"></a> </div>
                  </div>
                  <div class="features-outer no-padding">
                    <ul class="tabs">
                      <li class="tab current-tab" data-tab-content=".tab-marker">Marker</li>
                      <li class="tab" data-tab-content=".tab-label">Label</li>
                      <li class="tab" data-tab-content=".tab-icon">Shapes</li>
                    </ul>
                    <div class="tabs-set tabs-set-pad">
                      <div class="features-inner tab-marker main-tab-content display-block">
                        <h3>Position of Marker</h3>
                        <p class="txt-gray margin-bottom-10px margin-top-10px">Enter your cordinates or <a data-label-class="0" href="javascript:void(0);" class="section-link" onclick="getGeoLocation(jQuery(this))">Get auto coordinates.</a></p>
                        <div class="table margin-top-10px table-lat-lang seperator">
                          <div class="tr">
                            <div class="td txt-dark-gray txt-16">Latitude</div>
                            <div class="td">
                              <input type="text" maxlength="11" name="latitude[]" class="latitude txt-blue txt-14" value="0" onchange="validationLatitudeLongitude(jQuery(this))">
                            </div>
                          </div>
                          <div class="tr">
                            <div class="td txt-dark-gray txt-16">Longitude</div>
                            <div class="td">
                              <input type="text" maxlength="11" name="longitude[]" class="longitude txt-blue txt-14" value="0" onchange="validationLatitudeLongitude(jQuery(this))" />
                            </div>
                          </div>
                          <div class="tr location-heading-outer"> <span class="location-heading">Or find your location</span> </div>
                          <div class="tr">
                            <div class="location-wrp">
                              <input type="text" id="searchlocation" name="searchlocation[]" class="searchlocation width-250"  placeholder="Search your location" />
                              <!--<input type="submit" class="location-submit">--> 
                            </div>
                          </div>
                        </div>
                        <div class="primary-map clearfix">
                          <div class="content">
                            <p>Make this marker as primary</p>
                            <i> Only single marker can be primary, it allow to centralize the screen and zoom level. </i> </div>
                          <div class="option">
                            <input type="radio" maxlength="8" name="primaryMarker" class="primary-marker txt-blue txt-14" value="0" />
                          </div>
                        </div>
                        <div class="status-parent">
                          <div class="table no-margin">
                            <div class="tr nobackground">
                              <div class="td txt-16 left no-padding" >Info Window</div>
                              <div class="td txt-16 right no-padding"> <span class="txt-light-blue"></span>
                                <label class="enable fl">Enable</label>
                                <input type="checkbox" class="status info-window bg-161f25" name="infoWindow[]" value="1" />
                                <input type="hidden" class="show-info-window" name="showInfoWindow[]" value="0" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="info-tab">
                          <ul class="info-window-tabs tabs">
                            <li class="tab current-tab" data-tab-content=".tab-header-data">Header Data</li>
                            <li class="tab" data-tab-content=".tab-body-text">Body Text</li>
                          </ul>
                          <div class="tabs-set">
                            <div class="tab-content tab-header-data txt-16 txt-gray">
                              <div>
                                <label>Header Title <span>(H1)</span></label>
                                <input type="text" name="imageTitle[]" class="txt-blue txt-14 image-title input-bc" placeholder="Type header title here" value="" maxlength="50" />
                              </div>
                              <div class="margin-top-8px">
                                <label class="margin-top-8px">Header BG Image</label>
                                <input type="text" name="imageUrl[]" class="txt-blue txt-14 image-url input-bc" placeholder="Enter URL for here background image" value="" />
                                <span class="txt-12 img-size">Image Dimension 16:9 or 350 x 197</span> </div>
                            </div>
                            <div class="tab-content tab-header-data txt-16 txt-gray display-none">
                              <div>
                                <label>Body Text <span>(H1)</span></label>
                                <input type="text" name="bodyTextH1[]" placeholder="Type your company address" class="txt-blue txt-14 body-text-h1 input-bc " value="" maxlength="50" />
                              </div>
                              <div class="margin-top:8px;">
                                <label class="margin-top:8px;">Body Text <span>(P)</span></label>
                                <input type="text" name="bodyTextP1[]" class="txt-blue txt-14 body-text-p1 input-bc" value="" maxlength="50" placeholder="Type phone no#" onblur="validatePhone(jQuery(this))"/>
                                <input type="text" name="bodyTextP2[]" class="txt-blue txt-14 body-text-p2 input-bc" onblur="validateEmail(jQuery(this))" placeholder="Type email address " value="" maxlength="50" />
                              </div>
                              <div class="margin-top:8px;">
                                <label class="margin-top:8px;">Website URL</label>
                                <input type="text" name="bodyTextUrl[]" placeholder="Enter company website URL here " class="txt-blue txt-14 body-text-url" value="" maxlength="50" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="features-inner tab-label main-tab-content display-none">
                        <div class="table status-parent no-margin" >
                          <div class="tr nobackground">
                            <div class="td txt-16 left no-padding" >Label Text</div>
                          </div>
                        </div>
                        <div class=" margin-bottom-10px display-block">
                          <div>
                            <input type="text" name="labelTitle[]" class="txt-blue fr txt-14 marker-title bg-1e282f" value="" maxlength="30" placeholder="Label Title" />
                            <br />
                            <span class="txt-13 txt-gray">This text will appear under or next to marker.</span> </div>
                        </div>
                        <div class="seperator margin-bottom-10px">
                          <div class="left txt-16"> Visible</div>
                          <div class="right width-102" >
                            <div class="input-color-wrap padding-left-38px">
                              <input type="checkbox" class="marker-visible bg-161f25 fr" name="markerVisible[]" value="1" checked />
                            </div>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator margin-bottom-10px">
                          <div class="left txt-16">Color</div>
                          <div class="righ width-102t">
                            <div class="inc-dec-wrap">
                              <div class="left inc-dec-input">
                                <input type="hidden" value="5">
                              </div>
                              <div class="left inc-dec display-none">
                                <div class="inc"></div>
                                <div class="dec"></div>
                              </div>
                              <div class="clr"></div>
                            </div>
                            <div class="input-color-wrap color-wrp">
                              <input maxlength="7" type="text" name="labelTextColor[]" class="form-control demo label-text-color display_none" data-control="hue" value="#f4af0a" />
                            </div>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator table">
                          <div class="left txt-16">Stroke &amp; Color</div>
                          <div class="right width-102">
                            <div class="inc-dec-wrap">
                              <div class="left inc-dec-input">
                                <input type="hidden" value="5" name="strokeColor[]">
                              </div>
                              <div class="left inc-dec display-none">
                                <div class="inc"></div>
                                <div class="dec"></div>
                              </div>
                              <div class="clr"></div>
                            </div>
                            <div class="input-color-wrap color-wrp">
                              <input maxlength="7" name="labelStrokeColor[]" type="text" class="form-control demo label-stroke-color display_none" data-control="hue" value="#f31a33" />
                            </div>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Stroke &amp; Weight</div>
                          <div class="right width-102">
                            <div class="input-color-wrap">
                              <select class="label-stroke-weight width-50" name="labelStrokeWeight[]">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4" selected>4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                              </select>
                            </div>
                          </div>
                          <div class="clr"></div>
                        </div>
                      </div>
                      <div class="features-inner tab-icon main-tab-content display-none">
                        <div class="feature-editor-outer">
                          <div class="feature-editor">
                            <input type="hidden" name="markerICON[]" class="marker-ICON" value="map-icon-map-pin" />
                            <select name="markerType[]" class="marker-type" onchange="markerType(jQuery(this));">
                              <option value="svg">Svg Shapes (Customizable)</option>
                              <?php 
                                                        $url = $this->pluginDirPath;
                                                        if ($handle = opendir($url)) {
                                                            while (false !== ($file = readdir($handle))) {
                                                                if (($file != ".") && ($file != "..")){
                                                                    if(is_dir($url.$file)){
                                                    ?>
                              <option value="<?=($file)?>">
                              <?=str_replace("_"," ",$file)?>
                              </option>
                              <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                            </select>
                          </div>
                        </div>
                        <div class="table status-parent marker-type-svg no-margin">
                          <div class="tr marker-shapes-font clearfix shapes-tab-icon-wrp"> <?php echo $this->loadMarkerShapes(6);?> </div>
                          <div class="tr nobackground display-none">
                            <p class="center" style="color:#a0acb4; cursor:pointer;">Load More</p>
                          </div>
                          <div class="seperator table">
                            <div class="left txt-16">Fill Color </div>
                            <div class="right width-102">
                              <div class="inc-dec-wrap">
                                <div class="left inc-dec-input fill-color"> </div>
                                <div class="left inc-dec display-none" >
                                  <div class="inc"></div>
                                  <div class="dec"></div>
                                </div>
                                <div class="clr"></div>
                              </div>
                              <div class="input-color-wrap color-wrp">
                                <input type="text" maxlength="7" name="fillColor[]" class="form-control demo input-color display_none" data-control="hue" value="#4613ec" />
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                          <div class="seperator table">
                            <div class="left txt-16">Stroke Color </div>
                            <div class="right width-102" >
                              <div class="inc-dec-wrap">
                                <div class="left inc-dec-input fill-color"> </div>
                                <div class="left inc-dec display-none" >
                                  <div class="inc"></div>
                                  <div class="dec"></div>
                                </div>
                                <div class="clr"></div>
                              </div>
                              <div class="input-color-wrap color-wrp ">
                                <input type="text" maxlength="7" name="markerStrokeColor[]" class="form-control demo marker-stroke-color display_none" data-control="hue" value="#7f9819" />
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                          <div class="seperator">
                            <div class="left txt-16">Stroke Weight</div>
                            <div class="right width-102" >
                              <div class="inc-dec-wrap">
                                <div class="left inc-dec-input fill-color"> </div>
                                <div class="left inc-dec display-none">
                                  <div class="inc"></div>
                                  <div class="dec"></div>
                                </div>
                                <div class="clr"></div>
                              </div>
                              <div class="input-color-wrap">
                                <select name="markerStrokeWeight[]" class="marker-stroke-weight width-50">
                                  <option value="0" >0</option>
                                  <option value="1" >1</option>
                                  <option value="2">2</option>
                                  <option value="3" selected>3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                </select>
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                        </div>
                        <?php 
                                                        $markerDir = $this->pluginDirPath;
                                                        if ($handleMarkerDir = opendir($markerDir)) {
                                                            while (false !== ($readMarkerDir = readdir($handleMarkerDir))) {
                                                                if (($readMarkerDir != ".") && ($readMarkerDir != "..")){
                                                                    if(is_dir($markerDir.$readMarkerDir)){
                                                    ?>
                        <div class="table status-parent marker-type-<?=($readMarkerDir)?> no-margin display-no-imp">
                          <div class="tr nobackground">
                            <div class="td txt-16 left no-padding">Marker Shapes</div>
                          </div>
                          <div class="tr marker-shapes-font marker-png clearfix nobackground height-auto">
                            <?php 
                                                                        $firstMarker = 1;
                                                                        $urlMarkerFile = $this->pluginDirPath.$readMarkerDir.'/' ;
                                                                        if ($handleMarkerFile = opendir($urlMarkerFile)) {
                                                                            while (false !== ($fileMarkerPNG = readdir($handleMarkerFile))) {
                                                                                if (($fileMarkerPNG != ".") && ($fileMarkerPNG != "..")){
                                                                                    ?>
                            <i data-label-class="<?=$fileMarkerPNG?>" <?php if ((($id == 0) && ($firstMarker == 1)) || (($id > 0) && (@$marker->markerICON == $file))){echo "class='selected-icon'";}?>> <img src='<?=$this->pluginDirURL."markers/".$readMarkerDir."/".$fileMarkerPNG?>' alt="<?=$fileMarkerPNG?>"> </i>
                            <?php $firstMarker++;}
                                                                            }
                                                                            closedir($handleMarkerFile);
                                                                        }
                                                                    ?>
                          </div>
                        </div>
                        <?php
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                      </div>
                      <a href="javascript:void(0)" class="btns fr txt-white droppin">Drop Pin</a> </div>
                  </div>
                </div>
              </div>
              <div class="outerScroll" id="outerScroll2">
                <div class="clone-container" id="clone-container-marker">
                  <?php
                                        if ($markers){
                                            foreach($markers as $index=>$marker){
                                        ?>
                  <div id="marker_<?=(intval($index)+1)?>" class="styler-container">
                    <div class="styler-head clearfix">
                      <div class="number">0<?=($index+1)?></div>
                      <div class="styler-buttons clearfix"> <span class="markertitle-style">
                        <input type="text" maxlength="15" class="style-input" id="markerTitle" name="markerTitle[]" value="<?=$marker->markerTitle?>">
                        </span> <a class="fr show-hide-buttons" href=""></a> <a class="delet fr" href=""></a> </div>
                    </div>
                    <div class="no-padding features-outer">
                      <ul class="tabs">
                        <li data-tab-content=".tab-marker" class="tab current-tab">Marker</li>
                        <li data-tab-content=".tab-label" class="tab">Label</li>
                        <li data-tab-content=".tab-icon" class="tab">Shapes</li>
                      </ul>
                      <div class="tabs-set tabs-set-pad">
                        <div class="features-inner tab-marker main-tab-content display-block">
                          <h3>Position of Marker</h3>
                          <p class="txt-gray margin-bottom-10px margin-top-10px">Enter your cordinates or <a data-label-class="<?=($index+1)?>" href="javascript:void(0);" class="section-link" onclick="getGeoLocation(jQuery(this))">Get auto coordinates.</a></p>
                          <div class="table margin-top-10px table-lat-lang seperator">
                            <div class="tr">
                              <div class="td txt-dark-gray txt-16">Latitude</div>
                              <div class="td">
                                <input type="text" maxlength="11" class="latitude txt-blue txt-14" id="latitude<?=($index+1)?>" name="latitude[]" value="<?=$marker->latitude?>" onchange="validationLatitudeLongitude(jQuery(this))" />
                              </div>
                            </div>
                            <div class="tr">
                              <div class="td txt-dark-gray txt-16">Longitude</div>
                              <div class="td">
                                <input type="text" maxlength="11" class="longitude txt-blue txt-14" id="longitude<?=($index+1)?>" name="longitude[]" value="<?=$marker->longitude?>" onchange="validationLatitudeLongitude(jQuery(this))" />
                              </div>
                            </div>
                            <div class="tr location-heading-outer"> <span class="location-heading">Or find your location</span> </div>
                            <div class="tr">
                              <div class="location-wrp">
                                <input type="text" id="searchlocation<?=($index+1)?>" name="searchlocation[]" class="searchlocation width-250" placeholder="Re-enter your location" />
                              </div>
                            </div>
                          </div>
                          <div class="primary-map clearfix">
                            <div class="content">
                              <p>Make this marker as primary</p>
                              <i> Only single marker can be primary, it allow to centralize the screen and zoom level. </i> </div>
                            <div class="option">
                              <input <?php if ($id && (intval($marker->primary_marker))){?>checked="checked"<?php }?> type="radio" maxlength="8" id="primaryMarker<?=($index+1)?>" name="primaryMarker" class="primary-marker txt-blue txt-14" value="<?=($index+1)?>" />
                            </div>
                          </div>
                          <div class="status-parent">
                            <div class="table no-margin">
                              <div class="tr nobackground">
                                <div class="no-padding td txt-16 left">Info Window</div>
                                <div class="no-padding td txt-16 right"><span class="txt-light-blue"></span>
                                  <label class="enable fl"> Enable</label>
                                  <input type="checkbox" name="infoWindow[]" class="status info-window bg-161f25" <?php if ($marker->showInfoWindow == 1){echo "checked";}?> />
                                  <input type="hidden" name="showInfoWindow[]" class="show-info-window" value="<?=$marker->showInfoWindow?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="info-tab" style="display:<?php if ($marker->showInfoWindow == 1){echo 'block';}else{echo 'none';}?>">
                            <ul class="info-window-tabs tabs">
                              <li data-tab-content=".tab-header-data" class="tab current-tab">Header Data</li>
                              <li data-tab-content=".tab-body-text" class="tab">Body Text</li>
                            </ul>
                            <div class="tabs-set">
                              <div class="tab-content tab-header-data txt-16 txt-gray">
                                <div>
                                  <label>Header Title <span>(H1)</span></label>
                                  <input type="text" maxlength="50" placeholder="Type header title here" value="<?=$marker->imageTitle?>" class="txt-blue txt-14 image-title" name="imageTitle[]">
                                </div>
                                <div class="margin-top-8px">
                                  <label class="margin-top-8px">Header BG Image</label>
                                  <input type="text" placeholder="Enter URL for here background image" value="<?=$marker->imageUrl?>" class="txt-blue txt-14 image-url" name="imageUrl[]">
                                  <span class="txt-12 img-size">Image Dimension 16:9 or 350 x 197</span> </div>
                              </div>
                              <div class="display-none tab-content tab-header-data txt-16 txt-gray">
                                <div>
                                  <label>Body Text <span>(H1)</span></label>
                                  <input type="text" maxlength="50" placeholder="Type your company address" value="<?=$marker->bodyTextH1?>" class="txt-blue txt-14 body-text-h1" name="bodyTextH1[]">
                                </div>
                                <div class="margin-top-8px">
                                  <label class="margin-top-8px">Body Text <span>P</span></label>
                                  <input type="text" maxlength="50" placeholder="Type phone no#" value="<?=$marker->bodyTextP1?>" class="txt-blue txt-14 body-text-p1<?php if (!validPhone($marker->bodyTextP1)){echo ' invalid-phone';}?>" name="bodyTextP1[]" onblur="validatePhone(jQuery(this))" />
                                  <input type="text" maxlength="50" placeholder="Type email address" value="<?=$marker->bodyTextP2?>" class="txt-blue txt-14 body-text-p2<?php if (validEmail($marker->bodyTextP2)){echo ' invalid-email';}?>" name="bodyTextP2[]" onblur="validateEmail(jQuery(this))" />
                                </div>
                                <div class="margin-top-8px">
                                  <label class="margin-top-8px">Website URL</label>
                                  <br>
                                  <input type="text" maxlength="50" placeholder="Enter company website URL here " value="<?=$marker->bodyTextUrl?>" class="txt-blue txt-14 body-text-url" name="bodyTextUrl[]">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="display-none features-inner tab-label main-tab-content">
                          <div class="no-margin table status-parent">
                            <div class="nobackground tr">
                              <div class="no-padding td txt-16 left">Label Text</div>
                            </div>
                          </div>
                          <div class="display-block seperator margin-bottom-10px">
                            <div>
                              <input type="text" placeholder="Label Title" maxlength="30" value="<?=(($marker->labelTitle == 'Label Title')?'':$marker->labelTitle)?>" class="txt-blue txt-14 marker-title bg-1e282f" name="labelTitle[]">
                              <br>
                              <span class="txt-13 txt-gray">This text will appear under or next to marker.</span> </div>
                          </div>
                          <div class="seperator margin-bottom-10px">
                            <div class="left txt-16"> Visible</div>
                            <div class="width-102 right">
                              <div class="inc-dec-wrap"> </div>
                              <div class="padding-left-38px input-color-wrap">
                                <input type="checkbox" <?php if (intval($marker->markerVisible)){?>checked=""<?php }?> value="1" name="markerVisible[]" class="marker-visible bg-161f25 fr" />
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                          <div class="seperator margin-bottom-10px">
                            <div class="left txt-16"> Color</div>
                            <div class="right .width-102">
                              <div class="inc-dec-wrap">
                                <div class="left inc-dec-input">
                                  <input type="hidden" value="5">
                                </div>
                                <div class="left inc-dec display-none">
                                  <div class="inc"></div>
                                  <div class="dec"></div>
                                </div>
                                <div class="clr"></div>
                              </div>
                              <div class="input-color-wrap color-wrp">
                                <input type="text" name="labelTextColor[]" class="form-control demo label-text-color" data-control="hue" value="<?=$marker->labelTextColor?>" />
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                          <div class="seperator table">
                            <div class="left txt-16">Stroke &amp; Color</div>
                            <div class="right width-102">
                              <div class="inc-dec-wrap">
                                <div class="left inc-dec-input">
                                  <input type="hidden" value="5" name="strokeColor[]">
                                </div>
                                <div class="left inc-dec display-none">
                                  <div class="inc"></div>
                                  <div class="dec"></div>
                                </div>
                                <div class="clr"></div>
                              </div>
                              <div class="input-color-wrap color-wrp">
                                <input name="labelStrokeColor[]" type="text" class="form-control demo label-stroke-color" data-control="hue" value="<?=$marker->labelStrokeColor?>" />
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                          <div class="seperator">
                            <div class="left txt-16">Stroke &amp; Weight</div>
                            <div class="width-102 right">
                              <div class="input-color-wrap">
                                <?php
								$labelStrokeWeights = array();
								for($i=0 ; $i<=10 ; $i++){
									$labelStrokeWeights[] = $i;
								}
								if ($id){
									$selected = $marker->labelStrokeWeight;
								}else{
									$selected = 3;
								}?>
                                <select name="labelStrokeWeight[]" class="width-50 label-stroke-weight">
                                  <?php foreach($labelStrokeWeights as $labelStrokeWeight){?>
                                  <option <?=(($selected == $labelStrokeWeight)?'selected':'')?> value="<?=$labelStrokeWeight?>">
                                  <?=$labelStrokeWeight?>
                                  </option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="clr"></div>
                          </div>
                        </div>
                        <div class="display-none features-inner tab-icon main-tab-content">
                          <div class="feature-editor-outer">
                            <div class="feature-editor">
                              <input type="hidden" name="markerICON[]" class="marker-ICON" value="<?=$marker->markerICON?>" />
                              <select name="markerType[]" class="marker-type" onchange="markerType(jQuery(this));">
                                <option <?=(((($id == 0) || (($id > 0) && ($marker->marker_type == 'svg')))?'selected':''))?> value="svg">Svg Shapes (Customizable)</option>
                                <?php 
								$url = $this->pluginDirPath;
								if ($handle = opendir($url)) {
									while (false !== ($file = readdir($handle))) {
										if (($file != ".") && ($file != "..")){
											if(is_dir($url.$file)){?>
                                <option <?=(((($id == 0) || (($id > 0) && ($marker->marker_type == $file)))?'selected':''))?> value="<?=($file)?>">
                                <?=str_replace("_"," ",$file)?>
                                </option>
                                <?php
											}
										}
									}
								}?>
                              </select>
                            </div>
                          </div>
                          <?php 
                                                        $markerDir = $this->pluginDirPath ;
                                                        if ($handleMarkerDir = opendir($markerDir)) {
                                                            while (false !== ($readMarkerDir = readdir($handleMarkerDir))) {
                                                                if (($readMarkerDir != ".") && ($readMarkerDir != "..")){
                                                                    if(is_dir($markerDir.$readMarkerDir)){
                                                    ?>
                          <div class="table status-parent no-margin marker-type-<?=($readMarkerDir)?> <?=(((($id == 0) || (($id > 0) && ($marker->marker_type == $readMarkerDir)))?'':'display-no-imp'))?>">
                            <div class="tr nobackground">
                              <div class="td txt-16 left no-padding">Marker Shapes</div>
                            </div>
                            <div class="tr marker-shapes-font marker-png clearfix nobackground height-auto">
                              <?php 
                                                        $firstMarker = 1;
                                                        $urlMarkerFile = $this->pluginDirPath.$readMarkerDir.'/' ;
                                                        if ($handleMarkerFile = opendir($urlMarkerFile)) {
                                                            while (false !== ($fileMarkerPNG = readdir($handleMarkerFile))) {
                                                                if (($fileMarkerPNG != ".") && ($fileMarkerPNG != "..")){
                                                                    ?>
                              <i data-label-class="<?=$fileMarkerPNG?>" <?php if ((($id == 0) && ($firstMarker == 1)) || (($id > 0) && ($marker->markerICON == $fileMarkerPNG))){echo "class='selected-icon'";}?>> <img src='<?=$this->pluginDirURL."markers/".$readMarkerDir."/".$fileMarkerPNG?>' alt="<?=$fileMarkerPNG?>"> </i>
                              <?php $firstMarker++;}
                                                            }
                                                            closedir($handleMarkerFile);
                                                        }
                                                    ?>
                            </div>
                          </div>
                          <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                          <div class="table status-parent marker-type-svg no-margin <?=(((($id == 0) || (($id > 0) && ($marker->marker_type == 'svg')))?'':'display-no-imp'))?>">
                            <div class="nobackground tr">
                              <div class="no-padding td txt-16 left">Marker Shapes</div>
                            </div>
                            <div class="nobackground height-auto tr marker-shapes-font clearfix"> <?php echo $this->loadMarkerShapes(6, $id, $marker->markerICON);?> </div>
                            <div class="seperator table">
                              <div class="left txt-16">Fill Color </div>
                              <div class="right width-102">
                                <div class="inc-dec-wrap">
                                  <div class="left inc-dec-input fill-color"> </div>
                                  <div class="left inc-dec display-none">
                                    <div class="inc"></div>
                                    <div class="dec"></div>
                                  </div>
                                  <div class="clr"></div>
                                </div>
                                <div class="input-color-wrap color-wrp">
                                  <input type="text" name="fillColor[]" class="form-control demo input-color" data-control="hue" value="<?=$marker->fillColor?>" />
                                </div>
                              </div>
                              <div class="clr"></div>
                            </div>
                            <div class="seperator table">
                              <div class="left txt-16">Stroke Color </div>
                              <div class="right width-102">
                                <div class="inc-dec-wrap">
                                  <div class="left inc-dec-input fill-color"> </div>
                                  <div class="left inc-dec display-none">
                                    <div class="inc"></div>
                                    <div class="dec"></div>
                                  </div>
                                  <div class="clr"></div>
                                </div>
                                <div class="input-color-wrap color-wrp">
                                  <input type="text" name="markerStrokeColor[]" class="form-control demo marker-stroke-color" data-control="hue" value="<?=$marker->markerStrokeColor?>" />
                                </div>
                              </div>
                              <div class="clr"></div>
                            </div>
                            <div class="seperator">
                              <div class="left txt-16">Stroke Weight</div>
                              <div class="width-102 right">
                                <div class="inc-dec-wrap">
                                  <div class="left inc-dec-input fill-color"> </div>
                                  <div class="display-none left inc-dec">
                                    <div class="inc"></div>
                                    <div class="dec"></div>
                                  </div>
                                  <div class="clr"></div>
                                </div>
                                <div class="input-color-wrap">
                                  <?php
                                                        $markerStrokeWeights = array();
                                                        for($i=0 ; $i<=10 ; $i++){
                                                            $markerStrokeWeights[] = $i;
                                                        }
                                                        if ($id){
                                                            $selected = $marker->markerStrokeWeight;
                                                        }else{
                                                            $selected = 3;
                                                        }
                                                      ?>
                                  <select name="markerStrokeWeight[]" class="marker-stroke-weight width-50">
                                    <?php foreach($markerStrokeWeights as $markerStrokeWeight){?>
                                    <option <?=(($selected == $markerStrokeWeight)?'selected':'')?> value="<?=$markerStrokeWeight?>">
                                    <?=$markerStrokeWeight?>
                                    </option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="clr"></div>
                            </div>
                          </div>
                        </div>
                        <a class="btns fr txt-white droppin" href="javascript:void(0)">Update</a> </div>
                    </div>
                  </div>
                  <?php 
                                            }
                                        }
                                        ?>
                </div>
                <div class="getting-started-outer <?php if(($id > 0)  && (count($markers) > 0)){echo 'display-none';}else{}?>"> <a href="javascript:void(0);" class="clearfix make-clone">
                  <text>Add Marker </text>
                  &nbsp;&nbsp;&nbsp;
                  <label>+</label>
                  </a> <span class="txt-14">Getting Started</span> <img src="<?php echo $this->pluginDirURL?>images/marker-img.png" alt=""> </div>
              </div>
              <div class="option-bar crearfix <?php if(($id > 0)  && (count($markers) > 0)){}else{echo 'display-none';}?>">
                <ul>
                  <li><a href="javascript:void(0);" class="make-clone">Add Marker</a> </li>
                  <li class="dlt-all"><a href="javascript:void(0);" class="delete-all-markers">Delete All</a></li>
                  <li><a href="javascript:void(0);">Delete All</a></li>
                </ul>
              </div>
            </div>
            <!-- End Editing Marker Block --> <?php
		}
		
		public function loadMarkerShapes($limit = 0, $id = 0,  $icon = '') {

			$map_icons_array = array(
		
				'map-icon-map-pin', 		
				'map-icon-square-pin', 
				'map-icon-route-pin', 
				'map-icon-shield',
				'map-icon-square-rounded',
				'map-icon-square',
				'map-icon-flag',
				'map-icon-con', 
				'map-icon-circle', 
				'map-icon-expand', 
				'map-icon-fullscreen', 
				'map-icon-shield', 
				'map-icon-liquor-store', 
				'map-icon-bicycle-store', 
				'map-icon-hardware-store', 
				'map-icon-insurance-agency', 
				'map-icon-lawyer', 
				'map-icon-real-estate-agency', 
				'map-icon-art-gallery', 
				'map-icon-campground', 
				'map-icon-bakery', 
				'map-icon-bar', 
				'map-icon-amusement-park',
				'map-icon-aquarium',
				'map-icon-airport',
				'map-icon-bank',
				'map-icon-car-rental',
				'map-icon-car-dealer',
				'map-icon-hospital',
				'map-icon-hair-care',
				'map-icon-gym',
				'map-icon-grocery-or-supermarket',
				'map-icon-general-contractor',
				'map-icon-pharmacy',
				'map-icon-point-of-interest',
				'map-icon-political',
				'map-icon-post-box',
				'map-icon-health',
				'map-icon-post-office',
				'map-icon-real-estate-agency-copy',
				'map-icon-hindu-temple',
				'map-icon-restaurant',
				'map-icon-female',
				'map-icon-male',
				'map-icon-zoo',
				'map-icon-veterinary-care',
				'map-icon-car-repair',
				'map-icon-university',
				'map-icon-travel-agency',
				'map-icon-transit-station',
				'map-icon-beauty-salon',
				'map-icon-electronics-store',
				'map-icon-search',
				'map-icon-zoom-out-alt',
				'map-icon-movie-rental',
				'map-icon-atm',
				'map-icon-jewelry-store',
				'map-icon-car-wash',
				'map-icon-unisex',
				'map-icon-rv-park',
				'map-icon-school',
				'map-icon-clothing-store',
				'map-icon-laundry',
				'map-icon-casino',
				'map-icon-place-of-worship',
				'map-icon-furniture-store',
				'map-icon-zoom-in-alt',
				'map-icon-zoom-in',
				'map-icon-department-store',
				'map-icon-fire-station',
				'map-icon-church',
				'map-icon-library',
				'map-icon-shopping-mall',
				'map-icon-local-government',
				'map-icon-spa',
				'map-icon-convenience-store',
				'map-icon-police',
				'map-icon-route',
				'map-icon-zoom-out',
				'map-icon-location-arrow',
				'map-icon-postal-code',
				'map-icon-locksmith',
				'map-icon-doctor',
				'map-icon-mosque',
				'map-icon-stadium',
				'map-icon-storage',
				'map-icon-movie-theater',
				'map-icon-electrician',
				'map-icon-moving-company',
				'map-icon-postal-code-prefix',
				'map-icon-crosshairs',
				'map-icon-compass',
				'map-icon-dentist',
				'map-icon-plumber',
				'map-icon-museum',
				'map-icon-finance',
				'map-icon-parking',
				'map-icon-courthouse',
				'map-icon-accounting',
				'map-icon-store',
				'map-icon-subway-station',
				'map-icon-natural-feature',
				'map-icon-florist',
				'map-icon-food',
				'map-icon-night-club',
				'map-icon-synagogue', 
				'map-icon-taxi-stand', 
				'map-icon-painter', 
				'map-icon-train-station', 
				'map-icon-pet-store', 
				'map-icon-gas-station', 
				'map-icon-funeral-home', 
				'map-icon-cemetery', 
				'map-icon-bowling-alley', 
				'map-icon-roofing-contractor', 
				'map-icon-physiotherapist', 
				'map-icon-embassy', 
				'map-icon-city-hall', 
				'map-icon-bus-station', 
				'map-icon-park', 
				'map-icon-lodging', 
				'map-icon-toilet', 
				'map-icon-square', 
				'map-icon-book-store', 
				'map-icon-cafe', 
				'map-icon-wheelchair', 
				'map-icon-volume-control-telephone', 
				'map-icon-sign-language', 
				'map-icon-low-vision-access', 
				'map-icon-open-captioning', 
				'map-icon-closed-captioning', 
				'map-icon-braille', 
				'map-icon-audio-description', 
				'map-icon-assistive-listening-system', 
				'map-icon-abseiling', 
				'map-icon-tennis', 
				'map-icon-skateboarding', 
				'map-icon-playground', 
				'map-icon-inline-skating', 
				'map-icon-hang-gliding', 
				'map-icon-climbing', 
				'map-icon-baseball', 
				'map-icon-archery', 
				'map-icon-wind-surfing', 
				'map-icon-scuba-diving', 
				'map-icon-sailing', 
				'map-icon-marina', 
				'map-icon-canoe', 
				'map-icon-boat-tour', 
				'map-icon-boat-ramp', 
				'map-icon-swimming', 
				'map-icon-whale-watching', 
				'map-icon-waterskiing', 
				'map-icon-surfing', 
				'map-icon-rafting', 
				'map-icon-kayaking', 
				'map-icon-jet-skiing', 
				'map-icon-fishing-pier', 
				'map-icon-fish-cleaning', 
				'map-icon-diving', 
				'map-icon-boating', 
				'map-icon-fishing', 
				'map-icon-cross-country-skiing', 
				'map-icon-skiing', 
				'map-icon-snowmobile', 
				'map-icon-snowboarding', 
				'map-icon-snow', 
				'map-icon-snow-shoeing', 
				'map-icon-sledding', 
				'map-icon-ski-jumping', 
				'map-icon-ice-skating', 
				'map-icon-ice-fishing', 
				'map-icon-chairlift', 
				'map-icon-golf', 
				'map-icon-horse-riding', 
				'map-icon-motobike-trail', 
				'map-icon-trail-walking', 
				'map-icon-viewing', 
				'map-icon-walking', 
				'map-icon-bicycling'
			);

			$output = '';
			if($limit){
				$map_icons_array = array_slice($map_icons_array, 0, $limit);
			}
			if ($id){$first = 10000;}else{$first = 0;};
			foreach($map_icons_array as $key => $value) {
				if (($first == 0) || ($icon == $value)){
					$output .= '<i class="'.$value.' selected-icon" data-label-class="'.$value.'"></i>';
				}else{
					$output .= '<i class="'.$value.'" data-label-class="'.$value.'"></i>';
				}
				$first++;
			}
			return $output;
		}

		public function stylerJS(){
			$map = $this->map;
			if ($this->id && !empty($map->featured_map)){?>
				var styles = <?=html_entity_decode($map->featured_map)?>;
				map.set('styles', styles);<?php
			}
		}

		public function stylerHtml($stylers, $id=0){
			?>
              <!-- Editing Styler Block -->
              <div class="sections section-height" id="styler-section">
                <div class="outerScroll" id="outerScroll33">
                  <div class="clone-container" id="clone-container">
                    <?php if (count($stylers)){
                        foreach($stylers as $index=>$styler){?>
                    <div class="styler-container">
                      <div class="styler-head clearfix">
                        <div class="number">
                          <?=str_pad(($index+1), 2, '0', STR_PAD_LEFT);?>
                        </div>
                        <div class="styler-buttons clearfix"> <span class="styler-title">
                          <?php
                            if (count($this->features) && (($styler->feature != 'All') && ($styler->feature != 'all'))){
                                foreach($this->features as $feature){
                                    if ($feature['value'] == $styler->feature){
                                        $stylerTitle = explode("->",$feature['name']);
                                        if (count($stylerTitle) && (!empty($stylerTitle[0]))){
                                            echo $stylerTitle[0];
                                        }
                                    }
                                }
                            }else{
                                echo "All";	
                            }
                          ?>
                          </span> <a class="delete-styler"></a> <a href="" class="show-hide-buttons closed"></a> </div>
                      </div>
                      <div class="features-outer display-none">
                        <div class="features-inner" data-type="feature">
                          <div class="features-head">
                            <h2>Feature Type</h2>
                          </div>
                          <div class="feature-editor-outer">
                            <div class="feature-editor">
                              <select name="feature[]" class="feature" onchange="featureType(jQuery(this));">
                                <option value="all">All</option>
                                <?php if (count($this->features)){
                                    foreach($this->features as $feature){
                                        if ($feature['value'] == $styler->feature){
                                            ?><option selected="selected" value="<?=$feature['value']?>"><?=$feature['name']?></option><?php 
                                        }else{
                                    ?>
                                <option value="<?=$feature['value']?>"><?=$feature['name']?></option>
                                <?php	
                                        }
                                    }
                                }?>
                              </select>
                            </div>
                          </div>
                          <div class="features-head">
                            <h2>Element Type</h2>
                          </div>
                          <div class="feature-editor-outer">
                            <div class="feature-editor">
                              <select name="element[]" class="element" onchange="loadStyler()">
                                <option value="all">All</option>
                                <?php if (count($this->elements)){
                                    foreach($this->elements as $element){
                                        if ($element['value'] == $styler->element){
                                            ?><option selected="selected" value="<?=$element['value']?>"><?=$element['name']?></option><?php 
                                        }else{
                                            ?><option value="<?=$element['value']?>"><?=$element['name']?></option><?php
                                        }
                                    }
                              }?>
                              </select>
                            </div>
                          </div>
                          <div class="styling-outer">
                            <div class="styling-inner">
                              <div class="styling-head clearfix">
                                <div class="styling-left clearfix">
                                  <label> <span>Visibility</span> </label>
                                </div>
                                <div class="styling-right">
                                  <select class="visibilities" name="visibilities[]" data-correspondingid="#visibilities" onChange="triggerOperation(this, 'change'); setCheckbox(this, 'parents', '.styling-head');">
                                    <?php foreach($this->visibilities as $key=>$visibility){?>
                                    <option <?=(($styler->visibility == $key)?'selected':'')?> value="<?=$key?>" data-correspondingid="#visibility_on">
                                    <?=$visibility?>
                                    </option>
                                    <?php }?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="styling-inner">
                              <div class="styling-head clearfix">
                                <div class="styling-left clearfix">
                                  <label> <span>Invert Lightness</span> </label>
                                </div>
                                <div class="styling-right">
                                  <select class="invert-lightness" name="invertLightness[]" onchange="loadStyler()">
                                    <option <?=((0 == $styler->invert_lightness)?'selected':'')?>  value="0">None</option>
                                    <option <?=((1 == $styler->invert_lightness)?'selected':'')?>  value="1">Standard</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="styling-inner clearfix">
                              <div class="styling-head clearfix">
                                <div class="ranges styling-left clearfix">
                                  
                                  <div class="checkbox-wrap">
                                    <input id="color" <?php if (!empty($styler->color)){echo "checked";}?> type="checkbox" class="chkbx-color ch-styler-color" name="chStylerColor[]" data-correspondingid="#set_color" onClick="if (jQuery(this).is(':checked')){jQuery(this).next().val('1');}else{jQuery(this).next().val('0');}triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'next', '.styling-head', '128', 'range'); resetCorrespondingElement(this, 'next', '.styling-left', '#ff0000', 'color');">
                                    <label>Color</label>                                         
                                    <input type="hidden" name="chStyleColor[]" class="ch-style-color" value="<?php if (!empty($styler->color)){?>1<?php }else{?>0<?php }?>" />
                                  </div>
                                </div>
                                <div class="styling-right color-wrp">
                                  <input maxlength="7" type="text" name="stylerColor[]" class="form-control demo styler-color" data-control="hue" value="<?php if (!empty($styler->color)){echo $styler->color;}else{echo '#333333';}?>" />
                                </div>
                              </div>
                            </div>
                            <div class="styling-inner clearfix">
                              <div class="ranges with-check clearfix">
                                <div class="checkbox-wrap">
                                  <input id="weight" <?php if (floatval($styler->weight != 0)){echo "checked";}?> value="1" name="weight[]" type="checkbox" class="chkbx-weight weight" data-correspondingid="#set_weight" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                  <label>Weight</label>                                          
                                </div>
                                <input name="weightRange[]" type="range" class="range weightSlider weight-range" min="0" max="200" value="<?=floatval(($styler->weight)*10)?>" data-correspondingid="#weightSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                                <div class="output-range">
                                  <?php if (floatval($styler->weight != 0)){echo $styler->weight;}?>
                                </div>
                              </div>
                            </div>
                            <div class="advace-settings">
                              <div class="styling-inner clearfix">
                                <div class="styling-head clearfix">
                                  <div class="ranges styling-left clearfix">
                                    <div class="checkbox-wrap">
                                      <input id="hue" <?php if (!empty($styler->hue)){echo "checked";}?> name="hue[]" type="checkbox" class="chkbx-hue hue" data-correspondingid="#set_hue" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'next', '.styling-left', '#ff0000', 'color')">
                                      <input type="hidden" name="chHue[]" class="ch-hue" value="<?php if (!empty($styler->hue)){?>1<?php }else{?>0<?php }?>" />
                                      <label> Hue</label>
                                    </div>
                                  </div>
                                  <div class="styling-right color-wrp">
                                    <input maxlength="7" type="text" name="hueColor[]" class="form-control demo hue-color" data-control="hue" value="<?php if (!empty($styler->hue)){echo $styler->hue;}else{echo '#cccccc';}?>" />
                                  </div>
                                </div>
                              </div>
                              <div class="styling-inner clearfix">
                                <div class="ranges with-check clearfix">
                                  <div class="checkbox-wrap">
                                    <input id="saturation" <?php if (floatval($styler->saturation != 0)){echo "checked";}?> name="saturation[]" type="checkbox" class="chkbx-sat saturation" data-correspondingid="#set_saturation" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                    <label>Saturation</label>
                                  </div>
                                  <input name="saturationRange[]" type="range" class="range satSlider saturation-range" min="-100" max="100" value="<?=$styler->saturation?>" data-correspondingid="#satSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                                  <div class="output-range">
                                    <?php if (floatval($styler->saturation != 0)){echo $styler->saturation;}?>
                                  </div>
                                </div>
                              </div>
                              <div class="styling-inner clearfix">
                                <div class="ranges with-check clearfix">
                                  <div class="checkbox-wrap">
                                    <input id="lightness" <?php if (floatval($styler->lightness != 0)){echo "checked";}?> name="lightness[]" type="checkbox" class="chkbx-lightness lightness" data-correspondingid="#set_lightness" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                  <label>Lightness</label>
                                  </div>
                                  <input name="lightnessRange[]" type="range" class="range lightSlider lightness-range" min="-100" max="100" value="<?=$styler->lightness?>" data-correspondingid="#lightSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                                  <div class="output-range">
                                    <?php if (floatval($styler->lightness != 0)){echo $styler->lightness;}?>
                                  </div>
                                </div>
                              </div>
                              <div class="styling-inner clearfix">
                                <div class="ranges with-check clearfix">
                                  <div class="checkbox-wrap">
                                    <input id="gamma" <?php if (intval($styler->gamma != 0)){echo "checked";}?> name="gamma[]" type="checkbox" class="chkbx-gamma gamma" data-correspondingid="#set_gamma" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                    <label>Gamma</label>
                                  </div>
                                  <input name="gammaRange[]" type="range" class="range gammaSlider gamma-range" min="-100" max="100" value="<?=$styler->gamma?>" data-correspondingid="#gammaSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                                  <div class="output-range">
                                    <?php if (floatval($styler->gamma != 0)){echo $styler->gamma;}?>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- SUB FEATURES --> 
                        </div>
                      </div>
                    </div>
                    <?php 
                            }
                        }
                    ?>
                  </div>
                  <div class="getting-started-outer <?php if ($id && count($stylers)){?>display-none<?php }else{}?>"> <a href="javascript:void(0);" class="clearfix make-clone">
                    <text>Add Styler</text>
                    &nbsp;&nbsp;&nbsp;
                    <label>+</label>
                    </a> <span class="txt-14">Getting Started</span> <img src="<?php echo $this->pluginDirURL.'images/getting-started-img.png'?>" alt=""> </div>
                </div>
                <div class="option-bar crearfix <?php if ($id && count($stylers)){}else{?>display-none<?php }?>">
                  <ul>
                    <li> <a href="javascript:void(0);" class="make-clone">Add Styler</a>
                    </li>
                    <li class="dlt-all"><a href="javascript:void(0);" class="delete-all-styler">Delete All</a></li>
                    <li><a href="javascript:void(0);"></a></li>
                  </ul>
                </div>
              </div>
              <!-- End Editing Styler Block -->
            <?php
		}

		public function stylerAddNew(){		
			?>
              <!-- Styler Block -->
              <div class="display-none clone" id="clone" style="display:none">
                <div class="styler-container">
                  <div class="styler-head clearfix">
                    <div class="number">01</div>
                    <div class="styler-buttons clearfix"> <span class="styler-title">All</span> <a class="delete-styler"></a> <a href="" class="show-hide-buttons"></a> </div>
                  </div>
                  <div class="features-outer">
                    <div class="features-inner" data-type="feature">
                      <div class="features-head">
                        <h2>Feature Type</h2>
                      </div>
                      <div class="feature-editor-outer">
                        <div class="feature-editor">
                          <select name="feature[]" class="feature" onchange="featureType(jQuery(this));">
                            <option value="all">All</option>
                            <?php if (count($this->features)){
                                foreach($this->features as $feature){?>
                            <option value="<?=$feature['value']?>">
                            <?=$feature['name']?>
                            </option>
                            <?php 
                                }
                            }?>
                          </select>
                        </div>
                      </div>
                      <div class="features-head">
                        <h2>Element Type</h2>
                      </div>
                      <div class="feature-editor-outer">
                        <div class="feature-editor">
                          <select name="element[]" class="element" onchange="loadStyler()">
                            <option value="all">All</option>
                            <?php if (count($this->elements)){
                                foreach($this->elements as $element){?>
                            <option value="<?=$element['value']?>">
                            <?=$element['name']?>
                            </option>
                            <?php 
                                }
                            }?>
                          </select>
                        </div>
                      </div>
                      <div class="styling-outer">
                        <div class="styling-inner">
                          <div class="styling-head clearfix">
                            <div class="styling-left clearfix">
                              <label> <span>Visibility</span> </label>
                            </div>
                            <div class="styling-right">
                              <select class="visibilities" name="visibilities[]" data-correspondingid="#visibilities" onChange="triggerOperation(this, 'change'); setCheckbox(this, 'parents', '.styling-head');">
                                <?php foreach($this->visibilities as $key=>$visibility){?>
                                <option value="<?=$key?>" data-correspondingid="#visibility_on">
                                <?=$visibility?>
                                </option>
                                <?php }?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="styling-inner">
                          <div class="styling-head clearfix">
                            <div class="styling-left clearfix">
                              <label> <span>Invert Lightness</span> </label>
                            </div>
                            <div class="styling-right">
                              <select class="invert-lightness" name="invertLightness[]" onchange="loadStyler()">
                                <option value="0">None</option>
                                <option value="1">Standard</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="styling-inner clearfix">
                          <div class="styling-head clearfix">
                            <div class="ranges styling-left clearfix">
                              <div class="checkbox-wrap">
                                <input id="color" type="checkbox" class="chkbx-color ch-styler-color" name="chStylerColor[]" data-correspondingid="#set_color" onClick="if (jQuery(this).is(':checked')){jQuery(this).next().val('1');}else{jQuery(this).next().val('0');}triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'next', '.styling-head', '128', 'range'); resetCorrespondingElement(this, 'next', '.styling-left', '#ff0000', 'color');">
                                <input type="hidden" name="chStyleColor[]" class="ch-style-color" value="0" />
                                <label>Color</label>
                              </div>
                            </div>
                            <div class="styling-right color-wrp">
                              <input maxlength="7" type="text" value="" data-control="hue" class="form-control demo styler-color display_none minicolors-input" name="stylerColor[]" size="7" />
                            </div>
                          </div>
                        </div>
                        <div class="styling-inner clearfix">
                          <div class="ranges with-check clearfix">
                            <div class="checkbox-wrap">
                              <input id="weight" value="1" name="weight[]" type="checkbox" class="chkbx-weight weight" data-correspondingid="#set_weight" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                              <label>Weight</label>
                            </div>
                            <input name="weightRange[]" type="range" class="range weightSlider weight-range" min="0" max="200" value="0" data-correspondingid="#weightSlider" onChange="setCheckbox(this, 'parents', '.ranges'); " />
                            <div class="output-range"></div>
                          </div>
                        </div>
                        <div class="advace-settings">
                          <div class="styling-inner clearfix">
                            <div class="styling-head clearfix">
                              <div class="ranges styling-left clearfix">
                                <div class="checkbox-wrap">
                                  <input id="hue" name="hue[]" type="checkbox" class="chkbx-hue hue" data-correspondingid="#set_hue" onClick="if (jQuery(this).is(':checked')){jQuery(this).next().val('1');}else{jQuery(this).next().val('0');}triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'next', '.styling-left', '#ff0000', 'color')">
                                  <input type="hidden" name="chHue[]" class="ch-hue" value="0" />
                                  <label>Hue</label>
                                </div>
                                
                              </div>
                              <div class="styling-right color-wrp">
                                <input maxlength="7" type="text" name="hueColor[]" class="form-control demo hue-color display_none" data-control="hue" value="" />
                              </div>
                            </div>
                          </div>
                          <div class="styling-inner clearfix">
                            <div class="ranges with-check clearfix">
                              <div class="checkbox-wrap">
                                <input id="saturation" name="saturation[]" type="checkbox" class="chkbx-sat saturation" data-correspondingid="#set_saturation" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                <label>Saturation</label>                    
                              </div>
                              <input name="saturationRange[]" type="range" class="range satSlider saturation-range" min="-100" max="100" value="0" data-correspondingid="#satSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                              <div class="output-range"></div>
                            </div>
                          </div>
                          <div class="styling-inner clearfix">
                            <div class="ranges with-check clearfix">
                              <div class="checkbox-wrap">
                                <input id="lightness" name="lightness[]" type="checkbox" class="chkbx-lightness lightness" data-correspondingid="#set_lightness" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                <label>Lightness</label>
                              </div>
                              <input name="lightnessRange[]" type="range" class="range lightSlider lightness-range" min="-100" max="100" value="0" data-correspondingid="#lightSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                              <div class="output-range"></div>
                            </div>
                          </div>
                          <div class="styling-inner clearfix">
                            <div class="ranges with-check clearfix">
                              <div class="checkbox-wrap">
                                <input id="gamma" name="gamma[]" type="checkbox" class="chkbx-gamma gamma" data-correspondingid="#set_gamma" onClick="triggerOperation(this, 'click');" onChange="resetCorrespondingElement(this, 'parents', '.ranges', '0', 'range')">
                                <label>Gamma</label>
                              </div>
                              <input name="gammaRange[]" type="range" class="range gammaSlider gamma-range" min="-100" max="100" value="0" data-correspondingid="#gammaSlider" onChange="setCheckbox(this, 'parents', '.ranges')">
                              <div class="output-range"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Styler Block -->
            <?php
		}
		
		public function operationMenu(){
		?>
            <div class="top-link ">
              <ul class="clearfix">
                <li> <a href="admin.php?page=map">New Map</a> </li>
                <li> <a onClick="jQuery('#SaveMap').val(1);saveMap(1)">Save &amp; Exit</a> </li>
                <li> <a onClick="jQuery('#SaveMap').val(2);saveMap(1)">Save</a> </li>
                <li class="dropdown"> <a href="javascript.void(0);"></a>
                  <ul>
                    <li> <a href="javascript.void(0);"></a>
                      <ul>
                        <?php if ($this->id > 0){?>
                        <li><a onclick="return confirm('Are you sure?')" href="admin.php?page=myplug/muyplg.php&mapid=<?=$this->id;?>">delete map</a></li>
                        <?php }?>
                        <li><a href="admin.php?page=map<?php if ($this->id){?>&id=<?=$this->id;}?>">default reset</a></li>
                        <li><a href="http://mapwiz.io/faq/Helping-guide/" target="_blank">helping guide</a></li>
                        <li><a href="http://mapwiz.io/#contactform" target="_blank">report bug</a></li>
                        <li><a href="http://mapwiz.io" target="_blank">about mapwiz</a></li>
                        <li><a href="http://mapwiz.io/#contactform" target="_blank">contact us</a></li>
                        <li><a onclick="return confirm('Do you wish to exit ?')"  href="admin.php?page=myplug%2Fmuyplg.php">Exit</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>        
        <?php	
		}
		
		public function settingMenu(){
			$map = $this->map;
			$localizations = array();
			$localizations['ar'] = 'Arabic'; 
			$localizations['eu'] = 'Basque'; 
			$localizations['bn'] = 'Bengali'; 
			$localizations['bg'] = 'Bulgarian'; 
			$localizations['ca'] = 'Catalan'; 
			$localizations['zh-CN'] = 'Chinese (Simplified)'; 
			$localizations['zh-TW'] = 'Chinese (Traditional)'; 
			$localizations['hr'] = 'Croatian'; 
			$localizations['cs'] = 'Czech'; 
			$localizations['da'] = 'Danish'; 
			$localizations['nl'] = 'Dutch'; 
			$localizations['en'] = 'English'; 
			$localizations['en-AU'] = 'English (Australian)'; 
			$localizations['en-GB'] = 'English (Great Britain)'; 
			$localizations['fa'] = 'Farsi'; 
			$localizations['fil'] = 'Filipino'; 
			$localizations['fi'] = 'Finnish'; 
			$localizations['fr'] = 'French'; 
			$localizations['gl'] = 'Galician'; 
			$localizations['de'] = 'German'; 
			$localizations['el'] = 'Greek'; 
			$localizations['gu'] = 'Gujarati'; 
			$localizations['hi'] = 'Hindi'; 
			$localizations['hu'] = 'Hungarian'; 
			$localizations['id'] = 'Indonesian'; 
			$localizations['it'] = 'Italian'; 
			$localizations['iw'] = 'iw'; 
			$localizations['ja'] = 'Japanese'; 
			$localizations['kn'] = 'Kannada'; 
			$localizations['ko'] = 'Korean'; 
			$localizations['lv'] = 'Latvian'; 
			$localizations['lt'] = 'Lithuanian'; 
			$localizations['ml'] = 'Malayalam'; 
			$localizations['mr'] = 'Marathi'; 
			$localizations['no'] = 'Norwegian'; 
			$localizations['pl'] = 'Polish'; 
			$localizations['pt'] = 'Portuguese'; 
			$localizations['pt-BR'] = 'Portuguese (Brazil)'; 
			$localizations['pt-PT'] = 'Portuguese (Portugal)'; 
			$localizations['ro'] = 'Romanian'; 
			$localizations['ru'] = 'Russian'; 
			$localizations['sr'] = 'Serbian'; 
			$localizations['sk'] = 'Slovak'; 
			$localizations['sl'] = 'Slovenian'; 
			$localizations['es'] = 'Spanish'; 
			$localizations['sv'] = 'Swedish'; 
			$localizations['tl'] = 'Tagalog'; 
			$localizations['ta'] = 'Tamil'; 
			$localizations['te'] = 'Telugu'; 
			$localizations['th'] = 'Thai'; 
			$localizations['tr'] = 'Turkish'; 
			$localizations['uk'] = 'Ukrainian'; 
			$localizations['vi'] = 'Vietnamese';
			?>
            <!-- Setting -->            
            <div class="sections section-height" id="setting-section" >
              <div class="clone not-styler" id="marker-clone">
                <div class="styler-container setting-tab">
                  <div class="features-outer no-padding">
                    <ul class="tabs">
                      <li class="tab current-tab" data-tab-content=".tab-display">Display</li>
                      <li class="tab" data-tab-content=".tab-controls">Controls</li>
                    </ul>
                    <div class="tabs-set tabs-set-pad">
                      <div class="features-inner tab-display main-tab-content display-block">
                        <div class="seperator">
                          <div class="txt-16 map-dimentions">Map Dimentions</div>
                          <div class="dimentions-area">
                            <div class="fl"> <span> Width </span>
                              <input class="regular-text map-width" name="mapWidth" id="mapWidth" value="<?php if ($this->id > 0){echo $map->width;}else{echo '100';}?>" type="text" onChange="mapSizeWidthValidation()" />
                              <div class="clr"></div>
                            </div>
                            <div class="fr width-112">
                              <label style="color:#FFF; padding-right:5px;">
                                <input  style="opacity: 1;  background-color:#161f25; margin-right: 1px !important;" <?php if (($this->id == 0) || (($this->id >0 ) && ($map->width_unit == "%"))){echo 'checked="checked"';}?> id="mapWidthUnit1" name="mapWidthUnit" class="width_radio" value="%" type="radio" onClick="setMapSize()" />
                                %&nbsp;&nbsp; </label>
                              <label style="color:#FFF">&nbsp;
                                <input style="opacity: 1; background-color:#161f25; margin-right: -3px !important;" <?php if (($this->id > 0) && ($map->width_unit == "px")){echo 'checked="checked"';}?>  id="mapWidthUnit2"  name="mapWidthUnit" class="width_radio" value="px" type="radio" onClick="setMapSize()" />
                                px </label>
                              <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                          </div>
                          <div class="dimentions-area"> <span> Height </span>
                            <input class="regular-text map-height" name="mapHeight" id="mapHeight" value="<?php if ($this->id > 0){echo $map->height;}else{echo '800';}?>" type="text" onChange="mapSizeWidthValidation()" />
                            <div class="clr"></div>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Zoom (Default)</div>
                          <div class="right width-112" >
                            <?php
								$zoom = array();
								if ($this->id ){
									$zoomDefault = $map->zoom;
								}else{
									$zoomDefault = 2;
								}
								for($i=0 ; $i<=18 ; $i++){
									$zoom[] = $i;
								}
							?>
                            <select name="settingZoom" id="settingZoom" onChange="changeZoom(jQuery(this).val())">
                              <?php foreach($zoom as $z){?>
                              <option <?php if ($zoomDefault == $z){echo "selected";} ?> value="<?=$z?>">
                              <?=$z?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Map Type</div>
                          <div class="right width-112">
                            <?php
								$mapTypes = array();
								$mapTypes['ROADMAP'] = 'ROADMAP';
								$mapTypes['SATELLITE'] = 'SATELLITE';
								$mapTypes['HYBRID'] = 'HYBRID';
								$mapTypes['TERRAIN'] = 'TERRAIN';
							  ?>
                            <select name="settingMapType" id="settingMapType" onChange="changeMapType(jQuery(this).val())">
                              <?php if ($mapTypes){
                                                        foreach($mapTypes as $key=>$mapType){?>
                              <option <?php if ($this->id && ($map->mapTypeId === $key)){echo "selected";}?> value="<?=$key?>">
                              <?=$mapType?>
                              </option>
                              <?php } }?>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Localization</div>
                          <div class="right width-112">
                            <select id="settingLocalization" name="settingLocalization" onchange="buyMsg()">
                              <?php foreach($localizations as $code=>$local){?>
                              <option value="<?=$code?>">
                              <?=$local?>
                              </option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                      </div>
                      <div class="features-inner tab-controls main-tab-content display-none">
                        <div class="seperator border-bottom-none">
                          <div class="left txt-16">Zoom Control</div>
                          <div class="right width-112">
                            <select id="settingZoomControl" name="settingZoomControl" onChange="changeZoomChange()">
                              <option value="none" <?php if ($this->id && ($map->zoomControl == 0)){echo "selected";}?>>None</option>
                              <option value="DEFAULT" <?php if ($this->id && ($map->zoomControl == 1) && ($map->zoomControlOptions == 'DEFAULT')){echo "selected";}?>>Standard</option>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Position</div>
                          <div class="right width-112">
                            <?php 
								$positions = array();
								$positions['TOP_LEFT'] = 'TOP_LEFT';
								$positions['TOP_CENTER'] = 'TOP_CENTER';
								$positions['TOP_RIGHT'] = 'TOP_RIGHT';
								$positions['BOTTOM_LEFT'] = 'BOTTOM_LEFT';
								$positions['BOTTOM_RIGHT'] = 'BOTTOM_RIGHT';
								$positions['BOTTOM_CENTER'] = 'BOTTOM_CENTER';
								$positions['LEFT_CENTER'] = 'LEFT_CENTER';
								$positions['RIGHT_CENTER'] = 'RIGHT_CENTER';
							  ?>
                            <select id="settingZoomControlPosition" name="settingZoomControlPosition" onChange="changeZoomChange()">
                              <?php 
								foreach($positions as $key=>$position){
									if (($this->id > 0) && ($map->zoom_control_position == $position)){
										?><option selected="selected" value="<?=$key?>"><?=$position?></option><?php 
									}else{
										?><option value="<?=$key?>"><?=$position?></option><?php
									}
								}?>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator border-bottom-none">
                          <div class="left txt-16">Street View </div>
                          <div class="right width-112">
                            <select id="settingStreetView" name="settingStreetView" onChange="updateStreetView()">
                              <option value="none" <?=((($this->id > 0 ) && ($map->streetView == 0))?' selected':'')?>>None</option>
                              <option <?=((($this->id > 0 ) && ($map->streetView == 1))?' selected':'')?> value="1">Standard</option>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Position</div>
                          <div class="right width-112">
                            <select id="settingStreetViewPosition" name="settingStreetViewPosition" onChange="updateStreetView()">
                              <?php 
								foreach($positions as $key=>$position){
									if (($this->id > 0) && ($map->street_view_position == $position)){
										?><option selected="selected" value="<?=$key?>"><?=$position?></option><?php 
										}else{
											?><option value="<?=$key?>"><?=$position?></option><?php
									}
								}?>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator border-bottom-none">
                          <div class="left txt-16">Map Control Type</div>
                          <div class="right width-112">
                            <select class="cmb_select" name="settingMapControlType" id="settingMapControlType" onChange="setMapTypeControl()">
                              <option value="none" <?=(($this->id && ($map->mapTypeControl == 0))?' selected':'')?>>None</option>
                              <option value="horizontal_bar" <?php if ($this->id && ($map->mapTypeControl == 1) && ($map->mapTypeControlOptions == 'horizontal_bar')){echo "selected";}?>>Standard</option>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Position</div>
                          <div class="right width-112">
                            <select id="settingMapControlTypePosition" name="settingMapControlTypePosition" onChange="setMapTypeControl()">
                              <?php 
							  	foreach($positions as $key=>$position){
									if (($this->id > 0) && ($map->map_type_control_position == $position)){
										?><option selected="selected" value="<?=$key?>"><?=$position?></option><?php 
									}else{
										?><option value="<?=$key?>"><?=$position?></option><?php
                                    }
								}?>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Draggable Map</div>
                          <div class="right width-112">
                            <select id="settingDraggableMap" name="settingDraggableMap" onChange="setDraggable()">
                              <option <?=((($this->id > 0) && ($map->draggable == 1))?' selected':'')?> value="true">Standard</option>
                              <option <?=((($this->id > 0) && ($map->draggable == 0))?' selected':'')?> value="none">None</option>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Double Click Zoom</div>
                          <div class="right width-112">
                            <select id="settingDoubleClickZoom" name="settingDoubleClickZoom" onChange="setDoubleClickZoom()">
                              <option <?=((($this->id > 0) && ($map->disableDoubleClickZoom == 1))?' selected':'')?> value="none">None</option>
                              <option <?=((($this->id > 0) && ($map->disableDoubleClickZoom == 0))?' selected':'')?> value="true">Standard</option>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                        <div class="seperator">
                          <div class="left txt-16">Mouse Scroll</div>
                          <div class="right width-112">
                            <select id="settingMouseScroll" name="settingMouseScroll" onChange="setMouseScroll()">
                              <option <?=((($this->id > 0) && ($map->scrollwheel == 1))?' selected':'')?> value="true">Standard</option>
                              <option <?=((($this->id > 0) && ($map->scrollwheel == 0))?' selected':'')?> value="none">None</option>
                            </select>
                          </div>
                          <div class="clr"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Setting --> <?php
		}
	}
?>