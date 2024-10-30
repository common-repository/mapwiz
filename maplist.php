<?php
	require_once("classes/htmlclass.php");
	require_once("classes/maplistclass.php");
	
	$objMap = new mapClass();
	$objMaplist = new maplistClass();
	$objMaplist->pluginDirURL = plugin_dir_url( __FILE__ );
	
	if((isset($_REQUEST["mapid"])) && (!empty($_REQUEST["mapid"]))){
		$objMap->delete(intval($_REQUEST["mapid"]));
	}
	
	$objHtml = new htmlClass();
//	$objHtml->msg();
	$objMap->getMaps();
	$objMaplist->html($objMap->maps);
?>

