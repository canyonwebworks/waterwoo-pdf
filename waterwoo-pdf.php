<?php
/**
 * Plugin Name: WaterMark PDF for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/waterwoo-pdf/
 * Description: Custom watermark your PDF files upon WooCommerce customer download. Formerly known as "WaterWoo"
 * Version: 3.3.2
 * Author: Sagehen Studio
 * Author URI: https://web.little-package.com
 * Donate link: https://paypal.me/littlepackage
 * WC requires at least: 4.0
 * WC tested up to: 7.9
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

if ( ! defined( 'WWPDF_FREE_VERSION' ) ) {
	define( 'WWPDF_FREE_VERSION', '3.3.2' );
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

if ( ! class_exists( 'WaterWooPDF' ) ) :

	class WaterWooPDF {

		private static $instance = false;

		/**
		 * @return bool|WaterWooPDF
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			register_activation_hook( __FILE__, [ $this, 'activation_hook' ] );

			$this->update_db();
			$this->includes();

			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			if ( is_admin() ) {
				// Backend settings
				$this->settings = new WWPDF_Settings();
				new WWPDF_Admin_Notices();
			}
			// Only run when downloading
			if ( ! is_admin() && isset( $_GET['download_file'] )  ) {
				new WWPDF_File_Handler();
			}

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

		/**
		 * Import and convert WaterWoo settings to match paid plugin setting names
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

		/**
		 * @return void
		 */
		public function includes() {

			include_once WWPDF_PATH . 'classes/wwpdf-admin-notices.php';
			include_once WWPDF_PATH . 'classes/wwpdf-settings.php';
			include_once WWPDF_PATH . 'classes/wwpdf-file-handler.php';
			include_once WWPDF_PATH . 'classes/wwpdf-watermark.php';

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

if ( function_exists('is_plugin_active' ) && is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {
	wp_die( 'Before activating (WaterWoo) PDF WaterMark for WooCommerce, please deactivate the Premium version. You can use one or the other, but not both.', 'ERROR', [ 'back_link' => true ] );
}

function WWPDF_Free() {
	return WaterWooPDF::get_instance();
}

function wwpdf_old_php_notice() {
	echo '<div class="error"><p>' . sprintf( __( '<strong>Watermark PDF for WooCommerce</strong> supports PHP %s or later. Please update PHP on your server for better overall results.', 'waterwoo-pdf' ), WWPDF_FREE_MIN_PHP ) . '</p></div>';
}

function wwpdf_old_wp_notice() {
	echo '<div class="error"><p>' . sprintf( __( '<strong>Watermark PDF for WooCommerce</strong> supports WordPress version %s or later. Please update WordPress to use this plugin.', 'waterwoo-pdf' ), WWPDF_FREE_MIN_WP ) . '</p></div>';
}

function wwpdf_no_woo_notice() {
	echo '<div class="error"><p>' . sprintf( __( 'The <strong>Watermark PDF for WooCommerce</strong> plugin requires WooCommerce be activated. You can <a href="%s" target="_blank" rel="noopener">download WooCommerce here</a>.', 'waterwoo-pdf' ), 'https://wordpress.org/plugins/woocommerce/' ) . '</p></div>';
}

function wwpdf_broken_woo_notice() {
	echo '<div class="error"><p>' . __( 'Weird. The <strong>WC_Download_Handler</strong> WooCommerce class is missing! It is required for the Watermark PDF plugin to work. Perhaps your WooCommerce installation is corrupted?', 'waterwoo-pdf' ) . '</p></div>';
}

function wwpdf_old_woo_notice() {
	echo '<div class="error"><p>' . sprintf( __( 'Sorry, <strong>Watermark PDF for WooCommerce</strong> supports WooCommerce version %s or newer, for security reasons.', 'waterwoo-pdf' ), WWPDF_FREE_MIN_WC ) . '</p></div>';
}

/**
 * Checks for compatibility and maybe fires up the plugin
 *
 * @return void
 */
function wwpdf_plugins_loaded() {

	// Check PHP version
	if ( version_compare( PHP_VERSION, WWPDF_FREE_MIN_PHP, '<' ) ) {
		add_action( 'admin_notices', 'wwpdf_old_php_notice' );
		return;
	}
	// Check WordPress version
	if ( version_compare( get_bloginfo( 'version' ), WWPDF_FREE_MIN_WP, '<' ) ) {
		add_action( 'admin_notices', 'wwpdf_old_wp_notice' );
		return;
	}
	// Check if WooCommerce is enabled
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wwpdf_no_woo_notice' );
		return;
	}
	// Crucial WC hook for watermarking
	if ( ! class_exists( 'WC_Download_Handler' ) ) {
		add_action( 'admin_notices', 'wwpdf_broken_woo_notice' );
		return;
	}
	// Check WooCommerce version
	if ( version_compare( WC_VERSION, WWPDF_FREE_MIN_WC, '<' ) ) {
		add_action( 'admin_notices', 'wwpdf_old_woo_notice' );
		return;
	}

	load_plugin_textdomain( 'waterwoo-pdf', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	WWPDF_Free();

}
add_action( 'plugins_loaded', 'wwpdf_plugins_loaded', 1 );