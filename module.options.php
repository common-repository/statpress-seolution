<?php
  function sps_Options() {
		sps_general_headout(__('Options', 'statpress'));
	  if ($_POST['saveit'] == 'yes')
	  {
			update_option('sps_collectloggeduser', $_POST['sps_collectloggeduser']);
			update_option('sps_autodelete', $_POST['sps_autodelete']);
			update_option('sps_daysinoverviewgraph', $_POST['sps_daysinoverviewgraph']);
			update_option('sps_mincap', $_POST['sps_mincap']);
			update_option('sps_donotcollectspider', $_POST['sps_donotcollectspider']);
			update_option('sps_autodelete_spider', $_POST['sps_autodelete_spider']);
			$sps_theme = ($_POST['sps_theme'] == '') ? SPS_DEFTHEME : $_POST['sps_theme'];
			update_option('sps_theme', $sps_theme);
			$sps_show_footer = ($_POST['sps_show_footer'] == '') ? 'true' : $_POST['sps_show_footer'];
			update_option('sps_show_footer', $sps_show_footer);
			// update_option('sps_tableversion', $_POST['sps_tableversion']);
			// update database too
			sps_CreateTable();
			print "<br /><div class='updated'><p>" . __('Saved', 'statpress') . "!</p></div>";
	  }

?>
<div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span> &nbsp;</a></li>
			</ul>
			<div id='tabs-1' class='ui-tabs-hide'>
				<h2><?php _e('Options', 'statpress'); ?></h2>
			  <form method=post><table width=100%>
			<?php
				print "<tr><td><input type='checkbox' name='sps_collectloggeduser' value='checked' " . get_option('sps_collectloggeduser') . "> " . __('Collect data about logged users, too.', 'statpress') . "</td></tr>";
				print "<tr><td><input type='checkbox' name='sps_donotcollectspider' value='checked' " . get_option('sps_donotcollectspider') . "> " . __('Do not collect spiders visits', 'statpress') . "</td></tr>";
			?>
			  <tr><td><?php _e('Automatically delete visits older than', 'statpress'); ?>
				  <select name="sps_autodelete">
					  <option value="" <?php if (get_option('sps_autodelete') == '') print "selected"; ?>>
							<?php _e('Never delete!', 'statpress'); ?>
						</option>
					  <option value="1 month" <?php if (get_option('sps_autodelete') == "1 month") print "selected"; ?>>
							1 <?php _e('month', 'statpress'); ?>
						</option>
					  <option value="3 months" <?php if (get_option('sps_autodelete') == "3 months") print "selected"; ?>>
							3 <?php _e('months', 'statpress'); ?>
						</option>
					  <option value="6 months" <?php if (get_option('sps_autodelete') == "6 months") print "selected"; ?>>
							6 <?php _e('months', 'statpress'); ?>
						</option>
					  <option value="1 year" <?php if (get_option('sps_autodelete') == "1 year") print "selected"; ?>>
							1 <?php _e('year', 'statpress'); ?>
						</option>
				  </select>
				</td></tr>

			  <tr><td><?php _e('Automatically delete spider visits older than','statpress'); ?>
				  <select name="sps_autodelete_spider">
					  <option value="" <?php if(get_option('sps_autodelete_spider') =='' ) print "selected"; ?>>
							<?php _e('Never delete!','statpress'); ?></option>
					  <option value="1 day" <?php if(get_option('sps_autodelete_spider') == "1 day") print "selected"; ?>>
							1 <?php _e('day','statpress'); ?></option>
					  <option value="1 week" <?php if(get_option('sps_autodelete_spider') == "1 week") print "selected"; ?>>
							1 <?php _e('week','statpress'); ?></option>
					  <option value="1 month" <?php if(get_option('sps_autodelete_spider') == "1 month") print "selected"; ?>>
							1 <?php _e('month','statpress'); ?></option>
					  <option value="1 year" <?php if(get_option('sps_autodelete_spider') == "1 year") print "selected"; ?>>
							1 <?php _e('year','statpress'); ?></option>
				  </select>
				</td></tr>

			  <tr><td><?php _e('Days in Overview graph', 'statpress'); ?>
				  <select name="sps_daysinoverviewgraph">
					  <option value="7" <?php if (get_option('sps_daysinoverviewgraph') == 7) print "selected"; ?>>7</option>
					  <option value="14" <?php if (get_option('sps_daysinoverviewgraph') == 14) print "selected"; ?>>14</option>
					  <option value="21" <?php if (get_option('sps_daysinoverviewgraph') == 21) print "selected"; ?>>21</option>
					  <option value="28" <?php if (get_option('sps_daysinoverviewgraph') == 28) print "selected"; ?>>28</option>
					  <option value="10" <?php if (get_option('sps_daysinoverviewgraph') == 10) print "selected"; ?>>10</option>
					  <option value="20" <?php if (get_option('sps_daysinoverviewgraph') == 20) print "selected"; ?>>20</option>
					  <option value="30" <?php if (get_option('sps_daysinoverviewgraph') == 30) print "selected"; ?>>30</option>
					  <option value="50" <?php if (get_option('sps_daysinoverviewgraph') == 50) print "selected"; ?>>50</option>
					  <option value="100" <?php if (get_option('sps_daysinoverviewgraph') == 100) print "selected"; ?>>100</option>
				  </select>
				</td></tr>

			  <tr><td><?php _e('Minimum capability to view stats', 'statpress'); ?>
				  <select name="sps_mincap">
						<?php sps_dropdown_caps(get_option('sps_mincap')); ?>
				  </select>
				  <a href="http://codex.wordpress.org/Roles_and_Capabilities"><?php _e("more info", 'statpress'); ?></a>
			  </td></tr>

			  <tr><td><?php _e('Theme', 'statpress'); ?>
				  <select name="sps_theme">
						<option value="cupertino" <?php if (get_option('sps_theme') == 'cupertino') print "selected"; ?>>cupertino</option>
						<option value="dot-luv" <?php if (get_option('sps_theme') == 'dot-luv') print "selected"; ?>>dot-luv</option>
						<option value="flick" <?php if (get_option('sps_theme') == 'flick') print "selected"; ?>>flick</option>
						<option value="humanity" <?php if (get_option('sps_theme') == 'humanity') print "selected"; ?>>humanity</option>
						<option value="redmond" <?php if (get_option('sps_theme') == 'redmond') print "selected"; ?>>redmond</option>
						<option value="smoothness" <?php if (get_option('sps_theme') == 'smoothness') print "selected"; ?>>smoothness</option>
						<option value="ui-darkness" <?php if (get_option('sps_theme') == 'ui-darkness') print "selected"; ?>>ui-darkness</option>
						<option value="ui-lightness" <?php if (get_option('sps_theme') == 'ui-lightness') print "selected"; ?>>ui-lightness</option>
						<option value="vader" <?php if (get_option('sps_theme') == 'vader') print "selected"; ?>>vader</option>
				  </select> <?php _e('Maybe you have to reload or clean the browser cache to see the new theme.','statpress'); ?>
				</td></tr>
				
			  <tr><td><?php _e('Show or hide footer text of Statpress', 'statpress'); ?>
				  <select name="sps_show_footer">
						<option value="visible" <?php if (get_option('sps_show_footer') == 'visible') print "selected"; ?>>Visible Footer</option>
						<option value="hidden" <?php if (get_option('sps_show_footer') == 'hidden') print "selected"; ?>>Hidden Footer</option>
				  </select>
			  </td></tr>
				<tr><td>
						  Footer text preview:<br />
						  <div id="spsfooter-preview">
								<?php sps_info_footer(); ?>
						  </div>
				</td></tr>

			  <tr><td style="text-align:center;">
					<input type=submit value="<?php _e('Save', 'statpress'); ?>" style="padding:5px;width:50%;">
				</td></tr>

			  </table>
			  <input type='hidden' name='saveit' value='yes' />
			  <input type='hidden' name='page' value='statpress' />
				<input type='hidden' name='sps_action' value='options' />
			  </form>
			</div>
		</div>
</div>
<?php

		sps_general_footout();
      }
?>