=== Slideshow ===

Contributors: stefanboonstra
Tags: slideshow, slider, slide, images, image, photo, gallery, galleries
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.3.5
License: GPLv2

Integrate a fancy slideshow in just five steps. - Rainbows. Rainbows everywhere.


== Description ==

Slideshow provides an easy way to integrate a slideshow for any Wordpress installation.

Any sized image can be loaded into the slideshow using the upload button you're already familiar with from uploading
images to your posts. Once uploaded, the images are shown in your slideshow straight away!

Fancy doing something crazy? You can create and use as many slideshows as you'd like, with
different images, settings and styles for each one of them.

 - Upload as many images as you like.
 - Place it anywhere on your website.
 - Customize it to taste.
 - Show that visitor who's boss.

= Currently supported languages =

 - English
 - Dutch

== Installation ==

1. Install Slideshow either via the WordPress.org plugin directory, or by uploading the files to your server.

2. After activating Slideshow, you can create a new slideshow.

3. Upload images to your newly created slideshow with the upload button in the slides list.

4. Use the shortcode or code snippet visible in your slideshow admin panel to deploy your slideshow anywhere on your website.
You can also use the widget to show any of your slideshows in your sidebar.

5. Feel like a sir.


== Screenshots ==

1. Create a new slideshow. A shortcode and a code snippet of how to call it is already visible.

2. Attach images to the slideshow with the upload button in the slides list.

3. The Wordpress media uploader will pop up and you can start uploading images to the slideshow.

4. The attached images are now visible in your newly created slideshow.

5. Using the shortcode or code snippet the slideshow shows you, you can enjoy your slides in style.

6. Not satisfied with the handling or styling of the slideshow? Customize!


== Changelog ==

= 1.3.5 =
*   Fixed: Namespace complications found with the Slideshow widget, renamed all classes.

= 1.3.4 =
*   Fixed: Custom width of the slideshow will no longer cause buttons to fall off-screeen.

= 1.3.3 =
*   Extended compatibility to servers that do not support short php opening tags.

= 1.3.2 =
*   Fixed: 1.3.1 Bugfix failed to work, fixed problem entirely after reproducing it.
*   Added alternative way to load default css into empty custom-style box, so that users without 'allow_url_fopen' enabled aren't influenced negatively by it.

= 1.3.1 =
*   Fixed: Check if function 'file_get_contents' exists before calling it, some servers have this disabled. (This throws errors and messes up the plugin)

= 1.3.0 =
*   Added Dutch translation.
*   Custom styles for each slideshow are now available to be more compatable with every theme. (Black and transparent scheme)
*   Encapsulated a css class so that it does not interfere with anything outside the slideshow_container.
*   Moved slides list to the side, saving space on the slideshow specific settings page.
*   Settings bugs completely fixed, finally. (Previous version deleted post-meta on auto-save)
*   Moved Slideshow settings and images script to inside the slideshow_container, outputting a more coherent whole.
*   Settings moved from multiple meta keys to a single one. (This resets everyone's settings)
*   Added a Wordpress media upload button to the slides list, this simplifies attaching images to a slideshow.
*   Better way of including the jQuery library is now being used.
*   Fixed bug with the number of slides shown in the slideshow stuck at the default value of five.

= 1.2.1 =
*   Fixed: Slideshow specific settings not saving.

= 1.2.0 =
*   Slideshows can now be placed in posts as well, using shortcode [slideshow id=*SlideshowPostId*].
*   Added a widget that can be loaded with an existing slideshow of choice.
*   Tested up to version 3.4

= 1.1.0 =
*   Added jQuery library as Wordpress websites don't seem to load them by default.
*   Slideshow script now depends on by the plugin enqueued jQuery script.

= 1.0.1 =
*   Added documentary comments.
*   Fixed error with directory paths causing Slideshows post type page to generate warnings.

= 1.0.0 =
*	Initial release.


== Links ==

*	[Stefan Boonstra](http://stefanboonstra.com/)