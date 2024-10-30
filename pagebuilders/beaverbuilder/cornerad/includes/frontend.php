<?php
$ad = @intval( $settings->ad );
if ( ! empty( $ad ) ) {
	$output = '[corner-ad id="' . esc_attr( $ad ) . '"';

	$container = sanitize_text_field( $settings->ad_container );
	if ( ! empty( $container ) ) {
		$output .= ' container="' . esc_attr( $container ) . '"';
	}

	$output .= ']';
	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
}
