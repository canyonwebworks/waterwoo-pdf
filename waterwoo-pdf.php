<?php
/*
 * Plugin Name: WaterWoo PDF
 * Plugin URI: https://wordpress.org/plugins/waterwoo-pdf/
 * Description: Custom watermark your PDFs upon WooCommerce sale. 
 * Version: 2.4.6
 * Author: Little Package 
 * Author URI: https://web.little-package.com
 * Donate link: https://www.paypal.me/littlepackage
 * WC requires at least: 2.6
 * WC tested up to: 3.8.1
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
 * 	   https://www.paypal.me/littlepackage   or/and
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
if ( ! function_exists( 'is_woocommerce_active' ) || ! function_exists( 'get_woocommerce_version' ) ) require_once( 'woo-includes/woo-functions.php' );
if ( ! is_woocommerce_active() ) return;

if ( ! class_exists( 'WaterWooPDF' ) ) {

	class WaterWooPDF {

		public $version = '2.4.6';

		/**
		 * Init
		 */
		public static function init() {
			$wwpdf = new self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			
			define( 'WWPDF_BASE_URL', trailingslashit( plugins_url( 'waterwoo-pdf' ) ) );
			define( 'WWPDF_PATH', plugin_dir_path( __FILE__ ) );

			$this->autoload_classes();
			$this->load_hooks();

            add_filter( 'woocommerce_settings_tabs_array',              array( $this, 'settings_tabs_array' ), 99  );
            add_action( 'woocommerce_settings_tabs_waterwoo-pdf',       array( $this, 'settings_tabs' ) ); 
            add_action( 'woocommerce_update_options_waterwoo-pdf',      array( $this, 'update_options' ) );
		
		}
		
		/**
		 * All classes
		 */
		private function plugin_classes() {

			return array(
				'wwpdf_watermark'       => WWPDF_PATH . 'classes/class-wwpdf-watermark.php',
				'wwpdf_file_handler'    => WWPDF_PATH . 'classes/class-wwpdf-file-handler.php',
			);

		}

		/**
		 * Autoload classes to reduce memory consumption
		 */
		public function autoload( $class ) {

			$classes = $this->plugin_classes();
			$class_name = strtolower( $class );
			if ( isset( $classes[$class_name] ) && is_readable( $classes[$class_name] ) ) {
				require_once( $classes[$class_name] );
			}

		}
		
		/**
		 * Load required classes
		 *
         * @return null
         */
		private function autoload_classes() {

			$autoload_is_disabled = defined( 'WWPDF_AUTOLOAD_CLASSES' ) && WWPDF_AUTOLOAD_CLASSES === false;

			if ( function_exists( "spl_autoload_register" ) && ! ( $autoload_is_disabled ) ) {
			   // >= PHP 5.2 - Use auto loading
				if ( function_exists( "__autoload" ) ) {
					spl_autoload_register( "__autoload" ); 
				}
				spl_autoload_register( array( $this, 'autoload' ) );
			} else {
				// < PHP5.2 - Require all classes
				foreach ( $this->plugin_classes() as $id => $path ) {
					if ( is_readable( $path ) && ! class_exists( $id ) ) {
						require_once( $path );
					}
				}
			}

		}

		/**
		 * Remove woocommerce_download_product action hook and replace
         *
         * @return null
         */
		public function load_hooks() {

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_init', array( $this, 'nag_ignore' ) );

			if ( class_exists( 'WC_Download_Handler' ) ) {
                if ( 	
                    ( isset( $_GET['download_file'] ) && isset( $_GET['order'] ) && isset( $_GET['email'] ) )
					||
                    ( isset( $_GET['download_file'], $_GET['order'] ) && ( isset( $_GET['email'] ) || isset( $_GET['uid'] ) ) )
                ) {					
					if ( version_compare( get_woocommerce_version(), '2.4', '>=') ) { // 2.4 and newer
						new WWPDF_File_Handler();
					} else if ( version_compare( get_woocommerce_version(), '2.4', '<') ) {
                        if ( is_admin() ) {	
				            add_action( 'admin_notices', array( $this, 'version_notice' ) );
                        } 
                        return;
					}
					
				}

			}

		}

		/**
		 * Load the localization 
		 */
		public function load_localization() {

			load_plugin_textdomain( 'waterwoo-pdf', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );	

		}
	
		/**
		 * Load the admin hooks
		 */
		public function admin_init() {

            $this->load_localization();

            add_filter( 'plugin_row_meta',                              array( $this, 'add_support_links' ), 10, 2 );			
            add_action( 'current_screen',                               array( $this, 'load_screen_hooks' ) );

            $plugin = plugin_basename( __FILE__ );

            add_filter( "plugin_action_links_{$plugin}",                array( $this, 'plugin_action_links' ) );

		}	

		/**
		 * Add various support links to plugin page
		 *
         * @param array $links 
         * @param string $file
         * @return array $links
         */
		public function add_support_links( $links, $file ) {

			if ( ! current_user_can( 'install_plugins' ) ) {
				return $links;
			}
			if ( $file == 'waterwoo-pdf' ) {
				$links[] = '<a href="https://wordpress.org/extend/plugins/waterwoo-pdf/faq/" target="_blank" title="' . __( 'FAQ', 'waterwoo-pdf' ) . '" rel="noopener">' . __( 'FAQ', 'waterwoo-pdf' ) . '</a>';
				$links[] = '<a href="https://wordpress.org/support/plugin/waterwoo-pdf" target="_blank" title="' . __( 'Support', 'waterwoo-pdf' ) . '" rel="noopener">' . __( 'Support', 'waterwoo-pdf' ) . '</a>';
			}
			return $links;

		}

		/**
		 * Add CTA link on plugins page
		 *
         * @param array $links 
         * @return array $links
		 */
		public function plugin_action_links( $links ) {

			if ( function_exists( 'is_plugin_active' ) && ! is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {
				$links[] = '<a href="https://web.little-package.com/downloads/waterwoo-pdf-premium/" target="_blank" rel="noopener">' . __( 'Upgrade to Premium', 'waterwoo-pdf' ) . '</a>';
			}
			$settings = sprintf( '<a href="%s" title="%s">%s</a>' , admin_url( 'admin.php?page=wc-settings&tab=waterwoo-pdf' ) , __( 'Go to the settings page', 'waterwoo-pdf' ) , __( 'Settings', 'waterwoo-pdf' ) );
			array_unshift( $links, $settings );
			return $links;

		}
		
		/**
		 * Add settings link to plugin list page
		 *
         * @param array $links 
         * @return array $links
		*/
		public function add_settings_link( $links ) {

			$settings = sprintf( '<a href="%s" title="%s">%s</a>' , admin_url( 'admin.php?page=wc-settings&tab=waterwoo-pdf' ) , __( 'Go to the settings page', 'waterwoo-pdf' ) , __( 'Settings', 'waterwoo-pdf' ) );
			array_unshift( $links, $settings );
			return $links;

		}

		/**
		 * Check if we are on settings page
		 *
         * @return bool
		 */
		public function is_settings_page() {

			if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['tab'] == 'waterwoo-pdf' ) {
				return true;
			} else {
				return false;
			}

		}

		/**
		 * Load screen hooks
		*/
		public function load_screen_hooks() {

			$screen = get_current_screen();
			if ( $this->is_settings_page() ) {

				add_action( 'admin_print_scripts', array( $this, 'add_scripts' ) );
				add_action( 'load-' . $screen->id, array( $this, 'add_help_tabs' ) );
				add_action( 'admin_notices', array( $this, 'wwpdf_admin_notices' ) );

			}
		
		}

		/**
		 * Add the scripts	
		 */
		public function add_scripts() {

			wp_enqueue_style( 'wwpdf_admin_styles', WWPDF_BASE_URL . 'assets/css/admin.css', true );			
			wp_enqueue_script( 'wwpdf-scripts', WWPDF_BASE_URL . 'assets/js/wwpdf_admin.js', 'jquery', $this->version );

		}

		/**
		 * Add the help tabs	
		 */
		public function add_help_tabs() {

			// Check current admin screen
			$screen = get_current_screen();

			// Remove all existing tabs
			$screen->remove_help_tabs();
			
			// Create arrays with help tab titles
			$screen->add_help_tab(array(
				'id' => 'waterwoo-pdf-usage',
				'title' => __( 'About the Plugin', 'waterwoo-pdf' ),
				'content' => 
					'<h3>' . __( 'About WaterWoo PDF', 'waterwoo-pdf' ) . '</h3>' .
					'<p>' . __( 'Protect your intellectual property! WaterWoo PDF allows WooCommerce site administrators to apply custom watermarks to PDFs upon sale.' ). '</p>' . 
					'<p>' . __( 'WaterWoo PDF is a plugin that adds a watermark to every page of your PDF file(s). The watermark is customizable with font face, font color, font size, placement, and text. Not only that, but since the watermark is added when the download button is clicked (either on the customer\'s order confirmation page or email), the watermark can include customer-specifc data such as the customer\'s first name, last name, and email. Your watermark is highly customizable and manipulatable.', 'waterwoo-pdf' ) . '</p>' .
	                '<p>' . __( '<a href="https://web.little-package.com/downloads/waterwoo-pdf-premium/" target="_blank" rel="noopener">Consider upgrading to the Premium version</a> if you need more functionality. The premium version adds the ability to start watermarking on a specified page, password protect and copy/print/modify protect your document, a watermark overlay, HTML input, and UTF-8 and extended Unicode support for many more foreign language characters. Thanks again, and enjoy!', 'waterwoo-pdf' ) . '</p>'

				) );

			// Create help sidebar
			$screen->set_help_sidebar(
				'<p><strong>' . __( 'For more information:', 'waterwoo-pdf' ) . '</strong></p>'.
				'<p><a href="https://wordpress.org/extend/plugins/waterwoo-pdf/faq/" target="_blank" rel="noopener">' . __( 'Frequently Asked Questions', 'waterwoo-pdf' ) . '</a></p>' .
				'<p><a href="https://wordpress.org/extend/plugins/waterwoo-pdf/" target="_blank" rel="noopener">' . __( 'Project on WordPress.org', 'waterwoo-pdf' ) . '</a></p>' .
				'<p><a href="https://web.little-package.com/downloads/waterwoo-pdf-premium/" target="_blank" rel="noopener">' . __( 'Upgrade to Premium', 'waterwoo-pdf' ) . '</a></p>'
			);

		}

		/**
		 * Upgrade Call To Action (CTA)
		 */
		public function premium_cta() {

			if ( function_exists( 'is_plugin_active' ) && ! is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {

				$link = 'https://web.little-package.com/downloads/waterwoo-pdf-premium/';
				$get_premium = "<div style='display: none;' id='wwpdf-screen-options-link-wrap'><div id='contextual-help-link-wrap'><a target='_blank' class='button show-settings' href='{$link}' rel='noopener'>" .
					__( 'Upgrade to WaterWoo PDF Premium', 'waterwoo-pdf' ) .
					"</a></div></div>";
				echo $get_premium;

			}

		}

		/**
		 * Add a tab to the settings page	
		 *
         * @param array $tabs 
         * @return array $tabs
		 */
		public function settings_tabs_array( $tabs ) {

			$tabs['waterwoo-pdf'] = __( 'Watermark', 'waterwoo-pdf' );
			return $tabs;

		}

		/**
		 * Display a notice that WC >2.4 is required
		 */ 		
		public function version_notice() {
		
            $screen = get_current_screen();
            if ( $screen->id === 'woocommerce_page_wc-settings' || $screen->id === 'edit-product' || $screen->id === 'woocommerce_page_wc-status' || $screen->id === 'edit-shop_order' ) { ?>
        
                <div id="message" class="notice notice-error is-dismissible">
                    <p><?php echo wp_kses_post( __( 'Sorry, WaterWoo PDF requires you use WooCommerce version 2.4 or newer, mostly for security reasons.', 'waterwoo-pdf' ) ); ?></p>
                </div>
        
            <?php }
    
		}
		
		/**
		 * Display a notice that can be dismissed
		 */ 
		public function wwpdf_admin_notices() {
			global $current_user, $pagenow;
			$user_id = $current_user->ID;
			/* Check that the user hasn't already clicked to ignore the message */
			if ( ! get_user_meta($user_id, 'wwpdf_ignore_notice13') ) {
				$screen = get_current_screen();
				if ( $pagenow == 'admin.php' && $screen->id == 'woocommerce_page_wc-settings' ) {
					echo '<div class="updated"><p>'; 
                    echo sprintf( __( '<p>If you like WaterWoo PDF, please consider <a href="%1$s" target="_blank" rel="noopener">sending a donation</a> so I can continue improving it. No funds? Maybe <a href="%2$s" target="_blank" rel="noopener">write a kind review</a>.</p><p class="alignright"><a href="%3$s" target="_blank">Hide This Notice</a></p>' ),
                        'https://wordpress.org/support/view/plugin-reviews/waterwoo-pdf?filter=5', 
                        'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PB2CFX8H4V49L', 
                        '?page=wc-settings&tab=waterwoo-pdf&nag_ignore=0' );
					echo "</p></div>";
				}

			}

		}

		/**
		 * Display a notice that can be dismissed
		 */ 
		public function nag_ignore() {
			global $current_user;
			$user_id = $current_user->ID;
			/* If user clicks to ignore the notice, add that to their user meta */
			if ( isset($_GET['nag_ignore']) && '0' == $_GET['nag_ignore'] ) {
				delete_user_meta( $user_id, 'wwpdf_ignore_notice', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice2', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice3', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice4', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice5', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice6', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice7', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice8', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice9', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice10', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice11', 'true', true );
				delete_user_meta( $user_id, 'wwpdf_ignore_notice12', 'true', true );
				add_user_meta( $user_id, 'wwpdf_ignore_notice13', 'true', true );
			}
		}

		/**
		 * Settings array
		 */
		public function settings() {
	
			return apply_filters( 'wwpdf_settings_tab', array(

                    array(
                        'id' 		=> 'wwpdf_options',
                        'type' 		=> 'title',
                        'title' 	=> __( 'WaterWoo Options', 'waterwoo-pdf' ),
                        'desc' 	    => __( 'Note: This free watermarking plugin is rudimentary. You need the <a href="https://web.little-package.com/downloads/waterwoo-pdf-premium/" target="_blank" rel="noopener">Premium version of WaterWoo</a> if:<ol><li>You want extended shortcode function, opacity control, custom fonts & extended characters, RTL, password protection, per-product and variable product watermarking, and much more.</li></ol>', 'waterwoo-pdf' ), 
                    ),
    
                    array(	
                        'id' 		=> 'wwpdf_enable',
                        'type' 		=> 'checkbox', 
                        'title' 	=> __( 'Enable Watermarking', 'waterwoo-pdf' ), 
                        'desc' 	    => __( 'Check to enable PDF watermarking', 'waterwoo-pdf' ), 
                        'default' 	=> 'no',
                    ),

                    array(
                        'id' 		=> 'wwpdf_files',
                        'type' 		=> 'textarea', 
                        'title' 	=> __( 'File(s) to watermark', 'waterwoo-pdf' ), 
                        'desc' 		=> __( 'List file name(s) of PDF(s) to watermark, one per line, e.g., <code>upload.pdf</code> or <code>my_pdf.pdf</code> .<br />If left blank, WaterWoo PDF will watermark all PDFs sold through WooCommerce.', 'waterwoo-pdf' ), 
                        'default' 	=>  '',
                        'css' 		=> 'min-width:600px',
                    ),

                    array(
                        'id' 		=> 'wwpdf_footer_input',
                        'type'		=> 'textarea',
                        'title' 	=> __( 'Custom text for footer watermark', 'waterwoo-pdf' ),
                        'desc' 		=> __( 'Shortcodes available, all caps, in brackets: <code>[FIRSTNAME]</code> <code>[LASTNAME]</code> <code>[EMAIL]</code> <code>[PHONE]</code> <code>[DATE]</code>', 'waterwoo-pdf' ),
                        'default' 	=> __( 'Licensed to [FIRSTNAME] [LASTNAME], [EMAIL]', 'waterwoo-pdf' ),
                        'css' 		=> 'min-width:600px',
                    ),
    
                    array(	
                        'id' 		=> 'wwpdf_font',
                        'type' 		=> 'select',
                        'title' 	=> __( 'Font face', 'waterwoo-pdf' ),
                        'desc' 	    => __( 'Select a font for watermarks. M Sung will have limited Chinese characters, and Furat will have limited Arabic characters', 'waterwoo-pdf' ),
                        'default'	=> 'helvetica',
                        'css' 		=> 'min-width:300px',
                        'class'		=> 'chosen_select',
                        'options' => array(
                                'helvetica'     => 'Helvetica',
                                'times'         => 'Times New Roman',				
                                'courier'       => 'Courier',
                                'msungstdlight' => 'M Sung',
                                'aefurat'       => 'Furat',
                                ),
                        'desc_tip' 	=> true,

                    ),

                    array(
                        'id' 		=> 'wwpdf_footer_size',
                        'type' 		=> 'number',
                        'title' 	=> __( 'Font size', 'waterwoo-pdf' ),
                        'desc' 		=> __( 'Provide a number (suggested 10-20) for the footer watermark font size', 'waterwoo-pdf' ),
                        'default' 	=> '12',
                        'css' 		=> 'width:60px;',
                        'custom_attributes' => array(
                                        'min'   => 1,
                                        'max'   => 200,
                                        'step' 	=> 1,
                                    ),
                        'desc_tip' 	=> true,
                    ),

                    array(
                        'id' 		=> 'wwpdf_footer_color',
                        'type' 		=> 'color',
                        'title' 	=> __( 'Watermark color', 'waterwoo-pdf' ),
                        'desc' 		=> __( 'Color of the footer watermark. Default is black: <code>#000000</code>.', 'waterwoo-pdf' ),
                        'default'	=> '#000000',
                        'css' 		=> 'width:6em;',
                        'desc_tip' 	=> true,
                    ),

                    array(		
                        'id' 		=> 'wwpdf_footer_y',
                        'type' 		=> 'number',
                        'title' 	=> __( 'Y Fine Tuning', 'waterwoo-pdf' ),
                        'desc' 	    => __( 'Move the footer watermark up and down on the page by adjusting this number. Default is 260 millimeters (bottom of letter-sized page).', 'waterwoo-pdf' ),
                        'default' 	=> '260',
                        'css' 		=> 'width:60px;',
                        'custom_attributes' => array(
                                        'min' 	=> 0,
                                        'max'   => 1000,
                                        'step' 	=> 1,
                                    ),
                        'desc_tip' 	=> true,
                    ),
                
                    array(	
                        'id' 		=> 'wwpdf_delete_checkbox',
                        'type' 		=> 'checkbox', 
                        'title' 	=> __( 'Leave No Trace?', 'waterwoo-pdf' ), 
                        'desc' 	    => __( 'If this box is checked if/when you uninstall WaterWoo, all your WaterWoo settings will be deleted from your Wordpress database.', 'waterwoo-pdf' ), 
                        'default' 	=> 'no',
                    ),
                
                    array( 'id' => 'wwpdf_options', 'type' => 'sectionend' ),
                
                )
			);

		}

		/**
		 * Include and display the "Watermark" settings tab
		 */
		public function settings_tabs() {

			$this->premium_cta();
			
			woocommerce_admin_fields( $this->settings() );		

		}

		/**
		 * Save the "Watermark" settings tab options
		 */
		public function update_options() {

		    woocommerce_update_options( $this->settings() );

		}

	}

}

add_action( 'plugins_loaded', array( 'WaterWooPDF', 'init' ), 10 );