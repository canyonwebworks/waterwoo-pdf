=== WaterWoo PDF Plugin ===
Contributors: littlepackage
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PB2CFX8H4V49L
Tags: book, copyright, digital, ebook, ecommerce, e-commerce, file, marca de agua, pdf, plugin, property, protection, publishing, security, signature, watermark, watermarking, woocommerce
Requires at least: 4.0
Tested up to: 4.9.1
Stable tag: 1.2.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Protect your intellectual property! WaterWoo PDF allows WooCommerce site administrators to apply custom watermarks to PDFs upon sale.

== Description ==
WaterWoo PDF is a plugin that adds a watermark to every page of your sold PDF file(s). The watermark is customizable with font face, font color, font size, placement, and text. Not only that, but since the watermark is added when the download button is clicked (either on the customer's order confirmation page or email), the watermark can include customer-specific data such as the customer's first name, last name, and email. Your watermark is highly customizable and manipulatable, practically magic!

**Please note** you must have WooCommerce plugin installed and activated for this plugin to work. This plugin watermarks WooCommerce PDF products.

**Features:**

* Watermark only designated PDF downloads (as specified by you), or *all* PDF downloads from your site
* Files do not need to be in a specific directory
* Super customizable placement: watermark can be moved all over the page, allowing for different paper sizes (such as letter, A4, legal, etc)
* Watermark is applied to **all** pages of **every** PDF purchased
* Watermarks upon click of either the customer's order confirmation page link or email order confirmation link
* Dynamic customer data inputs (customer first name, last name, email, order paid date, and phone)
* Choice of font face, color, size and placement (horizontal line of text anywhere on the page).

**Premium version:**

[WaterWoo Premium](https://waterwoo.me "WaterWoo PDF Premium Version") offers these helpful extra features:

* Watermark all PDF files with same settings OR set individual watermarks per product or even per product variation!
* Supports all versions of Adobe PDF (through 1.7)
* Additional, rotatable watermark location - two watermark locations on one page!
* Additional text formatting options, such as font color and style (bold, italics) 
* Semi-opaque (transparent) watermarks
* RTL (right to left) watermarking
* Use of some HTML tags to style your output, including text-align CSS styling (right, center, left is default), links (&lt;a&gt;), bold (&lt;strong&gt;), italic (&lt;em&gt;)...
* Preserves external embedded PDF links despite watermarking; internal links are not preserved (in development)
* Line-wrapping, forced breaks with &lt;p&gt; and &lt;br /&gt; tags
* Additional font (Deja Vu) adding more international character support
* Additional fonts Furat (supports Arabic) and M Sung (supports Chinese)
* Hook to add your own fonts
* Additional dynamic customer data input (business name, order paid date)
* Hook to add 1D and 2D barcodes (including QR codes)
* Begin watermark on selected page of PDF document (to avoid watermarking a cover page, for example)
* Watermark every page, odd pages, or even pages
* Optionally password protect and/or encrypt PDF files
* Optionally prevent copying, annotating, or modifying of your PDF files
* Test watermark and/or manually watermark a file on the fly, from the admin panel

[Check out the full-featured version of this plugin](https://waterwoo.me "WaterWoo PDF Premium Version")!

== Installation ==

= Prerequisites =
1. WordPress 4.0 or newer
2. WooCommerce plugin and PDF products
3. PHP version 5.4 or newer
4. If you are selling large files or have high sales traffic, you may need to upgrade your hosting account. Do at least set your PHP memory limit high.

= To install plugin =
1. Upload the entire "waterwoo-pdf" folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Visit WooCommerce->Settings->Watermark tab to set your plugin preferences.
4. **Please test your watermarking** by making mock purchases before going live to make sure it works and looks great!
5.  Note: for this to work you need to have pretty URLs enabled from the WP settings. Otherwise a 404 error will be thrown.

= To remove plugin: =

1. Deactivate plugin through the 'Plugins' menu in WordPress
2. Delete plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= It doesn't seem to work =
1. Have you checked the box at the top of your settings page (Woocommerce -> Settings -> Watermark) so that watermarking is enabled?
2. Have you entered your PDF file names correctly in the second field if you've entered any at all?
3. WaterWoo may not be able to watermark PDF files version 1.5 and newer. Consider the Premium version of the plugin, which includes the PDF parser necessary for newer files (1.5+). 
4. Is your Y fine-tuning adjustment off the page? Read more below under "Why does the watermark go off the page, create blank pages?".

Please do get in touch with your issues via the Wordpress.org support forum or email ( hello @ waterwoo .me ) before leaving negative feedback about this free plugin.

If requesting help using the Wordpress.org support forum, please state which versions Wordpress/WooCommerce/WaterWoo you are using, what version your PDF is (it should be 1.4 or older), and what error messages if any you are seeing. Screenshots and clear descriptions of the steps it takes to reproduce your problem are also very helpful. Please also make your support request TITLE descriptive. I offer and support this plugin unpaid, and I'm sorry but I don't have time to guess or read minds.

**Do not use the Wordpress.org support forum for help with the Premium (paid) version** of WaterWoo - that is against Wordpress.org rules.

= Where do I change watermark settings? =
You can find the WaterWoo settings page by clicking on the "settings" link under the WaterWoo PDF plugin title on your Wordpress plugins panel, or by navigating to the WooCommerce->Settings->Watermark tab.

= How do I test my watermark? =
I recommend creating a coupon in your Woocommerce shop to allow 100% free purchases. Don't share this coupon code with anyone! Test your watermark by purchasing PDFs from your shop using the coupon. It's a bit more tedious. If you want an easier go of it (on-the-fly testing), purchase the Premium version of this plugin.

= Why does the watermark go off the page, create blank pages? =
Your watermark text string is too big or long for the page! Try decreasing font size or using the Y fine tuners to move the watermark back onto the page. Try lowering your "y-axis" value. This number corresponds to how many *millimeters* you want the watermark moved down the page. For example, if your PDF page is 11 inches tall, your Y-axis setting should be a deal less than 279.4mm in order for a watermark to show. The built-in adjustments on the settings page ultimately allow for watermarking on all document sizes. You may need to edit your watermark if it is too verbose.

= Where do the watermarked files go? =
They are generated with a unique name and stored in the same folder as your original Wordpress/Woo product media upload (usually wp-content/uploads/year/month/file). The unique name includes the order number and a time stamp. If your end user complains of not being able to access their custom PDF for some reason (most often after their max number of downloads is exceeded), you can find it in that folder, right alongside your original.

= Will WaterWoo PDF watermark images? =
WaterWoo PDF is intended to watermark PDF (.pdf) files. If you are specifically looking to watermark image files (.jpg, .jpeg, .gif, .png, .etc), you may want to look into a plugin such as [Image Watermark](http://wordpress.org/plugins/image-watermark/ "Image Watermark Plugin").

= I get an FPDF error =
If you get the "FPDF error: This document (../../yourfile.pdf) probably uses a compression technique which is not supported by the free parser shipped with FPDI" it is because the PDF you are trying to watermark uses a compression technique not supported by the bundled PDF generator, FPDI. FPDI parses PDFs through version 1.4, and occasionally has troubles with 1.5, 1.6 and 1.7. 

1. Try this [solution using Acrobat](http://stackoverflow.com/a/7155711 "Stack Overflow"), if possible. Alternatively, you can go to Edit->Preflight->Standards and Save As PDF/A.
2. If that doesn't work, test and perhaps purchase the [add-on from SetaSign](http://www.setasign.com/products/fpdi/demos/fpdi-pdf-parser/ "PDF Parser Add-On") and add it into this plugin. That will take some programming chops. Or consider buying the [Premium version](http://waterwoo.me "WaterWoo PDF Premium Version") of this plugin, as it will solve this problem.

= Does this work for ePub/Mobi files =
No, sorry. This plugin is just for PDF files.

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