<?php
      function sps_Update()
      {
      		sps_general_headout(__('Update', 'statpress'));
          global $wpdb;
          global $_STATPRESS;
          $table_name = $wpdb->prefix . "statpress";

?><div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span> &nbsp;</a></li>
			</ul>
			<div id='tabs-1' class='ui-tabs-hide'>
			<h2><?php _e('Update Table Structure and Datas', 'statpress'); ?></h2>
			
			<form method="get">
			<div>
			  <div class="ui-widget"><div class="ui-state-error ui-corner-all">
			  <p style="padding:5px;">[!] <?php _e('You only have to update after changes in the definition files (*.def in ".../def/") or plugin update','statpress'); ?></p>
				</div></div>
        <?php print "<p>" . __('StatPress table size', 'statpress') . ": <b>" . sps_tablesize($wpdb->prefix . "statpress") . "</b></p>"; ?>
				<div class="ui-widget"><div class="ui-state-highlight ui-corner-all">
					<p style="padding:5px;"><?php _e('Notice: Update process will need some minutes for huge tables (at some megabytes).','statpress'); ?></p>
				</div></div>
				<p>
					<?php print __('Actual table version','statpress') . ': <strong>' . get_option('sps_tableversion') . '</strong>' ?><br />
					<?php print __('Required table version','statpress') . ': <strong>' . $_STATPRESS['tableversion'] . '</strong>' ?><br />
					<?php if(get_option('sps_tableversion') < $_STATPRESS['tableversion']) { ?>
						<small style="color:#ff0000;"><?php _e('You really need an update!','statpress'); ?></small>
					<?php } ?>
				</p>
				<input type="hidden" name="page" value="sps/up" />
				<input type="submit" value="<?php _e('Start Update', 'statpress'); ?>!" name="updatesubmit" style="width:100%;height:3em;" />
			</div>
			</form>
<?php
					if(isset($_GET['updatesubmit'])) {
					
					print '<div style="padding:15px;">';


          $wpdb->show_errors();
          // update table
          print "" . __('Updating table struct', 'statpress') . " $table_name... ";
          sps_CreateTable();
          print "" . __('done', 'statpress') . "<br>";

          // Update Feed
          print "" . __('Updating Feeds', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET feed='';");

          // standard blog info urls
          $s = sps_extractfeedreq(get_bloginfo('comments_atom_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='COMMENT ATOM' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = sps_extractfeedreq(get_bloginfo('comments_rss2_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='COMMENT RSS' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = sps_extractfeedreq(get_bloginfo('atom_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='ATOM' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = sps_extractfeedreq(get_bloginfo('rdf_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='RDF'  WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = sps_extractfeedreq(get_bloginfo('rss_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='RSS'  WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = sps_extractfeedreq(get_bloginfo('rss2_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='RSS2' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }

          // not standard
          $wpdb->query("UPDATE $table_name SET feed='RSS2' WHERE urlrequested LIKE '%/feed%' AND feed='';");
          $wpdb->query("UPDATE $table_name SET feed='RSS2' WHERE urlrequested LIKE '%wp-feed.php%' AND feed='';");


          print "" . __('done', 'statpress') . "<br>";

          // Update OS
          print "" . __('Updating OS', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET os = '';");
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/os.dat');
          foreach ($lines as $line_num => $os)
          {
              list($nome_os, $id_os) = explode("|", $os);
              $qry = "UPDATE $table_name SET os = '$nome_os' WHERE os='' AND replace(agent,' ','') LIKE '%" . $id_os . "%';";
              $wpdb->query($qry);
          }
          print "" . __('done', 'statpress') . "<br>";

          // Update Browser
          print "". __('Updating Browsers', 'statpress') ."... ";
          $wpdb->query("UPDATE $table_name SET browser = '';");
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/browser.dat');
          foreach ($lines as $line_num => $browser)
          {
              list($nome, $id) = explode("|", $browser);
              $qry = "UPDATE $table_name SET browser = '$nome' WHERE browser='' AND replace(agent,' ','') LIKE '%" . $id . "%';";
              $wpdb->query($qry);
          }
          print "" . __('done', 'statpress') . "<br>";

          print "" . __('Updating Spiders', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET spider = '';");
          $lines = file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/spider.dat');
          if (file_exists(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/custom-def/spider.dat'))
              $lines = array_merge(file(ABSPATH . 'wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/custom-def/spider.dat'),$lines);
          foreach ($lines as $line_num => $spider)
          {
              list($nome, $id) = explode("|", $spider);
              $qry = "UPDATE $table_name SET spider = '$nome',os='',browser='' WHERE spider='' AND replace(agent,' ','') LIKE '%" . $id . "%';";
              $wpdb->query($qry);
          }
          print "" . __('done', 'statpress') . "<br>";

          // Update feed to ''
          print "" . __('Updating Feeds', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET feed = '' WHERE isnull(feed);");
          print "" . __('done', 'statpress') . "<br>";

          // Update Search engine
          print "" . __('Updating Search engines', 'statpress') . "... ";
          print "<br>";
          $wpdb->query("UPDATE $table_name SET searchengine = '', search='';");
          print "..." . __('null-ed', 'statpress') . "!<br>";
          $qry = $wpdb->get_results("SELECT id, referrer FROM $table_name WHERE referrer !=''");
          print "..." . __('select-ed', 'statpress') . "!<br>";
          foreach ($qry as $rk)
          {
              list($searchengine, $search_phrase) = explode("|", sps_GetSE($rk->referrer));
              if ($searchengine <> '')
              {
                  $q = "UPDATE $table_name SET searchengine = '$searchengine', search='" . addslashes($search_phrase) . "' WHERE id=" . $rk->id;
                  $wpdb->query($q);
              }
          }
          print "" . __('done', 'statpress') . "<br>";

          $wpdb->hide_errors();

          print "<br>&nbsp;<h1>" . __('Updated', 'statpress') . "!</h1>";
          
          print '</div>';
          
          } // if update was started
?>
			</div>
		</div>
</div>
<?php
		sps_general_footout();
      }
?>