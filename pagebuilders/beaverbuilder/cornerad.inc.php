<?php
require_once dirname( __FILE__ ) . '/cornerad/cornerad.php';

// Get the forms list
global $wpdb;
$options = array();
$default = '';

$rows = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . CORNER_AD_TABLE ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
foreach ( $rows as $item ) {
	$options[ $item->id ] = $item->name;
	if ( empty( $default ) ) {
		$default = $item->id;
	}
}

FLBuilder::register_module(
	'CornerAdBeaver',
	array(
		'cornerad-tab' => array(
			'title'    => __( 'Select the Ad to insert', 'corner-ad' ),
			'sections' => array(
				'cornerad-section' => array(
					'title'  => __( 'Corner Ad', 'corner-ad' ),
					'fields' => array(
						'ad'           => array(
							'type'    => 'select',
							'label'   => __( 'Select Ad', 'corner-ad' ),
							'options' => $options,
							'default' => $default,
						),
						'ad_container' => array(
							'type'        => 'text',
							'label'       => __( 'Ad container', 'corner-ad' ),
							'default'     => 'body',
							'description' => __( 'The Ad container affects only to the commercial version of the plugin', 'corner-ad' ),
						),
					),
				),
			),
		),
	)
);
