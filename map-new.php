<?php
	/* Map Class  */
	require_once("classes/featuredmapsclass.php");
	require_once("classes/htmlclass.php");

	$objMap = new mapClass();
	$objFeaturedMaps = new featuredMapsClass();
	$objHtml = new htmlClass();
	$objMap->admin = 1;
	$objMap->pluginDirPath = plugin_dir_path(__FILE__ )."markers/";
	$objMap->pluginDirURL = plugin_dir_url( __FILE__ );
	$objMap->pluginsURL = plugins_url(__FILE__ );
		
	/* Intialized the variables */
	global $wpdb;
	$id = 0;
	$markers = array();
	$stylers = array();
	$map = array();

	$objMap->save($_POST);

	/* Get Map Using Id */

	$objMap->getMap($_REQUEST);
	$map = $objMap->map;
	$id = $objMap->id;
	$markers = $objMap->markers;
	$primaryMarker = $objMap->primaryMarker;
	$stylers = $objMap->stylers;
	/* End - Get Map Using Id */

	/* Load CSS Files */
	wp_enqueue_style( 'mapwiz-stylemap', plugins_url( 'css/stylemap.css' , __FILE__ ), array(), false, false );
	wp_enqueue_style( 'mapwiz-msgBoxLight', plugins_url( 'css/msgBoxLight.css' , __FILE__ ), array(), false, false );
	wp_enqueue_style( 'mapwiz-admin', plugins_url( 'css/admin.css' , __FILE__ ), array(), false, false );
	wp_enqueue_style( 'mapwiz-animate', plugins_url( 'css/animate.css' , __FILE__ ), array(), false, false );
	wp_enqueue_style( 'mapwiz-map-icons', plugins_url( 'css/map-icons.css' , __FILE__ ), array(), false, false );
	wp_enqueue_style( 'mapwiz-maker-info-window', plugins_url( 'css/maker-info-window.css' , __FILE__ ), array(), false, false );
	wp_enqueue_style( 'mapwiz-jquery.minicolor', plugins_url( 'css/jquery.minicolors.css' , __FILE__ ), array(), false, false );
	/* End Load CSS Files */

	/* Load JS Files */
    wp_enqueue_script( 'maps', 'http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places&language=en' );
    wp_enqueue_script( 'mapwiz-jquery.msgBox', plugin_dir_url( __FILE__ ) . 'js/jquery.msgBox.js' );
    wp_enqueue_script( 'mapwiz-custom', plugin_dir_url( __FILE__ ) . 'js/custom.js' );
    wp_enqueue_script( 'mapwiz-enscroll-0.6.1.min', plugin_dir_url( __FILE__ ) . 'js/enscroll-0.6.1.min.js' );
    wp_enqueue_script( 'mapwiz-less', plugin_dir_url( __FILE__ ) . 'js/less.js' );
    wp_enqueue_script( 'mapwiz-jquery.minicolors', plugin_dir_url( __FILE__ ) . 'js/jquery.minicolors.js' );
    wp_enqueue_script( 'mapwiz-maplabel-compiled', plugin_dir_url( __FILE__ ) . 'js/maplabel-compiled.js' );
    wp_enqueue_script( 'mapwiz-setting', plugin_dir_url( __FILE__ ) . 'js/setting.js' );
?>
<!-- End JS Files -->
<script type="text/javascript">

	/* Intialized JS Variables */
	var allMarkers = {};
	var markerSharps = {
		'map-icon-map-pin':{'marker':'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z','scale':1.0},
		'map-icon-route-pin':{'marker':'M49.986-58.919c-0.51-27.631-16.538-38.612-17.195-39.049l-2.479-1.692l-2.5 1.689c-4.147 2.817-8.449 4.247-12.783 4.247 c-7.178 0-12.051-3.864-12.256-4.032L-0.023-100l-2.776 2.248c-0.203 0.165-5.074 4.028-12.253 4.028 c-4.331 0-8.63-1.429-12.788-4.253l-2.486-1.678l-2.504 1.692c-1.702 1.17-16.624 12.192-17.165 38.907 C-50.211-56.731-43.792-12.754-0.003 0C47.609-13.912 50.23-56.018 49.986-58.919z','scale':0.3},
		'map-icon-square-pin':{'marker':'M 50 -119.876 -50 -119.876 -50 -19.876 -13.232 -19.876 0.199 0 13.63 -19.876 50 -19.876 Z','scale':0.3},
		'map-icon-shield':{'marker':'M42.8-72.919c0.663-7.855 3.029-15.066 7.2-21.675L34.002-110c-5.054 4.189-10.81 6.509-17.332 6.919 c-5.976 0.52-11.642-0.574-16.971-3.287c-5.478 2.626-11.121 3.723-17.002 3.287c-6.086-0.523-11.577-2.602-16.495-6.281 l-16.041 15.398c3.945 6.704 6.143 13.72 6.574 21.045c0.205 3.373-0.795 8.016-3.038 14.018c-1.175 3.327-2.061 6.213-2.667 8.627 c-0.562 2.394-0.911 4.34-1.027 5.801c-0.082 6.396 1.78 12.168 5.602 17.302c2.986 3.745 7.911 7.886 14.748 12.41 c7.482 3.665 13.272 6.045 17.326 7.06c1.163 0.521 2.301 1.025 3.363 1.506C-7.9-5.708-6.766-5.232-5.586-4.713 C-3.034-3.242-1.243-1.646-0.301 0C0.858-1.782 2.69-3.338 5.122-4.713c1.717-0.723 3.173-1.346 4.341-1.896 c1.167-0.494 2.037-0.865 2.54-1.09c0.866-0.414 2.002-0.888 3.376-1.41c1.386-0.527 3.101-1.168 5.144-1.882 c3.951-1.348 6.83-2.62 8.655-3.77c6.634-4.524 11.48-8.595 14.566-12.235c3.958-5.152 5.879-10.953 5.79-17.475 c-0.232-2.922-1.52-7.594-3.85-13.959C43.463-64.631 42.479-69.445 42.8-72.919z','scale':0.3},
		'map-icon-square-rounded':{'marker':'M50-80c0-11-9-20-20-20h-60c-11 0-20 9-20 20v60c0 11 9 20 20 20h60c11 0 20-9 20-20V-80z','scale':0.3},
		'map-icon-flag':{'marker':'M  0,0,  0,-40,  20,-30 , 0,-20,  z','scale':1.2},
		'map-icon-square':{'marker':'M-24-48h48v48h-48z','scale':0.6},
		'map-icon-con':{'marker':'M 100 -20 L -100 -20 L -0 -400 z','scale':0.1},
		'map-icon-circle':{'marker':'M24-8c0 4.4-3.6 8-8 8h-32c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h32c4.4 0 8 3.6 8 8v32z','scale':1.0}
	};
	var plungInPath = '<?php echo plugin_dir_url( __FILE__ )?>';
    var selectedMapType = '<?php if ($objMap->id > 0){echo $map->mapTypeId;}else{echo "ROADMAP";}?>';
	var pathICON = '<?php echo plugin_dir_url( __FILE__ )?>';
	var features = <?=json_encode($objMap->features)?>;
	var elements = <?=json_encode($objMap->elements)?>;
	
</script>
<!-- Main Container -->

<div id="main-outer">
  <div class="main-height">
    <div xmlns="http://www.w3.org/1999/xhtml" class="wrap nosubsub mapapp-styling">
      <div class="icon32" id="icon-edit"><br/>
      </div>
      <div id="col-left" class="main-width">
        <div class="col-wrap">
          <div>
            <div class="form-wrap main-form-width">
              <form id="frmMAP" name="frmMAP" action="admin.php?page=myplug/muyplg.php&mid<?php if ($objMap->id){echo "=".$objMap->id;}?>" method="post">
                <div class="form-field form-container"> 
                  <!-- Page Loader -->
                  <div id="preloader">
                    <div id="status">
                      <div class="circleLoader"></div>
                    </div>
                  </div>
                  <!-- End Page Loader -->
                  
                  <div class="rhs-pannel-outer show-map" style="display:none !important">
                    <?php $objHtml->menu();?>
                    <div class="rhs-pannel">
                      
                      <?php 
					  	$objHtml->operation($objMap->id);
						$objMap->stylerHtml($stylers, $objMap->id);
						$objMap->markerHtml();
						$objFeaturedMaps->loadFeaturedMaps();
						$objMap->settingMenu();
						?>
                    </div>
                  </div>
                  
                  <!-- Map Page -->
                  <div class="map-contianer show-map">
                    <div id="map" <?php if ($objMap->id){echo "style='width:".$map->width.$map->width_unit." !important;height:".$map->height."px !important;'";} ?>></div>
                  </div>
                  <!-- End Map Page --> 
                  
                  <script type="text/javascript">
					var map;
					var geocoder;
					function init() {
						<?php if ($objMap->id){?>
						
						/* Applying Default Setting */
						var opt = {
							<?php if (count($primaryMarker)){?>
								center: new google.maps.LatLng(<?=$primaryMarker->latitude?>, <?=$primaryMarker->longitude?>),
							<?php }else{?>
								center: new google.maps.LatLng(0, 0),
							<?php }?>
							mapTypeId: google.maps.MapTypeId.<?=$map->mapTypeId?>,
							disableDefaultUI: false,
							streetViewControl:<?=$map->streetView?>,
							streetViewControlOptions:{
								position:google.maps.ControlPosition.<?=$map->street_view_position?>
							},
							zoomControl:<?=$map->zoomControl?>,
							zoomControlOptions:{
								position:google.maps.ControlPosition.<?=$map->zoom_control_position?>
							},
							panControl : <?=$map->panControl?>,
							mapTypeControl:<?=$map->mapTypeControl?>,
							<?php if ($map->mapTypeControl){?>
							mapTypeControlOptions:{
								style:google.maps.MapTypeControlStyle['<?=strtoupper($map->mapTypeControlOptions)?>'],
								position:google.maps.ControlPosition.<?=$map->map_type_control_position?>
							},
							<?php } ?>
							draggable: <?=$map->draggable?>,
							scrollwheel:<?=$map->scrollwheel?>,
							disableDoubleClickZoom:<?=$map->disableDoubleClickZoom?>,
							navigationControl: false,
							scrollwheel : <?=$map->scrollwheel?>,
							zoom : <?=$map->zoom?>
						};
						/* End Applying Default Setting */
					
					<?php }else{ ?>
					
						/* Default Setting */
						var opt = {
							center: new google.maps.LatLng(0, 0),
							disableDefaultUI: true,
							draggable: true,
							scrollwheel: true,
							disableDoubleClickZoom: true,
						};
						/* End Default Setting */
						
					<?php }?>
					<?php if ($objMap->id){?>
						var zoom = <?=$map->zoom?>;
						opt.zoom = zoom;
					<?php }else{?>
						var zoom = 0;
						var height = document.body.clientHeight;
						for (;height > 256 * (1 << zoom); zoom++);
							opt.zoom = zoom;
						<?php }?>
						var div = document.getElementById('map');
						map = new google.maps.Map(div, opt);
						<?php 
							$objMap->stylerJS();
							$objMap->markerJS();
						?>
					}
					/*function initMap(opt) {}*/
                  </script>
                  <input type="hidden" id="SaveMap" name="SaveMap" value="2" />
                  <input type="hidden" name="id" value="<?=$objMap->id;?>" >
                  <input type="hidden" name="featuredMap" id="featuredMap" style="background-color:#CCC" value="<?php if ($id && (!empty($map->featured_map))){echo htmlentities($map->featured_map);}?>" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $objMap->stylerAddNew();?>
  <input type="hidden" name="loadingFeatureMap" id="loadingFeatureMap" value="" />
</div>
<!-- End Main Container --> 
