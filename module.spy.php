<?php
      function sps_Spy()
      {
      		sps_general_headout(__('Spy', 'statpress'));
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          $LIMIT = 20;
          
          if(isset($_GET['pn']))
          {
          	// Get Current page from URL
          	$page = $_GET['pn'];
          	if($page <= 0)
          	{
          		// Page is less than 0 then set it to 1
          		$page = 1;
          	}
          }
          else
          {
          	// URL does not show the page set it to 1
          	$page = 1;
          }

?><div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span> &nbsp;</a></li>
			</ul>
			<div id='tabs-1' class='ui-tabs-hide'>
<?php
          	// Create MySQL Query String
			$strqry = "SELECT id FROM $table_name WHERE (spider='' AND feed='') GROUP BY ip";
			$query = $wpdb->get_results($strqry);
			$TOTALROWS = $wpdb->num_rows;
			$NumOfPages = $TOTALROWS / $LIMIT;
			$LimitValue = ($page * $LIMIT) - $LIMIT;
			
			
          // Spy
          $today = gmdate('Ymd', current_time('timestamp'));
          $yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
          $sql = "SELECT ip,nation,os,browser,agent FROM $table_name WHERE (spider='' AND feed='') GROUP BY ip ORDER BY id DESC LIMIT $LimitValue, $LIMIT";
          $qry = $wpdb->get_results($sql);
?>
<script>
function ttogle(thediv){
if (document.getElementById(thediv).style.display=="inline") {
document.getElementById(thediv).style.display="none"
} else {document.getElementById(thediv).style.display="inline"}
}
</script>
<div id="paginating" style="text-align:center;"><?php _e('Pages','statpress') ?>:
<?php

// Check to make sure we’re not on page 1 or Total number of pages is not 1
if($page == ceil($NumOfPages) && $page != 1) {
  for($i = 1; $i <= ceil($NumOfPages)-1; $i++) {
    // Loop through the number of total pages
    if($i > 0) {
      // if $i greater than 0 display it as a hyperlink
      echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=sps/spy&pn=' . $i . '">' . $i . '</a> ';
      }
    }
}
if($page == ceil($NumOfPages) ) {
  $startPage = $page;
} else {
  $startPage = 1;
}
for ($i = $startPage; $i <= $page+6; $i++) {
  // Display first 7 pages
  if ($i <= ceil($NumOfPages)) {
    // $page is not the last page
    if($i == $page) {
      // $page is current page
      echo " [{$i}] ";
    } else {
      // Not the current page Hyperlink them
      echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=sps/spy&pn=' . $i . '">' . $i . '</a> ';
    }
  }
}
?>
</div>
<table id="mainspytab" name="mainspytab" class="mainspytab">
<?php
          foreach ($qry as $rk)
          {
              ?>
<tr class="sps_trbase">
	<td class="sps_tdflag">
	  <img src='http://api.hostip.info/flag.php?ip=<?php echo $rk->ip ?>' class="sps_flag" />
	</td>
	<td class="sps_tdip">
		<strong><?php echo $rk->ip ?></strong>
	</td>
	<td class="sps_tdmore">
		<div><span style='color:#006dca;cursor:pointer;border-bottom:1px dotted #AFD5F9;font-size:8pt;' onClick="ttogle('<?php echo $rk->ip ?>')"><?php _e('more info', 'statpress') ?></span></div>
		<div id='<?php echo $rk->ip ?>' name='<?php echo $rk->ip ?>'><?php echo $rk->os ?>, <?php echo $rk->browser ?>
		<iframe class="sps_iframe_ipinfo" scrolling="no" style='' src="http://api.hostip.info/get_html.php?ip=<?php echo $rk->ip ?>"></iframe>
		<?php if ($rk->nation) { print "<br /><small>" . gethostbyaddr($rk->ip) . "</small>"; } ?>
		<br /><small><?php echo $rk->agent ?></small>
		<script>document.getElementById('<?php echo $rk->ip ?>').style.display='none';</script>
	</td>
</tr>
<?php
              $qry2 = $wpdb->get_results("SELECT * FROM $table_name WHERE ip='" . $rk->ip . "' AND (date BETWEEN '$yesterday' AND '$today') order by id LIMIT 10");
              foreach ($qry2 as $details)
              {
                  print "<tr class='sps_trdetails'><td class='sps_tdarrow'>&#8594;</td>";
                  print "<td valign='top' width='151'><div><font size='1' color='#3B3B3B'><strong>" . sps_hdate($details->date) . " " . $details->time . "</strong></font></div></td>";
                  print "<td><div><a href='" . sps_getblogurl() . ((strpos($details->urlrequested, 'index.php') === FALSE) ? $details->urlrequested : '') . "' target='_blank'>" . sps_Decode($details->urlrequested) . "</a>";
                  if ($details->searchengine != '')
                  {
                      print "<br><small>" . __('arrived from', 'statpress') . " <b>" . $details->searchengine . "</b> " . __('searching', 'statpress') . " <a href='" . $details->referrer . "' target=_blank>" . urldecode($details->search) . "</a></small>";
                  }
                  elseif ($details->referrer != '' && strpos($details->referrer, get_option('home')) === false)
                  {
                      print "<br><small>" . __('arrived from', 'statpress') . " <a href='" . $details->referrer . "' target=_blank>" . $details->referrer . "</a></small>";
                  }
                  print "</div></td>";
                  print "</tr>\n";
              }
          }
?>
</table>
			</div>
		</div>
</div>
<?php
		sps_general_footout();
      }
?>