=== Content Views - Query and display posts, pages without coding ===
Contributors: pt-guy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JGUF974QBRKQE
Tags: post, posts, page, pages, query, queries, search, display, show, shortcode, thumbnail, title, content, excerpt, meta, date, author, term, taxonomy, grid, scrollable, collapsible, list, slide, layout, ui
Requires at least: 3.3
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Query and display <strong>posts, pages</strong> in awesome layouts (<strong>grid, scrollable list, collapsible list</strong>) easier than ever, without coding!

== Description ==

[Content Views](http://wordpressquery.com "Visit Content Views website") is a WordPress plugin helps you to query and display posts, pages in different kind of **responsive** layouts (grid, scrollable list, collapsible list), in very 3 simple steps:

* Step 1 : Select criteria to query posts, pages
* Step 2 : Select layout which you want to display your queried entries
* Step 3 : Paste shortcode **[pt_view id="UNIQUE_ID"]** to editor of a post, page or a Text widget where you want to show your desired content. If you are a developer, you can **`<?php echo do_shortcode('[pt_view id="UNIQUE_ID"]'); ?>`** in theme of your WordPress site. (Please check FAQ to know how to get UNIQUE_ID of View)

= And here is your power with this plugin: =

**in Step 1, you can:**

* query & display a single post, page
* query & display multiple posts, pages
* query posts, pages written by, not written by any authors
* query posts, pages associate with, don't associate with tags, categories
* query posts, pages in any status (publish, draft, private...)
* sort posts, pages by Id, Title, Created date, Modified date in ascending, descending order
* query posts, pages which contain a specific keyword

**in Step 2, you can:**

* Select a layout to show queried posts, pages: Grid, Collapsible List, Scrollable List. More awesome layouts are available in **[Content Views PRO](http://wordpressquery.com "Content Views Pro plugin")**
* Choose a layout format of each item (item is the output of a post, page at frontend): 1 column, 2 columns
* Select fields to show (thumbnail, title, content, meta fields)
* Select size of thumbnail
* Show full content, or an excerpt with specific length
* Select meta fields to show (date, author, term [categories, tags], comment count)
* Enable/Disable pagination
* Open an item in new tab, current tab

Also, you can import/export 'View' to use in other WordPress sites (Please check **FAQ** tab to know what is 'View')


= More amazing features: =

* Be able to query custom post types (Woocommerce products, FAQ...)
* Advanced output & settings of Grid, Collapsible List, Scrollable List layout
* Additional layouts: Pinterest, Timeline
* Drag & drop to change display order of fields (thumbnail, title, content, meta fields)
* Customize Font settings
* More Pagination options
* And much more...

are available in **[Content Views PRO](http://wordpressquery.com "Content Views Pro plugin")**


== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Content Views'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `pt-content-views.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `pt-content-views.zip`
2. Extract the `pt-content-views` directory to your computer
3. Upload the `pt-content-views` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard



== Frequently Asked Questions ==

= How can I start? =

In left menu of your Admin dashboard, click Content View Settings > Add View

= What is 'View'? =

'View' is a custom post type which Content Views uses to store all settings to query & display your posts

= How can I see all my Views? =

In left menu of your Admin dashboard, click Content View Settings > All Views

= How can I edit a View? =

Firstly, you should go to "All Views" page (please check above question). Then click on Title of View you want to edit. You will be forwarded to editing page of View.

= How to get UNIQUE_ID of View? =

You can get View ID in URL of editing page of View (please check above question), it has this format: http://your_domain/wp-admin/admin.php?page=content-views-add&id=UNIQUE_ID

= How many Views I can create? =

You can create Unlimited Views, in Unlimited websites


== Screenshots ==

1. Content Views plugin overview
2. Display Setting form to customize output of queried posts at frontend
3. Query and display in Grid layout (Show Title, Thumbnail)
4. Query and display in Grid layout (Show Title, Thumbnail, Content, Meta fields), with Pagination
5. Query and display in Collapsible List
6. Query and display in Scrollable List



== Changelog ==

= 1.0.0 =
* Initial release



== Upgrade Notice ==

Initial release
