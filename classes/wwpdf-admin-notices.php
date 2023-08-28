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
		add_action( 'admin_notices',                array( $this, 'help_a_girl_out_notice' ) );

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

		// You're welcome
		if ( defined( 'DISABLE_NAG_NOTICES' ) && 'true' === 'DISABLE_NAG_NOTICES' ) {
			return;
		}

		if ( isset( $_GET['tab'] ) && 'waterwoo-pdf' === $_GET['tab'] && empty( get_option( 'wwpdf_donate_dismiss_08-28' ) ) ) { ?>
			<div id="wwpdf-donation" class="notice notice-info is-dismissible">
				<p style="font-size: 1.25em;">
					Keep in mind this free plugin is a very basic teaser of the paid upgrade. If it works as-is for you, great! If it doesn't do enough, consider upgrading.</p>
				<p style="font-size: 1.15em;">
					<strong><a href="https://wordpress.org/plugins/waterwoo-pdf/#faq" target="_blank">The plugin FAQ is online</a></strong>. Please <strong><a href="https://wordpress.org/support/plugin/waterwoo-pdf/" target="_blank" rel="noopener nofollow">open a topic at wordpress.org if you have questions</a></strong>. (Please search the forum first to make sure your question hasn't already been answered.) I respond to inquiries about the free version of WaterMark PDF as a volunteer in my spare time. Please <a href="https://wordpress.org/support/topic/before-you-post-please-read-2/" target="_blank" rel="noopener noreferrer">follow these guidelines for the quickest help</a>, and be patient.
				</p>
				<p style="font-size: 1.15em;">
					If you find WaterMark PDF useful, and particularly if you benefit from it, please take a moment to write <strong><a href="https://wordpress.org/support/plugin/waterwoo-pdf/reviews/?filter=5" target="_blank" rel="noopener">an encouraging review</a></strong> or <strong><a href="https://www.paypal.com/paypalme/SagehenStudio" target="_blank" rel="noopener noreferrer">donate a couple dollars using PayPal</a></strong>. Your enthusiasm makes donating my time to this open-source project worthwhile.
				</p>
				<p style="font-size: 1.25em;">
					The premium/paid version of this plugin includes many more features and prioritized email support.<br><br>
					<a href="https://web.little-package.com/wordpress-plugins/waterwoo-pdf-premium/" target="_blank" rel="noopener" class="button-primary">Learn More about PDF Premium</a>
				</p>
				<button style="z-index: 100;" data-nonce="<?php esc_attr_e( wp_create_nonce('wwpdf-donate-08-28') ); ?>" type="button" class="notice-dismiss wwpdf-donation-dismiss">
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

		check_admin_referer( 'wwpdf-donate-08-28', 'nonce' );

		delete_option( 'wwpdf_donate_dismiss_02-07' );
		delete_option( 'wwpdf_donate_dismiss_05-28' );
		delete_option( 'wwpdf_donate_dismiss_06-17' );
		delete_option( 'wwpdf_donate_dismiss_11-03' );
		delete_option( 'wwpdf_donate_dismiss_02-20' );
		delete_option( 'wwpdf_donate_dismiss_03-17' );
		delete_option( 'wwpdf_donate_dismiss_05-31' );
		delete_option( 'wwpdf_donate_dismiss_06-28' );
		update_option( 'wwpdf_donate_dismiss_08-28', true, false );
		wp_die();

	}

}