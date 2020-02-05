=== Plugin Name ===
Contributors: felipelungov
Tags: cron job, cron jobs, cron event, cron events
Tested up to: 5.3.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Regularly checks how many cron events are overdue.

== Description ==

There are some tasks that run in your WordPress installation that are called [WP cron events](https://developer.wordpress.org/plugins/cron/). These tasks include checking for updates, scanning your site for vulnerabilities, sending regular emails to site administrators informing site status, and much more.

There is always a queue of WP cron events in line to be run, each at its given time. There may be situations, however, that your site may not be able to process all of those events quickly enough, and you may have a few or several of them overdue.

This very simple and lightweight plugin will create a widget in your Dashboard with important information that will help you understand if your site is processing its WP cron events quickly enough.

Every approximately eight hours, the plugin will look at the cron events queue and count how many of them are overdue (i.e. should have been run) and store that number in the database. The information you see on the widget is based on the data collected over the previous 20 days.

== Installation ==

There are no special steps that need to be taken to install and start using the plugin. You can install it and activate it just like any other plugin.

There is no settings page. This is a set and forget plugin.

If you wish to change the frequency that the check is run, or for how long you want the data to be stored, you can define the following constants:

* CNC_CCQ_INTERVAL_CHECK (in hours, defaults to 8)
* CNC_CCQ_HISTORY_LENGTH (in days, defaults to 20)

== Frequently Asked Questions ==

= Is this plugin free? =

Free, open-source and the source code is available on GitHub.

= Why should I be worried about my cron job queue? =

If all is going well on your site, cron events should run without much delay from the time they were intended to run. But it is very hard to detect whether there is anything wrong with your cron events queue. Reasons why you might want to use this plugin include:

* If you have a low traffic website.
* If you are working on a testing or development environment with very little traffic.
* If you disabled WP_CRON and are not sure how often you should set your linux-based cron to.
* If you just want to have peace of mind.

= What information will the plugin provide me? =

The plugin will display on the dashboard:

* highest number found of events overdue
* average number of events overdue
* longest delay of an overdue event
* how often no events were found overdue
* when the last check was run

= Will this plugin slow down my site? =

It is very lightweight and should not have any noticeable effect on speed. If you are still concerned, and if you can edit wp-config.php, you can change how often the check runs.

= Will this plugin allow me to add, edit or remove WP cron events? =

No, the plugin will just show you information that will help you detect abnormalities in your cron events queue.

== Changelog ==

= 1.0.0 =
* First official version.