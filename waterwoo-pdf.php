<?php
/*
 * Plugin Name: WaterWoo PDF
 * Plugin URI: https://wordpress.org/plugins/waterwoo-pdf/
 * Description: Custom watermark your PDFs upon WooCommerce sale. 
 * Version: 2.7.8
 * Author: Little Package 
 * Author URI: https://web.little-package.com
 * Donate link: https://www.buymeacoffee.com/littlepackage
 * WC requires at least: 3.0
 * WC tested up to: 4.6
 *
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * Text Domain: waterwoo-pdf
 * Domain path: /lang
 *
 * Copyright 2013-2020 Little Package 
 *		
 *     This file is part of WaterWoo PDF, a plugin for WordPress. If
 * 	   it benefits you, please "buy me a coffee" 
 *
 * 	   https://www.buymeacoffee.com/littlepackage   or/and
 *
 * 	   leave a nice review at:
 *
 * 	   https://wordpress.org/support/view/plugin-reviews/waterwoo-pdf?filter=5
 *
 *     Thank you.
 *
 *     WaterWoo PDF is free software: You can redistribute
 *     it and/or modify it under the terms of the GNU General Public
 *     License as published by the Free Software Foundation, either
 *     version 3 of the License, or (at your option) any later version.
 *     
 *     WaterWoo PDF is distributed in the hope that it will
 *     be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WaterWooPDF' ) ) {

    if ( ! defined( 'WWPDF_VERSION' ) ) {
        define( 'WWPDF_VERSION', '2.7.8' );
    }
    if ( ! defined( 'WWPDF_MIN_PHP' ) ) {
        define( 'WWPDF_MIN_PHP', '5.6' );
    }
    if ( ! defined( 'WWPDF_MIN_WP' ) ) {
        define( 'WWPDF_MIN_WP', '4.9' );
    }
    if ( ! defined( 'WWPDF_MIN_WC' ) ) {
        define( 'WWPDF_MIN_WC', '3.0' );
    }
    if ( ! defined( 'WWPDF_BASE_FILE' ) ) {
        define( 'WWPDF_BASE_FILE', plugin_basename(__FILE__) );
    }
    if ( ! defined( 'WWPDF_PATH' ) ) {
        define( 'WWPDF_PATH', plugin_dir_path( __FILE__ ) );
    }

	class WaterWooPDF {

        private static $instance = false;

        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
        }	
        
        private $compatible;
    
		/**
		 * Constructor
		 */
		public function __construct() {
			
            $this->compatible = TRUE;

            register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );

            // Autoloader
            require_once WWPDF_PATH . '/autoloader.php';
            new WWPDF_Autoloader();
            
            add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 1 );

		}

		/**
         * Cloning is forbidden.
         */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'woocommerce' ), WWPDF_VERSION );
		}

        /**
         * Unserializing instances of this class is forbidden.
         */		
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'woocommerce' ), WWPDF_VERSION );
		}

		/**
		 * Plugin activation hook
		 * Deactivate the free version of WaterWoo if active
		 *
		 * @return void
	 	 */
		public function activation_hook() {
		
	 		if ( is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {
				deactivate_plugins( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' );
			}
			
		}

		/**
		 * Load language files, check compatibility
		 *
		 * @return void
		 */
		public function plugins_loaded() {
		
            load_plugin_textdomain( 'waterwoo-pdf', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );	

            if ( is_admin() ) { 
                $compatibility = WWPDF_Compatibility::get_instance();
                if ( ! $compatibility->check_requirements() ) {
                    $this->compatible = FALSE;
                    unset( $compatibility );
                }
                if ( $this->compatible ) {
                    // Backend settings
                    $this->settings = new WWPDF_Settings();
                }             
            }
            // Only run when downloading & system is compatible
            if ( ! is_admin() && isset( $_GET['download_file'] ) && $this->compatible ) {
                new WWPDF_File_Handler();   
            }
            return;
			
		}
		
	}

}

function WWPDF_Free() {
	return WaterWooPDF::get_instance();
}
WWPDF_Free();