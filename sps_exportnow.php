<?php
	function sps_ExportNow() {
		global $wpdb;
		$table_name = $wpdb->prefix . "statpress";
		$filename = get_bloginfo('title') . "-sps_" . $_GET['from'] . "-" . $_GET['to'];
		$filename .= ($_GET['del']=='tab') ? ".tsv" : ".csv";
		header('Content-Description: File Transfer');
		header("Content-Disposition: attachment; filename=$filename");
		header('Content-Type: text/plain charset=' . get_option('blog_charset'), true);
		$qry = $wpdb->get_results("SELECT * FROM $table_name WHERE date>='" . (date("Ymd", strtotime(sps_substr($_GET['from'], 0, 8)))) . "' AND date<='" . (date("Ymd", strtotime(sps_substr($_GET['to'], 0, 8)))) . "';");
		$del = sps_substr($_GET['del'], 0, 1);
		$fwrap = ($del=='|') ? '' : '"';
		if($del=='t') { $del="\t"; $fwrap=''; }

		// field names
		print "date" . $del . "time" . $del . "ip" . $del . "urlrequested" . $del . "agent" . $del . "referrer" . $del . "search" . $del . "nation" . $del . "os" . $del . "browser" . $del . "searchengine" . $del . "spider" . $del . "feed\n";

		// content
		foreach ($qry as $rk)
		{
			print
				$fwrap . $rk->date . $fwrap .
				$del .
				$fwrap . $rk->time . $fwrap .
				$del .
				$fwrap . $rk->ip . $fwrap .
				$del .
				$fwrap . $rk->urlrequested . $fwrap .
				$del .
				$fwrap . $rk->agent . $fwrap .
				$del .
				$fwrap . $rk->referrer . $fwrap .
				$del .
				$fwrap . urldecode($rk->search) . $fwrap .
				$del .
				$fwrap . $rk->nation . $fwrap .
				$del .
				$fwrap . $rk->os . $fwrap .
				$del .
				$fwrap . $rk->browser . $fwrap .
				$del .
				$fwrap . $rk->searchengine . $fwrap .
				$del .
				$fwrap . $rk->spider . $fwrap .
				$del .
				$fwrap . $rk->feed . $fwrap .
				"\n";
		}
		die();
	}
?>
