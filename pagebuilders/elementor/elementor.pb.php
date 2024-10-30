<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_CPCornerAd_Widget extends Widget_Base {

	public function get_name() {
		return 'corner-ad';
	} // End get_name

	public function get_title() {
		return 'Corner Ad';
	} // End get_title

	public function get_icon() {
		return 'eicon-editor-external-link';
	} // End get_icon

	public function get_categories() {
		return array( 'corner-ad-cat' );
	} // End get_categories

	public function is_reload_preview_required() {
		return false;
	} // End is_reload_preview_required

	protected function register_controls() {
		global $wpdb;

		$this->start_controls_section(
			'corner_ad_section',
			array(
				'label' => esc_html__( 'Corner Ad', 'corner-ad' ),
			)
		);

		$options = array();
		$default = '';

		$rows = $wpdb->get_results( 'SELECT id, name FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' ORDER BY name ASC;' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		foreach ( $rows as $item ) {
			$options[ $item->id ] = $item->name;
			if ( empty( $default ) ) {
				$default = $item->id;
			}
		}

		$this->add_control(
			'ad',
			array(
				'label'   => esc_html__( 'Select an Ad', 'corner-ad' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $options,
				'default' => $default,
			)
		);

		$this->add_control(
			'container',
			array(
				'label'       => esc_html__( 'Display the Ad relative to (Only available in the Professional version of the plugin)', 'corner-ad' ),
				'type'        => Controls_Manager::TEXT,
				'input_type'  => 'text',
				'classes'     => 'ca-widefat',
				'description' => esc_html__( '(leave in blank to display the Ad relative to the webpage)', 'corner-ad' ),
			)
		);

		$this->end_controls_section();
	} // End register_controls

	private function _get_shortcode() {
		$settings = $this->get_settings_for_display();
		return '[corner-ad id="' . esc_attr( sanitize_text_field( wp_unslash( $settings['ad'] ) ) ) . '"]';
	} // End _get_shortcode

	protected function render() {
		$shortcode = $this->_get_shortcode();
		if (
			isset( $_REQUEST['action'] ) &&
			(
				'elementor' == $_REQUEST['action'] ||
				'elementor_ajax' == $_REQUEST['action']
			)
		) {
			print $shortcode; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			print do_shortcode( shortcode_unautop( $shortcode ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}

	} // End render

	public function render_plain_content() {
		echo $this->_get_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput
	} // End render_plain_content

} // End Elementor_CPCornerAd_Widget

// Register the widgets
Plugin::instance()->widgets_manager->register( new Elementor_CPCornerAd_Widget() );
