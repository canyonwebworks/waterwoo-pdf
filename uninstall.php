<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { // If uninstall not called from WordPress exit
	exit();
}

/**
 * Manages WaterWoo PDF Premium uninstallation
 * The goal is to remove ALL Waterwoo related data in db
 *
 * @since 2.2
 */
class WWPDF_Uninstall {

	/**
	 * Constructor: manages uninstall for multisite
	 *
	 * @since 0.5
	 */
	function __construct() {
		global $wpdb;

		// Check if it is a multisite uninstall - if so, run the uninstall function for each blog id
		if ( is_multisite() ) {
			foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ) as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->uninstall();
			}
			restore_current_blog();
		}
		else {
			$this->uninstall();
		}
	}

	/**
	 * Removes ALL plugin data
	 * only when the relevant option is active
	 *
	 * @since 0.5
	 */
	function uninstall() {

		if ( get_option( 'wwpdf_delete_checkbox' ) !== 'yes' ) {
			return;
		}
		
        foreach ( array(
            'wwpdf_enable',
            'wwpdf_font',
            'wwpdf_footer_input',
            'wwpdf_footer_color',
            'wwpdf_footer_size',
            'wwpdf_footer_finetune_Y',
            'wwpdf_delete_checkbox', // BYE BYE!
        ) as $option) {
                delete_option( $option );
        }
	}
}		
new WWPDF_Uninstall();