<?php

namespace LittlePackage\lib;

use tecnick\TCPDF\TCPDF;

defined( 'ABSPATH' ) || exit;

class TCPDF_Child extends TCPDF {

	/**
	 * Set the default JPEG compression quality (1-100)
	 * @param int $quality JPEG quality, integer between 1 and 100
	 * @public
	 * @since 3.0.000 (2008-03-27)
	 */
	public function setJPEGQuality($quality) {
		if (($quality < 1) || ($quality > 100)) {
			$quality = 75;
		}
		if ( ! isset( $quality ) ) {
			$quality = apply_filters_deprecated( 'wwpdf_jpeg_quality', [ 100 ], '6.3', '', 'The `wwpdf_jpeg_quality` filter hook is included in the Premium (paid) version of WaterWoo PDF. Please upgrade to continue using it.' );
		}
		$this->jpeg_quality = intval($quality);
	}

	/**
	 * Set a flag to print page header.
	 * @param boolean $val set to true to print the page header (default), false otherwise.
	 * @public
	 */
	public function setPrintHeader($val=true) {
		$this->print_header = false;
	}

	/**
	 * Set a flag to print page footer.
	 * @param boolean $val set to true to print the page footer (default), false otherwise.
	 * @public
	 */
	public function setPrintFooter($val=true) {
		$this->print_footer = false;
	}

	/**
	 * This method is used to render the page header.
	 * It is automatically called by AddPage() and could be overwritten in your own inherited class.
	 * @public
	 */
	public function Header() {}

	/**
	 * This method is used to render the page footer.
	 * It is automatically called by AddPage() and could be overwritten in your own inherited class.
	 * @public
	 */
	public function Footer() {}

} // End of class