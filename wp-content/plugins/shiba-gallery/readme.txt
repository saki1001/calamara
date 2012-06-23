=== Popeye Gallery ===
Contributors: shibashake
Donate link: http://www.shibashake.com/
Tags: gallery, inline lightbox, lightbox, popeye, lytebox, popeye, galleria, smooth gallery, wordpress gallery, multiple galleries
Requires at least: 2.8
Tested up to: 2.8
Stable tag: 1.0

Allows you to display your WordPress galleries using Lytebox, Popeye, Galleria, SmoothGallery, or the WordPress native gallery. Display multiple galleries and mix and match any way you want using the gallery shortcode.

== Description ==

Render you WordPress galleries using a variety of gorgeous gallery Javascript libraries including -
<ul>
<li><strong><a href="http://smoothgallery.jondesign.net/">Smooth Gallery</a></strong> by JonDesign. Smooth Gallery is very slick looking inline gallery that allows users to tab through the images, or enjoy it in the automatic slideshow mode.</li>

<li><strong><a href="http://devkick.com/lab/galleria/">Galleria</a></strong> by DevKick Lab. Galleria is simply gorgeous. It is an inline gallery where you get to select images using thumbnails.</li>

<li><strong><a href="http://www.dolem.com/lytebox/">Lytebox</a></strong> by Markus F. Hay. Lytebox is similar to Lightbox but is a lighter version that does not require prototype.js or scriptaculous.js.</li>

<li><strong><a href="http://herr-schuessler.de/blog/jquerypopeye-an-inline-lightbox-alternative/">Popeye</a></strong> by <a href="http://herr-schuessler.de">Christoph Schüßler's</a>. Popeye is an inline Lightbox gallery. Display your gallery inline with your blog content, and enjoy Lightbox effects without using overlays.</li>
</ul>

Render your native WordPress galleries using any one of these scripts by just adding a 'type' in your gallery shortcode definition. For example -
<pre>
[gallery type=smoothgallery]
</pre>

It is as simple as that. And if you decide to deactivate the plugin, your gallery codes will still work with other WordPress gallery based plugins. 

If you do not specify a type, the system will just default to Smooth Gallery. You can change the default gallery by defining the constant SHIBA_GALLERY_DEFAULT in your theme functions.php file. For example -
<pre>
define('SHIBA_GALLERY_DEFAULT', 'popeye');
</pre>

== Installation ==

1. Upload `shiba-gallery.zip` onto your local computer.
2. Go to your WordPress Dashboard and select <strong>Plugins >> Add New</strong>.
3. Click on the <strong>Upload</strong> option at the top and select the popeye-gallery.zip file you just downloaded.
4. Click on <strong>Install</strong>.
5. Activate the plugin through the 'Plugins' menu in WordPress

WordPress galleries (activated by the gallery shortcode) will now be rendered using shiba-gallery. 


== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 1.0 =
First version includes 5 different gallery types - lytebox, popeye, smoothgallery, galleria, and native (for native WordPress galleries)