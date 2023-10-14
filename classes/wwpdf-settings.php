<?php defined( 'ABSPATH' ) || exit;

class WWPDF_Settings {

	public function __construct() {

		add_filter( 'plugin_row_meta',                                      array( $this, 'add_support_links' ), 10, 2 );
		add_action( 'current_screen',                                       array( $this, 'load_screen_hooks' ) );

		add_filter( 'plugin_action_links_waterwoo-pdf/waterwoo-pdf.php',    array( $this, 'plugin_action_links' ) );

		add_filter( 'woocommerce_settings_tabs_array',                      array( $this, 'settings_tabs_array' ), 99 );
		add_action( 'woocommerce_settings_tabs_waterwoo-pdf',               array( $this, 'settings_tabs' ) );
		add_action( 'woocommerce_update_options_waterwoo-pdf',              array( $this, 'update_options' ) );

	}

	/**
	 * Add various support links to plugin page
	 *
	 * @param array $links
	 * @param string $file
	 *
	 * @return array $links
	 */
	public function add_support_links( $links, $file ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return $links;
		}
		if ( 'waterwoo-pdf' === $file ) {
			$links[] = '<a href="https://wordpress.org/extend/plugins/waterwoo-pdf/faq/" target="_blank" title="' . __( 'FAQ', 'waterwoo-pdf' ) . '" rel="noopener">' . __( 'FAQ', 'waterwoo-pdf' ) . '</a>';
			$links[] = '<a href="https://wordpress.org/support/plugin/waterwoo-pdf" target="_blank" title="' . __( 'Support', 'waterwoo-pdf' ) . '" rel="noopener">' . __( 'Support', 'waterwoo-pdf' ) . '</a>';
		}

		return $links;

	}

	/**
	 * Add CTA link on plugins page
	 *
	 * @param array $links
	 *
	 * @return array $links
	 */
	public function plugin_action_links( $links ) {

		if ( function_exists( 'is_plugin_active' ) && ! is_plugin_active( 'waterwoo-pdf-premium/waterwoo-pdf-premium.php' ) ) {
			$links[] = '<a href="https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/" target="_blank" rel="noopener">' . __( 'Upgrade for more features', 'waterwoo-pdf' ) . '</a>';
		}
		$settings = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=waterwoo-pdf' ), __( 'Go to the settings page', 'waterwoo-pdf' ), __( 'Settings', 'waterwoo-pdf' ) );
		array_unshift( $links, $settings );

		return $links;

	}

	/**
	 * Load screen hooks
	 */
	public function load_screen_hooks() {

		if ( isset( $_GET['tab'] ) && 'waterwoo-pdf' === $_GET['tab'] ) {
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
		$screen->add_help_tab( array(
			'id'      => 'waterwoo-pdf-usage',
			'title'   => __( 'About the Plugin', 'waterwoo-pdf' ),
			'content' =>
				'<h3>' . __( 'About Watermark PDF for WooCommerce', 'waterwoo-pdf' ) . '</h3>' .
				'<p>' . __( 'Protect your intellectual property! Watermark PDF for WooCommerce allows WooCommerce site administrators to apply custom watermarks to PDFs upon sale.' ) . '</p>' .
				'<p>' . __( 'Watermark PDF for WooCommerce is a plugin that adds a watermark to every page of your PDF file(s). The watermark is customizable with font face, font color, font size, placement, and text. Not only that, but since the watermark is added when the download button is clicked (either on the customer\'s order confirmation page or email), the watermark can include customer-specifc data such as the customer\'s first name, last name, and email. Your watermark is highly customizable and manipulatable.', 'waterwoo-pdf' ) . '</p>' .
				'<p>' . sprintf( __( '<a href="%s" target="_blank" rel="noopener">Consider upgrading to the Premium version</a> if you need more functionality. The premium version adds the ability to start watermarking on a specified page, password protect and copy/print/modify protect your document, a watermark overlay, HTML input, and UTF-8 and extended Unicode support for many more foreign language characters. Thanks again, and enjoy!', 'waterwoo-pdf' ), 'https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/' ) . '</p>'

		) );

		// Create help sidebar
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'waterwoo-pdf' ) . '</strong></p>' .
			'<p><a href="https://wordpress.org/plugins/waterwoo-pdf/#faq" target="_blank" rel="noopener">' . __( 'Frequently Asked Questions', 'waterwoo-pdf' ) . '</a></p>' .
			'<p><a href="https://wordpress.org/plugins/waterwoo-pdf/" target="_blank" rel="noopener">' . __( 'Project on WordPress.org', 'waterwoo-pdf' ) . '</a></p>' .
			'<p><a href="https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/" target="_blank" rel="noopener">' . __( 'Upgrade for More Control', 'waterwoo-pdf' ) . '</a></p>'
		);

	}

	/**
	 * Add a tab to the settings page
	 *
	 * @param array $tabs
	 *
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
					'id'    => 'wwpdf_options',
					'type'  => 'title',
					'title' => __( 'WaterMark PDF Options', 'waterwoo-pdf' ),
					'desc'  => __( '<strong>Note:</strong> This free watermarking plugin is rudimentary and may not work on every PDF. Test before going live!', 'waterwoo-pdf' ) .
							'<div id="wwpdf-thickbox-content"><p style="color:darkred;font-size:20px"><small>Sorry, this feature is not part of the free plugin.</small></p><p style="font-size:17px;line-height:1.4em">' .
								sprintf( __( '<a href="%s" target="_blank" rel="noopener">Purchase a Watermark PDF for WooCommerce Premium</a> license and get:', 'waterwoo-pdf' ), 'https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/' ) . '</p>'
								. '<ul style="font-size:17px;line-height:1.4em;list-style-type:circle;margin-left:2em">'
								. '<li>Full watermark page and position control'
								. '<li>Watermark <strong>opacity</strong> control'
								. '<li>PDF <strong>Password</strong> protection, encryption & permissions control'
								. '<li>Extended magic <strong>shortcodes</strong>, including order number, future dates, and copies purchased'
								. '<li>Extended characters & RTL'
								. '<li>Upload your own TTF <strong>fonts</strong>'
								. '<li>Add <strong>barcodes</strong> and QR codes to PDFs'
								. '<li>Another full watermark position'
								. '<li>Backend <strong>test watermarking</strong> of PDFs on-the-fly'
								. '<li><strong>Per-product</strong> and variable product watermarking settings'
								. '<li>Keep your original file name'
								. '<li>Support for <strong>externally hosted files (like Amazon S3)</strong>'
								. '<li>Compatibility with <strong>Free Downloads WooCommerce</strong> and <strong>WooCommerce Bulk Downloads</strong>'
								. '<li>' . sprintf(__( 'Priority email support, <a href="%s" target="_blank" rel="noopener">and more!', 'waterwoo-pdf' ), 'https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/' )
								. '</ul></div><a id="wwpdf-thickbox-link" href="#TB_inline?width=600&height=550&inlineId=wwpdf-thickbox-content" class="thickbox" style="display:none">WaterWoo ThickBox</a>',
				),

				array(
					'id'      => 'wwpdf_global',
					'type'    => 'checkbox',
					'title'   => __( 'Enable Watermarking', 'waterwoo-pdf' ),
					'desc'    => __( 'Check to enable PDF watermarking', 'waterwoo-pdf' ),
					'default' => 'no',
				),

				array(
					'id'      => 'wwpdf_files',
					'type'    => 'textarea',
					'title'   => __( 'File(s) to watermark', 'waterwoo-pdf' ),
					'desc'    => __( 'List file name(s) of PDF(s) to watermark, one per line, e.g., <code>upload.pdf</code> or <code>my_pdf.pdf</code> .<br>If left blank, Watermark PDF for WooCommerce will watermark all PDFs sold through WooCommerce.', 'waterwoo-pdf' ),
					'default' => '',
					'css'     => 'min-height: 82px;',
				),

				array( // PREMIUM
					'id'        => 'wwpdf_rtl',
					'type'      => 'checkbox',
					'title'     => __( 'Right to Left Watermarking', 'waterwoo-pdf' ),
					'desc'      => __( 'Check to switch from default left-to-right (LTR) to right-to-left (RTL), for Arabic, Hebrew, etc.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_start_page',
					'type'      => 'number',
					'title'     => __( 'Start Page', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'Provide a number to indicate the page you wish watermarking to begin on. Defaults to page 1.', 'waterwoo-pdf' ),
					'default'   => 1,
					'custom_attributes' => array(
						'min'       => 1,
						'max'       => 9999,
						'step'      => 1,
					),
					'class'     => 'wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_end_page',
					'type'      => 'number',
					'title'     => __( 'End Page', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'Provide a number to indicate the page you wish watermarking to end on. Defaults to last page. Use -1 to indicate last page.', 'waterwoo-pdf' ),
					'default'   => -1,
					'custom_attributes' => array(
						'min'       => -1,
						'max'       => 9999,
						'step'      => 1,
					),
					'class'     => 'wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_watermark_pages',
					'type'      => 'select',
					'title'     => __( 'Pages to watermark', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'Watermark every page, the first page only, the last page only, every odd page, or every even page.', 'waterwoo-pdf' ),
					'class'     => 'wwpdf-premium',
					'default'   => 'every',
					'options'   => array(
						'every'     => 'Every page',
						'first'     => 'First page only',
						'last'      => 'Last page only',
						'odd'       => 'Odd pages',
						'even'      => 'Even pages',
					),
				),

				array(
					'id'      => 'wwpdf_footer_input_premium',
					'type'    => 'textarea',
					'title'   => __( 'Custom text for footer watermark', 'waterwoo-pdf' ),
					'desc'    => __( 'Shortcodes available, all caps, in brackets: <code>[FIRSTNAME]</code> <code>[LASTNAME]</code> <code>[EMAIL]</code> <code>[PHONE]</code> <code>[DATE]</code>', 'waterwoo-pdf' ),
					'default' => __( 'Licensed to [FIRSTNAME] [LASTNAME], [EMAIL]', 'waterwoo-pdf' ),
					'class'   => 'wide-input',
					'css'     => 'min-height: 82px;',
				),

				array(
					'id'       => 'wwpdf_font_premium',
					'type'     => 'select',
					'title'    => __( 'Font face', 'waterwoo-pdf' ),
					'desc'     => __( 'Select a font for watermarks. M Sung will have limited Chinese characters, and Furat will have limited Arabic characters', 'waterwoo-pdf' ),
					'default'  => 'helvetica',
					'class'    => 'chosen_select',
					'options'  => array(
						'helvetica'           => 'Helvetica',
						'times'               => 'Times New Roman',
						'courier'             => 'Courier',
						'dejavusanscondensed' => 'Deja Vu',
						'msungstdlight'       => 'M Sung',
						'aefurat'             => 'AE Furat',
					),
					'desc_tip' => true,

				),

				array( // PREMIUM
					'id'        => 'wwpdf_margin_top_bottom',
					'type'      => 'number',
					'title'     => __( 'Top/bottom margin', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'In millimeters. Yes, metric! Defaults to 10mm', 'waterwoo-pdf' ),
					'default'   => 10,
					'custom_attributes' => array(
						'min'       => 0,
						'max'       => 500,
						'step'      => 1,
					),
					'class'     => 'wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_margin_left_right',
					'type'      => 'number',
					'title'     => __( 'Left/right margin', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'In millimeters. Defaults to 10mm', 'waterwoo-pdf' ),
					'default'   => 10,
					'custom_attributes' => array(
						'min'       => 0,
						'max'       => 500,
						'step'      => 1,
					),
					'class'     => 'wwpdf-premium',
				),

				array(
					'id'                => 'wwpdf_footer_size_premium',
					'type'              => 'number',
					'title'             => __( 'Font size', 'waterwoo-pdf' ),
					'desc'              => __( 'Provide a number (suggested 10-20) for the footer watermark font size', 'waterwoo-pdf' ),
					'default'           => '12',
					'custom_attributes' => array(
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					),
					'desc_tip'          => true,
				),

				array(
					'id'       => 'wwpdf_footer_color_premium',
					'type'     => 'color',
					'title'    => __( 'Watermark color', 'waterwoo-pdf' ),
					'desc'     => __( 'Color of the footer watermark. Default is black: <code>#000000</code>.', 'waterwoo-pdf' ),
					'default'  => '#000000',
					'desc_tip' => true,
				),
				array( // PREMIUM
					'id'        => 'wwpdf_footer_finetune_X',
					'type'      => 'number',
					'title'     => __( 'X Fine Tuning', 'waterwoo-pdf' ),
					'desc'      => __( 'Move the footer watermark left and right on the page by adjusting this number. Default 15mm (left of portrait 8.5x11" page).', 'waterwoo-pdf' ),
					'default'   => 15,
					'custom_attributes' => array(
						'min'       => 0,
						'max'       => 500,
						'step'      => 1,
					),
					'desc_tip'  => true,
					'class'     => 'wwpdf-premium',
				),
				array(
					'id'                => 'wwpdf_footer_finetune_Y_premium',
					'type'              => 'number',
					'title'             => __( 'Y Fine Tuning', 'waterwoo-pdf' ),
					'desc'              => __( 'In millimeters. Move the footer watermark up and down on the page by adjusting this number. If this number is longer/higher than the length/height of your PDF, it will default back to -10 (10 millimeters from the bottom of the page). Account for the height of your font/text!', 'waterwoo-pdf' ),
					'default'           => -10,
					'custom_attributes' => array(
						'max'  => 2000,
						'step' => 1,
					),
					'desc_tip'          => true,
				),

				array(
					'id'      => 'wwpdf_delete_checkbox',
					'type'    => 'checkbox',
					'title'   => __( 'Leave No Trace?', 'waterwoo-pdf' ),
					'desc'    => __( 'If this box is checked if/when you uninstall WaterMark PDF, all your settings will be deleted from your Wordpress database.', 'waterwoo-pdf' ),
					'default' => 'no',
				),

				array( 'id' => 'wwpdf_options', 'type' => 'sectionend' ),


				/* Begin Security Settings */
				array(
					'type'      => 'title',
					'name'      => __( 'WaterWoo Security Options', 'waterwoo-pdf' ),
					'desc'      => sprintf( __( 'The following security settings are not included in the free version of this plugin; <a href="%s" target="_blank" rel="noopener">upgrade for access</a>', 'waterwoo-pdf'), 'https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/' )
				),
				array( // PREMIUM
					'id'        => 'wwpdf_failure',
					'type'      => 'select',
					'title'     => __( 'Serve PDF if not watermarked?', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'Should the unwatermarked PDF still be served if watermarking or encryption fails?', 'waterwoo-pdf' ),
					'default'   => 'no',
					'options'   => array(
						'no'        => 'No',
						'yes'       => 'Yes',
					),
					'class'     => 'wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_encrypt',
					'type'      => 'select',
					'title'     => __( 'Encryption Level', 'waterwoo-pdf' ),
					'desc_tip'  => __( 'Choose encryption key length. Encryption is required for passwording and file protections, but can slow down and possibly stall your downloads, especially if you are watermarking files with images or embedded fonts. Note the RC4 stream cipher is not bullet-proof.', 'waterwoo-pdf' ),
					'default'   => '4',
					'options'   => array(
						'4'     => 'None',
						'0'     => 'RC4 40 bit',
						'1'     => 'RC4 128 bit',
						'2'     => 'AES 128 bit',
						'3'     => 'AES 256 bit',
					),
					'class'     => 'wwpdf-disabled',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_printing',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Low Res Printing', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to make it much more difficult for your PDF to be printed at all by the end consumer.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'fortybit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_copy',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Copying', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to prevent your end consumer from copying and pasting content from your PDF.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'fortybit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_mods',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Editing', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to prevent editing/modification of your PDF by the end consumer in Acrobat.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'fortybit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_annot',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Annotations', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to prevent the addition or modification of text annotations/comments, and filling of interactive form fields. If "editing and annotation" are both allowed, customers can create or modify interactive form fields (including signature fields).', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'fortybit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_ass',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Assembly', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to prevent insertion, rotation, or deletion of pages and creation of bookmarks or thumbnail images.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'onetwoeightbit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_printing_high',
					'type'      => 'checkbox',
					'title'     => __( 'Disable High Res Printing', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to make it much more difficult for your PDF to be printed beautifully by the end consumer.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'onetwoeightbit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_fill_forms',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Form Filling', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to prevent filling in existing interactive form fields (including signature fields).', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'onetwoeightbit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_disable_extract',
					'type'      => 'checkbox',
					'title'     => __( 'Disable Accessibility', 'waterwoo-pdf' ),
					'desc'      => __( 'Check this box to prevent extraction of text and graphics (in support of accessibility to users with disabilities or for other purposes). Some PDF readers already disable this.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'onetwoeightbit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_password',
					'type'      => 'text',
					'title'     => __( 'User Password (optional)', 'waterwoo-pdf' ),
					'desc'      => __( 'This is a password your end user will need to enter *before viewing* the PDF file. Enter <code>email</code> to set the password automagically as the user\'s checkout email address.', 'waterwoo-pdf' ),
					'default'   => '',
					'desc_tip'  => true,
					'class'     => 'fortybit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_password_owner',
					'type'      => 'text',
					'title'     => __( 'Owner Password (optional)', 'waterwoo-pdf' ),
					'desc'      => __( 'An owner password allows the end user to take control of a PDF. Leave blank and it will be set by MD5 hash automatically (it will be different for every download, and you will not know it). Enter <code>email</code> to set the password automagically as the user\'s checkout email address.', 'waterwoo-pdf' ),
					'default'   => NULL,
					'desc_tip'  => true,
					'class'     => 'fortybit wwpdf-premium',
				),
				array( // PREMIUM
					'id'        => 'wwpdf_protect_unlock',
					'type'      => 'checkbox',
					'title'     => __( 'Unlock with User Password?', 'waterwoo-pdf' ),
					'desc'      => __( 'By default PDF protections can only be removed with an owner password. Check to allow removal with a user password. USE WITH CAUTION.', 'waterwoo-pdf' ),
					'default'   => 'no',
					'class'     => 'fortybit wwpdf-premium',
				),
				array( 'type' => 'sectionend' ),

				array(
					'id'   => 'wwpdf_thanks',
					'name' => __( 'Thank you!', 'woocommerce-gift-wrapper' ),
					'type' => 'title',
					'desc' => __( 'I\'ve been working very hard on this <em>helpful and free</em> plugin for you since October 2014.', 'waterwoo-pdf' ) . '<br>'
							. sprintf( __( 'Please show the love: <a href="%s" target="_blank" rel="noopener">leave a review</a> or <a href="%s" target="_blank" rel="noopener">make a small donation</a> if you benefit from my work.', 'waterwoo-pdf' ), 'https://wordpress.org/support/plugin/waterwoo-pdf/reviews/?filter=5', 'https://paypal.me/littlepackage?country.x=US&locale.x=en_US' ) . '<br>'
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

}