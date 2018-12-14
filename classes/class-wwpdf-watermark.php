<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WWPDFWatermark' ) ) :

	class WWPDFWatermark {

		public function __construct($origfile, $newfile, $footer) {

			$this->pdf = new setasign\Fpdi\Woo_Fpdi();
			$this->file = $origfile;
			$this->newfile = $newfile;
			$this->footer = $footer; 

		}

		public static function apply_and_spit($origfile, $newfile, $footer) {

			// FPDF Copyright 2011-2015 Olivier PLATHEY
			require_once WWPDF_PATH . 'inc/fpdf/fpdf.php';
			// FPDI Copyright 2004-2018 Setasign - Jan Slabon
            require_once( WWPDF_PATH . 'inc/fpdi/autoload.php' );

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
			$font = get_site_option( 'wwpdf_font', 'helvetica' );
			$font = apply_filters( 'wwpdf_add_custom_font', $font );
			$footer_size = get_site_option( 'wwpdf_footer_size', '12' );
			$this->pdf->SetFont( $font, '', $footer_size );	
			$footer_color = $this->hex2rgb( get_site_option( 'wwpdf_footer_color', '#000000' ) );
			$rgb_array = explode( ",", $footer_color );
			$this->pdf->SetTextColor( $rgb_array[0],$rgb_array[1],$rgb_array[2] );

			for( $i = 1; $i <= $pagecount; $i++ ) {
			
				$tplidx = $this->pdf->importPage($i);
				$specs = $this->pdf->getTemplateSize($tplidx);
				$this->pdf->addPage( $specs['height'] > $specs['width'] ? 'P' : 'L');
				$this->pdf->useTemplate( $tplidx, null, null, $specs['width'], $specs['height'], true );
				$this->pdf->Text( ( $specs['width'] / 2 ) - ( $this->pdf->GetStringWidth( $this->footer ) / 2 ), $footer_y, $this->footer );
				
			}
			$this->pdf->Output($this->newfile, 'F');

		} // end function do_watermark

		protected function hex2rgb($hex) {
			$hex = str_replace("#", "", $hex);
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
			$rgb = array($r, $g, $b);
			return implode(",", $rgb);
		}

	} // end Class WWPDFWatermark
	
endif;