<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WWPDF_File_Handler' ) ) {

    class WWPDF_File_Handler {

        private $watermarked_file;

        /**
         * Constructor
         */
        public function __construct() {
        
            $this->watermarked_file = '';
    
            // filter the file download path
            add_action( 'woocommerce_product_file_download_path', array( $this, 'pdf_file_download_path' ), 50, 3 );

		    if ( apply_filters( 'wwpdf_do_cleanup', true ) ) {
                $this->do_cleanup();
            }

        }
    
        public function pdf_file_download_path( $file_path, $product, $download_id ) {

            // PDF - watermarking - start by checking if it's even a PDF
            $file_extension = preg_replace( '/\?.*/', '', substr( strrchr( $file_path, '.' ), 1 ) );

            if ( strtolower( $file_extension ) == 'pdf' ) {

				// Is the WaterWoo plugin enabled?
                $wwpdf_enabled = get_site_option( 'wwpdf_enable', 'no' );
				
                if ( $wwpdf_enabled == "yes" ) {

                    // order ID is post ID for backend (admin) watermarking
                    $order_id = is_admin() ? $_GET['post'] : wc_get_order_id_by_order_key( wc_clean( wp_unslash( $_GET['order'] ) ) );
                    $order_key = wc_clean( isset( $_GET['key'] ) );

                	if ( isset( $_GET['email'] ) ) {
						$email_address  = wp_unslash( $_GET['email'] );
						$email_address  = sanitize_email( str_replace( ' ', '+', $email_address ) );
					} else {
						// Get email address from order to verify hash.
						$order          = wc_get_order( $order_id );
						$email_address  = is_a( $order, 'WC_Order' ) ? $order->get_billing_email() : null;
					}
        
 					$file_req           = basename( $file_path );
					$wwpdf_files        = get_site_option( 'wwpdf_files', '' );
					$wwpdf_file_list    = array_filter( array_map( 'trim', explode( PHP_EOL, $wwpdf_files ) ) );
					
					// Watermark desired files only
					if ( in_array( $file_req, $wwpdf_file_list ) || $wwpdf_files == '' ) { 
					
                        $product_id = $product->get_id();
					
                        $footer_input = $this->wwpdf_setup_watermark( $email_address, $product_id, $order_id );            
            
            			$parsed_file_path = WC_Download_Handler::parse_file_path( $file_path );
			            $wwpdf_file_path = str_replace( '.pdf', '', $parsed_file_path['file_path'] ) . '_' . time() . '_' . $order_key . '.pdf';      
            
                        try {
                            $this->watermarked_file = WWPDFWatermark::apply_and_spit( $parsed_file_path['file_path'], $wwpdf_file_path, $footer_input );
                        } catch ( Exception $ex ) {
                            _e( 'Unable to watermark this file for download. Please notify site administrator.', 'waterwoo-pdf' );
                            echo $ex->getMessage();
                            return;
                        }
                        
                        $watermarked_file = str_replace( ABSPATH, '', $this->watermarked_file );
                        return $watermarked_file;
                        
                    }

                }
                
            }
            return $file_path;
          
        }
    
        /**
         * Returns file_path, either altered/stamped or not
         */
        public static function wwpdf_setup_watermark( $user_email, $product_id, $order_id ) {
        
            $order = wc_get_order( $order_id );
            $order_data = $order->get_data();

            $first_name         = $order_data['billing']['first_name'];
            $last_name          = $order_data['billing']['last_name'];
            $phone              = $order_data['billing']['phone'];

            $order_paid_date    = $order_data['date_created']->date('Y-m-d H:i:s');
            $date_format        = get_site_option( 'date_format' );
            $order_paid_date    = date_i18n( $date_format, strtotime( $order_paid_date ) );
            
            $footer_input = get_site_option( 'wwpdf_footer_input', 'Licensed to [FIRSTNAME] [LASTNAME], [EMAIL]' );
			$footer_input = preg_replace( array( '/\[FIRSTNAME\]/','/\[LASTNAME\]/','/\[EMAIL\]/','/\[PHONE\]/','/\[DATE\]/' ), array( $first_name, $last_name, $user_email, $phone, $order_paid_date ), $footer_input );

            $footer_input = apply_filters( 'wwpdf_filter_footer', $footer_input, $order_id, $product_id );

            // text encoding
            if ( has_filter( 'wwpdf_font_decode' ) ) {
                $footer_input = apply_filters( 'wwpdf_font_decode', $footer_input );
            } else {
                $out_charset = 'UTF-8';
                $out_charset = apply_filters( 'wwpdf_out_charset', $out_charset );
                $footer_input = html_entity_decode( $footer_input, ENT_QUOTES | ENT_XML1, $out_charset );
                if ( $out_charset == 'UTF-8' && ! has_filter( 'wwpdf_add_custom_font') ) {
                    $footer_input = self::toASCII( $footer_input );
                }
            }     
            return $footer_input;
     
        }
        
        public static function toASCII( $str ) {
            return strtr(utf8_decode($str),
            utf8_decode(
                'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
                'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
        }        
        
        
        /**
         * Check if there is a stamped file and delete it.
         * @return void
         */
        public function cleanup_file() {

            if ( isset( $this->watermarked_file ) && ! empty( $this->watermarked_file ) ) {
                unlink( $this->watermarked_file );
                $this->watermarked_file = '';
            }

        }

        /**
         * Clean up files stored locally
         */
        private function do_cleanup() {

            // redirect
            if ( get_site_option( 'woocommerce_file_download_method', 'force' ) == 'force' ) {
                add_action( 'shutdown', array( $this, 'cleanup_file' ) );

            // force or xsendfile
            } else {
                // recommend setting up a cron job to remove watermarked files periodically
                do_action( 'wwpdf_file_cleanup', $this->watermarked_file );
                
            }
    
        }
        
    }
    
}