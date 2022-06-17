<?php
/*
 * Plugin Name: WaterMark PDF for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/waterwoo-pdf/
 * Description: Custom watermark your PDFs upon WooCommerce sale. Formerly known as "WaterWoo"
 * Version: 3.1.2
 * Author: Sagehen Studio
 * Author URI: https://web.little-package.com
 * Donate link: https://paypal.me/littlepackage
 * WC requires at least: 4.0
 * WC tested up to: 6.6
 *
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: waterwoo-pdf
 * Domain path: /lang
 *
 * Copyright 2013-2022 Little Package
 *
 *      This file is part of Watermark PDF for WooCommerce, a plugin for WordPress. If
 *      it benefits you, please "buy me a coffee"
 *
 *      https://paypal.me/littlepackage   or/and
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
 *      WaterMark PDF for WooCOmmerce is distributed in the hope that it will
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

			$this->compatible = TRUE;

			register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );

			$this->includes();

			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 1 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

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
				define( 'WWPDF_FREE_VERSION', '3.1.2' );
			}
			if ( ! defined( 'WWPDF_FREE_MIN_PHP' ) ) {
				define( 'WWPDF_FREE_MIN_PHP', '7.2' );
			}
			if ( ! defined( 'WWPDF_FREE_MIN_WP' ) ) {
				define( 'WWPDF_FREE_MIN_WP', '5.6' );
			}
			if ( ! defined( 'WWPDF_FREE_MIN_WC' ) ) {
				define( 'WWPDF_FREE_MIN_WC', '4.0' );
			}
			if ( ! defined( 'WWPDF_PATH' ) ) {
				define( 'WWPDF_PATH', plugin_dir_path( __FILE__ ) );
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
					$this->compatible = FALSE;
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
		public function admin_enqueue_scripts() {

			wp_register_script( 'wwpdf-admin', plugins_url( '/assets/js/wwpdf-admin.min.js', __FILE__ ), array('jquery'), WWPDF_FREE_VERSION );

		}

	}

endif;

function WWPDF_Free() {
	return WaterWooPDF::get_instance();
}
WWPDF_Free();