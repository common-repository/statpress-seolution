<?php
	// sps static settings:
	
	define('SPS_FCM0XML','sps_mainstats.xml'); 				// FlashChart Main
	define('SPS_FCS1XML','sps_spidervisits_wMovAvg.xml'); 	// FlashChart Spider Visits + Moving Average Line
	define('SPS_FCS2XML','sps_spidervisits_wMovAvg2.xml'); 	// same but with 2 MovAvg Lines
	define('SPS_FCS3XML','sps_spidervisits_wMovAvg3.xml'); 	// same but with 2 MovAvg Lines and a weighted one
	define('SPS_FCS4XML','sps_spidervisits_wMovAvg4.xml'); 	// same but with 2 MovAvg Lines and a weighted one + full average bar
	define('SPS_FCSXXML','sps_spidervisits_wMovAvgX.xml'); 	// same but with 2 MovAvg Lines and weighted lines + full average bar
	
	define('SPS_DEFTHEME','cupertino');

/* require additional modules: */
	include(dirname(__FILE__).'/sps_helpers.php');

	/*
	include(dirname(__FILE__).'/module.main.php');
	include(dirname(__FILE__).'/module.details.php');
	include(dirname(__FILE__).'/module.spiderstats.php');
	include(dirname(__FILE__).'/module.spy.php');
	include(dirname(__FILE__).'/module.search.php');
	include(dirname(__FILE__).'/module.agents.php');
	include(dirname(__FILE__).'/module.options.php');
	include(dirname(__FILE__).'/module.export.php');
	*/
	//include(dirname(__FILE__).'/module.XXX.php');

?>