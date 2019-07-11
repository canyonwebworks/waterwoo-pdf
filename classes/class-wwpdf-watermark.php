<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WWPDFWatermark' ) ) :

	class WWPDFWatermark {

		public function __construct($origfile, $newfile, $footer) {

            $this->pdf = new WWPDF_TCPDI();
			$this->file = $origfile;
			$this->newfile = $newfile;
			$this->footer = $footer; 

		}

		public static function apply_and_spit($origfile, $newfile, $footer) {

            /* 
             * Include TCPDF & TCPDI
             */
            require_once WWPDF_PATH . 'inc/tcpdf/tcpdf_child.php';
            require_once WWPDF_PATH . 'inc/tcpdi/tcpdi.php';
            
			$wm = new WWPDFWatermark($origfile, $newfile, $footer);
			
            if ( file_exists( $wm->newfile && ! apply_filters( 'wwpdf_do_cleanup', true ) ) ) {
				return $wm->newfile;
			} else {
				$wm->do_watermark();
				return $wm->newfile;
			}
		
		}

		public function do_watermark() {

			$pagecount = $this->pdf->setSourceFile( $this->file );
			$footer_y = get_site_option( 'wwpdf_footer_y', '260' );
			$footer_color = $this->hex2rgb( get_site_option( 'wwpdf_footer_color', '#000000' ) );
			$rgb_array = explode( ",", $footer_color );
			$font = get_site_option( 'wwpdf_font', 'helvetica' );
			$font = apply_filters( 'wwpdf_add_custom_font', $font );
			$footer_size = get_site_option( 'wwpdf_footer_size', '12' );

			for( $i = 1; $i <= $pagecount; $i++ ) {
			
				$idx        = $this->pdf->importPage( $i );
                $meta       = $this->pdf->getMetaData();

				$size       = $this->pdf->getTemplateSize( $idx );
                $size_arr   = array( $size['w'], $size['h'] );
                $orient     = ( $size['w'] > $size['h'] ) ? 'L' : 'P'; 

				$this->pdf->addPage( $orient, '', $meta );
				$this->pdf->setPageFormatFromTemplatePage( $i, $orient, $size_arr );
                $this->pdf->useTemplate( $idx );
                $this->pdf->SetFont( $font, '', $footer_size );
				$this->pdf->SetMargins( 0, 0 );
                $this->pdf->SetAutoPageBreak( true, 0 );
                $this->pdf->SetTextColor( $rgb_array[0], $rgb_array[1], $rgb_array[2] );
                $footer_x = ( $size['w'] / 2 ) - ( $this->pdf->GetStringWidth( $this->footer ) / 2 );
                $this->pdf->SetXY( $footer_x, $footer_y );
				$this->pdf->Write( 1, $this->footer );
				
			}
			$this->pdf->Output($this->newfile, 'F');

		} // end function do_watermark

		protected function hex2rgb( $hex ) {
			$hex = str_replace( "#", "", $hex );
			$r = hexdec( substr( $hex,0,2 ) );
			$g = hexdec( substr( $hex,2,2 ) );
			$b = hexdec( substr( $hex,4,2 ) );
			$rgb = array( $r, $g, $b );
			return implode( ",", $rgb );
		}

	} // end Class WWPDFWatermark
	
endif;