<?php
function sps_Export() {
	sps_general_headout(__('Export','statpress'));
?>
<div class='wrap'>
		<div id="tabs" class="boxspacing">
			<ul>
				<li><a href="#tabs-1"><span class="ui-icon ui-icon-star"></span> &nbsp;</a></li>
			</ul>
			<div id='tabs-1' class='ui-tabs-hide'>
				<h2><?php _e('Export stats to text file', 'statpress'); ?> (csv/tsv)</h2>
				<form method="get">
					<table cellspacing="3" cellpadding="3" style="width:66%;margin: 0 auto;border:1px solid #999;">
						<tr>
							<td><?php _e('From (date)', 'statpress'); ?></td>
							<td><input type="text" name="from"> (YYYYMMDD)</td>
						</tr>
						<tr>
							<td><?php _e('To (date)', 'statpress'); ?></td>
							<td><input type="text" name="to"> (YYYYMMDD)</td>
						</tr>
						<tr>
							<td><?php _e('Fields delimiter', 'statpress'); ?></td>
							<td>
								<span style="border:1px dotted #ccc;padding:3px;"><input type="radio" name="del" value="|" checked="checked">| </span>
								<span style="border:1px dotted #ccc;padding:3px;"><input type="radio" name="del" value=";">; </span>
								<span style="border:1px dotted #ccc;padding:3px;"><input type="radio" name="del" value=",">, </span>
								<span style="border:1px dotted #ccc;padding:3px;"><input type="radio" name="del" value="tab">[tab] <?php _e('The file extension will be .tsv','statpress'); ?></span>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td style="text-align:center;">
								<input type="submit" value="<?php _e('Export', 'statpress'); ?>" style="width:50%" />
							</td>
						</tr>
					</table>
					<input type="hidden" name="page" value="sps" />
					<input type="hidden" name="sps_action" value="exportnow" />
				</form>
			</div>
		</div>
</div>
<?php
}

?>
