<?php defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WWPDF_Watermark' ) ) :

	require_once WWPDF_PATH . 'inc/tcpdf/tcpdf.php';
	require_once WWPDF_PATH . 'inc/tcpdi/tcpdi.php';

	class WWPDF_Watermark {
	
		private $size = null;

		public function __construct( $origfile, $newfile, $footer ) {

			$this->pdf = new SS_TCPDI();
			$this->file = $origfile;
			$this->newfile = $newfile;
			$this->footer = $footer; 
			
			ob_start();
			$this->do_watermark();
			ob_end_clean();
			return $this->newfile;

		}

		public function do_watermark() {
		
			// If you want to do a whole lot more with TCPDF, get the premium version of this plugin (formerly known as WaterWoo)!
			// This is BASIC, if not CRUDE
			$pagecount = $this->pdf->setSourceFile( $this->file );
			$footer_y = sanitize_text_field( get_option( 'wwpdf_footer_y', 260 ) );
			$footer_color = $this->hex2rgb( sanitize_text_field( get_option( 'wwpdf_footer_color', '#000000' ) ) );
			$rgb_array = explode( ",", $footer_color );
			$font = sanitize_text_field( get_option( 'wwpdf_font', 'helvetica' ) );
			$font = apply_filters( 'wwpdf_add_custom_font', $font );
			$footer_size = absint( sanitize_text_field( get_option( 'wwpdf_footer_size', 12 ) ) );
			$string_width = $this->pdf->GetStringWidth( $this->footer, $font, '', $footer_size );

			for ( $i = 1; $i <= $pagecount; $i++ ) {
			
				if ( apply_filters( 'wwpdf_dont_watermark_this_page', FALSE, $i, $pagecount ) ) continue;

				$this->setup_page( $i ); // $i is page number

				if ( $footer_y < 0 ) { // for measuring from bottom of page
					// upper-left corner Y coordinate
					$footer_y = $this->size['h'] - abs( $footer_y );
				}
				if ( $footer_y > 0 && $footer_y >= $this->size['h'] ) {
					$footer_y = -10;
				}
				$this->pdf->SetFont( $font, '', $footer_size );
				$this->pdf->SetMargins( 0, 0 );
				$this->pdf->SetTextColor( $rgb_array[0], $rgb_array[1], $rgb_array[2] );
				if ( $string_width > $this->size['w'] ) {
					$footer_x = apply_filters( 'wwpdf_footer_x_abcissa', 0, $this->size['w'] );
				} else {
					$footer_x = ( $this->size['w'] / 2 ) - ( $this->pdf->GetStringWidth( $this->footer ) / 2 );
				}
				$this->pdf->SetXY( $footer_x, $footer_y );
				do_action( 'wwpdf_before_write', $this->pdf, $i );
				$this->pdf->Write( 1, $this->footer );
				do_action( 'wwpdf_after_write', $this->pdf, $i );
				
			}

			do_action( 'wwpdf_before_output', $this->pdf );

			$this->pdf->Output( $this->newfile, 'F' );

		} // end function do_watermark

		public function setup_page( $page ) {

			$idx			= $this->pdf->importPage( $page, '/BleedBox' );
			$meta			= $this->pdf->getMetaData();
			$this->size		= $this->pdf->getTemplateSize( $idx );
			$size_array		= array( $this->size['w'], $this->size['h'] );
			$orientation	= ( $this->size['w'] > $this->size['h'] ) ? 'L' : 'P';

			$this->pdf->SetAutoPageBreak( true, 0 );
			$this->pdf->AddPage( $orientation, '', $meta );
			$this->pdf->setPageFormatFromTemplatePage( $page, $orientation, $size_array );
			$this->pdf->useTemplate( $idx );
			$this->pdf->importAnnotations( $page );

		}

		protected function hex2rgb( $hex ) {
			$hex = str_replace( "#", "", $hex );
			$r = hexdec( substr( $hex,0,2 ) );
			$g = hexdec( substr( $hex,2,2 ) );
			$b = hexdec( substr( $hex,4,2 ) );
			$rgb = array( $r, $g, $b );
			return implode( ",", $rgb );
		}

	} // end Class WWPDF_Watermark

endif;