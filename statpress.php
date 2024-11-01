<?php
  /*
   Plugin Name: StatPress Seolution
   Plugin URI: http://blogcraft.de/wordpress-plugins/statpress-seolution/
   Description: Based on Statpress [Reloaded] with some more improvements (better crawling stats of important search engines, module structured code, Flash charts)
   Version: 0.4.2.2
   Author: Christoph Grabo
   Author URI: http://blogcraft.de/
   */
  $_STATPRESS['version'] =			'0.4.2.2';
  $_STATPRESS['feedtype'] =			'';
  $_STATPRESS['tableversion'] =	'0304'; // dont mix it up with the general plugin version
  
	include(dirname(__FILE__).'/sps_settings.php');
	
  if ($_GET['sps_action'] == 'exportnow') {
		include(dirname(__FILE__).'/sps_exportnow.php');
		sps_ExportNow();
  }


	function sps_adminhead_script() {
		sps_load_scripts();

	}
		
	function sps_load_scripts() {
		// style and script loader routine
		global $_STATPRESS;
		$s_theme = (get_option('sps_theme')=='') ? SPS_DEFTHEME : get_option('sps_theme');
		?>
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="pragma" content="no-cache">
		<link rel='stylesheet' href='<?php echo sps_wurl().'/lib/jquery/css/'.$s_theme.'/jquery-ui-1.7.2.custom.css?ver='.$_STATPRESS['version']; ?>' type='text/css' media='all' />
		<link rel='stylesheet' href='<?php echo sps_wurl().'/statpress.css?ver='.$_STATPRESS['version']; ?>' type='text/css' media='all' />
		<script type='text/javascript' src='<?php echo sps_wurl().'/lib/jquery/js/jquery-1.3.2.min.js?ver='.$_STATPRESS['version']; ?>'></script>
		<script type='text/javascript' src='<?php echo sps_wurl().'/lib/jquery/js/jquery-ui-1.7.2.custom.min.js?ver='.$_STATPRESS['version']; ?>'></script>
		<script type='text/javascript' src='<?php echo sps_wurl().'/statpress.js?ver='.$_STATPRESS['version']; ?>'></script>
		<?php
	}
  
	function sps_add_pages() {
		// Create table if it doesn't exist
		global $wpdb;
		$table_name = $wpdb->prefix . 'statpress';
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
			sps_CreateTable();
		}

		// add submenu
		$mincap = get_option('sps_mincap');
		if ($mincap == '')
		{
			$mincap = 'level_8';
		}

		$sps_apage = add_object_page(
					'StatPress SEOlution (SPS)',
					'StatPress SEO.',
					$mincap,
					'sps',
					'sps_Statpress'
					);
		
		$sps_apage_main = add_submenu_page('sps',
			'SPS '.__('Dashboard', 'statpress'), 
			__('Dashboard', 'statpress'), 
			$mincap, 
			'sps', 
			'sps_Statpress');
		$sps_apage_spiders = add_submenu_page('sps',
			'SPS '.__('Spider Stats', 'statpress'), 
			__('Spider Stats', 'statpress'), 
			$mincap, 
			'sps/spiderstats', 
			'sps_Statpress');
		$sps_apage_details = add_submenu_page('sps', 
			'SPS '.__('Details', 'statpress'), 
			__('Details', 'statpress'), 
			$mincap, 
			'sps/details', 
			'sps_Statpress');
		$sps_apage_spy = add_submenu_page('sps', 
			'SPS '.__('Spy', 'statpress'), 
			__('Spy', 'statpress'), 
			$mincap, 
			'sps/spy', 
			'sps_Statpress');
		$sps_apage_search = add_submenu_page('sps', 
			'SPS '.__('Search', 'statpress'), 
			__('Search', 'statpress'), 
			$mincap, 
			'sps/search', 
			'sps_Statpress');
		$sps_apage_export = add_submenu_page('sps', 
			'SPS '.__('Export', 'statpress'), 
			__('Export', 'statpress'), 
			$mincap, 
			'sps/export', 
			'sps_Statpress');
		$sps_apage_agents = add_submenu_page('sps', 
			'SPS '.__('User Agents', 'statpress'), 
			__('User Agents', 'statpress'), 
			$mincap,
			'sps/agents', 
			'sps_Statpress');
		$sps_apage_update = add_submenu_page('sps', 
			'SPS '.__('StatPressUpdate', 'statpress'), 
			__('StatPressUpdate', 'statpress'), 
			$mincap, 
			'sps/up', 
			'sps_Statpress');
		$sps_apage_options = add_submenu_page('sps', 
			'SPS '.__('Options', 'statpress'), 
			__('Options', 'statpress'), 
			$mincap, 
			'sps/options', 
			'sps_Statpress');
			
		//add_submenu_page('StatPress SEOlution', __('Support','statpress'), __('Support','statpress'), $mincap, 'http://blogcraft.de/wordpress-plugins/statpress-seolution/support/');

		#add_action('admin_head-'.$sps_apage,'sps_adminhead_script');
		
		add_action('admin_head-'.$sps_apage_main,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_spiders,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_details,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_spy,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_search,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_export,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_agents,'sps_adminhead_script');
		add_action('admin_head-'.$sps_apage_update,'sps_adminhead_script');
		
		add_action('admin_head-'.$sps_apage_options,'sps_adminhead_script');
	}
  
  function permalinksEnabled()
  {
      global $wpdb;
      
      $result = $wpdb->get_row('SELECT `option_value` FROM `' . $wpdb->prefix . 'options` WHERE `option_name` = "permalink_structure"');
      if ($result->option_value != '')
      {
          return true;
      }
      else
      {
          return false;
      }
  }
  

	function sps_Statpress() {
	  $page = explode('/',$_GET['page']);
	  $spsaction = $page[1];
	  switch($spsaction) {
			case 'up':
			  include(dirname(__FILE__).'/module.update.php');
				sps_Update(); break;

			case 'export':
			  include(dirname(__FILE__).'/module.export.php');
				sps_Export(); break;
				
			case 'spy':
			  include(dirname(__FILE__).'/module.spy.php');
				sps_Spy(); break;
				
			case 'search':
			  include(dirname(__FILE__).'/module.search.php');
				sps_Search(); break;
				
			case 'details':
			  include(dirname(__FILE__).'/module.details.php');
				sps_Details(); break;
				
			case 'spiderstats':
			  include(dirname(__FILE__).'/module.spiderstats.php');
				$type = $_GET['sps_stype'];
				switch($type) {
					case 'google':
						sps_SpiderStats('Google');
						break;
					case 'yahoo':
						sps_SpiderStats('Yahoo');
						break;
					case 'bing_msn':
					case 'bingmsn':
					case 'bing':
					case 'msn':
						sps_SpiderStats('MSN');
						break;
					case 'searched':
						$phrase = $_GET['sps_phrase'];
						if($phrase=='' || $phrase==='') sps_SpiderStats('all'); //if phrase is empty
						sps_SpiderStats('searched',$phrase);
						break;
					case 'all':
						sps_SpiderStats('all');
						break;
					default:
						$defview = get_option('sps_defaultspiderview');
						sps_SpiderStats($defview);
				}
				break;
				
			case 'options':
			  include(dirname(__FILE__).'/module.options.php');
				sps_Options(); break;
				
			case 'agents':
			  include(dirname(__FILE__).'/module.agents.php');
				sps_Agents(); break;
				
			case 'overview':
			default:
			  include(dirname(__FILE__).'/module.main.php');
				sps_Main();
			}
	} // sps_Statpress - switcher


      function sps_dropdown_caps($default = false)
      {
          global $wp_roles;
          $role = get_role('administrator');
          foreach ($role->capabilities as $cap => $grant)
          {
              print "<option ";
              if ($default == $cap)
              {
                  print "selected ";
              }
              print ">$cap</option>";
          }
      }
      
      function sps_Abbrevia($s, $c)
      {
          $res = "";
          if (strlen($s) > $c)
          {
              $res = "...";
          }
          return sps_substr($s, 0, $c) . $res;
      }
      
      function sps_Where($ip)
      {
          $url = "http://api.hostip.info/get_html.php?ip=$ip";
          $res = file_get_contents($url);
          if ($res === false)
          {
              return(array('', ''));
          }
          $res = str_replace("Country: ", "", $res);
          $res = str_replace("\nCity: ", ", ", $res);
          $nation = preg_split('/\(|\)/', $res);
          print "( $ip $res )";
          return(array($res, $nation[1]));
      }
      
      
      function sps_Decode($out_url)
      {
      	if(!permalinksEnabled())
      	{
	          if ($out_url == '')
	          {
	              $out_url = __('Page', 'statpress') . ": Home";
	          }
	          if (sps_substr($out_url, 0, 4) == "cat=")
	          {
	              $out_url = __('Category', 'statpress') . ": " . get_cat_name(sps_substr($out_url, 4));
	          }
	          if (sps_substr($out_url, 0, 2) == "m=")
	          {
	              $out_url = __('Calendar', 'statpress') . ": " . sps_substr($out_url, 6, 2) . "/" . sps_substr($out_url, 2, 4);
	          }
	          if (sps_substr($out_url, 0, 2) == "s=")
	          {
	              $out_url = __('Search', 'statpress') . ": " . sps_substr($out_url, 2);
	          }
	          if (sps_substr($out_url, 0, 2) == "p=")
	          {
	              $post_id_7 = get_post(sps_substr($out_url, 2), ARRAY_A);
	              $out_url = $post_id_7['post_title'];
	          }
	          if (sps_substr($out_url, 0, 8) == "page_id=")
	          {
	              $post_id_7 = get_page(sps_substr($out_url, 8), ARRAY_A);
	              $out_url = __('Page', 'statpress') . ": " . $post_id_7['post_title'];
	          }
	        }
	        else
	        {
	        	if ($out_url == '')
	          {
	              $out_url = __('Page', 'statpress') . ": Home";
	          }
	          else if (sps_substr($out_url, 0, 9) == "category/")
	          {
	              $out_url = __('Category', 'statpress') . ": " . get_cat_name(sps_substr($out_url, 9));
	          }
	          else if (sps_substr($out_url, 0, 8) == "//") // not working yet
	          {
	              //$out_url = __('Calendar', 'statpress') . ": " . sps_substr($out_url, 4, 0) . "/" . sps_substr($out_url, 6, 7);
	          }
	          else if (sps_substr($out_url, 0, 2) == "s=")
	          {
	              $out_url = __('Search', 'statpress') . ": " . sps_substr($out_url, 2);
	          }
	          else if (sps_substr($out_url, 0, 2) == "p=") // not working yet 
	          {
	              $post_id_7 = get_post(sps_substr($out_url, 2), ARRAY_A);
	              $out_url = $post_id_7['post_title'];
	          }
	          else if (sps_substr($out_url, 0, 8) == "page_id=") // not working yet
	          {
	              $post_id_7 = get_page(sps_substr($out_url, 8), ARRAY_A);
	              $out_url = __('Page', 'statpress') . ": " . $post_id_7['post_title'];
	          }
	        }
          return $out_url;
      }
      
      
      function sps_URL()
      {
          $urlRequested = (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
          if ($urlRequested == "")
          {
              // SEO problem!
              $urlRequested = (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '');
          }
          if (sps_substr($urlRequested, 0, 2) == '/?')
          {
              $urlRequested = sps_substr($urlRequested, 2);
          }
          if ($urlRequested == '/')
          {
              $urlRequested = '';
          }
          return $urlRequested;
      }
      
      function sps_getblogurl()
      {
      	$prsurl = parse_url(get_bloginfo('url'));
      	return $prsurl['scheme'] . '://' . $prsurl['host'] . ((!permalinksEnabled()) ? $prsurl['path'] . '/?' : '');
      }
      
      // Converte da data us to default format di Wordpress
      function sps_hdate($dt = "00000000")
      {
          return mysql2date(get_option('date_format'), sps_substr($dt, 0, 4) . "-" . sps_substr($dt, 4, 2) . "-" . sps_substr($dt, 6, 2));
      }
      
      
      function sps_tablesize($table)
      {
          global $wpdb;
          $res = $wpdb->get_results("SHOW TABLE STATUS LIKE '$table'");
          foreach ($res as $fstatus)
          {
              $data_lenght = $fstatus->Data_length;
              $data_rows = $fstatus->Rows;
          }
          return number_format(($data_lenght / 1024 / 1024), 2, ",", " ") . " MiB ($data_rows records)";
      }
      
      
      function sps_RGBHex($red, $green, $blue)
      {
          $red = 0x10000 * max(0, min(255, $red + 0));
          $green = 0x100 * max(0, min(255, $green + 0));
          $blue = max(0, min(255, $blue + 0));
          // convert the combined value to hex and zero-fill to 6 digits
          return "#" . str_pad(strtoupper(dechex($red + $green + $blue)), 6, "0", STR_PAD_LEFT);
      }
      
      
      function sps_ValueTable($fld, $fldtitle, $limit = 0, $param = "", $queryfld = "", $exclude = "")
      {
          /* Maddler 04112007: param addedd */
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          if ($queryfld == '')
          {
              $queryfld = $fld;
          }
          print "<div class='wrap'><h2>$fldtitle</h2><table style='width:100%;padding:0px;margin:0px;' cellpadding=0 cellspacing=0><thead><tr><th style='width:400px;background-color:white;'></th><th style='width:150px;background-color:white;'><u>" . __('Visits', 'statpress') . "</u></th><th style='background-color:white;'></th></tr></thead>";
          print "<tbody id='the-list'>";
          $rks = $wpdb->get_var("SELECT count($param $queryfld) as rks FROM $table_name WHERE 1=1 $exclude;");
          if ($rks > 0)
          {
              $sql = "SELECT count($param $queryfld) as pageview, $fld FROM $table_name WHERE 1=1 $exclude  GROUP BY $fld ORDER BY pageview DESC";
              if ($limit > 0)
              {
                  $sql = $sql . " LIMIT $limit";
              }
              $qry = $wpdb->get_results($sql);
              $tdwidth = 450;
              $red = 131;
              $green = 180;
              $blue = 216;
              $deltacolor = round(250 / count($qry), 0);
              //      $chl="";
              //      $chd="t:";
              foreach ($qry as $rk)
              {
                  $pc = round(($rk->pageview * 100 / $rks), 1);
                  if ($fld == 'date')
                  {
                      $rk->$fld = sps_hdate($rk->$fld);
                  }
                  if ($fld == 'urlrequested' or $fld=='referrer')
                  {
                      $rk->$fld = sps_Decode($rk->$fld);
                  }
                  
                  if ($fld == 'search')
                  {
                  	$rk->$fld = urldecode($rk->$fld);
                  }
                  
                  //      $chl.=urlencode(sps_substr($rk->$fld,0,50))."|";
                  //      $chd.=($tdwidth*$pc/100)."|";
				  if($fld == 'urlrequested' or $fld=='referrer') {
                  print "<tr><td style='width:400px;overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>";
					  if($fld=='urlrequested') {
						  print $rk->$fld; 
					  } else {
						  print '<a href="' . $rk->$fld . '">' . $rk->$fld . '</a>'; 
					  }
				  } else {
					  print "<tr><td style='width:400px;overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>" . sps_substr($rk->$fld, 0, 50);
					  if (strlen("$rk->fld") >= 50)
					  {
						  print "...";
					  }
				  }
                  // <td style='text-align:right'>$pc%</td>";
                  print "</td><td style='text-align:center;'>" . $rk->pageview . "</td>";
                  print "<td><div style='text-align:right;padding:2px;font-family:helvetica;font-size:7pt;font-weight:bold;height:16px;width:" . number_format(($tdwidth * $pc / 100), 1, '.', '') . "px;background:" . sps_RGBHex($red, $green, $blue) . ";border-top:1px solid " . sps_RGBHex($red + 20, $green + 20, $blue) . ";border-right:1px solid " . sps_RGBHex($red + 30, $green + 30, $blue) . ";border-bottom:1px solid " . sps_RGBHex($red - 20, $green - 20, $blue) . ";'>$pc%</div>";
                  print "</td></tr>\n";
                  $red = $red + $deltacolor;
                  $blue = $blue - ($deltacolor / 2);
              }
          }
          print "</table>\n";
          //  $chl=sps_substr($chl,0,strlen($chl)-1);
          //  $chd=sps_substr($chd,0,strlen($chd)-1);
          //  print "<img src=http://chart.apis.google.com/chart?cht=p3&chd=".($chd)."&chs=400x200&chl=".($chl)."&chco=1B75DF,92BF23>\n";
          print "</div>\n";
      }
      
      
      
      function sps_Domain($ip)
      {
          $host = gethostbyaddr($ip);
          if (ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}$', $host))
          {
              return "";
          }
          else
          {
              return sps_substr(strrchr($host, "."), 1);
          }
      }
      
      function sps_GetQueryPairs($url)
      {
          $parsed_url = parse_url($url);
          $tab = parse_url($url);
          $host = $tab['host'];
          if (key_exists("query", $tab))
          {
              $query = $tab["query"];
              $query = str_replace("&amp;", "&", $query);
              $query = urldecode($query);
              $query = str_replace("?", "&", $query);
              return explode("&", $query);
          }
          else
          {
              return null;
          }
      }
      
      
      function sps_GetOS($arg)
      {
          $arg = str_replace(" ", "", $arg);
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/os.dat');
          foreach ($lines as $line_num => $os)
          {
              list($nome_os, $id_os) = explode("|", $os);
              if (strpos($arg, $id_os) === false)
                  continue;
              // riconosciuto
              return $nome_os;
          }
          return '';
      }
      
      
      function sps_GetBrowser($arg)
      {
          $arg = str_replace(" ", "", $arg);
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/browser.dat');
          foreach ($lines as $line_num => $browser)
          {
              list($nome, $id) = explode("|", $browser);
              if (strpos($arg, $id) === false)
                  continue;
              // riconosciuto
              return $nome;
          }
          return '';
      }
      
	  function sps_CheckBanIP($arg)
      {
          if (file_exists(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/custom-def/banips.dat'))
              $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/custom-def/banips.dat');
          else
              $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/banips.dat');
         
        if ($lines !== false)
        {
            foreach ($lines as $banip)
              {
               if (@preg_match('/^' . rtrim($banip, "\r\n") . '$/', $arg)){
                   return true;
               }
                  // riconosciuto, da scartare
              }
          }
          return false;
      }
      
      function sps_GetSE($referrer = null)
      {
          $key = null;
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/searchengines.dat');
          foreach ($lines as $line_num => $se)
          {
              list($nome, $url, $key) = explode("|", $se);
              if (strpos($referrer, $url) === false)
                  continue;
              // trovato se
              $variables = sps_GetQueryPairs($referrer);
              $i = count($variables);
              while ($i--)
              {
                  $tab = explode("=", $variables[$i]);
                  if ($tab[0] == $key)
                  {
                      return($nome . "|" . urlencode($tab[1]));
                  }
              }
          }
          return null;
      }
      
      function sps_GetSpider($agent = null)
      {
          $agent = str_replace(" ", "", $agent);
          $key = null;
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/spider.dat');
          if (file_exists(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/custom-def/spider.dat'))
              $lines = array_merge(file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/custom-def/spider.dat'),$lines);
          foreach ($lines as $line_num => $spider)
          {
              list($nome, $key) = explode("|", $spider);
              if (strpos($agent, $key) === false)
                  continue;
              // trovato
              return $nome;
          }
          return null;
      }
      
      
      function sps_lastmonth()
      {
          $ta = getdate(current_time('timestamp'));
          
          $year = $ta['year'];
          $month = $ta['mon'];
          
          // go back 1 month;
          $month = $month - 1;
          
          if ($month === 0)
          {
          	// if this month is Jan
            // go back a year
            $year  = $year - 1;
          	$month = 12;
          }
          
          // return in format 'YYYYMM'
          return sprintf($year . '%02d', $month);
      }
      
      
      function sps_CreateTable()
      {
          global $wpdb;
          global $wp_db_version;
          global $_STATPRESS;
          $table_name = $wpdb->prefix . "statpress";
          
          $sql_createtable_OLD = "CREATE TABLE " . $table_name . " (
  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
  date TINYTEXT,
  time TINYTEXT,
  ip TINYTEXT,
  urlrequested TEXT,
  agent TEXT,
  referrer TEXT,
  search TEXT,
  nation TINYTEXT,
  os TINYTEXT,
  browser TINYTEXT,
  searchengine TINYTEXT,
  spider TINYTEXT,
  feed TINYTEXT,
  user TINYTEXT,
  timestamp TINYTEXT,
  threat_score SMALLINT,
  threat_type SMALLINT,
  UNIQUE KEY id (id)
  );";
  
          $sql_createtable = "CREATE TABLE " . $table_name . " (
  id mediumint(9) NOT NULL auto_increment,
  date tinytext,
  time tinytext,
  ip tinytext,
  urlrequested text,
  agent text,
  referrer text,
  search text,
  nation tinytext,
  os tinytext,
  browser tinytext,
  searchengine tinytext,
  spider tinytext,
  feed tinytext,
  user tinytext,
  timestamp tinytext,
  threat_score smallint(6) default NULL,
  threat_type smallint(6) default NULL,
  UNIQUE KEY id (id),
  KEY date (date(8)),
  KEY time_hour (time(2)),
  KEY date_time (date(8),time(8)),
  KEY spiders (spider(32))
) ENGINE=MyISAM DEFAULT CHARSET=utf8
;";

/*
          $sql_altertable = "ALTER TABLE " . $table_name . " CHANGE
  id mediumint(9) NOT NULL auto_increment,
  date tinytext,
  time tinytext,
  ip tinytext,
  urlrequested text,
  agent text,
  referrer text,
  search text,
  nation tinytext,
  os tinytext,
  browser tinytext,
  searchengine tinytext,
  spider tinytext,
  feed tinytext,
  user tinytext,
  timestamp tinytext,
  threat_score smallint(6) default NULL,
  threat_type smallint(6) default NULL,
  UNIQUE KEY id (id),
  KEY date (date(8)),
  KEY time_hour (time(2)),
  KEY date_time (date(8),time(8)),
  KEY spiders (spider(32))
;";
*/
          if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
          
	          if ($wp_db_version >= 5540)
	              $page = 'wp-admin/includes/upgrade.php';
	          else
	              $page = 'wp-admin/upgrade-functions.php';
	          require_once(ABSPATH . $page);
	          dbDelta($sql_createtable);
	          add_option('sps_tableversion',$_STATPRESS['tableversion']);
	          
					} elseif (get_option('sps_tableversion') < $_STATPRESS['tableversion']) {
					
	          if ($wp_db_version >= 5540)
	              $page = 'wp-admin/includes/upgrade.php';
	          else
	              $page = 'wp-admin/upgrade-functions.php';
	          require_once(ABSPATH . $page);
	          dbDelta($sql_createtable);
	          update_option('sps_tableversion',$_STATPRESS['tableversion']);
	          
					} else { /* if table exists and table version not changed > do nothing :-) */ }

      }
      
function sps_is_feed($url) {
   if (stristr($url,get_bloginfo('comments_atom_url')) != FALSE) { return 'COMMENT ATOM'; }
   elseif (stristr($url,get_bloginfo('comments_rss2_url')) != FALSE) { return 'COMMENT RSS'; }
   elseif (stristr($url,get_bloginfo('rdf_url')) != FALSE) { return 'RDF'; }
   elseif (stristr($url,get_bloginfo('atom_url')) != FALSE) { return 'ATOM'; }
   elseif (stristr($url,get_bloginfo('rss_url')) != FALSE) { return 'RSS'; }
   elseif (stristr($url,get_bloginfo('rss2_url')) != FALSE) { return 'RSS2'; }
   elseif (stristr($url,'wp-feed.php') != FALSE) { return 'RSS2'; }
   elseif (stristr($url,'/feed') != FALSE) { return 'RSS2'; }
   return '';
}

function sps_extractfeedreq($url)
{
		if(!strpos($url, '?') === FALSE)
		{
        list($null, $q) = explode("?", $url);
    		list($res, $null) = explode("&", $q);
    }
    else
    {
    	$prsurl = parse_url($url);
    	$res = $prsurl['path'] . $$prsurl['query'];
    }
    
    return $res;
}
      
      function sps_StatAppend()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          global $userdata;
          global $_STATPRESS;
          get_currentuserinfo();
          $feed = '';
          
          // Time
          $timestamp = current_time('timestamp');
          $vdate = gmdate("Ymd", $timestamp);
          $vtime = gmdate("H:i:s", $timestamp);
          
          // IP
          $ipAddress = $_SERVER['REMOTE_ADDR'];
          if (sps_CheckBanIP($ipAddress) === true)
          {
              return '';
          }
          
          // Determine Threats if http:bl installed
          $threat_score = 0;
          $threat_type = 0;
          $httpbl_key = get_option("httpbl_key");
          if ($httpbl_key !== false)
          {
              $result = explode( ".", gethostbyname( $httpbl_key . "." .
                  implode ( ".", array_reverse( explode( ".",
                  $ipAddress ) ) ) .
                  ".dnsbl.httpbl.org" ) );
              // If the response is positive
              if ($result[0] == 127)
              {
                  $threat_score = $result[2];
                  $threat_type = $result[3];
              }
          }
          
          // URL (requested)
          $urlRequested = sps_URL();
          if (eregi(".ico$", $urlRequested))
          {
              return '';
          }
          if (eregi("favicon.ico", $urlRequested))
          {
              return '';
          }
          if (eregi(".css$", $urlRequested))
          {
              return '';
          }
          if (eregi(".js$", $urlRequested))
          {
              return '';
          }
          if (stristr($urlRequested, "/wp-content/plugins") != false)
          {
              return '';
          }
          if (stristr($urlRequested, "/wp-content/themes") != false)
          {
              return '';
          }
          
          $referrer = (isset($_SERVER['HTTP_REFERER']) ? htmlentities($_SERVER['HTTP_REFERER']) : '');
          $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : '');
          $spider = sps_GetSpider($userAgent);
          
          if (($spider != '') and (get_option('sps_donotcollectspider') == 'checked'))
          {
              return '';
          }
          
          if ($spider != '')
          {
              $os = '';
              $browser = '';
          }
          else
          {
              // Trap feeds
              $prsurl = parse_url(get_bloginfo('url'));
              $feed = sps_is_feed($prsurl['scheme'] . '://' . $prsurl['host'] . $_SERVER['REQUEST_URI']);
              // Get OS and browser
              $os = sps_GetOS($userAgent);
              $browser = sps_GetBrowser($userAgent);
              list($searchengine, $search_phrase) = explode("|", sps_GetSE($referrer));
          }
          // Auto-delete visits if...
          if (get_option('sps_autodelete_spider') != '') 
          {
              $t = gmdate("Ymd", strtotime('-' . get_option('sps_autodelete_spider')));
              $results = $wpdb->query("DELETE FROM " . $table_name . " WHERE date < '" . $t . "' AND spider <> ''");
          }
          if (get_option('sps_autodelete') != '')
          {
              $t = gmdate("Ymd", strtotime('-' . get_option('sps_autodelete')));
              $results = $wpdb->query("DELETE FROM " . $table_name . " WHERE date < '" . $t . "'");
          }
          if ((!is_user_logged_in()) or (get_option('sps_collectloggeduser') == 'checked'))
          {
              if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
              {
                  sps_CreateTable();
              }
              
              $insert = "INSERT INTO " . $table_name . " (date, time, ip, urlrequested, agent, referrer, search,nation,os,browser,searchengine,spider,feed,user,threat_score,threat_type,timestamp) " . "VALUES ('$vdate','$vtime','$ipAddress','" . mysql_real_escape_string($urlRequested) . "','" . mysql_real_escape_string(strip_tags($userAgent)) . "','" . mysql_real_escape_string($referrer) . "','" . mysql_real_escape_string(strip_tags($search_phrase)) . "','" . sps_Domain($ipAddress) . "','" . mysql_real_escape_string($os) . "','" . mysql_real_escape_string($browser) . "','$searchengine','$spider','$feed','$userdata->user_login',$threat_score,$threat_type,'$timestamp')";
              $results = $wpdb->query($insert);
          }
      }
      
      

      function sps_Widget($w = '')
      {
      }
      
      function sps_Print($body = '')
      {
          print sps_Vars($body);
      }
      
      
      function sps_Vars($body)
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          if (strpos(strtolower($body), "%visits%") !== false)
          {
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as pageview FROM $table_name WHERE date = '" . gmdate("Ymd", current_time('timestamp')) . "' and spider='' and feed='';");
              $body = str_replace("%visits%", $qry[0]->pageview, $body);
          }
          if (strpos(strtolower($body), "%totalvisits%") !== false)
          {
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as pageview FROM $table_name WHERE spider='' and feed='';");
              $body = str_replace("%totalvisits%", $qry[0]->pageview, $body);
          }
          if (strpos(strtolower($body), "%thistotalvisits%") !== false)
          {
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as pageview FROM $table_name WHERE spider='' and feed='' AND urlrequested='" . mysql_real_escape_string(sps_URL()) . "';");
              $body = str_replace("%thistotalvisits%", $qry[0]->pageview, $body);
          }
          if (strpos(strtolower($body), "%since%") !== false)
          {
              $qry = $wpdb->get_results("SELECT date FROM $table_name ORDER BY date LIMIT 1;");
              $body = str_replace("%since%", sps_hdate($qry[0]->date), $body);
          }
          if (strpos(strtolower($body), "%os%") !== false)
          {
              $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
              $os = sps_GetOS($userAgent);
              $body = str_replace("%os%", $os, $body);
          }
          if (strpos(strtolower($body), "%browser%") !== false)
          {
              $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
              $browser = sps_GetBrowser($userAgent);
              $body = str_replace("%browser%", $browser, $body);
          }
          if (strpos(strtolower($body), "%ip%") !== false)
          {
              $ipAddress = $_SERVER['REMOTE_ADDR'];
              $body = str_replace("%ip%", $ipAddress, $body);
          }
          if (strpos(strtolower($body), "%visitorsonline%") !== false)
          {
              $to_time = current_time('timestamp');
              $from_time = strtotime('-4 minutes', $to_time);
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as visitors FROM $table_name WHERE spider='' and feed='' AND timestamp BETWEEN $from_time AND $to_time;");
              $body = str_replace("%visitorsonline%", $qry[0]->visitors, $body);
          }
          if (strpos(strtolower($body), "%usersonline%") !== false)
          {
              $to_time = current_time('timestamp');
              $from_time = strtotime('-4 minutes', $to_time);
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as users FROM $table_name WHERE spider='' and feed='' AND user<>'' AND timestamp BETWEEN $from_time AND $to_time;");
              $body = str_replace("%usersonline%", $qry[0]->users, $body);
          }
          if (strpos(strtolower($body), "%toppost%") !== false)
          {
              $qry = $wpdb->get_results("SELECT urlrequested,count(*) as totale FROM $table_name WHERE spider='' AND feed='' AND urlrequested LIKE '%p=%' GROUP BY urlrequested ORDER BY totale DESC LIMIT 1;");
              $body = str_replace("%toppost%", sps_Decode($qry[0]->urlrequested), $body);
          }
          if (strpos(strtolower($body), "%topbrowser%") !== false)
          {
              $qry = $wpdb->get_results("SELECT browser,count(*) as totale FROM $table_name WHERE spider='' AND feed='' GROUP BY browser ORDER BY totale DESC LIMIT 1;");
              $body = str_replace("%topbrowser%", sps_Decode($qry[0]->browser), $body);
          }
          if (strpos(strtolower($body), "%topos%") !== false)
          {
              $qry = $wpdb->get_results("SELECT os,count(*) as totale FROM $table_name WHERE spider='' AND feed='' GROUP BY os ORDER BY totale DESC LIMIT 1;");
              $body = str_replace("%topos%", sps_Decode($qry[0]->os), $body);
          }
          if(strpos(strtolower($body),"%pagestoday%") !== false)
          {
      				$qry = $wpdb->get_results("SELECT count(ip) as pageview FROM $table_name WHERE date = '".gmdate("Ymd",current_time('timestamp'))."' and spider='' and feed='';");
      				$body = str_replace("%pagestoday%", $qry[0]->pageview, $body);
   				}
   				
   				if(strpos(strtolower($body),"%thistotalpages%") !== FALSE)
   				{
      				$qry = $wpdb->get_results("SELECT count(ip) as pageview FROM $table_name WHERE spider='' and feed='';");
      				$body = str_replace("%thistotalpages%", $qry[0]->pageview, $body);
      		}
      		
      		if (strpos(strtolower($body), "%latesthits%") !== false)
			{
				$qry = $wpdb->get_results("SELECT search FROM $table_name WHERE search <> '' ORDER BY id DESC LIMIT 10");
				$body = str_replace("%latesthits%", urldecode($qry[0]->search), $body);
				for ($counter = 0; $counter < 10; $counter += 1)
				{
					$body .= "<br>". urldecode($qry[$counter]->search);
				}
			}
			
			if (strpos(strtolower($body), "%pagesyesterday%") !== false)
			{
				$yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
				$qry = $wpdb->get_row("SELECT count(DISTINCT ip) AS visitsyesterday FROM $table_name WHERE feed='' AND spider='' AND date = '" . $yesterday . "'");
				$body = str_replace("%pagesyesterday%", (is_array($qry) ? $qry[0]->visitsyesterday : 0), $body);
			}
          
			
          return $body;
      }
      
      
      function sps_TopPosts($limit = 5, $showcounts = 'checked')
      {
          global $wpdb;
          $res = "\n<ul>\n";
          $table_name = $wpdb->prefix . "statpress";
          $qry = $wpdb->get_results("SELECT urlrequested,count(*) as totale FROM $table_name WHERE spider='' AND feed='' GROUP BY urlrequested ORDER BY totale DESC LIMIT $limit;");
          foreach ($qry as $rk)
          {
              $res .= "<li><a href='" . sps_getblogurl() . ((strpos($rk->urlrequested, 'index.php') === FALSE) ? $rk->urlrequested : '') . "'>" . sps_Decode($rk->urlrequested) . "</a></li>\n";
              if (strtolower($showcounts) == 'checked')
              {
                  $res .= " (" . $rk->totale . ")";
              }
          }
          return "$res</ul>\n";
      }
      
      
      function widget_sps_init($args)
      {
          if (!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
              return;
          // Multifunctional StatPress pluging
          function widget_sps_control()
          {
              $options = get_option('widget_statpress');
              if (!is_array($options))
                  $options = array('title' => 'StatPress', 'body' => 'Visits today: %visits%');
              if ($_POST['statpress-submit'])
              {
                  $options['title'] = strip_tags(stripslashes($_POST['statpress-title']));
                  $options['body'] = stripslashes($_POST['statpress-body']);
                  update_option('widget_statpress', $options);
              }
              $title = htmlspecialchars($options['title'], ENT_QUOTES);
              $body = htmlspecialchars($options['body'], ENT_QUOTES);
              // the form
              echo '<p style="text-align:right;"><label for="statpress-title">' . __('Title:') . ' <input style="width: 250px;" id="statpress-title" name="statpress-title" type="text" value="' . $title . '" /></label></p>';
              echo '<p style="text-align:right;"><label for="statpress-body"><div>' . __('Body:', 'widgets') . '</div><textarea style="width: 288px;height:100px;" id="statpress-body" name="statpress-body" type="textarea">' . $body . '</textarea></label></p>';
              echo '<input type="hidden" id="statpress-submit" name="statpress-submit" value="1" /><div style="font-size:7pt;">%totalvisits% %visits% %thistotalvisits% %os% %browser% %ip% %since% %visitorsonline% %usersonline% %toppost% %topbrowser% %topos%</div>';
          }
          function widget_statpress($args)
          {
              extract($args);
              $options = get_option('widget_statpress');
              $title = $options['title'];
              $body = $options['body'];
              echo $before_widget;
              print($before_title . $title . $after_title);
              print sps_Vars($body);
              echo $after_widget;
          }
          register_sidebar_widget('StatPress', 'widget_statpress');
          register_widget_control(array('StatPress', 'widgets'), 'widget_sps_control', 300, 210);
          
          // Top posts
          function widget_statpresstopposts_control()
          {
              $options = get_option('widget_statpresstopposts');
              if (!is_array($options))
              {
                  $options = array('title' => 'StatPress TopPosts', 'howmany' => '5', 'showcounts' => 'checked');
              }
              if ($_POST['statpresstopposts-submit'])
              {
                  $options['title'] = strip_tags(stripslashes($_POST['statpresstopposts-title']));
                  $options['howmany'] = stripslashes($_POST['statpresstopposts-howmany']);
                  $options['showcounts'] = stripslashes($_POST['statpresstopposts-showcounts']);
                  if ($options['showcounts'] == "1")
                  {
                      $options['showcounts'] = 'checked';
                  }
                  update_option('widget_statpresstopposts', $options);
              }
              $title = htmlspecialchars($options['title'], ENT_QUOTES);
              $howmany = htmlspecialchars($options['howmany'], ENT_QUOTES);
              $showcounts = htmlspecialchars($options['showcounts'], ENT_QUOTES);
              // the form
              echo '<p style="text-align:right;"><label for="statpresstopposts-title">' . __('Title', 'statpress') . ' <input style="width: 250px;" id="statpress-title" name="statpresstopposts-title" type="text" value="' . $title . '" /></label></p>';
              echo '<p style="text-align:right;"><label for="statpresstopposts-howmany">' . __('Limit results to', 'statpress') . ' <input style="width: 100px;" id="statpresstopposts-howmany" name="statpresstopposts-howmany" type="text" value="' . $howmany . '" /></label></p>';
              echo '<p style="text-align:right;"><label for="statpresstopposts-showcounts">' . __('Visits', 'statpress') . ' <input id="statpresstopposts-showcounts" name="statpresstopposts-showcounts" type=checkbox value="checked" ' . $showcounts . ' /></label></p>';
              echo '<input type="hidden" id="statpress-submitTopPosts" name="statpresstopposts-submit" value="1" />';
          }
          function widget_statpresstopposts($args)
          {
              extract($args);
              $options = get_option('widget_statpresstopposts');
              $title = htmlspecialchars($options['title'], ENT_QUOTES);
              $howmany = htmlspecialchars($options['howmany'], ENT_QUOTES);
              $showcounts = htmlspecialchars($options['showcounts'], ENT_QUOTES);
              echo $before_widget;
              print($before_title . $title . $after_title);
              print sps_TopPosts($howmany, $showcounts);
              echo $after_widget;
          }
          register_sidebar_widget('StatPress TopPosts', 'widget_statpresstopposts');
          register_widget_control(array('StatPress TopPosts', 'widgets'), 'widget_statpresstopposts_control', 300, 110);
      }
	  
	function sps_info_header(){
		$s = '<style type="text/css">#spsfooter { text-align: center; }</style>';
		echo $s;
	}
	function sps_info_header_admin(){
		$s = '<style type="text/css">#spsfooter { text-align: center; } #spsfooter-preview { padding: 10px; background:#ccc; }</style>';
		echo $s;
	}
	function sps_info_footer(){
		$s = '<div id="spsfooter">Statistical data collected by <a href="http://blogcraft.de/wordpress-plugins/statpress-seolution/">Statpress SEOlution</a> (<strong><a href="http://blogcraft.de/">blogcraft</a></strong>).</div>';
		echo $s;
	}


if(get_option("sps_show_footer") == "visible") {	
	add_action('wp_head', 'sps_info_header');
	add_action('wp_footer', 'sps_info_footer',99);
}      
      
		// a custom function for loading localization
		function sps_load_textdomain() {
		//check whether necessary core function exists
		if ( function_exists('load_plugin_textdomain') ) {
		//load the plugin textdomain
		load_plugin_textdomain('statpress', 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/locale');
		}
		}
		// call the custom function on the init hook
		add_action('init', 'sps_load_textdomain');
		
     
      add_action('admin_menu', 'sps_add_pages');
	  
      add_action('plugins_loaded', 'widget_sps_init');
      //add_action('wp_head', 'sps_StatAppend');
      add_action('send_headers', 'sps_StatAppend');
	  
	  
	  /*
	  
    public function addHelpList($filters, $screen)
    {
        if(strpos($screen, 'page_Umapper')) { // we located UMapper configuration context
			$links = array(
				__('UMapper FAQ', 'umapper')=>'http://wordpress.org/extend/plugins/umapper/faq/',
				__('UMapper Google Group', 'umapper')=>'http://groups.google.com/group/umapper?hl=en'
			);

			$filters[$screen] = '';

			$i=0;
			foreach($links as $text => $url) {
				$filters[$screen] .= '<a href="' . $url . '">' . $text . '</a>' . ($i < (count($links)-1)?'<br />':'') ;
				$i++;
			}
        }
        return $filters;
    }

	  */
	### add_filter('contextual_help_list', sps_addHelpList,9999,2); // context help filter!
      
      register_activation_hook(__FILE__, 'sps_CreateTable');
?>
