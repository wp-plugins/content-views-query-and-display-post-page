=== Query posts by category... and display posts on page in grid layout without coding - Content Views ===
Contributors: pt-guy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JGUF974QBRKQE
Tags: post, posts, page, pages, query, queries, search, display, show, grid, layout, author, blog, categories, category, comment, content, custom, editor, filter, Formatting, image, list, meta, plugin, responsive, shortcode, excerpt, title, tag, term, Taxonomy, thumbnail, pagination, date, scrollable, slider, collapsible
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 1.3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It is easy as 1, 2, 3 to query posts by category, tag, author... and display posts on any Page in responsive Grid layout without coding!

== Description ==

Do you want to display posts by category on WordPress homepage, in responsive grid layout?

Or:

* display posts in grid layout in a specific page
* display posts in grid layout in a widget of sidebar
* display posts by a specific tag
* display posts by an author
* display posts in descending order of Title
* replace boring Next, Prev button of WordPress theme by a beautiful pagination
* display thumbnail in different size than 150x150 or 300x300

With **Content Views** plugin, you can do above things in minutes, without any line of code!

With [Content Views Pro](http://www.contentviewspro.com/?utm_source=wordpress&utm_medium=plugin&utm_campaign=content-views "Get Content Views Pro"), you can do even more than you expect!

**Content Views** provides an intuitive form to query posts and display posts in **responsive** layouts (grid layout, slider/scrollable list, accordion/collapsible list) quickly & easily, in very 3 simple steps:

* Step 1 : Customize filters (category, tag, author, order...) to query your desired posts
* Step 2 : Customize output (select a layout from 3 responsive layout, show title/content/excerpt/pagination ? ...)
* Step 3 : Save View then paste the generated shortcode **[pt_view id="VIEW_ID"]** to editor of page/Text widget where you want to display your posts. If you are a developer, you can paste `<?php echo do_shortcode('[pt_view id="VIEW_ID"]'); ?>` to file in WordPress theme


= A features list of Content Views plugin: =

**in Step 1:**

* query single/multiple post(s)
* query posts by category, tag, author
* query child pages of a parent page
* query posts by status (publish, draft, private...)
* query posts which contain a specific keyword
* sort posts by Id, Title, Created date, Modified date in Ascending, Descending order

**in Step 2:**

* Select a responsive layout to display posts: Grid, List(Collapsible/Slider).
* Display fields (thumbnail, title, content, meta fields) in vertical direction. Or display thumbnail in left/right side of other fields
* Select what fields (thumbnail, title, content, meta fields) to display
* Select thumbnail sizes
* Display full content, or display excerpt with specific amount of words
* Select what meta fields (date, author, terms [categories, tags], comment count) to display
* Enable/Disable pagination
* Open in new tab, current tab (when click on title, thumbnail of post)


= Need more awesome features? =

* More amazing layouts: **Pinterest, Timeline**
* Completely **replace WordPress layout** in Category page, Author page, Search page... by Grid, Pinterest, Timeline layout
* Display **WooCommerce** product in beautiful output
* Display **Portfolio** with shuffle animation
* More beautiful output & powerful settings for Grid, List layout
* **Drag & drop** to change display order of fields (thumbnail, title, content, meta fields)
* Customize **Font, Color** settings of Title, Content, Meta fields
* Custom **style of Thumbnail**: round, circle, border
* Customize style & text of **Read more** button
* Ajax **Load more** pagination
* And much more...

Please check [Content Views Pro](http://www.contentviewspro.com/?utm_source=wordpress&utm_medium=plugin&utm_campaign=content-views "Get Content Views Pro")

Just give a try (30 day money back guarantee), then you will know how it is awesome :)



== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Content Views'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `content-views-query-and-display-post-page.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `content-views-query-and-display-post-page.zip`
2. Extract the `content-views-query-and-display-post-page` directory to your computer
3. Upload the `content-views-query-and-display-post-page` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard



== Frequently Asked Questions ==

= How can I start? =

In left menu of your Admin dashboard, click Content View Settings > Add View

= What is 'View'? =

'View' is a custom post type which Content Views uses to store all settings to query & display your posts

= How can I see all my Views? =

In left menu of your Admin dashboard, click Content View Settings > All Views

= How can I edit a View? =

Firstly, you should go to "All Views" page (please check above question).
If you have View ID or View title, paste it to the text field beside of "Search Views" button then click that button.
Then click on Title of View you want to edit.
You will be forwarded to editing page of View.

= How many Views I can create? =

You can create Unlimited Views, in Unlimited websites



== Screenshots ==

1. Content Views plugin overview
2. Display Setting form to customize output of queried posts at front-end
3. Query and display in Grid layout (Show Title, Thumbnail)
4. Query and display in Grid layout (Show Title, Thumbnail, Content) with Pagination
5. Query and display in Collapsible layout
6. Query and display in Slider layout



== Changelog ==

= 1.3.6 =
* Improvement: A very new customized Bootstrap style
* Bug fixed: script which hooks to wp_footer is not loaded

= 1.3.5.1 =
* Bug fixed: Bootstrap style ruins theme layout

= 1.3.5 =
* Bug fixed: Show more posts than Limit value in some cases when pagination is enable
* Improvement: Customized Bootstrap style which only contains necessary properties
* Update: Display inline assets of View right after HTML if possible
* Update: Refine Javascript code for Preview/Front-end

= 1.3.4.1 =
* Improvement: Clean up 'Read more' button code
* Improvement: Remove unused code of Order setting

= 1.3.4 =
* Bug fixed: Read more button is invisible (color is white and no background color)
* Update: Able to set 0 as 'Excerpt length'

= 1.3.3 =
* Bug fixed: Return 'Empty settings' message for pagination request

= 1.3.2 =
* Update: Official refined Bootstrap version (bring here from Pro plugin)
* Update: Apply "Open in" setting for "Read more" button, too
* Bug fixed: Get wrong excerpt if content of post contains shortcode tags

= 1.3.1.9 =
* Update: Add some new hook for customizing options

= 1.3.1.8 =
* Bug fixed: Fix row style bug

= 1.3.1.6 =
* Improvement: Update page title as "Edit View" in edit View page
* Bug fixed: Fix some warnings in PHP 5.2

= 1.3.1.5 =
* Test up to 4.0

= 1.3.1.4 =
* Update: Fix some layout problems by influence of "box-sizing" property of Bootstrap
* Improvement: Code improvement for Grid rendering

= 1.3.1.3 =
* Update: Restructure Taxonomy filter (remove "Not In" list, add operator[In, Not in, And])

= 1.3.1.2 =
* Bug fixed: Loosing translation (WPML) in Ajax pagination
* Improvement: Performance optimization (when get settings of View)
* Improvement: Update style if only Title is selected to display (to have a more beautiful list of Posts title)

= 1.3.1.1 =
* Bug fixed: Thumbnail dimensions are empty
* Improvement: CSS code refinement

= 1.3.1 =
* Update: Important update about caching mechanism
* Update: Update translation file

= 1.3.0.2 =
* Refine Javascript code
* Update description in Setting page

= 1.3.0.1 =
* Update filter priority
* Update plugin description

= 1.3.0 =
* Bug fixed: Pagination returns Empty settings
* Improvement: UI improvement (Add icon to tabs. Show shortcode in text field for easier selecting. )
* Improvement: Assets loading improvement

= 1.2.6 =
* Fix bug: javascript error of missing function
* Update description for some options
* Update styles

= 1.2.5 =
* Fix bug: doesn't save Layout format value when select '2 columns' option
* Fix notice about constant value

= 1.2.4 =
* Update translation feature: load translation file from /wp-content/languages/content-views/
* Fix pagination bug

= 1.2.3 =
* Fix warning: Cannot send session cache limiter - headers already sent

= 1.2.2 =
* Performance optimization for pagination request
* Add translation file (.po)

= 1.2.1 =
* Fix pagination bug if number of pages > 10
* Fix bug of Preview button: click event fires twice
* Enable other user roles (Editor, Author, Contributor) to see Content Views menu and manage Views

= 1.2.0 =
* Remove shortcodes in excerpt
* Fix Scroll bug when click Show/Hide preview
* Update Pagination setting
* Optimize filters system
* Compatibility update

= 1.1.6 =
* Fix bug auto selected terms which its value is number in Taxonomy settings box

= 1.1.5 =
* Fix pagination bug (return 0)

= 1.1.4 =
* Fix pagination bug when don't load Bootstrap in frontend

= 1.1.3 =
* Add option to Settings page to enable/disable load Bootstrap in frontend
* Enable to search by View ID in "All Views" page
* Fix bug Scrollable List (when slide count = 1)
* Update settings page
* Add some custom filters

= 1.1.2 =
* Fix offset bug

= 1.1.1 =
* Fix pagination bug

= 1.1 =
* Add "Parent page" option to query child pages of a parent page
* Show shortcode [pt_view id="VIEW_ID"] to able to copy in editing page of a View
* Add link to Thumbnail
* Update Settings page
* Fix import/export bugs
* Classify "Add New View" vs "Edit View"

= 1.0.2 =
* Add some WP filters
* Add main action for Pro plugin to trigger

= 1.0.1 =
* Adjust styles

= 1.0.0 =
* Initial release



== Upgrade Notice ==

