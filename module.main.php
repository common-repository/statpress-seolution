<?php 
      function sps_Main()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";


		sps_general_headout(__('Overview','statpress'));
?>
<div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span>Overview</a></li>
				<li><a href="#tabs-2"><span class="ui-icon ui-icon-star"></span><?php print __('Last hits', 'statpress'); ?></a></li>
				<li><a href="#tabs-3"><span class="ui-icon ui-icon-star"></span><?php print __('Last search terms', 'statpress'); ?></a></li>
				<li><a href="#tabs-4"><span class="ui-icon ui-icon-star"></span><?php print __('Last referrers', 'statpress'); ?></a></li>
				<li><a href="#tabs-5"><span class="ui-icon ui-icon-star"></span><?php print __('Last agents', 'statpress'); ?></a></li>
				<li><a href="#tabs-6"><span class="ui-icon ui-icon-star"></span><?php print __('Last pages', 'statpress'); ?></a></li>
			</ul>
			
<?php          
          // OVERVIEW table
          $unique_color = "#117744";
          $web_color = "#3377B6";
          $rss_color = "#f38f36";
          $spider_color = "#83b4d8";
          $lastmonth = sps_lastmonth();
          $thismonth = gmdate('Ym', current_time('timestamp'));
          $yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
          $today = gmdate('Ymd', current_time('timestamp'));
          $tlm[0] = sps_substr($lastmonth, 0, 4);
          $tlm[1] = sps_substr($lastmonth, 4, 2);

          
          print "<div id='tabs-1' class='ui-tabs-hide'><h2>" . __('Overview', 'statpress') . "</h2>";
          print "<table class='widefat'><thead><tr>
  <th scope='col'></th>
  <th scope='col'>" . __('Total', 'statpress') . "</th>
  <th scope='col'>" . __('Last month', 'statpress') . "<br /><font size=1>" . gmdate('M, Y', gmmktime(0, 0, 0, $tlm[1], 1, $tlm[0])) . "</font></th>
  <th scope='col'>" . __('This month', 'statpress') . "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) . "</font></th>
  <th scope='col'>" . __('Target', 'statpress') . " " . __('This month', 'statpress') . "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) . "</font></th>
  <th scope='col'>" . __('Yesterday', 'statpress') . "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp') - 86400) . "</font></th>
  <th scope='col'>" . __('Today', 'statpress') . "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp')) . "</font></th>
  </tr></thead>
  <tbody id='the-list'>";
          
          //###############################################################################################
          // VISITORS ROW
          print "<tr><td><div style='background:$unique_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Visitors', 'statpress') . "</td>";
          
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
  ");
          print "<td>" . $qry_total->visitors . "</td>\n";
          
          //LAST MONTH
          $qry_lmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->visitors . "</td>\n";
          
          //THIS MONTH
          $qry_tmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->visitors <> 0)
          {
              $pc = round(100 * ($qry_tmonth->visitors / $qry_lmonth->visitors) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->visitors . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          
          $qry_tmonth->target = round($qry_tmonth->visitors / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->visitors <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->visitors) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->visitors . "</td>\n";
          
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->visitors . "</td>\n";
          print "</tr>";
          
          //###############################################################################################
          // PAGEVIEWS ROW
          print "<tr><td><div style='background:$web_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Pageviews', 'statpress') . "</td>";
          
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
  ");
          print "<td>" . $qry_total->pageview . "</td>\n";
          
          //LAST MONTH
          $prec = 0;
          $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->pageview . "</td>\n";
          
          //THIS MONTH
          $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->pageview <> 0)
          {
              $pc = round(100 * ($qry_tmonth->pageview / $qry_lmonth->pageview) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->pageview . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          $qry_tmonth->target = round($qry_tmonth->pageview / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->pageview <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->pageview) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->pageview . "</td>\n";
          
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->pageview . "</td>\n";
          print "</tr>";
          //###############################################################################################
          // SPIDERS ROW
          print "<tr><td><div style='background:$spider_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Spiders', 'statpress') . "</td>";
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
  ");
          print "<td>" . $qry_total->spiders . "</td>\n";
          //LAST MONTH
          $prec = 0;
          $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->spiders . "</td>\n";
          
          //THIS MONTH
          $prec = $qry_lmonth->spiders;
          $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->spiders <> 0)
          {
              $pc = round(100 * ($qry_tmonth->spiders / $qry_lmonth->spiders) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->spiders . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          $qry_tmonth->target = round($qry_tmonth->spiders / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->spiders <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->spiders) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->spiders . "</td>\n";
          
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->spiders . "</td>\n";
          print "</tr>";
          //###############################################################################################
          // FEEDS ROW
          print "<tr><td><div style='background:$rss_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Feeds', 'statpress') . "</td>";
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
  ");
          print "<td>" . $qry_total->feeds . "</td>\n";
          
          //LAST MONTH
          $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->feeds . "</td>\n";
          
          //THIS MONTH
          $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->feeds <> 0)
          {
              $pc = round(100 * ($qry_tmonth->feeds / $qry_lmonth->feeds) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->feeds . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          $qry_tmonth->target = round($qry_tmonth->feeds / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->feeds <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->feeds) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          $qry_y = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->feeds . "</td>\n";
          
          $qry_t = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->feeds . "</td>\n";
          
          print "</tr></table><br />\n\n";
          
          //###############################################################################################
          //###############################################################################################
          // THE GRAPHS
          
          // last "N" days graph  NEW
          $gdays = get_option('sps_daysinoverviewgraph');
          if ($gdays == 0)
          {
              $gdays = 20;
          }
		  
          //  $start_of_week = get_settings('start_of_week');
          $start_of_week = get_option('start_of_week');

// amline flash chart - start
$enl = "\n";


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

print		'so.addVariable("settings_file", escape("'.sps_wurl().'/lib/'.SPS_FCM0XML.'"));'.$enl;
print		'so.addParam("wmode", "opaque");'.$enl;

$chartdatas = 'date;visitors;pageviews;feeds;spiders\n';
// amline - settings loaded

          $qry = $wpdb->get_row("
    SELECT count(date) as pageview, date
    FROM $table_name
    GROUP BY date HAVING date >= '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gdays) . "'
    ORDER BY pageview DESC
    LIMIT 1
  ");
          $maxxday = $qry->pageview;
          if ($maxxday == 0)
          {
              $maxxday = 1;
          }
          
		  // Y
          $gd = (90 / $gdays) . '%';
		  		  
          for ($gg = $gdays - 1; $gg >= 0; $gg--)
          {
              //TOTAL VISITORS
              $qry_visitors = $wpdb->get_row("
      SELECT count(DISTINCT ip) AS total
      FROM $table_name
      WHERE feed=''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
              
              //TOTAL PAGEVIEWS (we do not delete the uniques, this is falsing the info.. uniques are not different visitors!)
              $qry_pageviews = $wpdb->get_row("
      SELECT count(date) as total
      FROM $table_name
      WHERE feed=''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
             
              //TOTAL SPIDERS
              $qry_spiders = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE feed=''
      AND spider<>''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
              
              //TOTAL FEEDS
              $qry_feeds = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE feed<>''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
	
	$chartdate = gmdate('Y', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('m', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('d', current_time('timestamp') - 86400 * $gg);
	$chartdatas .= $chartdate.';'.$qry_visitors->total.';'.$qry_pageviews->total.';'.$qry_feeds->total.';'.$qry_spiders->total.'\n';
	
          }

// amline datas:
print		'so.addVariable("chart_data","'.$chartdatas.'");'.$enl;

// amline output
print		'so.write("flashcontent");'.$enl;
print		'// ]]>'.$enl;
print	'</script>'.$enl.$enl.$enl;


// single chart VISITORS
//###############################################################################################
//###############################################################################################
// FLASH CHART START
print	'<br/><br/>Visitors:'.$enl;
          
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
print "<div id=\"flashcontent_visitors\">".$enl;
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

$chartsdatas_v = '';
$chartcollect = '';
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

  //TOTAL VISITORS
  $qry_visitors = $wpdb->get_row("
      SELECT count(DISTINCT ip) AS total
      FROM $table_name
      WHERE feed=''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
	
	$chartdate = gmdate('Y', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('m', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('d', current_time('timestamp') - 86400 * $gg);
	
	$chartcollect[] = array(
						'date'		=> $chartdate,
						'visits'	=> $qry_visitors->total,
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
		$chartdatas_v .= $line['date'].';'.$line['visits'].';';
		$chartdatas_v .= $line['movavg07'].';'.$line['movavg14'].';';
		$chartdatas_v .= $line['wmovavg14'].';'.$line['wmovavg30'].';'.$line['wmovavg90'].';';
		$chartdatas_v .= $line['avgbar'].'\n';
	}

// amline datas:
//$chartdatas .= $chartdatas_last;
#print		'so.addVariable("data_file",escape("'.sps_wurl().'/lib/'.'test_data.csv"));'.$enl;
print		'so.addVariable("chart_data","'.$chartdatas_v.'");'.$enl;
// amline output
print		'so.write("flashcontent_visitors");'.$enl;
print		'// ]]>'.$enl;
print	'</script>'.$enl.$enl.$enl;
// FLASH CHART END
//###################################################################################################

// single chart PAGEVIEWS
//###############################################################################################
//###############################################################################################
// FLASH CHART START
print	'<br/><br/>Pageviews:'.$enl;
          
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
print "<div id=\"flashcontent_pvs\">".$enl;
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

$chartsdatas_p = '';
$chartcollect = '';
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

  //TOTAL PAGEVIEWS (we do not delete the uniques, this is falsing the info.. uniques are not different visitors!)
  $qry_pageviews = $wpdb->get_row("
      SELECT count(date) as total
      FROM $table_name
      WHERE feed=''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
	
	$chartdate = gmdate('Y', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('m', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('d', current_time('timestamp') - 86400 * $gg);
	
	$chartcollect[] = array(
						'date'		=> $chartdate,
						'visits'	=> $qry_pageviews->total,
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
		$chartdatas_p .= $line['date'].';'.$line['visits'].';';
		$chartdatas_p .= $line['movavg07'].';'.$line['movavg14'].';';
		$chartdatas_p .= $line['wmovavg14'].';'.$line['wmovavg30'].';'.$line['wmovavg90'].';';
		$chartdatas_p .= $line['avgbar'].'\n';
	}

// amline datas:
//$chartdatas .= $chartdatas_last;
#print		'so.addVariable("data_file",escape("'.sps_wurl().'/lib/'.'test_data.csv"));'.$enl;
print		'so.addVariable("chart_data","'.$chartdatas_p.'");'.$enl;
// amline output
print		'so.write("flashcontent_pvs");'.$enl;
print		'// ]]>'.$enl;
print	'</script>'.$enl.$enl.$enl;
// FLASH CHART END
//###################################################################################################

// single charts FEEDS
//###############################################################################################
//###############################################################################################
// FLASH CHART START
print	'<br/><br/>Feeds:'.$enl;

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
print "<div id=\"flashcontent_feeds\">".$enl;
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

$chartsdatas_f = '';
$chartcollect = '';
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

  //TOTAL FEEDS
  $qry_feeds = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE feed<>''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
	
	$chartdate = gmdate('Y', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('m', current_time('timestamp') - 86400 * $gg) . '-' . gmdate('d', current_time('timestamp') - 86400 * $gg);
	
	$chartcollect[] = array(
						'date'		=> $chartdate,
						'visits'	=> $qry_feeds->total,
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
		$chartdatas_f .= $line['date'].';'.$line['visits'].';';
		$chartdatas_f .= $line['movavg07'].';'.$line['movavg14'].';';
		$chartdatas_f .= $line['wmovavg14'].';'.$line['wmovavg30'].';'.$line['wmovavg90'].';';
		$chartdatas_f .= $line['avgbar'].'\n';
	}

// amline datas:
//$chartdatas .= $chartdatas_last;
#print		'so.addVariable("data_file",escape("'.sps_wurl().'/lib/'.'test_data.csv"));'.$enl;
print		'so.addVariable("chart_data","'.$chartdatas_f.'");'.$enl;
// amline output
print		'so.write("flashcontent_feeds");'.$enl;
print		'// ]]>'.$enl;
print	'</script>'.$enl.$enl.$enl;
// FLASH CHART END
//###################################################################################################

// dont delete this line
print	'<br/><br/>'.$enl;
print '</div>'; //of tab 1

          // END OF OVERVIEW
          //###################################################################################################
          
          
          $querylimit = "LIMIT 25";
          
          // Tabella Last hits
          print "<div id='tabs-2' class='ui-tabs-hide'><h2>" . __('Last hits', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('IP', 'statpress') . "</th><th scope='col'>" . __('Threat', 'statpress') . "</th><th scope='col'>" . __('Domain', 'statpress') . "</th><th scope='col'>" . __('Page', 'statpress') . "</th><th scope='col'>" . __('OS', 'statpress') . "</th><th scope='col'>" . __('Browser', 'statpress') . "</th><th scope='col'>" . __('Feed', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          
          $fivesdrafts = $wpdb->get_results("SELECT * FROM $table_name WHERE (os<>'' OR feed<>'') order by id DESC $querylimit");
          foreach ($fivesdrafts as $fivesdraft)
          {
              print "<tr>";
              print "<td>" . sps_hdate($fivesdraft->date) . "</td>";
              print "<td>" . $fivesdraft->time . "</td>";
              print "<td>" . $fivesdraft->ip . "</td>";
              print "<td>" . $fivesdraft->threat_score;
              if ($fivesdraft->threat_score > 0)
              {
                  print "/";
                 
				  if ($fivesdraft->threat_type == 0)
                      print "Sp"; // Spider
                  else
                  {
                      if (($fivesdraft->threat_type & 1) == 1)
                          print "S"; // Suspicious
                      if (($fivesdraft->threat_type & 2) == 2)
                          print "H"; // Harvester
                      if (($fivesdraft->threat_type & 4) == 4)
                          print "C"; // Comment spammer
                  }
              }
              print "<td>" . $fivesdraft->nation . "</td>";
              print "<td>" . sps_Abbrevia(sps_Decode($fivesdraft->urlrequested), 30) . "</td>";
              print "<td>" . $fivesdraft->os . "</td>";
              print "<td>" . $fivesdraft->browser . "</td>";
              print "<td>" . $fivesdraft->feed . "</td>";
              print "</tr>";
          }
          print "</table></div>";
          
          
          // Last Search terms
          print "<div id='tabs-3' class='ui-tabs-hide'><h2>" . __('Last search terms', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('Terms', 'statpress') . "</th><th scope='col'>" . __('Engine', 'statpress') . "</th><th scope='col'>" . __('Target URL', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,referrer,urlrequested,search,searchengine FROM $table_name WHERE search<>'' ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . sps_hdate($rk->date) . "</td><td>" . $rk->time . "</td><td><a href='" . $rk->referrer . "'>" . urldecode($rk->search) . "</a></td><td>" . $rk->searchengine . "</td><td><a href='" . sps_getblogurl() . ((strpos($rk->urlrequested, 'index.php') === FALSE) ? $rk->urlrequested : '') . "'>" . $rk->urlrequested . "</a></td></tr>\n";
          }
          print "</table></div>";
          
          // Referrer
          print "<div id='tabs-4' class='ui-tabs-hide'><h2>" . __('Last referrers', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('URL', 'statpress') . "</th><th scope='col'>" . __('Result', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,referrer,urlrequested FROM $table_name WHERE ((referrer NOT LIKE '" . get_option('home') . "%') AND (referrer <>'') AND (searchengine='')) ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . sps_hdate($rk->date) . "</td><td>" . $rk->time . "</td><td><a href='" . $rk->referrer . "'>" . sps_Abbrevia($rk->referrer, 80) . "</a></td><td><a href='" . sps_getblogurl() . ((strpos($rk->urlrequested, 'index.php') === FALSE) ? $rk->urlrequested : '') . "'>" . __('page viewed', 'statpress') . "</a></td></tr>\n";
          }
          print "</table></div>";
          
          // Last Agents
          print "<div id='tabs-5' class='ui-tabs-hide'><h2>" . __('Last agents', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('Agent', 'statpress') . "</th><th scope='col'>" . __('What', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,agent,os,browser,spider FROM $table_name WHERE (agent <>'') ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . sps_hdate($rk->date) . "</td><td>" . $rk->time . "</td><td>" . $rk->agent . "</td><td> " . $rk->os . " " . $rk->browser . " " . $rk->spider . "</td></tr>\n";
          }
          print "</table></div>";
          
          // Last pages
          print "<div id='tabs-6' class='ui-tabs-hide'><h2>" . __('Last pages', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('Page', 'statpress') . "</th><th scope='col'>" . __('What', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,urlrequested,os,browser,spider FROM $table_name WHERE (spider='' AND feed='') ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . sps_hdate($rk->date) . "</td><td>" . $rk->time . "</td><td>" . sps_Abbrevia(sps_Decode($rk->urlrequested), 60) . "</td><td> " . $rk->os . " " . $rk->browser . " " . $rk->spider . "</td></tr>\n";
          }
          print "</table></div>";
          
          
          print "<br />";
          print "&nbsp;<i>" . __('StatPress table size', 'statpress') . ": <b>" . sps_tablesize($wpdb->prefix . "statpress") . "</b></i><br />";
          print "&nbsp;<i>" . __('StatPress current time', 'statpress') . ": <b>" . current_time('mysql') . "</b></i><br />";
          print "&nbsp;<i>" . __('RSS2 url', 'statpress') . ": <b>" . get_bloginfo('rss2_url') . ' (' . sps_extractfeedreq(get_bloginfo('rss2_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('ATOM url', 'statpress') . ": <b>" . get_bloginfo('atom_url') . ' (' . sps_extractfeedreq(get_bloginfo('atom_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('RSS url', 'statpress') . ": <b>" . get_bloginfo('rss_url') . ' (' . sps_extractfeedreq(get_bloginfo('rss_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('COMMENT RSS2 url', 'statpress') . ": <b>" . get_bloginfo('comments_rss2_url') . ' (' . sps_extractfeedreq(get_bloginfo('comments_rss2_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('COMMENT ATOM url', 'statpress') . ": <b>" . get_bloginfo('comments_atom_url') . ' (' . sps_extractfeedreq(get_bloginfo('comments_atom_url')) . ")</b></i><br />";
		  
		  sps_general_footout();
      }
?>
