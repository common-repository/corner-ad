<?php

/* FUNCTIONS RELATED TO THE SETTINGS PAGE OF CORNER AD */

if ( ! function_exists( 'corner_ad_settings_page_list' ) ) {
	function corner_ad_settings_page_list() {
		wp_enqueue_script( 'corner_ad_admin_script', CORNER_AD_PLUGIN_URL . '/js/ca-admin.js', array( 'jquery' ), CORNER_AD_PLUGIN_VERSION );
		wp_localize_script(
			'corner_ad_admin_script',
			'corner_ad_default_ad_errors',
			array(
				'posttype_required' => __( 'As was selected the post type option for the "Display on" attribute, you must select at least a post type from the list', 'corner-ad' ),
			)
		);
		global $wpdb;
		// Processing the submissions of default ad settnigs
		if ( isset( $_POST['corner_ad_default_ad_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['corner_ad_default_ad_nonce'] ) ), __FILE__ ) ) {
			update_option(
				'corner_ad_default_ad',
				( ! empty( $_POST['corner_ad_default_ad'] ) ) ? @intval( $_POST['corner_ad_default_ad'] ) : 0
			);
			update_option(
				'corner_ad_random_ad',
				( ! empty( $_POST['corner_ad_random_ad'] ) ) ? 1 : 0
			);
			update_option(
				'corner_ad_context',
				(
					! empty( $_POST['corner_ad_context'] ) &&
					in_array( $_POST['corner_ad_context'], array( 'everything', 'homepage', 'posttype' ) )
				) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_context'] ) ) : ''
			);
			update_option(
				'corner_ad_posttypes',
				(
					isset( $_POST['corner_ad_posttype_list'] ) &&
					is_array( $_POST['corner_ad_posttype_list'] )
				) ? array_map(
					function( $v ) {
						return sanitize_text_field( wp_unslash( $v ) );
					},
					$_POST['corner_ad_posttype_list']
				) : array()
			);
		}

		// Processing the submissions of analytis settnigs
		if ( isset( $_POST['corner_ad_analytics_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['corner_ad_analytics_nonce'] ) ), __FILE__ ) ) {
			update_option(
				'corner_ad_analytics',
				( ! empty( $_POST['corner_ad_analytics'] ) ) ? 1 : 0
			);
			update_option(
				'corner_ad_analytics_tracking_id',
				isset( $_POST['corner_ad_analytics_tracking_id'] ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_analytics_tracking_id'] ) ) : ''
			);
		}

		$ad = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . CORNER_AD_TABLE ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$output = '
			<div style="padding:10px; border: 1px solid #DADADA;margin-bottom:20px;text-align:center;">
				<h2><a href="javascript:jQuery(\'.videoWrapper,.cornerAdVideoTutorialClosseButton\').toggle();">' . esc_html__( '>> Video Tutorial <<', 'corner-ad' ) . '</a> <a href="javascript:jQuery(\'.videoWrapper,.cornerAdVideoTutorialClosseButton\').toggle();" class="cornerAdVideoTutorialClosseButton" style="display:none;">[ X ]</a></h2>
				<style>.videoWrapper {position: relative;padding-bottom: 56.25%;height: 0; display:none;} .videoWrapper iframe {position: absolute;top: 0;left: 0;width: 100%;height: 100%;}</style>
				<div class="videoWrapper">
					<iframe width="1263" height="480" src="https://www.youtube.com/embed/IrU6xnsek_g" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div>
			<div class="postbox">
                        <h2 class="handle" style="padding:5px;"><span>' . esc_html__( 'Select a Corner Ad or create a new one', 'corner-ad' ) . '</span></h2>
                            <div class="inside"><p  style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">' . __( 'If you want test the premium version of Corner Ad go to the following links:<br/> <a href="https://demos.dwbooster.com/corner-ad/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/> <a href="https://demos.dwbooster.com/corner-ad/" target="_blank">Public page: Click to access the Corner Ad</a><br><br><a href="https://wordpress.org/support/plugin/corner-ad/#new-post" target="_blank">I\'ve a question</a>' ) . '
					</p>';

		if ( $ad ) {
			$create_btn = '<div><input type="button" class="button-primary" onclick="alert(\'Only one Ad may be created in the free version of plugin\')" value="' . esc_attr__( 'Create New Ad', 'corner-ad' ) . '" /></div>';

			$output .= $create_btn;
			$output .= '<table class="form-table">
                <thead>
                <th style="white-space:nowrap;font-weight:bold;">Ad name</th><th style="font-weight:bold;">Actions</th><th style="font-weight:bold;">Shortcode</th><th style="white-space:nowrap;width:100%;font-weight:bold;">The Ad has been selected</th>
                </thead>
            ';

			$output .= '<tr><td style="white-space:nowrap;">' . esc_html( $ad->name ) . '</td><td style="white-space:nowrap;"><a href="?page=corner-ad.php&action=ad_edit&id=' . esc_attr( $ad->id ) . '&_nonce=' . esc_attr( wp_create_nonce( 'cff-coner-ad-edit' ) ) . '" class="button">' . esc_html__( 'Edit', 'corner-ad' ) . '</a> <a href="?page=corner-ad.php&action=ad_remove&id=' . esc_attr( $ad->id ) . '&_nonce=' . esc_attr( wp_create_nonce( 'cff-coner-ad-delete' ) ) . '" class="button">' . esc_html__( 'Remove', 'corner-ad' ) . '</a> </td><td  style="white-space:nowrap;">[corner-ad id="' . esc_attr( $ad->id ) . '"]</td><td>Only available in the <a href="https://wordpress.dwbooster.com/content-tools/corner-ad" target="_blank">commercial</a> version of plugin</td></tr>';

			$output .= '</table>';
		} else {
			$create_btn = '<div><a href="?page=corner-ad.php&action=ad_create&_nonce=' . esc_attr( wp_create_nonce( 'cff-coner-ad-add' ) ) . '" class="button-primary">' . esc_html__( 'Create New Ad', 'corner-ad' ) . '</a></div>';
		}

		$output .= $create_btn;
		$output .= '</div></div>';

		// Configure the default visualization of ads

		$default_ad = get_option( 'corner_ad_default_ad', 0 );
		$random_ad  = get_option( 'corner_ad_random_ad', 0 );
		$context    = get_option( 'corner_ad_context', 'everything' );
		$posttypes  = get_option( 'corner_ad_posttypes', array() );
		$output    .= '<div class="postbox">
                        <h3 class="handle" style="padding:5px;"><span>' . esc_html__( 'Displaying Ads at Website\'s Scope', 'corner-ad' ) . '</span></h3>
						<div class="inside">
							<form action="' . admin_url( 'options-general.php?page=corner-ad.php' ) . '" name="corner_ad_default_ad_settings" method="post">
							<p>' . esc_html__( 'Select the Ad to be displayed by default in the following circumstances', 'corner-ad' ) . ':</p>';
		if ( $ad ) {

			$output  .= '<div>
							<select aria-label="' . esc_attr__( 'Ads list', 'corner-ad' ) . '" name="corner_ad_default_ad">
								<option value="">'
								. esc_html( __( '- Select an Ad -', 'corner-ad' ) )
								. '</option>';
			$selected = ( $ad->id == $default_ad ) ? 'SELECTED' : '';
			$output  .= '<option value="' . esc_attr( $ad->id ) . '" ' . $selected . '>' . esc_html( $ad->name ) . '</option>';
			$output  .= '
							</select>
							' . esc_html__( '- or -', 'corner-ad' ) . '
							<input aria-label="' . esc_attr__( 'Random ad', 'corner-ad' ) . '" type="checkbox" name="corner_ad_random_ad" ' . ( ( 0 != $random_ad ) ? 'CHECKED' : '' ) . '>
							' . esc_html__( 'Display a random AD', 'corner-ad' ) . '
						</div>';
		} else {
			$output .= '<p style="font-size:1.2em; color:red; font-style:italic;">' . esc_html__( 'You should create at least an Ad first (section above).', 'corner-ad' ) . '</p>';
		}

		$output .= '
							<p>' . esc_html__( 'Display on', 'corner-ad' ) . ':</p>
							<div>
								<label>
									<input aria-label="' . esc_attr__( 'Every page', 'corner-ad' ) . '" type="radio" name="corner_ad_context" value="everything" ' . ( ( 'everything' == $context ) ? 'CHECKED' : '' ) . ' />
									' . esc_html__( 'Show the Ad on every page, post or section of website', 'corner-ad' ) . '
								</label><br />
								<label>
									<input aria-label="' . esc_attr__( 'Homepage only', 'corner-ad' ) . '" type="radio" name="corner_ad_context" value="homepage" ' . ( ( 'homepage' == $context ) ? 'CHECKED' : '' ) . ' />
									' . esc_html__( 'Show the Ad on the homepage only', 'corner-ad' ) . '
								</label><br />
								<label>
									<input aria-label="' . esc_attr__( 'Specific post type', 'corner-ad' ) . '" type="radio" name="corner_ad_context" value="posttype" ' . ( ( 'posttype' == $context ) ? 'CHECKED' : '' ) . ' />
									' . esc_html__( 'Show the Ad with the following post types (select them from the following list)', 'corner-ad' ) . ':
								</label>
								<div class="corner-ad-post-types-list" style="display:' . ( ( 'posttype' == $context ) ? 'block' : 'none' ) . ';margin-top:20px;">
								<select aria-label="' . esc_attr__( 'Post types list', 'corner-ad' ) . '" name="corner_ad_posttype_list[]" multiple rows="5">';

		// Read post types
		$posttypes_list = get_post_types( array( 'public' => true ) );
		foreach ( $posttypes_list as $posttype_name ) {
			$posttype_obj = get_post_type_object( $posttype_name );
			$output      .= '<option value="' . esc_attr( $posttype_name ) . '" ' . ( ( in_array( $posttype_name, $posttypes ) ) ? 'SELECTED' : '' ) . '>' . esc_html( $posttype_obj->labels->name ) . '</option>';
		}
		$output .= '
								</select>
								</div>
							</div>';
		$output .= '<input type="hidden" name="corner_ad_default_ad_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
		// Display submit button
		if ( $ad ) {
			$output .= '<div style="margin-top:20px;"><input type="submit" class="button-primary corner-ad-default-ad-settings" value="' . esc_attr__( 'Save Changes', 'corner-ad' ) . '" /></div>';
		}

		// Close form, inside and postbox
		$output .= '
							</form>
						</div>
					</div>';

		/** Analytics */
		$enabled_analytics     = get_option( 'corner_ad_analytics', 0 );
		$analytics_tracking_id = get_option( 'corner_ad_analytics_tracking_id', '' );
		$output               .= '<div class="postbox">
                        <h3 class="handle" style="padding:5px;"><span>' . esc_html__( 'Integrate with Google Analytics', 'corner-ad' ) . '</span></h3>
						<div class="inside">
							<form action="' . admin_url( 'options-general.php?page=corner-ad.php' ) . '" name="corner_ad_analytics_settings" method="post">
							<p><input aria-label="' . esc_attr__( 'Analytics integration', 'corner-ad' ) . '" type="checkbox" name="corner_ad_analytics" ' . ( ( $enabled_analytics ) ? 'CHECKED' : '' ) . ' /> ' . esc_html__( 'Registering the click events in Google Analytics', 'corner-ad' ) . '</p>
							<p>' . esc_html__( 'Enter the Google Analytics Measurement Id', 'corner-ad' ) . ':</p>
							<p><input aria-label="' . esc_attr__( 'Analytics Measurement id', 'corner-ad' ) . '" type="text" name="corner_ad_analytics_tracking_id" value="' . esc_attr( $analytics_tracking_id ) . '" placeholder="G-XXXXXXX" /></p>
							<div style="margin-top:20px;"><input type="submit" class="button-primary" value="' . esc_attr__( 'Save Changes', 'corner-ad' ) . '" /></div>
							<input type="hidden" name="corner_ad_analytics_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />
							</form>
						</div>
					</div>';

		return $output;
	} // End corner_ad_settings_page_list
}

if ( ! function_exists( 'corner_ad_settings_page_form' ) ) {
	function corner_ad_settings_page_form() {
		global $wpdb;

		if ( ! empty( $_POST ) ) {
			$_POST = stripslashes_deep( $_POST );
		}

		$data;
		$error = array();
		$id;
		$output = '';
		$title  = __( 'Create or edit a Corner Ad', 'corner-ad' );

		wp_enqueue_script( 'jquery' );

		// Load the datepicker resources
		wp_enqueue_script( 'jquery-ui-datepicker' );
		$wp_scripts = wp_scripts();
		wp_enqueue_style(
			'plugin_name-admin-ui-css',
			'//code.jquery.com/ui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css',
			array(),
			CORNER_AD_PLUGIN_VERSION
		);

		// Load the picker color resources
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'corner_ad_admin_script', CORNER_AD_PLUGIN_URL . '/js/ca-admin.js', array( 'jquery' ), CORNER_AD_PLUGIN_VERSION );

		if ( isset( $_REQUEST['id'] ) ) {
			$data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . CORNER_AD_TABLE . ' WHERE id=%d', sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( $data ) {
				$id                       = $data->id;
				$corner_ad_name           = $data->name;
				$corner_ad_alignTo        = $data->alignTo;
				$corner_ad_mirror         = $data->mirror;
				$corner_ad_colorIn        = $data->colorIn;
				$corner_ad_openIn         = $data->openIn;
				$corner_ad_closeIn        = $data->closeIn;
				$corner_ad_adURL          = $data->adURL;
				$corner_ad_target         = $data->target;
				$corner_ad_desktop_device = ( ! isset( $data->desktop ) || ! empty( $data->desktop ) ) ? 1 : 0;
				$corner_ad_mobile_device  = ( ! isset( $data->mobile ) || ! empty( $data->mobile ) ) ? 1 : 0;
				$corner_ad_from           = ( ! empty( $data->fromDate ) ) ? trim( $data->fromDate ) : '';
				$corner_ad_to             = ( ! empty( $data->toDate ) ) ? trim( $data->toDate ) : '';
				$corner_ad_extra          = ( ! empty( $data->extra ) ) ? json_decode( $data->extra, true ) : array();

				$corner_ad_imgPath = array();

				$result = $wpdb->get_row( $wpdb->prepare( 'SELECT imgPath, thumbPath FROM ' . $wpdb->prefix . CORNER_AD_IMG_TABLE . ' WHERE ad=%d', $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				if ( $result ) {
					$corner_ad_imgPath   = $result->imgPath;
					$corner_ad_thumbPath = ! empty( $result->thumbPath ) ? $result->thumbPath : '';
				}
			}
		}

		if ( isset( $_POST['corner_ad_edition_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['corner_ad_edition_nonce'] ) ), __FILE__ ) ) {
				// Check by column and add it
				call_user_func(
					function() {
						global $wpdb;

						$add_column = true;
						$columns    = $wpdb->get_results( 'SHOW columns FROM `' . $wpdb->prefix . CORNER_AD_TABLE . '`' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
						foreach ( $columns as $column ) {
							if ( 'extra' == $column->Field ) {
								$add_column = false;
								break;
							}
						}
						if ( $add_column ) {
							$sql = 'ALTER TABLE  `' . $wpdb->prefix . CORNER_AD_TABLE . '` ADD `extra` TEXT';
							$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
						}
					}
				);

			if ( ! empty( $_POST['corner_ad_name'] ) ) {
				$corner_ad_name = sanitize_text_field( wp_unslash( $_POST['corner_ad_name'] ) );
			} else {
				$error[] = esc_html__( 'The Ad name is required', 'corner-ad' );
			}

				$corner_ad_alignTo = ( isset( $_POST['corner_ad_alignTo'] ) && 'tr' == $_POST['corner_ad_alignTo'] ) ? 'tr' : 'tl';
				$corner_ad_mirror  = ( isset( $_POST['corner_ad_mirror'] ) ) ? 1 : 0;
				$corner_ad_colorIn = ( isset( $_POST['corner_ad_colorIn'] ) ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_colorIn'] ) ) : 'FFFFFF';
				$corner_ad_openIn  = ( isset( $_POST['corner_ad_openIn'] ) && is_numeric( $_POST['corner_ad_openIn'] ) ) ? intval( $_POST['corner_ad_openIn'] ) : 0;
				$corner_ad_closeIn = ( isset( $_POST['corner_ad_closeIn'] ) && is_numeric( $_POST['corner_ad_closeIn'] ) ) ? intval( $_POST['corner_ad_closeIn'] ) : 0;
				$corner_ad_from    = ( isset( $_POST['corner_ad_from'] ) ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_from'] ) ) : '';
				$corner_ad_to      = ( isset( $_POST['corner_ad_to'] ) ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_to'] ) ) : '';

				$corner_ad_extra = array(
					'ga_category' => isset( $_POST['corner_ad_ga_category'] ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_ga_category'] ) ) : '',
					'ga_action'   => isset( $_POST['corner_ad_ga_action'] ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_ga_action'] ) ) : '',
					'ga_label'    => isset( $_POST['corner_ad_ga_label'] ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_ga_label'] ) ) : '',
				);

				if ( ! empty( $_POST['corner_ad_imgPath'] ) ) {
					$corner_ad_imgPath   = sanitize_text_field( wp_unslash( $_POST['corner_ad_imgPath'] ) );
					$corner_ad_thumbPath = ( ! empty( $_POST['corner_ad_thumbPath'] ) ) ? sanitize_text_field( wp_unslash( $_POST['corner_ad_thumbPath'] ) ) : $corner_ad_imgPath;

					if ( ! empty( $corner_ad_imgPath ) ) {
						$img = corner_ad_get_images( $corner_ad_imgPath, $corner_ad_thumbPath );

						// Large
						if (
							! empty( $img->large->id ) &&
							! empty($img->large->url) &&
							! preg_match('/\.gif$/i', $img->large->url)
						) {
							$img_obj = wp_get_image_editor( $img->large->url );
							if ( ! is_wp_error( $img_obj ) ) {

								$imgSize = $img_obj->get_size();
								$size    = min( $imgSize['height'], $imgSize['width'], 500 );
								add_image_size( 'corner_ad', $size, $size, true );
							}
							$img->large->file = get_attached_file( $img->large->id );
							wp_update_attachment_metadata( $img->large->id, wp_generate_attachment_metadata( $img->large->id, $img->large->file ) );
						}

						// Thumb
						if ( ! empty( $img->thumb->id ) ) {
							$img_obj = wp_get_image_editor( $img->thumb->url );
							if ( ! is_wp_error( $img_obj ) ) {

								$imgSize = $img_obj->get_size();
								$size    = min( $imgSize['height'], $imgSize['width'], 100 );
								add_image_size( 'corner_ad_thumb', $size, $size, true );
							}
							$img->thumb->file = get_attached_file( $img->thumb->id );
							wp_update_attachment_metadata( $img->thumb->id, wp_generate_attachment_metadata( $img->thumb->id, $img->thumb->file ) );
						}
					}
				} else {
					$error[] = esc_html__( 'The Ad image is required', 'corner-ad' );
				}

				if ( ! empty( $_POST['corner_ad_adURL'] ) ) {
					$corner_ad_adURL = sanitize_text_field( wp_unslash( $_POST['corner_ad_adURL'] ) );
				} else {
					$error[] = esc_html__( 'The Ad link is required', 'corner-ad' );
				}
				$corner_ad_target = ( isset( $_POST['corner_ad_target'] ) && '_blank' == $_POST['corner_ad_target'] ) ? '_blank' : '_self';

				$corner_ad_desktop_device = ! empty( $_POST['corner_ad_desktop_device'] ) ? 1 : 0;
				$corner_ad_mobile_device  = ! empty( $_POST['corner_ad_mobile_device'] ) ? 1 : 0;

				if ( count( $error ) == 0 ) {
					if ( ! empty( $data ) ) {
						// Update
						$success = $wpdb->update(
							$wpdb->prefix . CORNER_AD_TABLE,
							array(
								'name'     => $corner_ad_name,
								'alignTo'  => 'tl',
								'mirror'   => $corner_ad_mirror,
								'colorIn'  => $corner_ad_colorIn,
								'openIn'   => $corner_ad_openIn,
								'closeIn'  => $corner_ad_closeIn,
								'adURL'    => $corner_ad_adURL,
								'target'   => $corner_ad_target,
								'desktop'  => $corner_ad_desktop_device,
								'mobile'   => $corner_ad_mobile_device,
								'fromDate' => $corner_ad_from,
								'toDate'   => $corner_ad_to,
								'extra'    => json_encode( $corner_ad_extra ),
							),
							array(
								'id' => $id,
							),
							array( '%s', '%s', '%d', '%s', '%d', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s' ),
							array( '%d' )
						);
						// Remove associate images
						$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . CORNER_AD_IMG_TABLE . ' WHERE ad=%d', $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

						if ( ! empty( $corner_ad_imgPath ) ) {
							$wpdb->insert(
								$wpdb->prefix . CORNER_AD_IMG_TABLE,
								array(
									'ad'        => $id,
									'imgPath'   => $corner_ad_imgPath,
									'thumbPath' => $corner_ad_thumbPath,
								),
								array( '%d', '%s', '%s' )
							);
						}
					} elseif ( $wpdb->get_var( 'SELECT COUNT(id) FROM ' . $wpdb->prefix . CORNER_AD_TABLE ) == 0 ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

						// Insert
						$success = $wpdb->insert(
							$wpdb->prefix . CORNER_AD_TABLE,
							array(
								'name'     => $corner_ad_name,
								'alignTo'  => 'tl',
								'mirror'   => $corner_ad_mirror,
								'colorIn'  => $corner_ad_colorIn,
								'openIn'   => $corner_ad_openIn,
								'closeIn'  => $corner_ad_closeIn,
								'adURL'    => $corner_ad_adURL,
								'target'   => $corner_ad_target,
								'desktop'  => $corner_ad_desktop_device,
								'mobile'   => $corner_ad_mobile_device,
								'fromDate' => $corner_ad_from,
								'toDate'   => $corner_ad_to,
								'extra'    => json_encode( $corner_ad_extra ),
							),
							array( '%s', '%s', '%d', '%s', '%d', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s' )
						);

						$id = $wpdb->insert_id;

						if ( ! empty( $corner_ad_imgPath ) ) {
							$wpdb->insert(
								$wpdb->prefix . CORNER_AD_IMG_TABLE,
								array(
									'ad'        => $id,
									'imgPath'   => $corner_ad_imgPath,
									'thumbPath' => $corner_ad_thumbPath,
								),
								array( '%d', '%s', '%s' )
							);
						}
					}

					$output .= '<div class="notice notice-success"><div style="padding:20px;">' .
								__( 'The corner Ad has been stored successfully', 'corner-ad' ) . '<br>' .
								__( 'To use it insert the shortcode into the page/post content', 'corner-ad' ) . ': <b>[corner-ad id="' . $id . '"]</b><br>' .
								__( 'To inser the ads shortcode directly into the template files of the active theme, use the piece of code', 'corner-ad' ) . ': <b>&lt;?php print do_shortcode(\'[corner-ad id="' . $id . '"]\'); ?&gt;</b></div></div>';
				}
		}

		$title = ( isset( $_REQUEST['id'] ) ) ? __( 'Edit the Corner Ad', 'corner-ad' ) : __( 'Create a new Corner Ad', 'corner-ad' );
		if ( count( $error ) ) {
			$output .= '<div class="error settings-error">' . implode( '<br />', $error ) . '</div>';
		}

		$output .= '
                    <form action="options-general.php?page=corner-ad.php" method="post">
                    <input type="hidden" name="corner_ad_edition_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />
                    <input type="hidden" name="action" value="ad_save" />
                    ' . ( ( ! empty( $id ) ) ? '<input type="hidden" name="id" value="' . esc_attr( $id ) . '">' : '' ) . '
                    <div class="postbox">
                        <h2 class="handle" style="padding:5px;"><span>' . $title . '</span></h2>
                        <div class="inside">
                            <table class="form-table">
								<tr valign="top">
									<th>' . esc_html__( 'Ad name', 'corner-ad' ) . '*</th>
									<td>
										<input aria-label="' . esc_attr__( 'Ad name', 'corner-ad' ) . '" type="text" name="corner_ad_name" size="40" value="' . ( ( isset( $corner_ad_name ) ) ? esc_attr( $corner_ad_name ) : '' ) . '" />
									</td>
								</tr>
                            	<tr valign="top">
									<th>' . esc_html__( 'Enter Ad link', 'corner-ad' ) . '*</th>
									<td>
										<input aria-label="' . esc_attr__( 'Ad link', 'corner-ad' ) . '" type="text" name="corner_ad_adURL" size="40" value="' . ( ( isset( $corner_ad_adURL ) ) ? esc_attr( $corner_ad_adURL ) : '' ) . '" />
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Open Ad in', 'corner-ad' ) . '</th>
									<td>
                                        <select aria-label="' . esc_attr__( 'Open in self or blank', 'corner-ad' ) . '" name="corner_ad_target">
                                            <option value="_blank" ' . ( ( isset( $corner_ad_target ) && '_blank' == $corner_ad_target ) ? 'SELECTED' : '' ) . '>' . esc_html__( 'New page', 'corner-ad' ) . '</option>
                                            <option value="_self" ' . ( ( isset( $corner_ad_target ) && '_self' == $corner_ad_target ) ? 'SELECTED' : '' ) . '>' . esc_html__( 'Self page', 'corner-ad' ) . '</option>
                                        </select>
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Select Ad image', 'corner-ad' ) . '*</th>
									<td><i>*' . esc_html__( 'It is recommended the use of square images greater than 400x400 pixels', 'corner-ad' ) . '</i>';

		$hasThumb = ( ! empty( $corner_ad_thumbPath ) && $corner_ad_thumbPath != $corner_ad_imgPath ) ? true : false;

		$output .= '                 <div>
                                        <input aria-label="' . esc_attr__( 'Ad image', 'corner-ad' ) . '" type="text" name="corner_ad_imgPath" size="40" value="' . ( ( ! empty( $corner_ad_imgPath ) ) ? esc_attr( $corner_ad_imgPath ) : '' ) . '" /> <input type="button" class="corner_ad_button_for_upload button" value="' . esc_attr__( 'Browse', 'corner-ad' ) . '" /> <input type="button" class="corner_ad_button_for_add_img_field button" value="Add another one" /> <input type="button" class="corner_ad_button_for_rmv_img_field button" value="Remove the image" DISABLED /><br />
										<input aria-label="' . esc_attr__( 'Thumbnail image', 'corner-ad' ) . '" type="text" name="corner_ad_thumbPath" size="40" value="' . ( $hasThumb ? esc_attr( $corner_ad_thumbPath ) : '' ) . '" placeholder="' . esc_attr__( 'Thumbnail URL', 'corner-ad' ) . '" ' . ( ! $hasThumb ? 'style="display:none;"' : '' ) . ' /> <input type="button" class="corner_ad_button_for_upload button" value="' . esc_attr__( 'Browse', 'corner-ad' ) . '" ' . ( ! $hasThumb ? 'style="display:none;"' : '' ) . ' /> <input type="checkbox" name="corner_ad_generateThum" ' . ( ! $hasThumb ? 'CHECKED' : '' ) . ' class="corner_ad_thumb_chk" />' . esc_html__( 'Get the thumbnail of the Ad\'s image', 'corner-ad' ) . '
                                    </div>
                                    <tr valign="top">
									<th>' . esc_html__( 'Select an audio file to play in background', 'corner-ad' ) . '</th>
									<td>
										<input aria-label="' . esc_attr__( 'Ad audio', 'corner-ad' ) . '" type="text" size="40" DISABLED /> <input type="button" value="' . esc_attr__( 'Browse', 'corner-ad' ) . '" DISABLED />
                                        The <a href="https://wordpress.dwbooster.com/content-tools/corner-ad" target="_blank">commercial version</a> of plugin allows to select an audio file to play in background.
                                    </td>
								</tr>
                                ';

		$corner_ad_from = ( empty( $corner_ad_from ) || '0000-00-00' == $corner_ad_from ) ? '' : $corner_ad_from;
		$corner_ad_to   = ( empty( $corner_ad_to ) || '0000-00-00' == $corner_ad_to ) ? '' : $corner_ad_to;

		$output .= '              </td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Set as mirror', 'corner-ad' ) . '</th>
									<td>
										<input aria-label="' . esc_attr__( 'Set as mirror', 'corner-ad' ) . '" type="checkbox" name="corner_ad_mirror" ' . ( ( ! isset( $corner_ad_mirror ) || $corner_ad_mirror ) ? 'checked' : '' ) . ' />									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Use corner with color', 'corner-ad' ) . '</th>
									<td>
                                        <input aria-label="' . esc_attr__( 'Color code', 'corner-ad' ) . '" type="text" name="corner_ad_colorIn" id="corner_ad_colorIn" value="' . ( ( isset( $corner_ad_colorIn ) ) ? esc_attr( $corner_ad_colorIn ) : '#FFFFFF' ) . '" style="background-color:' . ( ( isset( $corner_ad_colorIn ) ) ? esc_attr( $corner_ad_colorIn ) : '#FFFFFF' ) . ';" />
                                        <div id="corner_ad_colorIn_picker"></div>
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Display Ad in corner', 'corner-ad' ) . '</th>
									<td>
                                        <select aria-label="' . esc_attr__( 'Align to corner', 'corner-ad' ) . '" name="corner_ad_alignTo" DISABLED>
                                            <option value="tl">' . esc_html__( 'Top-Left', 'corner-ad' ) . '</option>
                                        </select>
                                        The free version of plugin allows to display the Ad only in the Left-Top corner only. The <a href="https://wordpress.dwbooster.com/content-tools/corner-ad" target="_blank">commercial version</a> allows to select between the Left or Right top corner.
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Load Ad on device', 'corner-ad' ) . '</th>
									<td>
                                        <input type="checkbox" name="corner_ad_desktop_device" ' . ( ! isset( $corner_ad_desktop_device ) || ! empty( $corner_ad_desktop_device ) ? 'CHECKED' : '' ) . ' />' . esc_html__( 'Desktop', 'corner-ad' ) . '
                                        <br>
                                        <input type="checkbox" name="corner_ad_mobile_device" ' . ( ! isset( $corner_ad_mobile_device ) || ! empty( $corner_ad_mobile_device ) ? 'CHECKED' : '' ) . ' />' . esc_html__( 'Mobile', 'corner-ad' ) . '
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Open corner in', 'corner-ad' ) . '</th>
									<td><input aria-label="' . esc_attr__( 'Time in seconds', 'corner-ad' ) . '" type="text" name="corner_ad_openIn" value="' . ( ( isset( $corner_ad_openIn ) && $corner_ad_openIn > 0 ) ? esc_attr( $corner_ad_openIn ) : '' ) . '">' . esc_html__( 'Seconds', 'corner-ad' ) . '
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'Close corner in', 'corner-ad' ) . '</th>
									<td><input aria-label="' . esc_attr__( 'Time in seconds', 'corner-ad' ) . '" type="text" name="corner_ad_closeIn" value="' . ( ( isset( $corner_ad_closeIn ) && $corner_ad_closeIn > 0 ) ? esc_attr( $corner_ad_closeIn ) : '' ) . '">' . esc_html__( 'Seconds', 'corner-ad' ) . '
									</td>
								</tr>
								<tr>
									<th colspan="2">
										' . esc_html__( 'Schedule', 'corner-ad' ) . '
										<hr />
										<div style="font-weight:normal;font-style:italic;">(' . esc_html__( 'The from and to attributes are optional, fill them only if you want to restrict the ad by date', 'corner-ad' ) . ')</div>
									</th>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'From', 'corner-ad' ) . '
									</th>
									<td><input aria-label="' . esc_attr__( 'From date', 'corner-ad' ) . '" type="text" name="corner_ad_from" value="' . ( ( ! empty( $corner_ad_from ) ) ? esc_attr( $corner_ad_from ) : '' ) . '" pattern="([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))" title="' . esc_attr__( 'Enter a valid date with the format: yyyy-mm-dd', 'corner-ad' ) . '">
									</td>
								</tr>
                                <tr valign="top">
									<th>' . esc_html__( 'To', 'corner-ad' ) . '</th>
									<td><input aria-label="' . esc_attr__( 'To date', 'corner-ad' ) . '" type="text" name="corner_ad_to" value="' . ( ( ! empty( $corner_ad_to ) ) ? esc_attr( $corner_ad_to ) : '' ) . '" pattern="([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))" title="' . esc_attr__( 'Enter a valid date with the format: yyyy-mm-dd', 'corner-ad' ) . '">
									</td>
								</tr>
                            </table>
                        </div>
                    </div>';

		// Google Analytics
		$output .= '
                    <div class="postbox">
                        <h2 class="handle" style="padding:5px;"><span>' . esc_html__( 'Google Analytics Integration', 'corner-ad' ) . '</span></h2>
                        <div class="inside">
                            <p>' . esc_html__( 'Are you interested in how to add custom event tracking to your ads with Google Analytics? You can set up custom categories, labels, and actions for your ads clicks with Google Analytics. If Google Analytics Tracking Id was not entered, this section takes no action.', 'corner-ad' ) . '</p>
                            <table class="form-table">
								<tr valign="top">
									<th>' . esc_html__( 'Google Analytics - event category', 'corner-ad' ) . '</th>
									<td>
										<input aria-label="' . esc_attr__( 'category', 'corner-ad' ) . '" type="text" name="corner_ad_ga_category" size="40" value="' . esc_attr( ! empty( $corner_ad_extra['ga_category'] ) ? $corner_ad_extra['ga_category'] : '' ) . '" />
									</td>
								</tr>
								<tr valign="top">
									<th>' . esc_html__( 'Google Analytics - event action', 'corner-ad' ) . '</th>
									<td>
										<input aria-label="' . esc_attr__( 'action', 'corner-ad' ) . '" type="text" name="corner_ad_ga_action" size="40" value="' . esc_attr( ! empty( $corner_ad_extra['ga_action'] ) ? $corner_ad_extra['ga_action'] : '' ) . '" />
									</td>
								</tr>
								<tr valign="top">
									<th>' . esc_html__( 'Google Analytics - event label', 'corner-ad' ) . '</th>
									<td>
										<input aria-label="' . esc_attr__( 'label', 'corner-ad' ) . '" type="text" name="corner_ad_ga_label" size="40" value="' . esc_attr( ! empty( $corner_ad_extra['ga_label'] ) ? $corner_ad_extra['ga_label'] : '' ) . '" />
									</td>
								</tr>
                            </table>
                        </div>
                    </div>';

		$output .= '
                    <div style="text-align:center"><input type="submit" value="' . esc_attr__( 'Save Corner Ad', 'corner-ad' ) . '" class="button-primary" /> <a href="options-general.php?page=corner-ad.php" class="button-secondary">' . esc_html__( 'Back to the list', 'corner-ad' ) . '</a></div>
                    </form>
                    <script>
                        jQuery(function(){
                            jQuery("#corner_ad_colorIn_picker").hide();
                            jQuery("#corner_ad_colorIn_picker").farbtastic("#corner_ad_colorIn");
                            jQuery("#corner_ad_colorIn").on("click",function(){jQuery("#corner_ad_colorIn_picker").slideToggle()});
	                    });
                    </script>
                   ';
		return $output;
	} // End corner_ad_settings_page_form
}

if ( ! function_exists( 'corner_ad_get_images' ) ) {
	function corner_ad_process_images( &$obj, $size = 'large' ) {
		global $wpdb;
		if ( preg_match( '/attachment_id=(\d+)/', $obj->url, $matches ) ) {
			$obj->id = $matches[1];
		} else {
			$id = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'posts WHERE guid=%s', $obj->url ) );
			if ( $id ) {
				$obj->id = $id;
			}
		}

		if ( ! empty( $obj->id ) ) {
			$resized  = wp_get_attachment_image_src( $obj->id, $size );
			$obj->url = $resized[0];
		}
	} // End corner_ad_process_images

	function corner_ad_get_images( $url, $thumbUrl, $to_show = false ) {
		$img             = new stdClass();
		$img->thumb      = new stdClass();
		$img->large      = new stdClass();
		$img->thumb->url = ( ! empty( $thumbUrl ) ) ? $thumbUrl : $url;
		$img->large->url = $url;
		corner_ad_process_images( $img->thumb, $to_show ? 'corner_ad_thumb' : 'large' );
		corner_ad_process_images( $img->large, $to_show ? 'corner_ad' : 'large' );
		return $img;
	} // End corner_ad_get_images
}
