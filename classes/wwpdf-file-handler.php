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
            add_filter( 'woocommerce_product_file_download_path', array( $this, 'pdf_file_download_path' ), 50, 3 );

		    if ( apply_filters( 'wwpdf_do_cleanup', true ) ) {
                $this->do_cleanup();
            }

        }
    
        public function pdf_file_download_path( $file_path, $product, $download_id ) {

            // Is the WaterWoo plugin enabled?
            if ( "no" == get_option( 'wwpdf_enable', 'no' ) ) return $file_path;
            // PDF - watermarking - start by checking if it's even a PDF
            $file_extension = preg_replace( '/\?.*/', '', substr( strrchr( $file_path, '.' ), 1 ) );
            if ( 'pdf' !== strtolower( $file_extension ) ) return $file_path;
            // order ID is post ID for backend (admin) watermarking
            $order_id = is_admin() ? $_GET['post'] : wc_get_order_id_by_order_key( wc_clean( wp_unslash( $_GET['order'] ) ) );
            $order_key = wc_clean( isset( $_GET['key'] ) );
            // get order email address, if available
            if ( isset( $_GET['email'] ) ) {
                $email = wp_unslash( $_GET['email'] );
            } else {
                // Get email address from order to verify hash.
                $order = wc_get_order( $order_id );
                $email = is_a( $order, 'WC_Order' ) ? $order->get_billing_email() : '';
            }
            // get requested PDF and compare to admin designated PDFs
            $file_req           = basename( $file_path );
            $wwpdf_files        = get_option( 'wwpdf_files', '' );
            $wwpdf_file_list    = array_filter( array_map( 'trim', explode( PHP_EOL, $wwpdf_files ) ) );
            // Watermark desired files only
            if ( in_array( $file_req, $wwpdf_file_list ) || $wwpdf_files == '' ) { 
            
                // set up watermark content according to admin settings
                $product_id = $product->get_id();
                $footer_input = $this->wwpdf_setup_watermark( $email, $product_id, $order_id );            
                // adjust output file name/path
                $parsed_file_path = WC_Download_Handler::parse_file_path( $file_path );
                $wwpdf_file_path = str_replace( '.pdf', '', $parsed_file_path['file_path'] ) . '_' . time() . '_' . $order_key . '.pdf';      
                // attempt to watermark using tcpdi/tcpdf
                try {
                    $this->watermarked_file = WWPDF_Watermark::apply_and_spit( $parsed_file_path['file_path'], $wwpdf_file_path, $footer_input );
                } catch ( Exception $e ) {
                    _e( 'Unable to watermark this file for download. Please notify site administrator.', 'waterwoo-pdf' );
                    echo $e->getMessage();
                    return;
                }
                // send watermarked file back to Woo
                $watermarked_file = str_replace( ABSPATH, '', $this->watermarked_file );
                return $watermarked_file;
                
            }
            return $file_path;
          
        }
    
        /**
         * Returns file_path, either altered/stamped or not
         */
        public static function wwpdf_setup_watermark( $email, $product_id, $order_id ) {
        
            $order = wc_get_order( $order_id );
            $order_data = $order->get_data();

            $first_name         = $order_data['billing']['first_name'];
            $last_name          = $order_data['billing']['last_name'];
            $phone              = $order_data['billing']['phone'];

            $order_paid_date    = $order_data['date_created']->date('Y-m-d H:i:s');
            $date_format        = get_option( 'date_format' );
            $order_paid_date    = date_i18n( $date_format, strtotime( $order_paid_date ) );
            
            $footer_input = get_option( 'wwpdf_footer_input', 'Licensed to [FIRSTNAME] [LASTNAME], [EMAIL]' );
			$footer_input = preg_replace( array( '/\[FIRSTNAME\]/','/\[LASTNAME\]/','/\[EMAIL\]/','/\[PHONE\]/','/\[DATE\]/' ), array( $first_name, $last_name, $email, $phone, $order_paid_date ), $footer_input );

            $footer_input = apply_filters( 'wwpdf_filter_footer', $footer_input, $order_id, $product_id );

            // text encoding
            if ( has_filter( 'wwpdf_font_decode' ) ) {
                $footer_input = apply_filters( 'wwpdf_font_decode', $footer_input );
            } else {
                $out_charset = 'UTF-8';
                $out_charset = apply_filters( 'wwpdf_out_charset', $out_charset );
                $footer_input = html_entity_decode( $footer_input, ENT_QUOTES | ENT_XML1, $out_charset );
            }
                              
            return $footer_input;
     
        }
                
        /**
         * Check if there is a stamped file and delete it.
         *
         * @return void
         */
        public function cleanup_file() {

            // this only happens if download type is set to FORCE
            if ( isset( $this->watermarked_file ) && ! empty( $this->watermarked_file ) ) {
                unlink( $this->watermarked_file );
                $this->watermarked_file = '';
            }

        }

        /**
         * Clean up files stored locally
         */
        private function do_cleanup() {

            // force
            if ( get_option( 'woocommerce_file_download_method', 'force' ) == 'force' ) {
                add_action( 'shutdown', array( $this, 'cleanup_file' ) );
            // redirect or xsendfile
            } else {
                // recommend setting up a cron job to remove watermarked files periodically,
                // but adding a hook here just in case you have other plans
                do_action( 'wwpdf_file_cleanup', $this->watermarked_file );
            }
    
        }
        
    }
    
}