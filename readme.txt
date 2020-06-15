=== WaterWoo PDF Plugin ===
Contributors: littlepackage
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PB2CFX8H4V49L
Tags: watermark, stamp, pdf, ebook, security, property
Requires at least: 4.9
Tested up to: 5.4.2
Stable tag: 2.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Protect your intellectual property! WaterWoo PDF allows WooCommerce site administrators to apply custom watermarks to PDFs upon sale.

== Description ==
WaterWoo PDF is a plugin that adds a watermark to every page of your sold PDF file(s). The watermark is customizable with font face, font color, font size, placement, and text. Not only that, but since the watermark is added when the download button is clicked (either on the customer's order confirmation page or email), the watermark can include customer-specific data such as the customer's first name, last name, and email. Your watermark is highly customizable and manipulatable, practically magic!

**Please note** you must have WooCommerce plugin installed and activated for this plugin to work. This plugin watermarks WooCommerce PDF products.

If have a Wordpress site and need to watermark PDFs, but do not have WooCommerce, [check out WP TCPDF Bridge](https://web.little-package.com/downloads/wp-tcpdf-bridge/ "WP TCPDF Bridge").

= Features: =

* Watermark only designated PDF downloads (as specified by you), or *all* PDF downloads from your site
* Files do not need to be in a specific directory
* Super customizable placement: watermark can be moved all over the page, allowing for different paper sizes (such as letter, A4, legal, etc)
* Watermark is applied to **all** pages of **every** PDF purchased
* Watermarks upon click of either the customer's order confirmation page link or email order confirmation link
* Dynamic customer data inputs (customer first name, last name, email, order paid date, and phone)
* Choice of font face, color, size and placement (horizontal line of text anywhere on the page).

= Premium version: =

The free version works fine for most people, but [WaterWoo PDF Premium](https://web.little-package.com/downloads/waterwoo-pdf-premium/ "WaterWoo PDF Premium Version") offers these helpful extra features in addition to free features:

* Optionally password protect and/or set PDF permissions (copy, annotate, or modify, etc) while encrypting PDFs
* Keep original file name
* Watermark all PDF files with same settings OR set individual watermarks per product or even per product variation!
* Additional, rotatable watermark location - two watermark locations on one page!
* Additional text formatting options, such as font color and style (bold, italics) 
* Semi-opaque (transparent) watermarks
* RTL (right to left) watermarking
* Use of some HTML tags to style your output, including text-align CSS styling (right, center, left is default), links (&lt;a&gt;), bold (&lt;strong&gt;), italic (&lt;em&gt;)...
* Line-wrapping, forced breaks with &lt;p&gt; and &lt;br /&gt; tags
* Preserves external embedded PDF links despite watermarking; internal links are not preserved (research "WooStamper" for this feature)
* AJAX font uploader - use your own font for stamping. Also, hooks to further customize font use
* Additional dynamic customer data input (business name, address, order number, product name, quantity of product), hooks for adding even more
* Hook to add 1D and 2D barcodes (including QR codes)
* Begin watermark on selected page of PDF document (to avoid watermarking a cover page, for example), and/or select end page
* Watermark every page, odd pages, or even pages
* Test watermark and/or manually watermark a file on the fly, from the admin panel

[Check out the full-featured version of this plugin](https://web.little-package.com/downloads/waterwoo-pdf-premium/ "WaterWoo PDF Premium Version")!

== Installation ==

= Prerequisites =
1. WordPress 4.9 or newer
2. WooCommerce plugin version 3.0 and newer, and PDF products
3. PHP version 5.6 or newer
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

Please do get in touch with your issues via the Wordpress.org support forum before leaving negative feedback about this free plugin.

If requesting help using the Wordpress.org support forum, please state which versions Wordpress/WooCommerce/WaterWoo you are using, and what error messages if any you are seeing. Screenshots and clear descriptions of the steps it takes to reproduce your problem are also very helpful. Please also make your support request TITLE descriptive. I offer and support this plugin unpaid, and I'm sorry but I don't have time to guess or read minds.

**Do not use the Wordpress.org support forum for help with the Premium (paid) version** of WaterWoo - that is against Wordpress.org rules.

= Where do I change watermark settings? =
You can find the WaterWoo settings page by clicking on the "settings" link under the WaterWoo PDF plugin title on your Wordpress plugins panel, or by navigating to the WooCommerce->Settings->Watermark tab.

= How do I test my watermark? =
Maybe create a coupon in your Woocommerce shop to allow 100% free purchases. Don't share this coupon code with anyone! Test your watermark by purchasing PDFs from your shop using the coupon. It's a bit more tedious. If you want an easier go of it (on-the-fly testing), purchase the Premium version of this plugin.

= Why does the watermark go off the page, create blank pages? =
Your watermark text string is too big or long for the page, and goes off it! Try decreasing font size or using the Y fine tuners to move the watermark back onto the page. Try lowering your "y-axis" value. This number corresponds to how many *millimeters* you want the watermark moved down the page. For example, if your PDF page is 11 inches tall, your Y-axis setting should be a deal less than 279.4mm in order for a watermark to show. The built-in adjustments on the settings page ultimately allow for watermarking on all document sizes. You may need to edit your watermark if it is too verbose.

New in version 2.6 you can use a negative integer value for your Y-tuner and measure up from the bottom of the page. This is especially helpful if your PDF has variable sized pages.

= Where do the watermarked files go? =
They are generated with a unique name and stored in the same folder as your original Wordpress/Woo product media upload (usually wp-content/uploads/year/month/file). The unique name includes the order number and a time stamp. If your end user complains of not being able to access their custom PDF for some reason (most often after their max number of downloads is exceeded), you can find it in that folder, right alongside your original.

However, if you are using Woo FORCED downloads, the plugin attempts to delete the watermarked files after being delivered. If you don't like that behavior, you can change it with the 'wwpdf_do_cleanup' filter hook (set it to FALSE).

= Will WaterWoo PDF watermark images? =
WaterWoo PDF is intended to watermark PDF (.pdf) files. If you are specifically looking to watermark image files (.jpg, .jpeg, .gif, .png, .etc), you may want to look into a plugin such as [Image Watermark](http://wordpress.org/plugins/image-watermark/ "Image Watermark Plugin").

= Does this work for ePub/Mobi files =
No, sorry. This plugin is just for PDF files.

= WaterWoo seems to break my PDF =
WaterWoo PDF is a plugin which bridges WooCommerce and the open-source PDF-writing TCPDF library. WaterWoo functions by parsing/reading your PDF into memory the best it can, then adding a watermark to the PDF syntax and outputting a revised file. Between the reading and output, certain features may be lost and other features (interactive PDF elements like internal links and fillable forms) will be lost. This is a limitation of a third-party library and the wild-west nature of PDF syntax, and not the fault of WaterWoo. If you are serious about watermarking and/or encrypting complex PDF files, look into WooStamper. Ultimately, WaterWoo PDF is best for simple, smaller-sized PDFs.

== Screenshots ==

1. Screenshot of the settings page, as a Woocommerce settings tab.

== Changelog ==

= 1.0, 2014.10.23 =
* Initial release

= 1.0.2, 2014.10.26 =
* Support for landscape orientation

= 1.0.3, 2014.11.30 = 
* Fixed 4 PHP warnings

= 1.0.4, 2014.12.16 = 
* Support for odd-sized PDFs

= 1.0.5, 2014.12.29 = 
* Clean up code in waterwoo-pdf.php class_wwpdf_system_check.php and class_wwpdf_download_product.php
* UTF font encoding
* Support for redirect downloads (as long as file is in wp-content folder)
* Better watermark centering on page

= 1.0.6, 2015.1.26 =
* Readme updates
* Implemented woo-includes to determine if Woo is active
* Fixed link to settings from plugin page
* Tidy "inc/class_wwpdf_watermark.php"

= 1.0.7, 2015.1.26 =
* Missing folder replaced

= 1.0.8, 2015.1.27 =
* Fix default option variable names

= 1.0.9, 2015.2.5 =
* WC 2.3 ready
* added phone number shortcode
* tidied folder structure

= 1.0.10, 2015.2.17 = 
* WC 2.3.4 update
* added order paid date shortcode: [DATE]

= 1.0.11, 2015.2.24 = 
* fix to woo-includes / Woo Dependencies

= 1.0.12, 2015.2.25 = 
* further fix to woo-includes / Woo Dependencies

= 1.0.13, 2015.3.10 =
* author domain name change
* streamlining of FPDI process

= 1.0.14, 2015.8.14 =
* minor error fixes
* Fixes for Wordpress 4.3

= 1.1, 2015.9.8 =
* Fixes for WoocCommerce 2.4
* Support added for more page/paper sizes
* Date i18n and by WP date setting format

= 1.2, 2015.10.25 =
* Watermarking over PDF images/embedded vectors

= 1.2.1, 2015.12.12 =
* Checks for Wordpress 4.4 
* Small language updates - French (FR/CA), Spanish (ES/MX) and German(DE)

= 1.2.2, 2016.2.2 =
* Checks for Wordpress 4.4.1
* Fix for legacy version of WooCommerce < 2.3

= 1.2.3, 2016.6.6 =
* Checks for Wordpress 4.5.2
* Remove file path from error message

= 1.2.4, 2016.6.29 =
* Compatibility with WC v.2.6.1
* Updated internal links to premium version of plugin

= 1.2.5, 2017.3.16 =
* WC 2.7 ready
* Hook into 'woocommerce_product_file_download_path' for download manipulation - for WC 2.4+ users
* Updates to readme.txt to reflect changes in Premium version, FAQ, copyright
* Remove SQL caching from watermarking queries

= 1.2.6, 2017.7.24 =
* Remove INI directive 'safe_mode' (removed with PHP version 5.4)
* More checks before use of set_magic_quotes_runtime() (removed with PHP version 5.4)

= 1.2.7, 2017.12.31 =
* Woocommerce version check support

= 1.2.8, 2018.04.09 =
* Give unique FPDF, etc. classes a unique name and check for existence before using
* Woocommerce version check support

= 2.0, 2018.05.30 =
* Drop support for versions of WooCommerce older than 2.4 (released 8/10/2015)
* Fix missing HTTP vars in wwpdf_file_handler and waterwoo-pdf.php

= 2.1, 2018.06.20 =
* SQL calls removed from /classes/class-wwpdf-file-handler.php
* Filter hooks added to /classes/class-wwpdf-file-handler.php: 'wwpdf_filter_footer' to filter footer watermark input; 'wwpdf_font_decode' & 'wwpdf_out_charset' filters to allow character encoding manipulation
* Minor readability improvement to settings page
* Remove PHP each() from FPDI to get ready for PHP 7.2
* Fix so download link in Woo order management screen is watermarked

= 2.2, 2018.08.08 =
* Filter hook 'wwpdf_add_custom_font' added to /classes/class-wwpdf-watermark.php
* Language file updates
* Convert non-printable Unicode characters into the "equivalent" ASCII 
* Notice to customers placed in settings to clarify free plugin limitations

= 2.3, 2018.12.13 =
* Improved error handling for FPDI limitations
* File cleanup improvement
* FPDI and FPDF updated to most recent available versions with performance enhancements
* WP/WooCommerce compatibility version updated
* DB cleanup option added for uninstall events

= 2.4, 2019.07.06 =
* Use TCPDI/TCPDF libraries in favor of FPDI/FPDF in order to offer watermarking for ALL version PDFs
* WP/WooCommerce compatibility version updated

= 2.4.1 2019.07.10 = 
* TCPDF properties SetAutoPageBreak and SetMargins used to remove margins on Text Cell for more accurate watermark Y positioning
* Extra fonts removed to slim down plugin folder size
* WooCommerce testing compatibility with version 2.6.5

= 2.4.2 2019.07.11 = 
* Retain special UTF-8 characters while watermarking

= 2.4.3 = 
* Tweak - /inc/tcpdi/tcpdi_parser.php property getXrefData() - runs 4-5x faster
* Importannotations() in order to allow preserving of many (external) URL links in PDF

= 2.4.4 2019.08.18 = 
* Fix for settings not updating, fire settings hooks earlier in waterwoo-pdf.php
* Links to waterwoo official website updated, and with noopener rel tags
* Testing with WooCommerce 3.7
* Minimum requirements for PHP, Wordpress, and WooCommerce updated

= 2.4.5 2019.11.22 = 
* Testing with WooCommerce 3.8
* Update language files
* Tweak - allow font sizes down to 1pt in settings (previously 6pt)

= 2.4.6 2020.1.8 = 
* Required WP version moved from 4.4 to 4.9
* Class naming and checks to prevent conflicts with other plugins using TCPDI/TCPDF
* Update Flate filter
* Added M Sung font, which also supports Chinese characters
* Added Furat font, which also supports Arabic characters
* Update lang files

= 2.5 2020.1.20 = 
* Improve autoloader, performance improvements
* Added Deja Vu font, which has extensive international language character support
* Added compatibility check - because folks really should be upgrading their servers before running e-commerce!
* 'woocommerce_product_file_download_path' is a filter, not an action hook in classes/wwpdf-file-handler

= 2.5.1 2020.2.10 = 
* Testing to WC 3.9.1
* Rephrase test for PDF in classes/wwpdf-file-handler.php - don't allow non-PDFs through watermarker

= 2.6 2020.4.9 = 
* Feature - allow for watermark placement measured from bottom of PDF page by using negative integer for Y-tuner
* Testing to WC 4.0.1
* Testing to WP 5.4
* Update .pot file

= 2.6.1 2020.5.16 = 
* Tweak - use str_replace instead of preg_replace with shortcodes in classes/wwpdf-file-handler for speed
* Tweak - use output buffering while processing PDF with TCPDI/TCPDF
* Tweak - log potential errors to debug.log while showing customers simple (filterable) error message
* Tweak - don't try to center watermark if using large font size
* Update TCPDF library to version 6.3.2 for better compatibility with PHP 7
* Testing to WC 4.1.0
* Testing to WP 5.4.1

= 2.7 2020.6.15 =
* Feature - [TIMESTAMP] shortcode watermarks date file was downloaded/marked (use [DATE] for sold date)
* Feature - 'wwpdf_before_output' action hook for folks wanting to get entrepreneuring with TCPDF
* Feature - compatibility up to PHP 7.4.2
* Testing to WC 4.2.0
* Testing to WP 5.4.2

== Upgrade Notice ==

= 1.0.2 =
* Support for landscape orientation

= 1.0.4 = 
* Support for odd-sized PDFs

= 1.0.5 = 
* UTF font encoding
* Support for redirect downloads (as long as file is in wp-content folder)
* Better watermark centering on page

= 1.0.9 = 
* WC 2.3 ready
* added phone number shortcode

= 1.0.10 = 
* added order paid date shortcode: [DATE]

= 1.1 = 
* Update required to function with WooCommerce 2.4 and Wordpress 4.3!

= 1.2 = 
* Update to fix watermark not showing over PDF images/vectors

= 2.0 = 
* Support for WooCommerce versions older than 2.4 removed for security reasons. Please update your WooCommerce installation to the newest version before using WaterWoo.

= 2.3 =
* Version 2.3 is potentially (unlikely) a breaking update as the FPDI/FPDF libraries were updated

= 2.4.2 = 
* Adjustments in Y-positioning (distance down the page) of the watermark since version 2.4 may cause blank pages or changes in watermark positioning. Please review your watermark settings to be sure they are where you like them.
