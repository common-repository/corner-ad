<?php
/**
 * Main class to interace with the different Content Editors: CPCA_BUILDERS class
 */
if ( ! class_exists( 'CPCA_BUILDERS' ) ) {
	class CPCA_BUILDERS {

		private static $_instance;

		private function __construct(){}
		private static function instance() {
			if ( ! isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		} // End instance

		public static function run() {
			$instance = self::instance();
			add_action( 'init', array( $instance, 'init' ) );
			add_action( 'after_setup_theme', array( $instance, 'after_setup_theme' ) );
		}

		public function init() {
			$instance = self::instance();

			// Gutenberg
			add_action( 'enqueue_block_editor_assets', array( $instance, 'gutenberg_editor' ) );

			// Elementor
			add_action( 'elementor/widgets/register', array( $instance, 'elementor_editor' ) );
			add_action( 'elementor/elements/categories_registered', array( $instance, 'elementor_editor_category' ) );

			// Beaver builder
			if ( class_exists( 'FLBuilder' ) ) {
				include_once dirname( __FILE__ ) . '/beaverbuilder/cornerad.inc.php';
			}
		} // End init

		public function after_setup_theme() {
			$instance = self::instance();

			// SiteOrigin
			add_filter( 'siteorigin_widgets_widget_folders', array( $instance, 'siteorigin_widgets_collection' ) );
			add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $instance, 'siteorigin_panels_widget_dialog_tabs' ) );
		} // End after_setup_theme

		/**************************** GUTENBERG ****************************/

		/**
		 * Loads the javascript resources to integrate the plugin with the Gutenberg editor
		 */
		public function gutenberg_editor() {
			global $wpdb;

			$ads  = $wpdb->get_results( 'SELECT id, name FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' ORDER BY name ASC;' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$list = '';
			if ( $ads ) {
				foreach ( $ads as $ad ) {
					$list .= '<option value="' . esc_attr( $ad->id ) . '">' . esc_html( $ad->name ) . '</option>';
				}
			}

			wp_enqueue_script( 'corner-ad-gutenberg-editor', plugin_dir_url( __FILE__ ) . 'gutenberg/ca-gutenberg.js', array(), CORNER_AD_PLUGIN_VERSION );
			wp_localize_script( 'corner-ad-gutenberg-editor', 'corner_ad', array( 'list' => $list ) );
		} // End gutenberg_editor

		/**************************** ELEMENTOR ****************************/

		public function elementor_editor_category() {
			require_once dirname( __FILE__ ) . '/elementor/elementor_category.pb.php';
		} // End elementor_editor

		public function elementor_editor() {
			wp_enqueue_style( 'corner_ad_elementor', CORNER_AD_PLUGIN_URL . '/pagebuilders/elementor/elementor.css', array(), CORNER_AD_PLUGIN_VERSION );
			require_once dirname( __FILE__ ) . '/elementor/elementor.pb.php';
		} // End elementor_editor

		/**************************** SITEORIGIN ****************************/

		public function siteorigin_widgets_collection( $folders ) {
			 $folders[] = dirname( __FILE__ ) . '/siteorigin/';
			return $folders;
		} // End siteorigin_widgets_collection

		public function siteorigin_panels_widget_dialog_tabs( $tabs ) {
			 $tabs[] = array(
				 'title'  => esc_html__( 'Corner Ad', 'corner-ad' ),
				 'filter' => array(
					 'groups' => array( 'cp-corner-ad' ),
				 ),
			 );

			 return $tabs;
		} // End siteorigin_panels_widget_dialog_tabs
	} // End CPCA_BUILDERS
}
