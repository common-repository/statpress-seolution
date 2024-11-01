<?php
      function sps_Details()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          $querylimit = "LIMIT 25";
		  
		sps_general_headout(__('Details','statpress'));
?>
<div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-01">Top Days</a></li>
				<li><a href="#tabs-05">Search Stuff</a></li>
				<li><a href="#tabs-09">Spiders</a></li>
				<li><a href="#tabs-07">Referrer</a></li>
				<li><a href="#tabs-10">Pages</a></li>
				<li><a href="#tabs-08">Countries</a></li>
				<li><a href="#tabs-02">O.S.</a></li>
				<li><a href="#tabs-03">Browser</a></li>
				<li><a href="#tabs-04">Feeds</a></li>
			</ul>
			
<?php          
          
          print "<div id='tabs-01' class='ui-tabs-hide'>";
          // Top days
          sps_ValueTable("date", __('Top days', 'statpress'), 10);
          // Top Days - Unique visitors
          sps_ValueTable("date", __('Top Days - Unique visitors', 'statpress'), 10, "distinct", "ip", "AND feed='' and spider=''");
          /* Maddler 04112007: required patching sps_ValueTable */
          // Top Days - Pageviews
          sps_ValueTable("date", __('Top Days - Pageviews', 'statpress'), 10, "", "urlrequested", "AND feed='' and spider=''");
          /* Maddler 04112007: required patching sps_ValueTable */
          // Top IPs - Pageviews
          sps_ValueTable("ip", __('Top IPs - Pageviews', 'statpress'), 10, "", "urlrequested", "AND feed='' and spider=''");
          /* Maddler 04112007: required patching sps_ValueTable */
          print "</div>";
          
          // O.S.
          print "<div id='tabs-02' class='ui-tabs-hide'>";
          sps_ValueTable("os", __('O.S.', 'statpress'), 0, "", "", "AND feed='' AND spider='' AND os<>''");
          print "</div>";
          
          // Browser
          print "<div id='tabs-03' class='ui-tabs-hide'>";
          sps_ValueTable("browser", __('Browser', 'statpress'), 0, "", "", "AND feed='' AND spider='' AND browser<>''");
          print "</div>";
          
          // Feeds
          print "<div id='tabs-04' class='ui-tabs-hide'>";
          sps_ValueTable("feed", __('Feeds', 'statpress'), 25, "", "", "AND feed<>''");
          print "</div>";
          
          print "<div id='tabs-05' class='ui-tabs-hide'>";
          // SE
          sps_ValueTable("searchengine", __('Search engines', 'statpress'), 25, "", "", "AND searchengine<>''");
          // Search terms
          sps_ValueTable("search", __('Top search terms', 'statpress'), 50, "", "", "AND search<>''");
          print "</div>";
          
          // Top referrer
          print "<div id='tabs-07' class='ui-tabs-hide'>";
          sps_ValueTable("referrer", __('Top referrer', 'statpress'), 25, "", "", "AND referrer<>'' AND referrer NOT LIKE '%" . get_bloginfo('url') . "%'");
          print "</div>";
          
          // Countries
          print "<div id='tabs-08' class='ui-tabs-hide'>";
          sps_ValueTable("nation", __('Countries (domains)', 'statpress'), 25, "", "", "AND nation<>'' AND spider=''");
          print "</div>";
          
          // Spider
          print "<div id='tabs-09' class='ui-tabs-hide'>";
          sps_ValueTable("spider", __('Spiders', 'statpress'), 25, "", "", "AND spider<>''");
          print "</div>";
          
          // Top Pages
          print "<div id='tabs-10' class='ui-tabs-hide'>";
          sps_ValueTable("urlrequested", __('Top pages', 'statpress'), 25, "", "urlrequested", "AND feed='' and spider=''");
          print "</div>";
          
          
    
		print "</div>"; // #tabs
		
		sps_general_footout();
      }
?>
