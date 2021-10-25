=== WaterWoo PDF Plugin ===
Contributors: littlepackage
Donate link: https://paypal.me/littlepackage
Tags: watermark, stamp, pdf, ebook, security, property
Requires at least: 5.3
Tested up to: 5.8
Requires PHP: 7.0
Stable tag: 2.7.11
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Protect your intellectual property! WaterWoo PDF allows WooCommerce site administrators to apply custom watermarks to PDFs upon sale.

== Description ==
Protect your intellectual property! WaterWoo PDF allows WooCommerce site administrators to apply custom watermarks to PDFs upon sale.

WaterWoo PDF is a plugin that adds a watermark to every page of your sold PDF file(s). The watermark is customizable with font face, font color, font size, placement, and text. Not only that, but since the watermark is added when the download button is clicked (either on the customer's order confirmation page or email), the watermark can include customer-specific data such as the customer's first name, last name, and email. Your watermark is highly customizable and manipulatable, practically magic!

Upon WooCommerce purchase download link, WaterWoo uses the open source TCPDI and TCPDF libraries to customize your PDF. This process isn't fool-proof, but works well in many cases. You may encounter problems if your PDF is malformed (bad PDF syntax), encrypted, web-optimized, linearized, or if your server cannot handle the memory load of PDF processing. 

**Please note** you must have WooCommerce plugin installed and activated for this plugin to work. This plugin watermarks WooCommerce PDF products when downloaded.

If have a Wordpress site and need to watermark PDFs, but do not have WooCommerce, [check out WP TCPDF Bridge](https://web.little-package.com/downloads/wp-tcpdf-bridge/ "WP TCPDF Bridge").

= Features: =

* Watermark only designated PDF downloads (as specified by you), or *all* PDF downloads from your site
* Files do not need to be in a specific directory
* Super customizable placement: watermark can be moved all over the page, allowing for different paper sizes (such as letter, A4, legal, etc)
* Watermark is applied to **all** pages of **every** PDF purchased
* Watermarks upon click of either the customer's order confirmation page link, email order confirmation link, or My Account page download links
* Dynamic customer data inputs (customer first name, last name, email, order paid date, and phone)
* Choice of font face, color, size and placement (horizontal line of text anywhere on the page).

= Premium version: =

The free version works fine for many people, but [WaterWoo PDF Premium](https://web.little-package.com/downloads/waterwoo-pdf-premium/ "WaterWoo PDF Premium Version") offers helpful extra features in addition to free features:

* Optionally password protect and/or set PDF permissions (copy, annotate, or modify, etc) while encrypting PDFs
* Keep original file name
* Watermark all PDF files with same settings OR set individual watermarks/passwords per product or even per product variation!
* Additional, rotatable watermark location - two watermark locations on one page!
* Additional text formatting options, such as font color and style (bold, italics) 
* Semi-opaque (transparent) watermarks
* RTL (right to left) watermarking
* Use of some HTML tags to style your output, including text-align CSS styling (right, center, left is default), links (&lt;a&gt;), bold (&lt;strong&gt;), italic (&lt;em&gt;)...
* Line-wrapping, forced breaks with &lt;p&gt; and &lt;br /&gt; tags
* Preserves external embedded PDF links despite watermarking; internal links are not preserved (research "WooStamper" for this feature)
* AJAX font uploader - use your own font for stamping. Also, hooks to further customize font use
* Additional dynamic customer data input (business name, address, order number, product name, quantity of product), hooks for adding even more
* Filter hooks to add 1D and 2D barcodes (including QR codes)
* Begin watermark on selected page of PDF document (to avoid watermarking a cover page, for example), and/or select end page
* Watermark every page, odd pages, or even pages
* Test watermark and/or manually watermark a file on the fly, from the admin panel

[Check out the full-featured version of this plugin](https://web.little-package.com/downloads/waterwoo-pdf-premium/ "WaterWoo PDF Premium Version")! WaterWoo is the only watermarker for Wordpress which includes necessary libraries (so you don't have to ask your host to load them), is compatible with PHP 7, and watermarks ALL versions of PDFs (not just older versions).

== Installation ==

= Prerequisites =
1. WordPress 4.9 or newer
2. WooCommerce plugin version 4.0 and newer, and PDF products
3. PHP version 7.0 or newer. The fresher, the better!
4. If you are selling large files or have high sales traffic, you may need to upgrade your hosting account. Do at least set your PHP memory limit high.

= To install plugin =
1. Upload the entire "waterwoo-pdf" folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Visit WooCommerce->Settings->Watermark tab to set your plugin preferences.
4. **Please test your watermarking** by making mock purchases before going live to make sure it works and looks great!
5.  Note: for this to work you might need to have pretty URLs enabled from the WP settings. Otherwise a 404 error will be thrown.

= To remove plugin: =

1. Deactivate plugin through the 'Plugins' menu in WordPress
2. Delete plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= It doesn't seem to work =
1. Is WooCommerce installed, and do you have a PDF product in your shop to watermark?
2. Have you checked the box at the top of your settings page (Woocommerce -> Settings -> Watermark) so that watermarking is enabled?
3. Have you entered your PDF file names correctly in the second field if you've entered any at all?
4. Is your Y fine-tuning adjustment off the page? Read more below under "Why does the watermark go off the page, create blank pages?".
5. Check your WP debug logs (link to instructions below). If logs suggest your PDF is "goofy" or "template does not exist," try using Apple Preview application to resave your PDF by clicking "Export as PDF" in the menu. Preview might fix bad PDF syntax and allow your PDF to be processed for watermarking.
6. It may also help to increase your PHP time limit and memory limits if they are set low.

Please do get in touch with your issues via the Wordpress.org support forum before leaving negative feedback about this free plugin.

If requesting help using the Wordpress.org support forum, please state which versions Wordpress/WooCommerce/WaterWoo you are using, and what error messages if any you are seeing. You will find more detailed error messaging if you [turn on Wordpress debugging](https://wordpress.org/support/article/debugging-in-wordpress/). Screenshots and clear descriptions of the steps it takes to reproduce your problem are also very helpful. Please also make your support request TITLE descriptive. If the answer to your question can be found on this page, you might be waiting a while to hear from me, as I must prioritize support requests. Thanks for understanding.

**Do not use the Wordpress.org support forum for help with the Premium (paid) version** of WaterWoo - that is against Wordpress.org rules.

= Where do I change watermark settings? =
You can find the WaterWoo settings page by clicking on the "settings" link under the WaterWoo PDF plugin title on your Wordpress plugins panel, or by navigating to the WooCommerce->Settings->Watermark tab.

= My watermark isn’t English =

Select the “Deja Vu,” “Furat,” or “M Sung” font in the WaterWoo settings panel if your language uses accent characters and find out if one supports characters in your language.

A primary reason watermarks do not show up is when the watermark contains special characters but a font which doesn’t support those characters is in use.

= How do I test my watermark? =
Maybe create a coupon in your Woocommerce shop to allow 100% free purchases. Don't share this coupon code with anyone! Test your watermark by purchasing PDFs from your shop using the coupon. It's a bit more tedious. If you want an easier go of it (on-the-fly testing), purchase the Premium version of this plugin.

= Why does the watermark go off the page, create blank pages? =
Your watermark text string is too big or long for the page, and goes off it! Try decreasing font size or using the Y fine tuners to move the watermark back onto the page. Try lowering your "y-axis" value. This number corresponds to how many *millimeters* you want the watermark moved down the page. For example, if your PDF page is 11 inches tall, your Y-axis setting should be a deal less than 279.4mm in order for a watermark to show. The built-in adjustments on the settings page ultimately allow for watermarking on all document sizes. You may need to edit your watermark if it is too verbose.

You can use a negative integer value for your Y-tuner and measure up from the bottom of the page. This is especially helpful if your PDF has variable sized pages.

= Where do the watermarked files go? =
They are generated with a unique name and stored in the same folder as your original Wordpress/Woo product media upload (usually wp-content/uploads/year/month/file). The unique name includes the order number and a time stamp. If your end user complains of not being able to access their custom PDF for some reason (most often after their max number of downloads is exceeded), you can find it in that folder, right alongside your original.

However, if you are using Woo FORCED downloads, the plugin attempts to delete the watermarked files after being delivered. If you don't like that behavior, you can change it with the 'wwpdf_do_cleanup' filter hook (set it to FALSE).

= Will WaterWoo PDF watermark images? =
WaterWoo PDF is intended to watermark PDF (.pdf) files. If you are specifically looking to watermark image files (.jpg, .jpeg, .gif, .png, .etc), you may want to look into a plugin such as [Image Watermark](http://wordpress.org/plugins/image-watermark/ "Image Watermark Plugin").

= Does this work for ePub/Mobi files =
No, sorry. This plugin is just for PDF files.

= WaterWoo seems to break my PDF =
WaterWoo PDF is a plugin which bridges WooCommerce and the open-source PDF reading TCPDI and PDF writing TCPDF library. WaterWoo functions by parsing/reading your PDF into memory the best it can, then adding a watermark to the PDF syntax and outputting a revised file. Between the reading and output, certain features may be lost and other features (interactive PDF elements like internal links and fillable forms) will be lost. This is a limitation of a third-party library and the wild-west nature of PDF syntax, and not the fault of WaterWoo. If you are serious about watermarking and/or encrypting complex PDF files, [consider purchasing WooStamper](https://web.little-package.com/downloads/woostamper-pdf/ "WooStamper PDF Plugin"). Ultimately, WaterWoo PDF is best for simple, smaller-sized PDFs.

= Is there a fallback in case watermarking fails? =
Yes, you can serve the file untouched if watermarking fails, and avoid any error messages, by using the following filter code in your (child) theme functions.php file:

`add_filter( 'wwpdf_serve_unwatermarked_file', '__return_true' );`

If you do not know how to edit your functions.php file, you can use the Code Snippets plugin to easily add this code to your WP site frontend.

== Screenshots ==

1. Screenshot of the settings page, as a Woocommerce settings tab.

== Upgrade Notice ==

= 2.4.2 = 
* Adjustments in Y-positioning (distance down the page) of the watermark since version 2.4 may cause blank pages or changes in watermark positioning. Please review your watermark settings to be sure they are where you like them.

= 2.3 =
* Version 2.3 is potentially (unlikely) a breaking update as the FPDI/FPDF libraries were updated

= 2.0 = 
* Support for WooCommerce versions older than 2.4 removed for security reasons. Please update your WooCommerce installation to the newest version before using WaterWoo.

= 2.7.11 = 
* Support for WooCommerce versions older than 4.0 will be removed from WaterWoo soon for security reasons. Update WooCommerce if you haven't already!

== Changelog ==

= 2.7.11 - 25 October 2021 =
* Update TCPDF to version 6.4.2
* Discontinue use of DIRECTORY_SEPARATOR constant
* Use wp_normalize_path() method for PDF paths if manipulated - for Windows users
* Add page number parameter to 'wwpdf_before_write' and wwpdf_after_write' hooks
* Testing with Wordpress 5.8 and WooCommerce 5.7+
* WooCommerce requires PHP 7.0, and so as a technicality, so does WaterWoo

= 2.7.10 - 13 January 2020 =
* Testing with WooCommerce version 4.9
* Tweak - Catch for when users have Y-axis taller than the page - use -10mm and help prevent blank pages (will catch egregious instances)

= 2.7.9 - 26 December 2020 =
* Testing with WP version 5.6 & WooCommerce version 4.8
* Minor code tidying in TCPDI & TCPDF

= 2.7.8 - 21 0ctober 2020 =
* Testing with WooCommerce version 4.6
* Minor changes to readme.txt

= 2.7.7 - 8 0ctober 2020 =
* Error logging for if (corrupted) PDF /Root not found - helpful feedback for a delivery failure
* More descriptive error source added to error log lines for easier tracing
* Clear on-screen error feedback with instructions for folks trying to watermark remotely-hosted PDFs
* Reverse chronological order of changelog, clean up upgrade notices in readme.txt, archive changelog to trunk
* Fallback instructions added to readme.txt
* Update language files

= 2.7.6 - 29 September 2020 =
* Deal with file paths same way with new Woo filter as with old in classes/wwpdf-file-handler.php

= 2.7.5 - 24 September 2020 =
* Fix - Better accommodate files listed in Woo by absolute path
* Tweak - Minor updates to TCPDF for PHP 7+ compatibility
* Testing with WooCommerce version 4.5 

= 2.7.4 - 12 July 2020 =
* Fix - Account for PDFs not stored in the /wp-content directory

= 2.7.3 - 11 July 2020 =
* Fix - property $size not correctly defined in classes/wwpdf-watermark.php line 47
* Tweak - don't attempt to remove ABSPATH from watermarked file path before returning to WC in classes/wwpdf-file-handler.php
* Tweak - filters 'wwpdf_filter_file_list', 'wwpdf_filter_file_path' and 'wwpdf_filter_watermarked_file' added in classes/wwpdf-file-handler.php
* Tweak - use order ID instead of order key for unique file naming, order ID now before time()
* Tweak - use new WC filter 'woocommerce_download_product_filepath' for Woo 4.0+ users
* Tweak - enforce use of Woo 3.0+
* Testing with WooCommerce version 4.3 

= 2.7.2 - 2 July 2020 =
* Fix - check if $res exists for PHP 7+ compatibility inc/tcpdi/tcpdi_parser.php line 1325
* Tweak - thorough class renaming for TCPDF/TCPDI to avoid conflicts with other PDF-manipulating plugins
* Tweak - more reliable handling of variable page size watermark placement in classes/wwpdf-watermark.php

= 2.7.1 - 22 June 2020 =
* Fix - cleanup expecting file and finding object in classes/wwpdf-file-handler.php line 67

= 2.7 - 15 June 2020 =
* Feature - [TIMESTAMP] shortcode watermarks date file was downloaded/marked (use [DATE] for sold date)
* Feature - 'wwpdf_before_output' action hook for folks wanting to get entrepreneuring with TCPDF
* Feature - compatibility up to PHP 7.4.2
* Testing to WC 4.2.0
* Testing to WP 5.4.2

= 2.6.1 - 16 May 2020 = 
* Tweak - use str_replace instead of preg_replace with shortcodes in classes/wwpdf-file-handler for speed
* Tweak - use output buffering while processing PDF with TCPDI/TCPDF
* Tweak - log potential errors to debug.log while showing customers simple (filterable) error message
* Tweak - don't try to center watermark if using large font size
* Update TCPDF library to version 6.3.2 for better compatibility with PHP 7
* Testing to WC 4.1.0
* Testing to WP 5.4.1

= 2.6 - 9 April 2020 = 
* Feature - allow for watermark placement measured from bottom of PDF page by using negative integer for Y-tuner
* Testing to WC 4.0.1
* Testing to WP 5.4
* Update .pot file

= 2.5.1 - 10 February 2020 = 
* Testing to WC 3.9.1
* Rephrase test for PDF in classes/wwpdf-file-handler.php - don't allow non-PDFs through watermarker

= 2.5 - 20 January 2020 = 
* Improve autoloader, performance improvements
* Added Deja Vu font, which has extensive international language character support
* Added compatibility check - because folks really should be upgrading their servers before running e-commerce!
* 'woocommerce_product_file_download_path' is a filter, not an action hook in classes/wwpdf-file-handler

= 2.4.6 - 8 January 2020 = 
* Required WP version moved from 4.4 to 4.9
* Class naming and checks to prevent conflicts with other plugins using TCPDI/TCPDF
* Update Flate filter
* Added M Sung font, which also supports Chinese characters
* Added Furat font, which also supports Arabic characters
* Update lang files

= 2.4.5 - 22/11/2019 = 
* Testing with WooCommerce 3.8
* Update language files
* Tweak - allow font sizes down to 1pt in settings (previously 6pt)

Older changes are found <a href="https://plugins.svn.wordpress.org/waterwoo-pdf/trunk/changelog.txt">in the changelog.txt file in the plugin directory.</a>
