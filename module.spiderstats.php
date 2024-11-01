<?php
	  function sps_SpiderStats($type = 'all',$stype = '')
	  {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
		  
		  $view_term = "<> ''"; // means 'all'
		  
		  if(''===$type || ''==$type) $type = 'all';
		  
		  if($type != 'searched')
		  {
			  $stype = $type;
		  } else {
			  $pre_value = $stype;
		  }
		  
		#header links
		$sps_modssbase = get_bloginfo('wpurl')."/wp-admin/admin.php?page=sps/spiderstats&sps_stype=";
		$sps_modssbase_search = get_bloginfo('wpurl')."/wp-admin/admin.php";
		
		function check_active($type,$atype) {
			if ($atype == $type) echo ' class="active"';
		}
		
		sps_general_headout(__('Spider Stats','statpress'));
?>
<div class='wrap'>
		<div class="sps_boxspacing sps_ssmenu">
				<ul class="sps_navigator">
					<li<?php check_active($type,'all'); ?>><a href="<?php echo $sps_modssbase; ?>all"><span class="ui-icon ui-icon-tag"></span>All Spiders</a></li>
					<li<?php check_active($type,'Google'); ?>><a href="<?php echo $sps_modssbase; ?>google"><span class="ui-icon ui-icon-tag"></span>Google</a></li>
					<li<?php check_active($type,'Yahoo'); ?>><a href="<?php echo $sps_modssbase; ?>yahoo"><span class="ui-icon ui-icon-tag"></span>Yahoo</a></li>
					<li<?php check_active($type,'MSN'); ?>><a href="<?php echo $sps_modssbase; ?>bing"><span class="ui-icon ui-icon-tag"></span>Bing/MSN</a></li>
						<li class="spacer"><span class="ui-icon ui-icon-grip-dotted-vertical"></span></li>
					<li<?php check_active($type,'searched'); ?>>
						<form action="<?php echo $sps_modssbase_search; ?>" method="get" accept-charset="UTF-8">
							<input type="hidden" name="page" value="sps/spiderstats" />
							<input type="hidden" name="sps_stype" value="searched" />
							<?php echo __('Search for Bot:','statpress'); ?> 
							 <input name="sps_phrase" id="sps_phrase" type="text" size="23" value="<?php echo $pre_value; ?>" />
							<input type="submit" value="<?php echo __('Send','statpress'); ?>" />
							<input type="reset" value="<?php echo __('Reset','statpress'); ?>" />
						</form>
					</li>
				</ul>
				<br class="sps_clearer" />
		</div>
</div>
<div class='wrap'>
		<div id="tabs" class="sps_boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span>Overview</a></li>
				<li><a href="#tabs-2"><span class="ui-icon ui-icon-star"></span>Visits of Bots (type separated)</a></li>
				<li><a href="#tabs-3"><span class="ui-icon ui-icon-star"></span>Visits of Bots (all types)</a></li>
				<li><a href="#tabs-4"><span class="ui-icon ui-icon-star"></span>Top 25 of crawled URLs</a></li>
			</ul>
			
<?php

		  if('all'==$type) {
			$view_term = "<> ''";
			}
		  else {
			$view_term = "LIKE '%$stype%'";
			}
			
		  $yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
		  $today = gmdate('Ymd', current_time('timestamp'));

          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE spider $view_term
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE spider $view_term
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          //ALLTIME
          $qry_at = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE spider $view_term
  ");
		$percentage_today = round($qry_t->spiders * 100 / ($qry_y->spiders+0.001) , 2); // percent
		$percentage_today = ($percentage_today > 100) ? "+".($percentage_today - 100): "-".(100 - $percentage_today); // percent
		$permille_alltime = round($qry_t->spiders * 1000 / ($qry_at->spiders+0.001) , 1); // permille
          print "<div id='tabs-1' class='ui-tabs-hide'><h2>".$stype.": ".__('How often today and yesterday?','statpress')."</h2>";
		  print "<p>$stype - ".__('Total visits of the spider today','statpress').": <strong>".$qry_t->spiders."&times;</strong>, ".__('and yesterday','statpress').": <strong>".$qry_y->spiders."&times;</strong> &mdash; [".$percentage_today." % ".__('change','statpress')."]</p>";
		  print "<p>".__('Total visits since start time of counting','statpress').": ".$qry_at->spiders."&times; (".__('Todays alltime thousandth','statpress').": ".$permille_alltime." &permil;)</p>";
          print "";
		  
		  
//###############################################################################################
//###############################################################################################
// FLASH CHART START
          
          // last "N" days graph  NEW
          $gdays = get_option('sps_daysinoverviewgraph');
		  
		  $gd_ma_offset7 = 7; // for a 7 days mov avg
		  $gd_ma_offset14 = 14; // for a 14 days mov avg
		  $gd_ma_offset30 = 30; // for a 30 days mov avg
		  $gd_ma_offset90 = 90; // for a 90 days mov avg
		  
          if ($gdays == 0)
          {
              $gdays = 20;
          }
		  
// amline flash chart - start
$enl = "\n";

print "";
print "".$enl.$enl.$enl;
print "<script type=\"text/javascript\" src=\"".sps_wurl().'/lib/'."amline/swfobject.js\"></script>".$enl;
print "<div id=\"flashcontent\">".$enl;
print "<strong>".__('You need to upgrade your Flash Player','statpress')."</strong>".$enl;
print "<br /><small>".__('Please check also following URL','statpress').": ".sps_wurl().'/lib/amline/swfobject.js'."<br />This URL should be a reachable file!</small>".$enl;
print "<br /><small>".__('And: Javascript should be enabled of course!','statpress')."</small>".$enl;
print "</div>".$enl;
print	'<script type="text/javascript">'.$enl;
print		'// <![CDATA['.$enl;
print		'var so = new SWFObject("'.sps_wurl().'/lib/'.'amline/amline.swf", "amline", "100%", "500", "8", "#FFFFFF");'.$enl;
print		'so.addVariable("path", "'.sps_wurl().'/lib/'.'amline");'.$enl;

print		'so.addVariable("settings_file", escape("'.sps_wurl().'/lib/'.SPS_FCSXXML.'"));'.$enl;
print		'so.addParam("wmode", "opaque");'.$enl;

$chartsdatas = '';
$chartcollect[0]['date'] = 'date';
$chartcollect[0]['visits'] = 'spider_visits';
$chartcollect[0]['movavg07'] = 'moving_average';
$chartcollect[0]['movavg14'] = 'moving_average2';
$chartcollect[0]['wmovavg14'] = 'wmovavg14';
$chartcollect[0]['wmovavg30'] = 'wmovavg30';
$chartcollect[0]['wmovavg90'] = 'wmovavg90';
$chartcollect[0]['avgbar'] = 'avgbar';

// amline - settings loaded

for ($gg = ($gdays + $gd_ma_offset90) - 1; $gg >= 0; $gg--)
{
	$qry_spiders = $wpdb->get_row("
	SELECT count(date) AS total
	FROM $table_name
	WHERE spider $view_term
	AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
	");
	
	$chartdate = gmdate('Y', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('m', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('d', current_time('timestamp') - 86400 * $gg);
	
	$chartcollect[] = array(
						'date'		=> $chartdate,
						'visits'	=> $qry_spiders->total,
						'movavg07'	=> 0,
						'movavg14'	=> 0,
						'wmovavg14'	=> 0,
						'wmovavg30'	=> 0,
						'wmovavg90'	=> 0,
						'avgbar'	=> 0
					);
}

for($id = $gd_ma_offset7; $id <= (count($chartcollect) - 1); $id++) {
	$chartcollect = sps_movavg($chartcollect,'visits','movavg07',$gd_ma_offset7,$id);
}	
for($id = $gd_ma_offset14; $id <= (count($chartcollect) - 1); $id++) {
	$chartcollect = sps_movavg($chartcollect,'visits','movavg14',$gd_ma_offset14,$id);
}	

for($id = $gd_ma_offset14; $id <= (count($chartcollect) - 1); $id++) {
	$chartcollect = sps_weighted_movavg($chartcollect,'visits','wmovavg14',$gd_ma_offset14,$id);
}
for($id = $gd_ma_offset30; $id <= (count($chartcollect) - 1); $id++) {
	$chartcollect = sps_weighted_movavg($chartcollect,'visits','wmovavg30',$gd_ma_offset30,$id);
}
for($id = $gd_ma_offset90; $id <= (count($chartcollect) - 1); $id++) {
	$chartcollect = sps_weighted_movavg($chartcollect,'visits','wmovavg90',$gd_ma_offset90,$id);
}

$cc_start = array_shift($chartcollect);
for($x=1;$x<=$gd_ma_offset90;$x++) $null = array_shift($chartcollect);
$chartcollect = sps_full_average($chartcollect,'visits','avgbar');
array_unshift($chartcollect,$cc_start);

	foreach($chartcollect as $line){
		$chartdatas .= $line['date'].';'.$line['visits'].';';
		$chartdatas .= $line['movavg07'].';'.$line['movavg14'].';';
		$chartdatas .= $line['wmovavg14'].';'.$line['wmovavg30'].';'.$line['wmovavg90'].';';
		$chartdatas .= $line['avgbar'].'\n';
	}

// amline datas:
//$chartdatas .= $chartdatas_last;
#print		'so.addVariable("data_file",escape("'.sps_wurl().'/lib/'.'test_data.csv"));'.$enl;
print		'so.addVariable("chart_data","'.$chartdatas.'");'.$enl;
// amline output
print		'so.write("flashcontent");'.$enl;
print		'// ]]>'.$enl;
print	'</script>'.$enl.$enl.$enl;
// FLASH CHART END
//###################################################################################################
print "</div>";
          
		  
		  
		  
		  
		if('all'==$type) {
          print "<div id='tabs-2' class='ui-tabs-hide'><h2>$stype ".__('Bots - 5 visits per type','statpress')."</h2>";
		} else {
          print "<div id='tabs-2' class='ui-tabs-hide'><h2>$stype ".__('Bots - 25 visits per type','statpress')."</h2>";
		}
          print "<table class='widefat'><thead><tr>";
          print "<th scope='col'>" . __('Spider', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Date', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Time', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Page', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Agent', 'statpress') . "</th>";
          print "</tr></thead><tbody id='the-list'>";
		  $preqry = $wpdb->get_results("SELECT date,time,agent,spider,COUNT(spider) as 'spider_count',urlrequested,agent FROM $table_name WHERE (spider $view_term) GROUP BY spider");
		  $last_spiders = array();
		  foreach ($preqry as $prk) { $last_spiders[] = $prk->spider; }
		  $last_spiders = array_unique($last_spiders);
		  foreach ($last_spiders as $lspider) {
			if('all'==$type) {
			  $qry = $wpdb->get_results("SELECT date,time,agent,spider,urlrequested,agent FROM $table_name WHERE (spider='".$lspider."') ORDER BY id DESC LIMIT 5");
			} else {
			  $qry = $wpdb->get_results("SELECT date,time,agent,spider,urlrequested,agent FROM $table_name WHERE (spider='".$lspider."') ORDER BY id DESC LIMIT 25");
			}
		  
              print "<tr><td colspan='5' style='background:#ffee99;'><strong>" . $lspider . " &dArr;</strong> (".__('last visited','statspress')." ".sps_hdate($qry[0]->date)." ".$qry[0]->time.")</td></tr>\n";
          foreach ($qry as $rk)
          {
              print "<tr><td><!-- " . $rk->spider . " --></td>";
			  $dcn_is_today = (gmdate('Ymd', current_time('timestamp')) == $rk->date)? ' style="font-weight:bold;"' : "";
              print "<td".$dcn_is_today.">" . sps_hdate($rk->date) . "</td>";
              print "<td>" . $rk->time . "</td>";
			  $requrl = ($rk->urlrequested[0]=="/") ? $rk->urlrequested : "/?".$rk->urlrequested;
			  $requrl = ($requrl=="/?") ? "/" : $requrl;
              print "<td><a href='".$requrl."'>" . sps_Decode($rk->urlrequested) . "</a></td>";
              print "<td> " . $rk->agent . "</td></tr>\n";
          } //qry
		  } //preqry
          print "</table></div>";
		  
          print "<div id='tabs-3' class='ui-tabs-hide'><h2>$stype ".__('Bots - The last 25 visits (of all types)','statpress')."</h2>";
          print "<table class='widefat'><thead><tr>";
          print "<th scope='col'>" . __('Date', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Time', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Page', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Spider', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Agent', 'statpress') . "</th>";
          print "</tr></thead><tbody id='the-list'>";

          $qry = $wpdb->get_results("SELECT date,time,agent,spider,urlrequested,agent FROM $table_name WHERE (spider $view_term) ORDER BY id DESC LIMIT 25");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . sps_hdate($rk->date) . "</td>";
              print "<td>" . $rk->time . "</td>";
			  $requrl = ($rk->urlrequested[0]=="/") ? $rk->urlrequested : "/?".$rk->urlrequested;
			  $requrl = ($requrl=="/?") ? "/" : $requrl;
              print "<td><a href='".$requrl."'>" . sps_Decode($rk->urlrequested) . "</a></td>";
              print "<td>" . $rk->spider . "</td>";
              print "<td> " . $rk->agent . "</td></tr>\n";
          } //qry
          print "</table></div>";
		  
          print "<div id='tabs-4' class='ui-tabs-hide'><h2>".__('Top 25 of last crawled URLs','statpress')."</h2>";
          print "<table class='widefat'><thead><tr>";
          print "<th scope='col'>".__('Count','statpress')."</th>";
          print "<th scope='col'>".__('URL','statpress')."</th>";
          print "</tr></thead><tbody id='the-list'>";

          $qry = $wpdb->get_results("SELECT urlrequested,COUNT(urlrequested) as visitcount FROM $table_name WHERE (spider $view_term) GROUP BY urlrequested ORDER BY visitcount DESC LIMIT 25");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . $rk->visitcount . "</td>";
			  $requrl = ($rk->urlrequested[0]=="/") ? $rk->urlrequested : "/?".$rk->urlrequested;
			  $requrl = ($requrl=="/?") ? "/" : $requrl;
              print "<td><a href='".$requrl."'>" . sps_Decode($rk->urlrequested) . "</a></td>";
          } //qry
          print "</table></div>";

#		  
#          print "<div class='wrap'><h2>".__('All crawled URLs, alphabetic order','statpress')."</h2>";
#          print "<table class='widefat'><thead><tr>";
#          print "<th scope='col'>".__('Count','statpress')."</th>";
#          print "<th scope='col'>".__('URL','statpress')."</th>";
#          print "</tr></thead><tbody id='the-list'>";
#
#          $qry = $wpdb->get_results("SELECT urlrequested,COUNT(urlrequested) as visitcount FROM $table_name WHERE (spider $view_term) GROUP BY urlrequested ORDER BY urlrequested ASC");
#          foreach ($qry as $rk)
#          {
#              print "<tr><td>" . $rk->visitcount . "</td>";
#			  $requrl = ($rk->urlrequested[0]=="/") ? $rk->urlrequested : "/?".$rk->urlrequested;
#			  $requrl = ($requrl=="/?") ? "/" : $requrl;
#              print "<td><a href='".$requrl."'>" . sps_Decode($rk->urlrequested) . "</a></td>";
#          } //qry
#          print "</table></div>";
#
?>
		</div>
</div>
<?php
		  sps_general_footout();
	  }
?>
