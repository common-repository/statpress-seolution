<?php
function sps_Agents()
      {
      		sps_general_headout(__('Unknown User Agents', 'statpress'));
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          $query = "SELECT date, MAX(time), ip, COUNT(*) as count, agent";
          $query .= " FROM " . $table_name;
          $query .= " WHERE spider = '' AND browser = ''";
          $query .= " GROUP BY date, ip, agent";
          $query .= " ORDER BY date DESC";
          $result = $wpdb->get_results($query);

?><div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span> &nbsp;</a></li>
			</ul>
			<div id='tabs-1' class='ui-tabs-hide'>
<?php
          print "<table class='widefat'><thead><tr>";
          print "<th scope='col'>" . __('Date', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Last Time', 'statpress') . "</th>";
          print "<th scope='col'>" . __('IP', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Count', 'statpress') . "</th>";
          print "<th scope='col'>" . __('User Agent', 'statpress') . "</th>";
          print "</tr></thead><tbody id='the-list'>";

          foreach ($result as $line)
          {   
            $col = 0;
            print '<tr>';
            foreach ($line as $col_value)
{
    $col++;
    if ($col == 1)
        print '<td>' . sps_hdate($col_value) . '</td>';
    else if ($col == 3)
        print "<td><a href='http://www.projecthoneypot.org/ip_" . $col_value . "' target='_blank'>" . $col_value . "</a></td>";
    else
        print '<td>' . $col_value . '</td>';
}
            print '</tr>';
          }
          print '</table>';
?>
			</div>
		</div>
</div>
<?php
		sps_general_footout();
      }
?>