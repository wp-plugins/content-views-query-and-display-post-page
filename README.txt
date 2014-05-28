=== Content Views - Admin UI to query and displaying post, page ===
Contributors: pt-guy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JGUF974QBRKQE
Tags: post, posts, page, pages, shortcode, thumbnail, title, content, excerpt, meta, date, author, term, taxonomy, query, search, display, show, querying, displaying, grid, scrollable, collapsible, list, slide, layout, ui
Requires at least: 3.3
Tested up to: 3.9.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Query posts, pages and display result in different kind of layouts (grid, scrollable list, collapsible list) easier than ever, without coding!

== Description ==

[Content Views](http://wordpressquery.com "Visit Content Views website") is a WordPress plugin helps you to display posts, pages in different kind of **responsive** layouts (grid, scrollable list, collapsible list), in very 3 simple steps:

* Step 1 : Select criteria to querying posts, pages
* Step 2 : Select layout which you want to displaying your entries
* Step 3 : Paste this shortcode **[pt_view id="UNIQUE_ID"]** to a post, page or a widget text, it will show up your desired content. If you are a developer, you can **`<?php echo do_shortcode('[pt_view id="UNIQUE_ID"]'); ?>`**. (Check FAQ to know how to get UNIQUE_ID of View)

= And here is your power with this plugin: =

**in Step 1, you can:**

* Show a specific post, page
* Show list of posts, pages
* Query posts, pages written by, not written by any authors
* Query posts, pages in, not in any tags, categories
* Query posts, pages in any statuses (publish, draft, private...)
* Sort posts, pages by Id, Title, Created date, Modified date in ascending, descending order
* Query posts, pages which contain a specific keyword

**in Step 2, you can:**

* Select a specific layout to show posts, page: Grid, Collapsible List, Scrollable List. More awesome layouts are available in PRO version!
* Choose a layout format of each item (item is a content block of a post, page when it is shown): 1 column, 2 columns
* Select content fields to show (thumbnail, title, content, meta fields)
* Select a specific dimensions of thumbnail
* Show full content, or an excerpt with specific length
* Select meta fields to show (date, author, term [categories, tags], comment count)
* Enable/Disable pagination
* Open an item in new tab, current tab

Also, you can export 'View' to query posts, pages in your other WordPress sites (Please check **Frequently Asked Questions** tab to know what is 'View')

**Want more features, such as**

* Advanced output & settings for Grid, Collapsible List, Scrollable List
* Pinterest, Timeline layout
* Drag & Drop to change display order of title, content, meta fields
* Customize Font settings
* More pagination options
* More "Open item in" options

please check [Content Views PRO](http://wordpressquery.com "Get Content Views Pro plugin") version.

Have questions? Please check **FAQ** tab

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

'View' is a custom post type which "Content View" uses to store all settings to query & display your posts.

= How can I see all created Views? =

In left menu of your Admin dashboard, click Content View Settings > All Views

= How can I edit a View? =

Firstly, you should go to "All Views" page (please check above question). Then click on Title of View you want to edit. You will be forwarded to editing page of View.

= How to get UNIQUE_ID of View? =

You can get View ID in the editing page of View (please check above question), it looks like: 'admin.php?page=content-views-add&id=UNIQUE_ID'

= How many Views I can create? =

Unlimited


== Screenshots ==

1. Plugin screenshot overview
2. Display Setting options
3. Grid layout (Show Title, Thumbnail)
4. Grid layout (Show Title, Thumbnail, Content, Meta fields), with Pagination
5. Collapsible List
6. Scrollable List



== Changelog ==

= 1.0.0 =
* Initial release



== Upgrade Notice ==

Initial release
