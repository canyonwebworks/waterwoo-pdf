<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WWPDF_Watermark' ) ) :

    require_once WWPDF_PATH . 'inc/tcpdf/tcpdf.php';
    require_once WWPDF_PATH . 'inc/tcpdi/tcpdi.php';

	class WWPDF_Watermark {

		public function __construct( $origfile, $newfile, $footer ) {

            $this->pdf = new LP_TCPDI();
			$this->file = $origfile;
			$this->newfile = $newfile;
			$this->footer = $footer; 
			
			ob_start();
			$this->do_watermark();
			ob_end_clean();               
            return $this->newfile;

		}

		public function do_watermark() {

			$pagecount = $this->pdf->setSourceFile( $this->file );
			$footer_y = get_option( 'wwpdf_footer_y', 260 );
			$footer_color = $this->hex2rgb( get_option( 'wwpdf_footer_color', '#000000' ) );
			$rgb_array = explode( ",", $footer_color );
			$font = get_option( 'wwpdf_font', 'helvetica' );
			$font = apply_filters( 'wwpdf_add_custom_font', $font );
			$footer_size = get_option( 'wwpdf_footer_size', 12 );
			$string_width = $this->pdf->GetStringWidth( $this->footer, $font, '', $footer_size );
	
			for ( $i = 1; $i <= $pagecount; $i++ ) {
			
                if ( apply_filters( 'wwpdf_dont_watermark_this_page', FALSE, $i, $pagecount ) ) continue;

				$idx        = $this->pdf->importPage( $i );
                $meta       = $this->pdf->getMetaData();

				$size       = $this->pdf->getTemplateSize( $idx );
                $size_arr   = array( $size['w'], $size['h'] );
                $orient     = ( $size['w'] > $size['h'] ) ? 'L' : 'P'; 
                if ( $footer_y < 0 ) { // for measuring from bottom of page
                    // upper-left corner Y coordinate
                    $footer_y = $size['h'] - abs( $footer_y );                        
                }
				$this->pdf->addPage( $orient, '', $meta );
				$this->pdf->setPageFormatFromTemplatePage( $i, $orient, $size_arr );
                $this->pdf->useTemplate( $idx );
                $this->pdf->importAnnotations( $idx );
                $this->pdf->SetFont( $font, '', $footer_size );
				$this->pdf->SetMargins( 0, 0 );
                $this->pdf->SetAutoPageBreak( true, 0 );
                $this->pdf->SetTextColor( $rgb_array[0], $rgb_array[1], $rgb_array[2] );
                if ( $string_width > $size['w'] ) {
                    $footer_x = apply_filters( 'wwpdf_footer_x_abcissa', 0, $size['w'] );
                } else {
                    $footer_x = ( $size['w'] / 2 ) - ( $this->pdf->GetStringWidth( $this->footer ) / 2 );
                }
                $this->pdf->SetXY( $footer_x, $footer_y );
				$this->pdf->Write( 1, $this->footer );
				
			}
			
            do_action( 'wwpdf_before_output', $this->pdf );
			
			$this->pdf->Output( $this->newfile, 'F' );

		} // end function do_watermark

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