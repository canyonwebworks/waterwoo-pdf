<?php defined( 'ABSPATH' ) || exit;

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

		var $error = [];

		/**
		 * Autoloader constructor
		 *
		 */
		public function __construct() {

			add_action( 'admin_notices', [ $this, 'admin_notices' ] );

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
				return false;
			}
			// Crucial hook
			if ( ! class_exists( 'WC_Download_Handler' ) ) {
				$this->error[] = 2;
				return false;
			}
			// Check PHP version
			if ( ! version_compare( PHP_VERSION, WWPDF_FREE_MIN_PHP, '>=' ) ) {
				$this->error[] = 3;
			}
			// Check WordPress version
			if ( ! version_compare( get_bloginfo( 'version' ), WWPDF_FREE_MIN_WP, '>=' ) ) {
				$this->error[] = 4;
			}
			// Check WooCommerce version
			else if ( ! version_compare( WC_VERSION, WWPDF_FREE_MIN_WC, '>=' ) ) {
				$this->error[] = 5;
			}
			return true;

		}

		public function admin_notices() {

			$errors = $this->error;

			if ( empty( $errors ) || ( defined( 'DISABLE_NAG_NOTICES' ) && DISABLE_NAG_NOTICES === TRUE ) ) return;

			foreach ( $errors as $error ) {
				switch ( $error ) {
					case 1: // WC enabled
						$message = sprintf( __('<strong>Watermark PDF for WooCommerce</strong> requires WooCommerce to work. You can <a href="%s" target="_blank" rel="noopener">download WooCommerce here</a>.', 'waterwoo-pdf' ), 'https://wordpress.org/plugins/woocommerce/' );
						break;
					case 2: // Download Handler
						$message = __('The <strong>WC_Download_Handler</strong> WooCommerce class is missing! It is required for the Watermark PDF plugin to work. Perhaps your WooCommerce installation is corrupted?', 'waterwoo-pdf' );
						break;
					case 3: // PHP version
						$message = sprintf( __('<strong>Watermark PDF for WooCommerce</strong> supports PHP %s or later. Please update PHP on your server for better results.', 'waterwoo-pdf' ), WWPDF_FREE_MIN_PHP );
						break;
					case 4: // WP version
						$message = sprintf( __('<strong>Watermark PDF for WooCommerce</strong> supports WordPress version %s or later. Please update WordPress to use this plugin.', 'waterwoo-pdf' ), WWPDF_FREE_MIN_WP );
						break;
					case 5: // WC version
						$message = sprintf( __('Sorry, <strong>Watermark PDF for WooCommerce</strong> supports WooCommerce version %s or newer, for security reasons.', 'waterwoo-pdf' ), WWPDF_FREE_MIN_WC );
						break;
				}
				if ( isset( $message ) ) {
					echo '<div class="error"><p>' . wp_kses( $message, array( 'strong' => [], 'a' => array( 'href' => [], 'target' => [], 'rel' => [ 'noopener' ] ) ) ) . '</p></div>';
				}
			}

		}

	}

endif;