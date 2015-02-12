=== Secondary HTML Content ===
Contributors: jakemgold, thinkoomph
Donate link: http://www.get10up.com/plugins/secondary-html-content-wordpress/
Tags: HTML, editor, WYSIWYG, tinymce, widget, sidebar, content
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 2.0

Add a up to 5 blocks of HTML content to pages and posts. Perfect for layouts with distinct content blocks, such as a sidebar or two column view.

== Description ==

Add up to 5 blocks of HTML content to WordPress pages andposts. A perfect solution for layouts with distinct content "blocks", such as a sidebar or multi-column view. When editing content, the secondary WYSIWYG content editors will appear beneath the standard content editor.

You can choose to add up to 5 new HTML blocks to pages and posts independently. For example, you could have no extra post blocks, and 3 extra page blocks. With pages, you can optionally inherit secondary HTML content from the page's ancestry (parents, grandparents, etc). Perfect for
section-wide sidebars.

Secondary content can be used by added to the theme by using the new widget ("Secondary HTML Content"), or by calling the content via a function in your template. See "Installation" for guidance on using the function.



== Installation ==

1. Install easily with the WordPress plugin control panel or manually download the plugin and upload the extracted folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress
1. Configure with the new "Secondary HTML" menu option under "Settings" 
1. Start entering secondary content! Output using the widget by going to the widget menu under apperance, or use the `get_secondary_content()` and `the_secondary_content()` functions in your template!

= The Functions =

Both functions take 2 optional parameters: block number, and post ID. If no block number is specified, it will default to the first secondary content block (value of 1). If no post ID is provided, it will default to the current post. Consistent with standard WordPress conventions, `get_secondary_content()` will return the value for the content block, while `the_secondary_content()` will echo it.

Example: `the_secondary_content();`

Outputs the current pages first additional content block.

Example: `the_secondary_content(2,22);`

That will output the the second additional content block for page/post ID 22.


== Screenshots ==

1. Screenshot of the page editor with new, secondary HTML block.
2. New widget: add secondary HTML to sidebar.
3. Configuration panel.


== Changelog ==

= 2.0 =
* Add up to 5 blocks for pages and posts (configured independently)
* Multiwidget support & specify which block to use in the widget
* Optionally add media buttons to secondary content blocks
* Various other improvements to the code base

= 1.5 =
* Option to use on pages, posts, or both (only pages before)
* Option to inherit ancestor secondary HTML content on pages
* Many under the hood changes and enhancements

== Upgrade Notice ==

= 2.0 =
The pre-2.0 function calls are backwards compatible, and the plugin will upgrade your custom fields. If you are using the widget, you may, however, need to re-add the widget due to fundamental changes to the widget's configuration and setup.