<?php
//============================================================+
// File name   : tcpdf_config.php
// Begin       : 2004-06-11
// Last Update : 2014-12-11
//
// Description : Configuration file for TCPDF.
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)
// -------------------------------------------------------------------
// Copyright (C) 2004-2014  Nicola Asuni - Tecnick.com LTD
//
// This file is part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with TCPDF.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT file for more information.
//============================================================+

/**
 * Configuration file for TCPDF.
 * @author Nicola Asuni
 * @package com.tecnick.tcpdf
 * @version 4.9.005
 * @since 2004-10-27
 */

// IMPORTANT:
// If you define the constant K_TCPDF_EXTERNAL_CONFIG, all the following settings will be ignored.
// If you use the tcpdf_autoconfig.php, then you can overwrite some values here.

/**
 * Installation path (/var/www/tcpdf/).
 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_MAIN', '');

/**
 * URL path to tcpdf installation folder (http://localhost/tcpdf/).
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_URL', '');

/**
 * Path for PDF fonts.
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_FONTS', K_PATH_MAIN.'fonts/');

/**
 * Default images directory.
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define ('K_PATH_IMAGES', '');

/**
 * Cache directory for temporary files (full path).
 */
//define ('K_PATH_CACHE', '/tmp/');

/**
 * Page format.
 */
if (!defined('WaterWoo_PDF_PAGE_FORMAT')) {
    define ('WaterWoo_PDF_PAGE_FORMAT', 'A4');
}
/**
 * Page orientation (P=portrait, L=landscape).
 */
if (!defined('WaterWoo_PDF_PAGE_ORIENTATION')) {
    define ('WaterWoo_PDF_PAGE_ORIENTATION', 'P');
}
/**
 * Document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch].
 */
if (!defined('WaterWoo_PDF_UNIT')) {
    define ('WaterWoo_PDF_UNIT', 'mm');
}
/**
 * Document creator.
 */
if (!defined('WaterWoo_PDF_CREATOR')) {
    define ('WaterWoo_PDF_CREATOR', '');
} 
/**
 * Document author.
 */
if (!defined('WaterWoo_PDF_AUTHOR')) {
    define ('WaterWoo_PDF_AUTHOR', '');
}
/**
 * Top margin.
 */
if (!defined('WaterWoo_PDF_MARGIN_TOP')) {
    define ('WaterWoo_PDF_MARGIN_TOP', 27);
}
/**
 * Bottom margin.
 */
if (!defined('WaterWoo_PDF_MARGIN_BOTTOM')) {
    define ('WaterWoo_PDF_MARGIN_BOTTOM', 25);
}
/**
 * Left margin.
 */
if (!defined('WaterWoo_PDF_MARGIN_LEFT')) {
    define ('WaterWoo_PDF_MARGIN_LEFT', 15);
}
/**
 * Right margin.
 */
if (!defined('WaterWoo_PDF_MARGIN_RIGHT')) {
    define ('WaterWoo_PDF_MARGIN_RIGHT', 15);
}
/**
 * Default main font name.
 */
if (!defined('WaterWoo_PDF_FONT_NAME_MAIN')) {
    define ('WaterWoo_PDF_FONT_NAME_MAIN', 'helvetica');
}
/**
 * Default main font size.
 */
if (!defined('WaterWoo_PDF_FONT_SIZE_MAIN')) {
    define ('WaterWoo_PDF_FONT_SIZE_MAIN', 10);
}
/**
 * Default data font name.
 */
if (!defined('WaterWoo_PDF_FONT_NAME_DATA')) {
    define ('WaterWoo_PDF_FONT_NAME_DATA', 'helvetica');
}
/**
 * Default data font size.
 */
if (!defined('WaterWoo_PDF_FONT_SIZE_DATA')) {
    define ('WaterWoo_PDF_FONT_SIZE_DATA', 8);
}
/**
 * Default monospaced font name.
 */
if (!defined('WaterWoo_PDF_FONT_MONOSPACED')) {
    define ('WaterWoo_PDF_FONT_MONOSPACED', 'courier');
}
/**
 * Ratio used to adjust the conversion of pixels to user units.
 */
if (!defined('WaterWoo_PDF_IMAGE_SCALE_RATIO')) {
    define ('WaterWoo_PDF_IMAGE_SCALE_RATIO', 1.25);
}
/**
 * Magnification factor for titles.
 */
if (!defined('WaterWoo_HEAD_MAGNIFICATION')) {
    define('WaterWoo_HEAD_MAGNIFICATION', 1.1);
}
/**
 * Height of cell respect font height.
 */
if (!defined('WaterWoo_K_CELL_HEIGHT_RATIO')) {
    define('WaterWoo_K_CELL_HEIGHT_RATIO', 1.25);
}
/**
 * Title magnification respect main font size.
 */
if (!defined('WaterWoo_K_TITLE_MAGNIFICATION')) {
    define('WaterWoo_K_TITLE_MAGNIFICATION', 1.3);
}
/**
 * Reduction factor for small font.
 */
if (!defined('WaterWoo_K_SMALL_RATIO')) {
    define('WaterWoo_K_SMALL_RATIO', 2/3);
}
/**
 * Set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language.
 */
if (!defined('WaterWoo_K_THAI_TOPCHARS')) {
    define('WaterWoo_K_THAI_TOPCHARS', false);
}
/**
 * If true allows to call TCPDF methods using HTML syntax
 * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
 */
if (!defined('WaterWoo_K_TCPDF_CALLS_IN_HTML')) {
    define('WaterWoo_K_TCPDF_CALLS_IN_HTML', false);
}
/**
 * Default timezone for datetime functions
 */
if (!defined('WaterWoo_K_TIMEZONE')) {
    define('WaterWoo_K_TIMEZONE', 'UTC');
}