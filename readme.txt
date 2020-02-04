=== Plugin Name ===
Contributors: felipelungov
Tags: cron job, cron jobs
Tested up to: 5.3.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Regularly checks how many cron jobs are overdue.

== Description ==

== Installation ==

1. Simply install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the same screen in WordPress.

== Frequently Asked Questions ==

= Is this plugin free? =

Free, open-source and available on GitHub.

= Why should I be worried about my cron job queue? =

If all is going well on your site, cron events should run without much delay from the time they were intended to run. But it is very hard to detect whether there is anything wrong with your cron events queue. Reasons why you might want to use this plugin include:

* If you have a low traffic website.
* If you are working on a testing or development environment with very little traffic.
* If you disabled WP_CRON and are not sure how often you should set your real cron to.
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

No, the plugin will just show you information that will help you detect abnormalities in your cron job queue.

== Changelog ==

= 1.0 =
* First official version.

