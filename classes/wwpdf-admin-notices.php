<?php

defined( 'ABSPATH' ) || exit;

class WWPDF_Admin_Notices {

	/**
	 * Constructor
	 */
	public function __construct() {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		// Help cure open source burnout!
		add_action( 'admin_notices', array( $this, 'help_a_girl_out_notice' ) );

		// Ajax dismiss "help a girl out" notice
		add_action( 'wp_ajax_wwpdf_dismiss_notice', array( $this, 'dismiss_notice' ) );


	}

	/**
	 * Maybe bother users with important information
	 *
	 * @return void
	 */
	public function help_a_girl_out_notice() {

		$screen = get_current_screen();
		if ( 'woocommerce_page_wc-settings' !== $screen->id && 'woocommerce_page_wc-status' !== $screen->id ) {
			return;
		}

		if ( defined( 'DISABLE_NAG_NOTICES' ) && 'true' === 'DISABLE_NAG_NOTICES' ) {
			return;
		}

		if ( isset( $_GET['tab'] ) && 'waterwoo-pdf' === $_GET['tab'] && empty( get_option( 'wwpdf_donate_dismiss_06-17' ) ) ) { ?>
			<div class="updated settings-error notice is-dismissible wwpdf-donation">
				<p style="font-size: 1.15em;">
					If you have questions about the free version of <strong>WaterMark PDF</strong> (FKA WaterWoo), please <a href="https://wordpress.org/support/plugin/waterwoo-pdf/" target="_blank" rel="noopener nofollow">open a topic at wordpress.org</a> after first searching the forum to make sure your question hasn't already been answered. I respond to inquiries about the free version of WaterMark PDF as a volunteer in my spare time. Please <a href="https://wordpress.org/support/topic/before-you-post-please-read-2/" target="_blank" rel="noopener noreferrer">follow these guidelines for the quickest help</a>, and be patient.
				</p>
				<p style="font-size: 1.15em;">
					If you find <strong>WaterMark PDF</strong> useful, and particularly if you benefit from it, please take a moment to write <a href="https://wordpress.org/support/plugin/waterwoo-pdf/reviews/?filter=5" target="_blank" rel="noopener">an encouraging review</a>, donate a couple dollars using the PayPal link below, or maybe even <a href="https://web.little-package.com/downloads/waterwoo-pdf-premium" target="_blank" rel="noopener">upgrade to the premium version from Sagehen Studio (FKA Little Package) for MANY more features and prioritized email support</a>. Your enthusiasm makes donating my time to this open-source project worthwhile.
				</p>
				<p style="font-size: 1.25em;text-align:center">
					* * * PayPal: <a href="https://www.paypal.com/paypalme/SagehenStudio" target="_blank" rel="noopener noreferrer">paypal.me/SagehenStudio</a> * * *
				</p>
				<button style="z-index: 100;" data-security="<?php echo esc_attr( wp_create_nonce('wwpdf-donate-06-17') ); ?>" type="button" class="notice-dismiss wwpdf-donation-dismiss">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</div>
			<?php wp_enqueue_script( 'wwpdf-admin' );
		}

	}

	/**
	 * AJAX-called function to delete nag
	 *
	 * @return void
	 */
	public function dismiss_notice() {

		check_admin_referer( 'wwpdf-donate-06-17', 'security' );
		// Newest at top

		delete_option( 'wwpdf_donate_dismiss_02-07' );
		delete_option( 'wwpdf_donate_dismiss_05-28' );
		update_option( 'wwpdf_donate_dismiss_06-17', true, false );

	}

}