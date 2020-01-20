<?php

defined( 'ABSPATH' ) || exit;

// Check if already defined
if ( ! class_exists( 'WWPDF_Compatibility' ) ) :

	class WWPDF_Compatibility {
	
        private static $instance = false;

        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }	

        var $error = array();
        
		/**
		 * Autoloader constructor
		 *
		 * @param string $prefix
		 * @param string $abspath
		 */
		public function __construct() {
		
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		}	

	    /**
         * Check if system meets requirements
         *
         * @access public
         * @return bool
         */
        public function check_requirements() {

            // Check if WooCommerce is enabled
            if ( ! class_exists( 'WooCommerce' ) ) {                
                $this->error[] = 1;
                return FALSE;
            }
            // Crucial hook
			if ( ! class_exists( 'WC_Download_Handler' ) ) {
                $this->error[] = 2;
                return FALSE;
			}
            // Check PHP version
            if ( ! version_compare( PHP_VERSION, WWPDF_MIN_PHP, '>=' ) ) {
                $this->error[] = 3;
            }
            // Check WordPress version
            if ( ! version_compare( get_bloginfo( 'version' ), WWPDF_MIN_WP, '>=' ) ) {
                $this->error[] = 4;
            }
            // Check WooCommerce version
            else if ( ! version_compare( WC_VERSION, WWPDF_MIN_WC, '>=' ) ) {
                $this->error[] = 5;
            }
            return TRUE;

        }		
        
        public function admin_notices(  ) {
        
            $error = array();
            $error = $this->error;
        
            if ( empty( $error ) || ( defined( 'DISABLE_NAG_NOTICES' ) && DISABLE_NAG_NOTICES === TRUE ) ) return; 

            foreach ( $error as $error ) {
                switch ($error) {
                    case 1: // WC enabled
                        $message = '<div class="error"><p>' . sprintf(__('<strong>WaterWoo PDF</strong> requires WooCommerce to work. You can download WooCommerce %s.', 'waterwoo-pdf-premium'), '<a href="https://wordpress.org/plugins/woocommerce/" rel="noopener">' . __('here', 'waterwoo-pdf-premium') . '</a>') . '</p></div>';
                        break;
                    case 2: // Download Handler
                        $message = '<div class="error"><p>' . __('The <strong>WC_Download_Handler</strong> WooCommerce class is missing! It is required for WaterWoo to work. Perhaps your WooCommerce installation is corrupted?', 'waterwoo-pdf-premium' ) . '</p></div>';
                        break;
                    case 3: // PHP version
                        $message = '<div class="error"><p>' . sprintf(__('<strong>WaterWoo PDF</strong> supports PHP %s or later. Please update PHP on your server for better results.', 'waterwoo-pdf-premium'), WWPDF_MIN_PHP) . '</p></div>';
                        break;
                    case 4: // WP version
                        $message = '<div class="error"><p>' . sprintf(__('<strong>WaterWoo PDF</strong> supports WordPress version %s or later. Please update WordPress to use this plugin.', 'waterwoo-pdf-premium'), WWPDF_MIN_WP) . '</p></div>';
                        break;
                    case 5: // WC version
                        $message = '<div class="error"><p>' . sprintf(__('Sorry, <strong>WaterWoo PDF</strong> supports WooCommerce version %s or newer, for security reasons.', 'waterwoo-pdf-premium'), WWPDF_MIN_WC ) . '</p></div>';
                        break;
                }
                echo $message;
            }
        
        }

	} // End WWPDF_Compatibility
    
endif;
		