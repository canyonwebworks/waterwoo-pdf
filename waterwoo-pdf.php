<?php
/*
 * Plugin Name: WaterMark PDF for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/waterwoo-pdf/
 * Description: Custom watermark your PDFs upon WooCommerce sale. Formerly known as "WaterWoo"
 * Version: 3.3
 * Author: Sagehen Studio
 * Author URI: https://web.little-package.com
 * Donate link: https://paypal.me/littlepackage
 * WC requires at least: 4.0
 * WC tested up to: 7.7
 *
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: waterwoo-pdf
 * Domain path: /lang
 *
 * Copyright 2013-2023 Sagehen Studio
 *
 *      This file is part of Watermark PDF for WooCommerce, a plugin for WordPress. If
 *      it benefits you, please support my volunteer work
 *
 *      https://paypal.me/SagehenStudio  or/and
 *
 *      leave a nice review at:
 *
 *      https://wordpress.org/support/view/plugin-reviews/waterwoo-pdf?filter=5
 *
 *      Thank you.
 *
 *      WaterMark PDF for WooCommerce is free software: You can redistribute
 *      it and/or modify it under the terms of the GNU General Public
 *      License as published by the Free Software Foundation, either
 *      version 3 of the License, or (at your option) any later version.
 *
 *      WaterMark PDF for WooCommerce is distributed in the hope that it will
 *      be useful, but WITHOUT ANY WARRANTY; without even the
 *      implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *      PURPOSE. See the GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WaterWooPDF' ) ) :

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

			$this->define_constants();
			$this->update_db();

			$this->compatible = true;

			register_activation_hook( __FILE__, [ $this, 'activation_hook' ] );

			$this->includes();

			add_action( 'plugins_loaded',           [ $this, 'plugins_loaded' ], 1 );

			add_action( 'admin_enqueue_scripts',    [ $this, 'admin_enqueue_scripts' ] );

		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'woocommerce' ), WWPDF_FREE_VERSION );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'woocommerce' ), WWPDF_FREE_VERSION );
		}

		/**
		 * Plugin activation hook
		 * Deactivate the paid version of the plugin if active
		 *
		 * @return void
		 */
		public function activation_hook() {

			if ( is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {
				deactivate_plugins( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' );
			}

		}

		private function define_constants() {

			if ( ! defined( 'WWPDF_FREE_VERSION' ) ) {
				define( 'WWPDF_FREE_VERSION', '3.3' );
			}
			if ( ! defined( 'WWPDF_FREE_MIN_PHP' ) ) {
				define( 'WWPDF_FREE_MIN_PHP', '7.2' );
			}
			if ( ! defined( 'WWPDF_FREE_MIN_WP' ) ) {
				define( 'WWPDF_FREE_MIN_WP', '5.9' );
			}
			if ( ! defined( 'WWPDF_FREE_MIN_WC' ) ) {
				define( 'WWPDF_FREE_MIN_WC', '4.0' );
			}
			if ( ! defined( 'WWPDF_PATH' ) ) {
				define( 'WWPDF_PATH', plugin_dir_path( __FILE__ ) );
			}

		}

		/**
		 * Import and convert WaterWoo settings to match paid setting names
		 *
		 * @return void
		 */
		private function update_db() {

			if ( $global = get_option( 'wwpdf_enable' ) ) {
				update_option( 'wwpdf_global', $global );
				delete_option( 'wwpdf_enable' );
			}
			if ( $footer_input = get_option( 'wwpdf_footer_input' ) ) {
				update_option( 'wwpdf_footer_input_premium', $footer_input );
				delete_option( 'wwpdf_footer_input' );
			}
			if ( $font = get_option( 'wwpdf_font' ) ) {
				update_option( 'wwpdf_font_premium', $font );
				delete_option( 'wwpdf_font' );
			}
			if ( $footer_size = get_option( 'wwpdf_footer_size' ) ) {
				update_option( 'wwpdf_footer_size_premium', $footer_size );
				delete_option( 'wwpdf_footer_size' );
			}
			if ( $footer_color = get_option( 'wwpdf_footer_color' ) ) {
				update_option( 'wwpdf_footer_color_premium', $footer_color );
				delete_option( 'wwpdf_footer_color' );
			}
			if ( $footer_y = get_option( 'wwpdf_footer_y' ) ) {
				update_option( 'wwpdf_footer_finetune_Y_premium', $footer_y );
				delete_option( 'wwpdf_footer_y' );
			}

		}

		public function includes() {

			include_once WWPDF_PATH . 'classes/wwpdf-admin-notices.php';
			include_once WWPDF_PATH . 'classes/wwpdf-compatibility.php';
			include_once WWPDF_PATH . 'classes/wwpdf-settings.php';
			include_once WWPDF_PATH . 'classes/wwpdf-file-handler.php';
			include_once WWPDF_PATH . 'classes/wwpdf-watermark.php';

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
					$this->compatible = false;
					unset( $compatibility );
				}
				if ( $this->compatible ) {
					// Backend settings
					$this->settings = new WWPDF_Settings();
				}
				new WWPDF_Admin_Notices();
			}
			// Only run when downloading & system is compatible
			if ( ! is_admin() && isset( $_GET['download_file'] ) && $this->compatible ) {
				new WWPDF_File_Handler();  
			}

		}

		/**
		 * Enqueue admin-side scripts
		 *
		 * @return void
		 */
		public function admin_enqueue_scripts( $hook ) {

			if ( 'woocommerce_page_wc-settings' !== $hook ) {
				return;
			}
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			add_thickbox();
			wp_register_script( 'wwpdf-admin', plugins_url( '/assets/js/wwpdf-admin' . $suffix . '.js', __FILE__ ), [ 'jquery' ], WWPDF_FREE_VERSION );
			wp_enqueue_style( 'wwpdf-admin-css', plugins_url( '/assets/css/wwpdf-admin' . $suffix . '.css', __FILE__ ), [], WWPDF_FREE_VERSION );

		}

	}

endif;

/**
 * Declare compatibility with HPOS
 * @todo won't be required after 8/23 or so when HPOS becomes standard
 * @return void
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

if ( function_exists('is_plugin_active') && is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {
	wp_die( 'Before activating (WaterWoo) PDF WaterMark for WooCommerce, please deactivate the Premium version. You can use one or the other, but not both.', 'ERROR', [ 'back_link' => true ] );
}

function WWPDF_Free() {
	return WaterWooPDF::get_instance();
}
WWPDF_Free();