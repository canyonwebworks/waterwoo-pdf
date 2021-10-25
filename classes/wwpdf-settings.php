<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WWPDF_Settings' ) ) :

	class WWPDF_Settings {
	
        private static $instance = false;

        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }	

        var $error = array();
        
		public function __construct() {
		
            add_filter( 'plugin_row_meta',                              array( $this, 'add_support_links' ), 10, 2 );			
            add_action( 'current_screen',                               array( $this, 'load_screen_hooks' ) );

            add_filter( 'plugin_action_links_' . WWPDF_BASE_FILE,       array( $this, 'plugin_action_links' ) );

            add_filter( 'woocommerce_settings_tabs_array',              array( $this, 'settings_tabs_array' ), 99 );
            add_action( 'woocommerce_settings_tabs_waterwoo-pdf',       array( $this, 'settings_tabs' ) ); 
            add_action( 'woocommerce_update_options_waterwoo-pdf',      array( $this, 'update_options' ) );

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
		 * Load screen hooks
		 */
		public function load_screen_hooks() {

			if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'waterwoo-pdf' ) {
			    $screen = get_current_screen();
				add_action( 'load-' . $screen->id, array( $this, 'add_help_tabs' ) );
			}
		
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
	                '<p>' . sprintf( __( '<a href="%s" target="_blank" rel="noopener">Consider upgrading to the Premium version</a> if you need more functionality. The premium version adds the ability to start watermarking on a specified page, password protect and copy/print/modify protect your document, a watermark overlay, HTML input, and UTF-8 and extended Unicode support for many more foreign language characters. Thanks again, and enjoy!', 'waterwoo-pdf' ), 'https://web.little-package.com/downloads/waterwoo-pdf-premium/' ) . '</p>'

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
		 * Settings array
		 */
		public function settings() {
	
			return apply_filters( 'wwpdf_settings_tab', array(

                    array(
                        'id' 		=> 'wwpdf_options',
                        'type' 		=> 'title',
                        'title' 	=> __( 'WaterWoo Options', 'waterwoo-pdf' ),
                        'desc' 	    => __( '<strong>Note:</strong> This free watermarking plugin is rudimentary and may not work on every PDF. Test before going live!', 'waterwoo-pdf' ) .
                                        __( '<p><a href="https://web.little-package.com/downloads/waterwoo-pdf-premium/" target="_blank" rel="noopener">Purchase the premium version of WaterWoo PDF</a> if you want: extended <strong>shortcodes</strong>, including order number, quantity sold and future dates;<br />', 'waterwoo-pdf' ) . 
                                        __( '<strong>opacity</strong> control; custom <strong>fonts</strong>, extended characters & RTL; <strong>barcodes</strong> and QR codes; <strong>password</strong> protection & permissions control;<br />', 'waterwoo-pdf' ) . 
                                        __( 'backend <strong>test watermarking</strong> of PDFs on-the-fly; <strong>per-product</strong> and variable product watermarking; <strong>Dropbox & S3</strong> support; compatibility with <strong>Free Downloads WooCommerce</strong>; AND MORE!</p>', 'waterwoo-pdf' ), 
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
						'css'       => 'min-height: 82px;',
                    ),

                    array(
                        'id' 		=> 'wwpdf_footer_input',
                        'type'		=> 'textarea',
                        'title' 	=> __( 'Custom text for footer watermark', 'waterwoo-pdf' ),
                        'desc' 		=> __( 'Shortcodes available, all caps, in brackets: <code>[FIRSTNAME]</code> <code>[LASTNAME]</code> <code>[EMAIL]</code> <code>[PHONE]</code> <code>[DATE]</code>', 'waterwoo-pdf' ),
                        'default' 	=> __( 'Licensed to [FIRSTNAME] [LASTNAME], [EMAIL]', 'waterwoo-pdf' ),
                        'class'		=> 'wide-input',
						'css'       => 'min-height: 82px;',
                    ),
    
                    array(	
                        'id' 		=> 'wwpdf_font',
                        'type' 		=> 'select',
                        'title' 	=> __( 'Font face', 'waterwoo-pdf' ),
                        'desc' 	    => __( 'Select a font for watermarks. M Sung will have limited Chinese characters, and Furat will have limited Arabic characters', 'waterwoo-pdf' ),
                        'default'	=> 'helvetica',
                        'class'		=> 'chosen_select',
                        'options' => array(
                                'helvetica'             => 'Helvetica',
                                'times'                 => 'Times New Roman',				
                                'courier'               => 'Courier',
                                'dejavusanscondensed'   => 'Deja Vu',
                                'msungstdlight'         => 'M Sung',
                                'aefurat'               => 'Furat',
                                ),
                        'desc_tip' 	=> true,

                    ),

                    array(
                        'id' 		=> 'wwpdf_footer_size',
                        'type' 		=> 'number',
                        'title' 	=> __( 'Font size', 'waterwoo-pdf' ),
                        'desc' 		=> __( 'Provide a number (suggested 10-20) for the footer watermark font size', 'waterwoo-pdf' ),
                        'default' 	=> '12',
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
                        'desc_tip' 	=> true,
                    ),

                    array(		
                        'id' 		=> 'wwpdf_footer_y',
                        'type' 		=> 'number',
                        'title' 	=> __( 'Y Fine Tuning', 'waterwoo-pdf' ),
                        'desc' 	    => __( 'In millimeters. Move the footer watermark up and down on the page by adjusting this number. Default is 260 millimeters (bottom of letter-sized page). If this number is longer/higher than the length/height of your PDF, it will default back to -10 (10 millimeters from the bottom of the page). Account for the height of your font/text!', 'waterwoo-pdf' ),
                        'default' 	=> '260',
                        'custom_attributes' => array(
                                        'max'   => 2000,
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

                    array( 
                        'id' 				=> 'wwpdf_thanks',
                        'name' 				=> __( 'Thank you!', 'woocommerce-gift-wrapper' ), 
                        'type' 				=> 'title', 
                        'desc' 				=> __( 'I\'ve been working very hard on this <em>helpful and free</em> plugin for you since October 2014.', 'waterwoo-pdf' ) . '<br />'
                                            . sprintf(__( 'Please show the love: <a href="%s" target="_blank" rel="noopener">leave a review</a>, <a href="%s" target="_blank" rel="noopener">make a small donation</a>, or <a href="https://web.little-package.com/downloads/waterwoo-pdf-premium/" target="_blank" rel="noopener">upgrade to premium</a> if you benefit from my work.', 'waterwoo-pdf' ), 'https://wordpress.org/support/plugin/waterwoo-pdf/reviews/?filter=5', 'https://paypal.me/littlepackage' ) . '<br />'
                    ),             

                )
			);

		}

		/**
		 * Include and display the "Watermark" settings tab
		 */
		public function settings_tabs() {
			woocommerce_admin_fields( $this->settings() );		
		}

		/**
		 * Save the "Watermark" settings tab options
		 */
		public function update_options() {
		    woocommerce_update_options( $this->settings() );
		}
    

	} // End WWPDF_Settings
    
endif;
		