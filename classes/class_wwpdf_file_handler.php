<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WWPDF_File_Handler' ) ) {

    class WWPDF_File_Handler {

        /**
         * Constructor
         */
        public function __construct() {
    
            // filter the file download path
            add_action( 'woocommerce_product_file_download_path', array( $this, 'pdf_file_download_path' ), 50, 3 );
         
        }
    
        public function pdf_file_download_path( $file_path, $product, $download_id ) {

            // PDF - watermarking - start by checking if it's even a PDF
            $file_extension = preg_replace( '/\?.*/', '', substr( strrchr( $file_path, '.' ), 1 ) );

            if ( strtolower( $file_extension ) == 'pdf' ) {

				// Is the WaterWoo plugin enabled?
                $wwpdf_enabled = get_option( 'wwpdf_enable' );
				
                if ( $wwpdf_enabled == "yes" ) {
                
                    // returns an array( $user_email, $order_key, $product_id, $download_id )
                    $download_data  = self::get_download_data( absint( $_GET['download_file'] ), sanitize_email( str_replace( ' ', '+', $_GET['email'] ) ), wc_clean( $_GET['order'] ), wc_clean( isset( $_GET['key'] ) ? preg_replace( '/\s+/', ' ', $_GET['key'] ) : '' ) );
        
 					$file_req = basename( $file_path );
					$wwpdf_files = get_option( 'wwpdf_files', '' );
					$wwpdf_file_list = array_filter( array_map( 'trim', explode( PHP_EOL, $wwpdf_files ) ) );
					
					// Watermark desired files only
					if ( in_array( $file_req, $wwpdf_file_list ) || $wwpdf_files == '' ) { 
			
                        $watermarked_file_path = self::wwpdf_setup_watermark( $download_data->user_email, $download_data->order_key, $download_data->product_id, $download_data->user_id, $download_id, $download_data->order_id, $file_path );
            
                        $watermarked_file_path = str_replace( ABSPATH, '', $watermarked_file_path );
                        return $watermarked_file_path;
                        
                    }

                }
                return $file_path;
                
            }
            return $file_path;
          
        }
    
        /**
         * Returns file_path, either altered/stamped or not
         */
        public static function wwpdf_setup_watermark( $user_email, $order_key, $product_id, $user_id, $download_id, $order_id, $file_path ) {
            global $wpdb;
        
  	        $first_name = "_billing_first_name";      
			$watermark_first_name = $wpdb->get_row( $wpdb->prepare("
				SELECT SQL_NO_CACHE meta_value
				FROM ".$wpdb->prefix."postmeta
				WHERE post_id = %s
				AND meta_key = %s
			;", $order_id, $first_name) );
			$first_name = $watermark_first_name->meta_value;

			$last_name = "_billing_last_name";      
			$watermark_last_name = $wpdb->get_row( $wpdb->prepare("
				SELECT SQL_NO_CACHE meta_value
				FROM ".$wpdb->prefix."postmeta
				WHERE post_id = %s
				AND meta_key = %s
			;", $order_id, $last_name) );
			$last_name = $watermark_last_name->meta_value;

			$phone = "_billing_phone";			
			$watermark_phone = $wpdb->get_row( $wpdb->prepare("
				SELECT SQL_NO_CACHE meta_value
				FROM ".$wpdb->prefix."postmeta
				WHERE post_id = %s
				AND meta_key = %s
				;", $order_id, $phone) );
			$phone = $watermark_phone->meta_value;

			$order_paid_date = "_paid_date";			
			$watermark_order_paid_date = $wpdb->get_row( $wpdb->prepare("
				SELECT SQL_NO_CACHE meta_value
				FROM ".$wpdb->prefix."postmeta
				WHERE post_id = %s
				AND meta_key = %s
				;", $order_id, $order_paid_date) );
			$order_paid_date_SQL = $watermark_order_paid_date->meta_value;
			// change time from SQL format: 2015-01-10 13:31:12
			$date_format = get_option( 'date_format' );
			$order_paid_date = date_i18n( $date_format, strtotime( $order_paid_date_SQL ) );
            
            $footer_input = get_option( 'wwpdf_footer_input' );
			$footer_input = preg_replace( array( '/\[FIRSTNAME\]/','/\[LASTNAME\]/','/\[EMAIL\]/','/\[PHONE\]/','/\[DATE\]/' ), array( $first_name, $last_name, $user_email, $phone, $order_paid_date ), $footer_input );
            $footer_input = iconv( 'UTF-8', 'windows-1252', html_entity_decode( $footer_input ) );
			
			$parsed_file_path = WC_Download_Handler::parse_file_path( $file_path );
			$wwpdf_file_path = str_replace( '.pdf', '', $parsed_file_path['file_path'] ) . '_' . time() . '_' . $order_key . '.pdf';      
            
            return WWPDFWatermark::apply_and_spit( $parsed_file_path['file_path'], $wwpdf_file_path, $footer_input );
    
        }

        /**
         * Fetch the download data for a specific download
         * @param  int $product_id
         * @param  string $user_email
         * @param  string $order_key
         * @param  int $download_id
         * @return object
         */
        private static function get_download_data( $product_id, $user_email, $order_key, $download_id ) {
            global $wpdb;

            $query = "SELECT SQL_NO_CACHE * FROM " . $wpdb->prefix . "woocommerce_downloadable_product_permissions ";
            $query .= "WHERE user_email = %s ";
            $query .= "AND order_key = %s ";
            $query .= "AND product_id = %s ";
            $query .= "AND download_id = %s ";

            return $wpdb->get_row( $wpdb->prepare( $query, array( $user_email, $order_key, $product_id, $download_id ) ) );
        }

    }
    
}