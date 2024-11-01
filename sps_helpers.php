<?php
/***
* SPS Helper Functions:
* 
***/

/**
 * Get a URL to the plugin.  Useful for specifying JS and CSS files
 *
 * For example, <img src="<_?_php echo $sps_wurl() ?_>/myimage.png"/>
 * (delete underscores)	 
 *
 * @return string URL
 **/
function sps_wurl( $url = 'statpress-seolution' ) {

	if ( defined( 'WP_PLUGIN_URL' ) )
		$url = WP_PLUGIN_URL.'/'.ltrim( $url, '/' );
	else
		$url = get_bloginfo( 'wpurl' ).'/'.ltrim( $url, '/' );

	return $url;
} // sps_wurl


// AVERAGES:

// needs an array in following format: $array[offset][fieldname]
function sps_movavg($collection,$in_field,$out_field,$range,$offset) {
	// mov avg = values of range at offset summized and divided by count of values (range)
	$insum = 0;
	for($i = $offset-1; $i >= ($offset - $range); $i--) {
		$insum = $insum + $collection[$i][$in_field];
	}
	$collection[$offset][$out_field] = round($insum / $range);
	return $collection;
} // moving average of array


// needs an array in following format: $array[offset][fieldname]
function sps_weighted_movavg($collection,$in_field,$out_field,$range,$offset) {
	// mov avg = values of range at offset summized and divided by count of values (range)
	$insum = 0; $weightsum = 0;
	for($i = $offset-1; $i >= ($offset - $range); $i--) {
		$weightfactor = ( ($i + $range - $offset) + 1 ) / $range * 100;
		$insum = $insum + ($weightfactor * $collection[$i][$in_field]);
		$weightsum = $weightsum + $weightfactor;
	}
	$collection[$offset][$out_field] = round($insum / $weightsum, 1);
	return $collection;
} // weighted moving average of array


function sps_full_average($collection,$in_field,$out_field) {
	$sum = 0;
	$counts = count($collection);
	for($i = 0; $i <= count($collection)-1; $i++){
		$sum = $sum + $collection[$i][$in_field];
	}
	$avg = round($sum / $counts);
	for($i = 0; $i <= count($collection)-1; $i++){
		$collection[$i][$out_field] = $avg;
	}
	return $collection;
} // full average


// GENERAL HEADER:
function sps_general_headout($subpage = '') {
	echo '<div class="wrap"><h2>Statpress <strong>SEO</strong>lution '.$subpage.'</h2>';
	echo '<noscript>You need Javascript enabled to get the full functionality of Statpress SEOlution!</noscript>';
	//echo '<br /><br />';
	echo '</div>';
	
	global $_STATPRESS;
	if (get_option('sps_tableversion') < $_STATPRESS['tableversion']) {
	  $sps_modsbase = get_bloginfo('wpurl')."/wp-admin/admin.php?page=sps/up";
		print '<div class="wrap ui-widget"><div class="ui-state-error ui-corner-all"><p style="padding:5px;text-align:center;font-size:1.25em;">';
		print __('Your Statpress SEOlution tables need an update!','statpress');
		print ' <small>(&#8594; <a href="'.$sps_modsbase.'">Update</a>)</small>';
		print '</p></div></div>';
	}
}

function sps_general_footout() {
		?>
<div class="sps_admin_footer_info">
	Please support the developer of this plugin! 
	<strong>Make a donation via 
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LLN4YP8SUMV2W">Paypal</a> 
	or <a href="https://flattr.com/thing/62904/Statpress-SEOlution-Wordpress-Plugin-blogcraft-de">flattr</a>. 
	Thank you!</strong> <em>&mdash;<a href="http://blogcraft.de/">Blogcrafter Chris</a></em>
</div>
		<?php
}  

	function sps_substr($str, $x, $y = 0) {
		if($y == 0)
		{
			$y = strlen($str) - $x;
		}
	if(function_exists('mb_substr'))
	{
		return mb_substr($str, $x, $y);
	}
	else
	{
		return substr($str, $x, $y);
	}
	}



//eof
?>