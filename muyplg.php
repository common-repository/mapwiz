<?php
	/*
	Plugin Name: Mapwiz
	Plugin URI: http://mapwiz.io
	Description: MapWiz empower Google Map Integration with Ease. Build & customize Google maps without technical hassle.
	Version: 1.0.1
	Author: Concept Beans
	Author URI: http://conceptbeans.com
	*/

	require_once("classes/mapclass.php");
	$objMap = new mapClass();
	
	function addmyplug() {
		global $wpdb;
		
		/* Create Map Table */
		$table_name = $wpdb->prefix . "map";
		$MSQL = "show tables like '$table_name'";
		if($wpdb->get_var($MSQL) != $table_name){

			require_once(ABSPATH . "wp-admin/includes/upgrade.php");
			$sql = "
				CREATE TABLE IF NOT EXISTS `".$table_name."` (
				id int(9) NOT NULL AUTO_INCREMENT,
				`width` int(11) NOT NULL DEFAULT '0',
				`width_unit` enum('px','%') NOT NULL DEFAULT '%',
				`height` int(11) NOT NULL DEFAULT '0',
				`px_percent` enum('px','%') NOT NULL DEFAULT '%',
				`mapTypeId` varchar(255) NOT NULL DEFAULT 'ROADMAP',
				`zoom` int(11) NOT NULL DEFAULT '2',
				`zoomControl` tinyint(1) NOT NULL DEFAULT '1',
				`zoomControlOptions` enum('','SMALL','LARGE','DEFAULT') NOT NULL DEFAULT 'DEFAULT',
				`zoom_control_position` enum('TOP_LEFT','TOP_CENTER','TOP_RIGHT','BOTTOM_LEFT','BOTTOM_RIGHT','BOTTOM_CENTER','LEFT_CENTER','RIGHT_CENTER') NOT NULL DEFAULT 'TOP_LEFT',
				`panControl` tinyint(1) NOT NULL DEFAULT '1',
				`mapTypeControl` tinyint(1) NOT NULL DEFAULT '1',
				`map_type_control_position` enum('TOP_LEFT','TOP_CENTER','TOP_RIGHT','BOTTOM_LEFT','BOTTOM_RIGHT','BOTTOM_CENTER','LEFT_CENTER','RIGHT_CENTER') NOT NULL DEFAULT 'TOP_LEFT',
				`mapTypeControlOptions` enum('dropdown_menu','horizontal_bar','') NOT NULL DEFAULT 'dropdown_menu',
				`draggable` tinyint(1) NOT NULL DEFAULT '1',
				`scrollwheel` tinyint(1) NOT NULL DEFAULT '1',
				`disableDoubleClickZoom` tinyint(1) DEFAULT '0',
				`streetView` tinyint(1) NOT NULL DEFAULT '1',
				`street_view_position` enum('TOP_LEFT','TOP_CENTER','TOP_RIGHT','BOTTOM_LEFT','BOTTOM_RIGHT','BOTTOM_CENTER','LEFT_CENTER','RIGHT_CENTER') NOT NULL DEFAULT 'TOP_LEFT',
				`time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`imageTitle` text NOT NULL,
				`imageUrl` text NOT NULL,
				`bodyTextH1` text NOT NULL,
				`bodyTextP1` text NOT NULL,
				`bodyTextP2` text NOT NULL,
				`bodyTextUrl` text NOT NULL,
				`labelTitle` varchar(255) NOT NULL,
				`markerVisible` tinyint(1) NOT NULL DEFAULT '1',
				`labelTextColor` varchar(255) NOT NULL,
				`labelStrokeColor` varchar(255) NOT NULL DEFAULT '0',
				`labelStrokeWeight` int(11) NOT NULL DEFAULT '0',
				`infoWindow` tinyint(1) NOT NULL DEFAULT '1',
				`featured_map` text NOT NULL,
				PRIMARY KEY id (id)				  
				);
			";
			dbDelta($sql);
		}
		
		/* Create Marker Table*/
		$table_name = $wpdb->prefix . "marker";
		$MSQL = "show tables like '$table_name'";
		if($wpdb->get_var($MSQL) != $table_name){
			require_once(ABSPATH . "wp-admin/includes/upgrade.php");
			$sql = "
				CREATE TABLE IF NOT EXISTS `".$table_name."` (
				id int(9) NOT NULL AUTO_INCREMENT,
				  `map_id` int(11) NOT NULL DEFAULT '0',
			      `primary_marker` tinyint(1) NOT NULL DEFAULT '0',
				  `markerTitle` varchar(255) NOT NULL,
				  `markerICON` varchar(255) NOT NULL DEFAULT 'MAP_PIN',
				  `latitude` DOUBLE NOT NULL DEFAULT '0',
				  `longitude` DOUBLE NOT NULL DEFAULT '0',
				  `fillColor` varchar(255) NOT NULL,
				  `markerFillOpacity` int(11) NOT NULL DEFAULT '2',
				  `markerStrokeColor` varchar(255) NOT NULL,
				  `markerStrokeWeight` int(11) NOT NULL DEFAULT '0',
				  `labelTitle` varchar(255) NOT NULL,
				  `labelTextColor` varchar(255) NOT NULL,
				  `labelStrokeColor` varchar(255) NOT NULL,
				  `labelStrokeWeight` int(11) NOT NULL DEFAULT '0',
				  `showInfoWindow` tinyint(1) NOT NULL DEFAULT '0',
				  `imageTitle` varchar(255) NOT NULL,
				  `imageUrl` text NOT NULL,
				  `bodyTextH1` varchar(255) NOT NULL,
				  `bodyTextP1` varchar(255) NOT NULL,
				  `bodyTextP2` varchar(255) NOT NULL,
				  `bodyTextUrl` varchar(255) NOT NULL,
				  `markerVisible` tinyint(1) NOT NULL DEFAULT '1',
				  `strokeColor` varchar(255) NOT NULL,
				  `marker_type` varchar(255) NOT NULL DEFAULT 'svg',

				PRIMARY KEY id (id)
				);
			";
			dbDelta($sql);		
		}
	}
	/* Hook Plugin to create tables */
	register_activation_hook(__FILE__,'addmyplug');

	function deactivationmap(){
			
		global $wpdb;
		
		/* Drop Map Table */
		$table_name = $wpdb->prefix."map";
		$sql = "DROP TABLE ". $table_name;
		$wpdb->query($sql);	
		
		/* Drop Map Marker */
		$table_name = $wpdb->prefix."marker";
		$sql = "DROP TABLE ". $table_name;
		$wpdb->query($sql);	
	}

	/* Hook Plugin to drop tables */
	register_uninstall_hook(__FILE__,'deactivationmap');
	
	/* Creating Menus */
	function map_Menu(){
		if (isset($_REQUEST['mid'])){
			$pageLink = 'map';
		}else{
			$pageLink = 'map_list';
		}
		
		/* Adding menus */		
		add_menu_page( 'Map Wiz', 'Map Wiz', 'manage_options', 'myplug/muyplg.php', $pageLink, plugins_url( 'mapwiz/images/icon.png'));
	}

	add_action('admin_menu', 'map_Menu');
	
	/* List of Created Maps */
	function map_list() {
		wp_enqueue_script( 'map-list', plugin_dir_url( __FILE__ ) . 'js/map-list.js' );
		wp_enqueue_style( 'map-list', plugins_url( 'css/map-list.css' , __FILE__ ), array(), false, false );
		include "maplist.php";
	}

	/* Create or Update Map */
	function map() {
		include "map-new.php";
	}

	add_action('init', 'mapSession', 1);

	function mapSession() {
		if(!session_id()) {
			session_start();
		}
	}

	/* Show Map at Front End */
	function viewmap_list($atts){
		if (isset($atts['id']) && intval($atts['id'])){
			$id = intval($atts['id']);	
		}else{
			$id = 0;
		}
		if ($id){

			$objMap = new mapClass();
			$objMap->getMapInfo($id);

			if ($objMap->map){
				
				$row = $objMap->map;
				$map = $objMap->map;
				$markers = $objMap->markers;
				$stylers = $objMap->stylers;
				$primaryMarker = $objMap->primaryMarker;	
			    
				/* Load JS & CSS Files */
				wp_enqueue_style( 'mapwiz-style', plugins_url( 'css/style.css' , __FILE__ ), array(), false, false );
                wp_enqueue_style( 'mapwiz-maker-info-window', plugins_url( 'css/maker-info-window.css' , __FILE__ ), array(), false, false );

                wp_enqueue_script( 'mapwiz-style', plugin_dir_url( __FILE__ ) . 'js/style.js' );
				wp_enqueue_script( 'mapwiz-maps', plugin_dir_url( __FILE__ ) . 'js/gmap.js' );
                wp_enqueue_script( 'mapwiz-maplabel-compiled', plugin_dir_url( __FILE__ ) . 'js/maplabel-compiled.js' );
				?>
					<script type="text/javascript">
        
                        /* Initialized the variables*/
                        var allMarkers = {};
                        var plungInPath = '<?php echo plugin_dir_url( __FILE__ )?>';
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
                    </script>
	
                    <!-- Map Container -->
                    <div class="map-contianer">
                      <div id="map" style="width:<?=$row->width?><?=$row->width_unit?> !important; height:<?=$row->height?>px !important;"></div>
                    </div>
                    <!-- End Map Container -->
				
                    <!-- End Load JS & CSS Files -->
                    <script type="text/javascript">
                        var plungInPath = '<?php echo plugin_dir_url( __FILE__ )?>';
                        var map;

                        function init() {
                            var opt = {
                                <?php if (count($primaryMarker)){?>
                                center: new google.maps.LatLng(<?=$primaryMarker->latitude?>, <?=$primaryMarker->longitude?>),
                                    <?php }else{?>
                                center: new google.maps.LatLng(0, 0),
                                <?php }?>
            
                                mapTypeId: google.maps.MapTypeId.<?=$row->mapTypeId?>,
                                disableDefaultUI: true,
                                streetViewControl:<?=$row->streetView?>,
                                streetViewControlOptions:{
                                    position:google.maps.ControlPosition.<?=$row->street_view_position?>
                                },
                                zoomControl:<?=$row->zoomControl?>,
                                <?php if ($row->zoomControl && (!empty($row->zoomControlOptions))){?>
                                zoomControlOptions:{
                                    position:google.maps.ControlPosition.<?=$row->zoom_control_position?>
                                },
                                <?php }?>
                                panControl : <?=$row->panControl?>,
                                panControlOptions : {
                                },
								mapTypeControl:<?=$map->mapTypeControl?>,
								<?php if ($map->mapTypeControl){?>
								mapTypeControlOptions:{
									style:google.maps.MapTypeControlStyle['<?=strtoupper($map->mapTypeControlOptions)?>'],
									position:google.maps.ControlPosition.<?=$map->map_type_control_position?>
								},
								<?php } ?>
                                draggable: <?=$row->draggable?>,
                                scrollwheel:<?=$row->scrollwheel?>,
                                disableDoubleClickZoom:<?=$row->disableDoubleClickZoom?>,
                                navigationControl: false,
                                scrollwheel : <?=$row->scrollwheel?>,
                            };
                            var zoom = 0;
                            var height = document.body.clientHeight;
                            for (;height > 256 * (1 << zoom); zoom++);
                            opt.zoom = <?=$row->zoom?>;
                            initMap(opt);
                        }
                        
                        function initMap(opt) {
                            var div = document.getElementById('map');
                            map = new google.maps.Map(div, opt);

							<?php 
								$objMap->stylerJS();
								$objMap->markerJS();
							?>
                        }
                    </script>
	<?php
			}
		}
	}

	/* Call to view map */
	add_shortcode('mapwiz', 'viewmap_list');
	
	function validPhone($str){
		$valid = preg_replace("/[^0-9]/", '', $str);;
		if($valid) {
			return true;
		}else{
			return false;	
		}
	}

	function validEmail($email){

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}else{
			return false;	
		}
	}	
?>
