<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Register the categories
Plugin::$instance->elements_manager->add_category(
	'corner-ad-cat',
	array(
		'title' => 'Corner Ad',
		'icon'  => 'fa fa-plug',
	),
	2 // position
);
