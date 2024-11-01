<?php
      function sps_Search($what = '')
      {
      		sps_general_headout(__('Search', 'statpress'));
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          $f['urlrequested'] = __('URL Requested', 'statpress');
          $f['agent'] = __('Agent', 'statpress');
          $f['referrer'] = __('Referrer', 'statpress');
          $f['search'] = __('Search terms', 'statpress');
          $f['searchengine'] = __('Search engine', 'statpress');
          $f['os'] = __('Operative system', 'statpress');
          $f['browser'] = __('Browser', 'statpress');
          $f['spider'] = __('Spider', 'statpress');
          $f['ip'] = __('IP', 'statpress');
?><div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span> &nbsp;</a></li>
			</ul>
			<div id='tabs-1' class='ui-tabs-hide'>
  <form method="get"><table>
  <?php
          for ($i = 1; $i <= 3; $i++)
          {
              print "<tr>";
              print "<td>" . __('Field', 'statpress') . " <select name=where$i><option value=''></option>";
              foreach (array_keys($f) as $k)
              {
                  print "<option value='$k'";
                  if ($_GET["where$i"] == $k)
                  {
                      print " SELECTED ";
                  }
                  print ">" . $f[$k] . "</option>";
              }
              print "</select></td>";
              print "<td><input type=checkbox name=groupby$i value='checked' " . $_GET["groupby$i"] . "> " . __('Group by', 'statpress') . "</td>";
              print "<td><input type=checkbox name=sortby$i value='checked' " . $_GET["sortby$i"] . "> " . __('Sort by', 'statpress') . "</td>";
              print "<td>, " . __('if contains', 'statpress') . " <input type=text name=what$i value='" . $_GET["what$i"] . "'></td>";
              print "</tr>";
          }
?>
  </table>
  <br>
  <table>
  <tr>
    <td>
      <table>
        <tr><td><input type=checkbox name=oderbycount value=checked <?php
          print $_GET['oderbycount']
?>> <?php
          _e('sort by count if grouped', 'statpress');
?></td></tr>
        <tr><td><input type=checkbox name=spider value=checked <?php
          print $_GET['spider']
?>> <?php
          _e('include spiders/crawlers/bot', 'statpress');
?></td></tr>
        <tr><td><input type=checkbox name=feed value=checked <?php
          print $_GET['feed']
?>> <?php
          _e('include feed', 'statpress');
?></td></tr>
<tr><td><input type=checkbox name=distinct value=checked <?php
          print $_GET['distinct']
?>> <?php
          _e('SELECT DISTINCT', 'statpress');
?></td></tr>
      </table>
    </td>
    <td width=15> </td>
    <td>
      <table>
        <tr>
          <td><?php
          _e('Limit results to', 'statpress');
?>
            <select name=limitquery><?php
          if ($_GET['limitquery'] > 0)
          {
              print "<option>" . $_GET['limitquery'] . "</option>";
          }
?><option>1</option><option>5</option><option>10</option><option>20</option><option>50</option><option>100</option><option>250</option><option>500</option></select>
          </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
          <td align="right">
						<input type="submit" value="<?php _e('Search', 'statpress'); ?>" name="searchsubmit" />
					</td>
        </tr>
      </table>
    </td>
  </tr>    
  </table>  
  <input type="hidden" name="page" value="sps/search" />
  </form><br>
<?php
          if (isset($_GET['searchsubmit']))
          {
              // query builder
              $qry = "";
              // FIELDS
              $fields = "";
              for ($i = 1; $i <= 3; $i++)
              {
                  if ($_GET["where$i"] != '')
                  {
                      $fields .= $_GET["where$i"] . ",";
                  }
              }
              $fields = rtrim($fields, ",");
              // WHERE
              $where = "WHERE 1=1";
              if ($_GET['spider'] != 'checked')
              {
                  $where .= " AND spider=''";
              }
              if ($_GET['feed'] != 'checked')
              {
                  $where .= " AND feed=''";
              }
              for ($i = 1; $i <= 3; $i++)
              {
                  if (($_GET["what$i"] != '') && ($_GET["where$i"] != ''))
                  {
                      $where .= " AND " . $_GET["where$i"] . " LIKE '%" . mysql_real_escape_string($_GET["what$i"]) . "%'";
                  }
              }
              // ORDER BY
              $orderby = "";
              for ($i = 1; $i <= 3; $i++)
              {
                  if (($_GET["sortby$i"] == 'checked') && ($_GET["where$i"] != ''))
                  {
                      $orderby .= $_GET["where$i"] . ',';
                  }
              }
              
              // GROUP BY
              $groupby = "";
              for ($i = 1; $i <= 3; $i++)
              {
                  if (($_GET["groupby$i"] == 'checked') && ($_GET["where$i"] != ''))
                  {
                      $groupby .= $_GET["where$i"] . ',';
                  }
              }
              if ($groupby != '')
              {
                  $groupby = "GROUP BY " . rtrim($groupby, ',');
                  $fields .= ",count(*) as totale";
                  if ($_GET['oderbycount'] == 'checked')
                  {
                      $orderby = "totale DESC," . $orderby;
                  }
              }
              
              if ($orderby != '')
              {
                  $orderby = "ORDER BY " . rtrim($orderby, ',');
              }
              
              
              $limit = "LIMIT " . $_GET['limitquery'];
              
              if ($_GET['distinct'] == 'checked')
{
   $fields = " DISTINCT " . $fields;
}
              
              // Results
              print "<h2>" . __('Results', 'statpress') . "</h2>";
              $sql = "SELECT $fields FROM $table_name $where $groupby $orderby $limit;";
              //  print "$sql<br>";
              print "<table class='widefat'><thead><tr>";
              for ($i = 1; $i <= 3; $i++)
              {
                  if ($_GET["where$i"] != '')
                  {
                      print "<th scope='col'>" . ucfirst($_GET["where$i"]) . "</th>";
                  }
              }
              if ($groupby != '')
              {
                  print "<th scope='col'>" . __('Count', 'statpress') . "</th>";
              }
              print "</tr></thead><tbody id='the-list'>";
              $qry = $wpdb->get_results($sql, ARRAY_N);
              foreach ($qry as $rk)
              {
                  print "<tr>";
                  for ($i = 1; $i <= 3; $i++)
                  {
                      print "<td>";
                      if ($_GET["where$i"] == 'urlrequested')
                      {
                          print sps_Decode($rk[$i - 1]);
                      }
                      else
                      {
                          print $rk[$i - 1];
                      }
                      print "</td>";
                  }
                  print "</tr>";
              }
              print "</table>";
              print "<br /><br /><font size=1 color=gray>sql: $sql</font>";
          }
?>
			</div>
		</div>
</div>
<?php
		sps_general_footout();
      }
?>