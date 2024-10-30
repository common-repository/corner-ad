<?php
/*
Plugin Name: Corner Ad
Plugin URI: https://wordpress.dwbooster.com/content-tools/corner-ad
Description: Corner Ad is a minimally invasive advertising display that uses any of your webpage's top corners - a position typically under-utilized by developers - and attracts users' attention by a cool visual effect imitating a page flip
Version: 1.1.1
Author: CodePeople
Author URI: https://wordpress.dwbooster.com/content-tools/corner-ad
Text Domain: corner-ad
License: GPLv2
*/

require_once 'banner.php';
$codepeople_promote_banner_plugins['codepeople-corner-ad'] = array(
	'plugin_name' => 'Corner Ad',
	'plugin_url'  => 'https://wordpress.org/support/plugin/corner-ad/reviews/#new-post',
);

// CONST
define( 'CORNER_AD_PLUGIN_VERSION', '1.1.1' );
define( 'CORNER_AD_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'CORNER_AD_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'CORNER_AD_TD', 'corner-ad' );
define( 'CORNER_AD_TABLE', 'corner_ad' );
define( 'CORNER_AD_IMG_TABLE', 'corner_ad_img' );

require CORNER_AD_PLUGIN_DIR . '/includes/admin_functions.php';

// Loading the page builders connectors
require_once CORNER_AD_PLUGIN_DIR . '/pagebuilders/builders.php';
CPCA_BUILDERS::run();

add_filter( 'option_sbp_settings', 'corner_ad_troubleshoot' );
if ( ! function_exists( 'corner_ad_troubleshoot' ) ) {
	function corner_ad_troubleshoot( $option ) {
		if ( ! is_admin() ) {
			// Solves a conflict caused by the "Speed Booster Pack" plugin
			if ( is_array( $option ) && isset( $option['jquery_to_footer'] ) ) {
				unset( $option['jquery_to_footer'] );
			}
		}
		return $option;
	} // End corner_ad_troubleshoot
}

/**
* Plugin activation
*/
register_activation_hook( __FILE__, 'corner_ad_install' );
if ( ! function_exists( 'corner_ad_install' ) ) {

	function _corner_ad_update_db() {
		if ( get_option( 'CORNER_AD_PLUGIN_VERSION' ) == CORNER_AD_PLUGIN_VERSION ) {
			return;
		}
		update_option( 'CORNER_AD_PLUGIN_VERSION', CORNER_AD_PLUGIN_VERSION );

		global $wpdb;

		$list    = array();
		$columns = $wpdb->get_results( 'SHOW columns FROM `' . $wpdb->prefix . CORNER_AD_TABLE . '`' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		foreach ( $columns as $column ) {
			$list[ $column->Field ] = $column->Field;
		}

		if ( empty( $list['desktop'] ) ) {
			$wpdb->query( 'ALTER TABLE  `' . $wpdb->prefix . CORNER_AD_TABLE . '` ADD `desktop` TINYINT(1) NOT NULL DEFAULT 1;' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		if ( empty( $list['mobile'] ) ) {
			$wpdb->query( 'ALTER TABLE  `' . $wpdb->prefix . CORNER_AD_TABLE . '` ADD `mobile` TINYINT(1) NOT NULL DEFAULT 1;' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

	} // End _corner_ad_update_db

	function _corner_ad_install() {

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$db_queries = array();
		// Create related table
		$db_queries[] = 'CREATE TABLE ' . $wpdb->prefix . CORNER_AD_TABLE . "
                (id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                name VARCHAR(250) NOT NULL DEFAULT '',
                alignTo CHAR(2) NOT NULL DEFAULT 'tr',
                mirror TINYINT(1) NOT NULL DEFAULT 1,
                colorIn VARCHAR(7) NOT NULL DEFAULT 'FFFFFF',
                openIn INT NOT NULL DEFAULT 5,
                closeIn INT NOT NULL DEFAULT 5,
                adURL VARCHAR(250) NOT NULL DEFAULT '',
                target VARCHAR(50) NOT NULL DEFAULT '_blank',
                stat INT NOT NULL DEFAULT 0,
				desktop TINYINT(1) NOT NULL DEFAULT 1,
				mobile TINYINT(1) NOT NULL DEFAULT 1,
				fromDate DATE NULL,
				toDate DATE NULL,
                PRIMARY KEY id (id)
                ) $charset_collate;";

		// Create related table
		$db_queries[] = 'CREATE TABLE ' . $wpdb->prefix . CORNER_AD_IMG_TABLE . "
                (id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                ad MEDIUMINT(9) NOT NULL,
                imgPath VARCHAR(255) NOT NULL,
                thumbPath VARCHAR(255) NULL,
                PRIMARY KEY id (id)
                ) $charset_collate;";

		dbDelta( $db_queries ); // Running the queries

		// Set the image size required by the corner_ad
		// add_image_size( 'corner_ad', 300, 300, true );
	} // End +corner_ad_install

	function corner_ad_install( $network_wide ) {
		global $wpdb;
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ( $network_wide ) {
				$current_blog = $wpdb->blogid;
				// Get all blog ids
				$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					_corner_ad_install();
				}
				switch_to_blog( $current_blog );
				return;
			}
		}
		_corner_ad_install();
	} // End corner_ad_install
} // End plugin activation

// A new blog has been created in a multisite WordPress
add_action( 'wpmu_new_blog', 'corner_ad_new_blog', 10, 6 );

function corner_ad_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;
	if ( is_plugin_active_for_network() ) {
		$current_blog = $wpdb->blogid;
		switch_to_blog( $blog_id );
		_corner_ad_install();
		switch_to_blog( $current_blog );
	}
}

// Redirecting the user to the settings page of the plugin
add_action( 'activated_plugin', 'corner_ad_redirect_to_settings', 10, 2 );
if ( ! function_exists( 'corner_ad_redirect_to_settings' ) ) {
	function corner_ad_redirect_to_settings( $plugin, $network_activation ) {
		global $wpdb;
		if (
			empty( $_REQUEST['_ajax_nonce'] ) &&
			plugin_basename( __FILE__ ) == $plugin &&
			( ! isset( $_POST['action'] ) || 'activate-selected' != $_POST['action'] ) && // phpcs:ignore WordPress.Security.NonceVerification
			( ! isset( $_POST['action2'] ) || 'activate-selected' != $_POST['action2'] ) // phpcs:ignore WordPress.Security.NonceVerification
		) {
			// If there is an add inserted go to the settings page of the plugin
			if ( $wpdb->get_var( 'SELECT COUNT(id) FROM ' . $wpdb->prefix . CORNER_AD_TABLE ) ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				wp_redirect( esc_url( admin_url( 'options-general.php?page=corner-ad.php' ) ) );
				exit;
			} else {
				// or go directly to create an ad
				wp_redirect( admin_url( 'options-general.php?page=corner-ad.php&action=ad_create' ) );
				exit;
			}
		}
	}
}

// Feedback system
require_once 'feedback/cp-feedback.php';
new CP_FEEDBACK( plugin_basename( dirname( __FILE__ ) ), __FILE__, 'https://wordpress.dwbooster.com/contact-us' );

/*
*   Plugin initializing
*/
add_action( 'init', 'corner_ad_init' );
if ( ! function_exists( 'corner_ad_init' ) ) {
	function corner_ad_init() {

		_corner_ad_update_db();

		// Set the add shortcode
		add_shortcode( 'corner-ad', 'corner_ad_replace_shortcode' );
		add_image_size( 'corner_ad_thumb', 100, 100, true );
		add_image_size( 'corner_ad', 500, 500, true );
		add_action( 'wp_footer', 'corner_ad_wp_footer' );
	} // End corner_ad_init
}

/*
*   Admin initionalizing
*/
add_action( 'admin_init', 'corner_ad_admin_init' );
if ( ! function_exists( 'corner_ad_admin_init' ) ) {
	function corner_ad_admin_init() {
		// Load the associated text domain
		load_plugin_textdomain( 'corner-ad', false, CORNER_AD_PLUGIN_DIR . '/languages/' );

		// Set plugin links
		$plugin = plugin_basename( __FILE__ );
		add_filter( 'plugin_action_links_' . $plugin, 'corner_ad_links' );

		// Set a new media button for corner ad insertion
		add_action( 'media_buttons', 'corner_ad_media_button', 100 );
	} // End corner_ad_admin_init
}

if ( ! function_exists( 'corner_ad_links' ) ) {
	function corner_ad_links( $links ) {
		// Help link
		$custom_link = '<a href="https://wordpress.org/support/plugin/corner-ad/#new-post" target="_blank">' . __( 'Help', 'corner-ad' ) . '</a>';
		array_unshift( $links, $custom_link );

		// Custom link
		$custom_link = '<a href="https://wordpress.dwbooster.com/contact-us" target="_blank">' . __( 'Request custom changes', 'corner-ad' ) . '</a>';
		array_unshift( $links, $custom_link );

		// Settings link
		$settings_link = '<a href="options-general.php?page=corner-ad.php">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	} // End corner_ad_customization_link
}

// Set the settings menu option
add_action( 'admin_menu', 'corner_ad_settings_menu' );
if ( ! function_exists( 'corner_ad_settings_menu' ) ) {
	function corner_ad_settings_menu() {
		// Add to admin_menu
		add_options_page( 'Corner AD', 'Corner AD', 'edit_posts', basename( __FILE__ ), 'corner_ad_settings_page' );
	} // End corner_ad_settings_menu
}

if ( ! function_exists( 'corner_ad_settings_page' ) ) {
	function corner_ad_settings_page() {
		global $wpdb;
		wp_enqueue_media();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Corner Ad', 'corner-ad' ); ?></h1>
		<?php
		if ( isset( $_REQUEST['action'] ) ) {
			switch ( $_REQUEST['action'] ) {
				case 'ad_remove':
					if ( isset( $_REQUEST['_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ), 'cff-coner-ad-delete' ) &&  isset( $_REQUEST['id'] ) ) {
						if ( $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . CORNER_AD_IMG_TABLE . ' WHERE ad=%d', sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ) ) ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
							$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' WHERE id=%d', sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
						}
					}
					print corner_ad_settings_page_list(); // phpcs:ignore WordPress.Security.EscapeOutput
					break;
				case 'ad_edit':
				case 'ad_create':
					if (
						isset( $_REQUEST['_nonce'] ) &&
						(
							wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ), 'cff-coner-ad-edit' ) ||
							wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ), 'cff-coner-ad-add' )
						)
					) {
						print corner_ad_settings_page_form(); // phpcs:ignore WordPress.Security.EscapeOutput
					} else {
						print corner_ad_settings_page_list(); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					break;
				case 'ad_save':
					print corner_ad_settings_page_form(); // phpcs:ignore WordPress.Security.EscapeOutput
					break;
				default:
					print corner_ad_settings_page_list(); // phpcs:ignore WordPress.Security.EscapeOutput
					break;
			}
		} else {
			print corner_ad_settings_page_list(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		?>
		</div>
		<?php
	} // End corner_ad_settings_page
}

if ( ! function_exists( 'corner_ad_replace_shortcode' ) ) {
	function corner_ad_replace_shortcode( $attr ) {
		global $wpdb, $corner_ad_inserted;

		if ( ! isset( $corner_ad_inserted ) ) {
			if ( empty( $attr['id'] ) ) {
				$ad = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' WHERE ' . ( wp_is_mobile() ? 'mobile=1' : 'desktop=1' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			} else {
				$ad = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' WHERE id=%d AND ' . ( wp_is_mobile() ? 'mobile=1' : 'desktop=1' ), $attr['id'] ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			}

			if ( $ad ) {
				$date = gmdate( 'Y-m-d' );

				if ( ! empty( $ad->fromDate ) && '0000-00-00' != $ad->fromDate && $date < $ad->fromDate ) {
					return '';
				}
				if ( ! empty( $ad->toDate ) && '0000-00-00' != $ad->toDate && $ad->toDate < $date ) {
					return '';
				}

				$corner_ad_inserted = true;
				// Enqueue required files
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'corner_ad_raphael_script', CORNER_AD_PLUGIN_URL . '/js/raphael-min.js', array(), CORNER_AD_PLUGIN_VERSION );
				wp_enqueue_script( 'corner_ad_public_script', CORNER_AD_PLUGIN_URL . '/js/cornerAd.min.js', array( 'jquery', 'corner_ad_raphael_script' ), CORNER_AD_PLUGIN_VERSION );

				// Select the image
				$row = $wpdb->get_row( $wpdb->prepare( 'SELECT imgPath, thumbPath FROM ' . $wpdb->prefix . CORNER_AD_IMG_TABLE . ' WHERE ad=%d', $ad->id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				if ( $row ) {
					$img = corner_ad_get_images( $row->imgPath, ( ( ! empty( $row->thumbPath ) ) ? $row->thumbPath : '' ), true );
				}

				$colorIn = $ad->colorIn;
				if ( get_option( 'corner_ad_analytics', 0 ) ) {
					$ga = esc_js( get_option( 'corner_ad_analytics_tracking_id', '' ) );
				}

				if ( isset( $ad->extra ) ) {
					$extra = json_decode( $ad->extra, true );
				}

				return "<script>
                if(window.addEventListener){
                    window.addEventListener(
                        'load',
                        function(){
                            printCornerAd(
                                {
                                    alignTo:'tl',
                                    mirror:" . esc_js( ( 1 == $ad->mirror ) ? 'true' : 'false' ) . ",
                                    colorIn:'" . esc_js( $colorIn ) . "',
                                    thumbPath:'" . esc_js( $img->thumb->url ) . "',
                                    imgPath:'" . esc_js( $img->large->url ) . "',
                                    adUrl:'" . esc_js( $ad->adURL ) . "',
                                    openIn:" . esc_js( ( $ad->openIn ) ? $ad->openIn : -1 ) . ',
                                    closeIn:' . esc_js( ( $ad->closeIn ) ? $ad->closeIn : -1 ) . ",
                                    target:'" . esc_js( $ad->target ) . "'" . ( ( ! empty( $ga ) ) ? ",
                                    ga:'" . $ga . "'" : '' ) . ",
                                    ga_category:'" . esc_js( ! empty( $extra ) && ! empty( $extra['ga_category'] ) ? $extra['ga_category'] : 'Corner Ad' ) . "',
                                    ga_action:'" . esc_js( ! empty( $extra ) && ! empty( $extra['ga_action'] ) ? $extra['ga_action'] : 'click' ) . "',
                                    ga_label:'" . esc_js( ! empty( $extra ) && ! empty( $extra['ga_label'] ) ? $extra['ga_label'] : '' ) . "'
                                }
                            );
                        }
                    );
                }else{
                    window.attachEvent(
                        'onload',
                        function(){
                            printCornerAd(
                                {
                                    alignTo:'tl',
                                    mirror:" . esc_js( ( 1 == $ad->mirror ) ? 'true' : 'false' ) . ",
                                    colorIn:'" . esc_js( $colorIn ) . "',
                                    thumbPath:'" . esc_js( $img->thumb->url ) . "',
                                    imgPath:'" . esc_js( $img->large->url ) . "',
                                    adUrl:'" . esc_js( $ad->adURL ) . "',
                                    openIn:" . esc_js( ( $ad->openIn ) ? $ad->openIn : -1 ) . ',
                                    closeIn:' . esc_js( ( $ad->closeIn ) ? $ad->closeIn : -1 ) . ",
                                    target:'" . esc_js( $ad->target ) . "'" . ( ( ! empty( $ga ) ) ? ",
                                    ga:'" . $ga . "'" : '' ) . ",
                                    ga_category:'" . esc_js( ! empty( $extra ) && ! empty( $extra['ga_category'] ) ? $extra['ga_category'] : 'Corner Ad' ) . "',
                                    ga_action:'" . esc_js( ! empty( $extra ) && ! empty( $extra['ga_action'] ) ? $extra['ga_action'] : 'click' ) . "',
                                    ga_label:'" . esc_js( ! empty( $extra ) && ! empty( $extra['ga_label'] ) ? $extra['ga_label'] : '' ) . "'
                                }
                            );
                        }
                    );
                }
                </script>";
			}
		}

		return '';
	} // End corner_ad_replace_shortcode
}

if ( ! function_exists( 'corner_ad_media_button' ) ) {
	function corner_ad_media_button() {
		global $wpdb;

		// Enqueue required files
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'corner_ad_insertion', CORNER_AD_PLUGIN_URL . '/js/ca-insertion.js', array( 'jquery', 'jquery-ui-dialog' ), CORNER_AD_PLUGIN_VERSION );

		$ads  = $wpdb->get_results( 'SELECT id, name FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' ORDER BY name ASC;' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$list = '';
		if ( $ads ) {
			foreach ( $ads as $ad ) {
				$list .= '<option value="' . esc_attr( $ad->id ) . '">' . esc_html( $ad->name ) . '</option>';
			}
		}

		wp_localize_script( 'corner_ad_insertion', 'corner_ad', array( 'list' => $list ) );

		print '<a href="javascript:open_insertion_corner_ad_window();" title="' . esc_attr( __( 'Insert Corner Ad' ) ) . '"><img src="' . esc_url( CORNER_AD_PLUGIN_URL . '/images/corner-ad-icon.gif' ) . '" alt="' . esc_attr( __( 'Insert Corner Ad' ) ) . '" /></a>';
	} // End corner_ad_media_button
}

if ( ! function_exists( 'corner_ad_wp_footer' ) ) {
	function corner_ad_wp_footer() {
		$default_ad = @intval( get_option( 'corner_ad_default_ad', 0 ) );
		$random_ad  = @intval( get_option( 'corner_ad_random_ad', 0 ) );
		$context    = get_option( 'corner_ad_context', 'everything' );
		$posttypes  = get_option( 'corner_ad_posttypes', array() );
		if (
			( $default_ad || $random_ad ) &&
			(
				'everything' == $context ||
				( 'homepage' == $context && is_home() ) ||
				( 'posttype' == $context && in_array( get_post_type(), $posttypes ) )
			)
		) {
			if ( $random_ad ) {
				global $wpdb;
				$ad         = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' WHERE ' . ( wp_is_mobile() ? 'mobile=1' : 'desktop=1' ) . ' ORDER BY RAND()' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$default_ad = $ad->id;
			}

			if ( $default_ad ) {
				print do_shortcode( '[corner-ad id="' . $default_ad . '"]' );
			}
		}
	}
}
