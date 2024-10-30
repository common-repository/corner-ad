<?php
/*
Widget Name: Corner Ad
Description: Minimally invasive advertising display that uses any of your webpage's top corners - a position typically under-utilized by developers - and attracts users' attention by a cool visual effect imitating a page flip.
Documentation: https://wordpress.dwbooster.com/content-tools/corner-ad
*/

class SiteOrigin_CPCA_Shortcode extends SiteOrigin_Widget {

	public function __construct() {
		global $wpdb;
		$options = array();
		$ads     = $wpdb->get_results( 'SELECT id, name FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' ORDER BY name ASC;' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$default = '';
		if ( $ads ) {
			foreach ( $ads as $ad ) {
				$options[ $ad->id ] = esc_html( $ad->name );
				$default            = $ad->id;
			}
		}

		parent::__construct(
			'siteorigin-cpca-shortcode',
			__( 'Corner Ad', 'corner-ad' ),
			array(
				'description'   => esc_html__( "Minimally invasive advertising display that uses any of your webpage's top corners", 'corner-ad' ),
				'panels_groups' => array( 'cp-corner-ad' ),
				'help'          => 'https://wordpress.dwbooster.com/content-tools/corner-ad',
			),
			array(),
			array(
				'ad' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select the Ad', 'corner-ad' ),
					'options' => $options,
					'default' => $default,
				),
			),
			plugin_dir_path( __FILE__ )
		);
	} // End __construct

	public function get_template_name( $instance ) {
		return 'siteorigin-cpca-shortcode';
	} // End get_template_name

	public function get_style_name( $instance ) {
		return '';
	} // End get_style_name

} // End Class SiteOrigin_CPCA_Shortcode

// Registering the widget
siteorigin_widget_register( 'siteorigin-cpca-shortcode', __FILE__, 'SiteOrigin_CPCA_Shortcode' );
