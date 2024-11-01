=== Statpress SEOlution ===
Contributors: blogcrafter_chris
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LLN4YP8SUMV2W
Tags: stats, statistics, widget, admin, sidebar, visits, visitors, pageview, referrer, spy, seo, spider, robots, flash, charts
Requires at least: 2.7
Tested up to: 3.0.1
Stable Tag: 0.4.2.2

A fork of Statpress Reloaded with some more improvements like: better graphics (flash charts), more and detailed information about spiders/robots.


== Description ==


**This plugin is still working and compatible with Wordpress 3!**

No new features will be added, only bug fixes will be released.

= NOTICE! I'll discontinue this fork of Statpress! =

***Why I stopped development?*** *I got a lot of feature request and improvement input - so I decided to rewrite the plugin completely new because there are some old fashioned and foreign structures in code and tables. With a new code base it is easier for me to develop the plugin.*

***New statistic plugin will be developed under [Statscraft](http://statscraft.de/). Maybe bookmark or wait for a notice here.***

09/2010: Maybe the last release for Statpress SEOlution - the [reVierphone](http://mannaz.cc/p/revierphone/ "revierPhone") Edition in September 2010.

A fork of Statpress Reloaded with some more improvements like: better graphics (flash charts), more and detailed information about spider/robots.

= Whats new and better now? =

* flash charts for statistics
* detailed stats for some search engine robots (spiders) like Google, Yahoo and MSN
* general spider stats
* tabbed sections in some views to avoid long scrolling
* some charts with (moving and weighted) average lines

... and all features you know from Statpress Reloaded.

Plugin home: [Statpress SEOlution](http://blogcraft.de/wordpress-plugins/statpress-seolution/) on blogcraft.de

== Installation ==

Upload "statpress-seolution" directory in wp-content/plugins/ . Then just activate it on your plugin management page.
That's it, you're done!

(Note: If you have been using the old StatPress [/Reloaded] before, deactivate it. Your data is taken over!)


= Update =

* For manual updates: replace the files in wp-content/plugins/statpress-seolution/

Update from within Wordpress Admin Panel does work, too. But don't forget to run "StatPressUpdate" afterwards!

== Frequently Asked Questions ==

* Since version 0.4.x there is a footer text. It breaks my design.

	Easy going. Do an update to version **0.4.2** and you can change visibility of the footer under Settings in Wordpress.
	You also can hack the footer part with CSS, the ID of the box is `#spsfooter`


* I can't see the Flash chart, only a error message

	Please report this problem (use wordpress.org board)! In future releases there will be a possibility to switch between different types of charts
	
* Is there also a widget like in good old Statpress Reloaded?

	Yes, of course. Take a look into the Widgets, you'll find it there. :-)
	
* Why does it take so much time while updating?

	This depends on the actual size of the statpress table in your database. The plugin update process deactivate and reactivate the plugin, and a hook in the plugin wants to update also some table row data. Maybe I'll try a fix in a later version.
	
	Don't panic!
	
	I've a table size of approx. 30 MB and it took only some minutes - maybe on your webserver you can get script execution troubles because of low limits (RAM and/or time).
	
	Workaround: Download the plugin manually and upload the files by yourself. You do not need to deactivate the plugin.

== Screenshots ==

1. Main Screen (Overview)

2. Spider Stats

3. Details View, Search Stuff tab

== Changelog ==

* Version 0.1

	Initial Release

* Version 0.1.1

	Minor Changes, no critical stuff
	
* Version 0.1.2
	
	Fix: Loading of flash should be mostly fixed
	
* Version 0.1.2.1
	
	Revision fix, no troubles in files
	
* Version 0.1.2.2
	
	Minor Fix: changed named of plugin title in wordpress admin backend; update not really necessary

* Version 0.2.3

	Features: more flashified charts, javascript enabled tabs for the sections in a view
	
	Change: no more separate subpages for spiders, all goes into the spider stats tab
	
	Feature: search field in spider stats, if you need to check other spiders
	
	Feature: all spider visit charts (and some general) have now average lines, also moving and weighted ones
	
* Version 0.2.3.1

	Minor fix: resorted tabs in Detail View, referrer tab: links are clickable

* Version 0.2.3.2

	Minor fix: in main overview > "last searches"-tab the searchstrings are now decoded and human readable (spaces and special characters were urlencoded)
	
	Added/changed: entry for "Bing" (formerly known as MSN/Windows Live Search) - if searches come really from this domain!
	
	Minor fix: jQuery integration changed, library brought with plugin now only loaded on plugin's page

* Version 0.2.3.3

	Minor fix: Overview / main stats chart corrections

* Version 0.3.0.0

	Lot of style changes.
	
	Change: Update tab > Now you are able to start the process manually
	
	Change: You can change between some styles (jQuery UI), default style is now "cupertino" (blue theme), old style was "humanity".
	Take a look into the Options.
	
	Change: Even more code modularization. Also some little speed improvements and clean ups.
	
	Introduction of table version for future purposes.
	
	NO bugfixes in this version! They will follow in future versions.
	
* Version 0.3.0.1

	Change: Overview > Search Terms: instead of result short link the target URL is shown
	
* Version 0.3.0.2 - *dev version, not released.*

* Version 0.3.0.3

	Last release! See Description tab for further info!
	
* Version 0.4

	Really last release(?)
	
	Compatibility check for Wordpress Version 3. It should work properly.
	
* Version 0.4.1

	Minor fix (corrupt php opening tag / `<?php` and not `<?` )

* Version 0.4.2

	You can choose if you want to show or hide the footer text. (Under "Options" of the plugin.)
	
* Version 0.4.2.1

	Footer for plugin is opt-in now.
	
	Some fixes for better admin menu: active submenu should be highlighted correctly in the sidebar.
	
* Version 0.4.2.2

	Fix: Broken links for update notice are now correct. (Also fixed in "search" submenu.)
	
	Added: footer in admin backend (donation links).
	
== Upgrade Notice ==

= 0.4 =
Upgrade not really necessary. Only a compatibility check for Wordpress version 3.

= 0.4.1 =
Only if you have trouble with corrupt opening tags for PHP ( `<?php` and not `<?` )

= 0.4.2 =
Option for switching between showing or hiding footer text.

= 0.4.2.1 =
Footer for plugin is opt-in now.

= 0.4.2.2 =
Link in table update notice is now correct!