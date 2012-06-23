=== Elevate Parent Category Template ===
Contributors: echoz
Author URI: http://and.thirdly.org
Tags: category, loop, template
Tested up to: 3.1
Requires at least: 2.5
Stable tag: 1.0.4

Elevates parent category template to top of WordPress Loop.

== Description ==

This plugin provides functions to retrieve the parent category of any particular post should it be assigned to a sub category of that parent category.

Using this feature, the plugin is also able to modify the WordPress Loop to redirect any category page generation to the parent category. This is especially useful when theme developers want to be able to specify category-x.php templates and have all related posts, be it those assigned to the category or the sub categories use that template for page generation.

So for example the current category structure is as follows.

* Main Category (has category-x.php template)
	* Sub Category 1
	* Sub Category 2

A post is assigned to "Sub Category 2".

When the post is requested the plugin will automatically detect if it has a parent category. If so, it will modify the WP variables to set the current category as the parent category and add a new variable stating the actual category of the post. This affects the WordPress Loop to category-x.php template defined for the parent 

== Installation ==

1. Upload elevate-parent-category-template.php into your plugins folder.
2. Activate and you're done.

== Usable Commands for Theme Developers ==

The PHP commands for theme developers to use are pretty straight forward.

*get_category_child()* returns the current post child category ID.

*is_parent()* checks if the current post is at the parent category.

*get_category_title($id)* gets the title of the category that is $id.

*get_category_parent()* returns the current post's parent category ID.
