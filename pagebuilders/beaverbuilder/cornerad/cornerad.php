<?php
class CornerAdBeaver extends FLBuilderModule {
	public function __construct() {
		 $modules_dir = dirname( __FILE__ ) . '/';
		$modules_url  = plugins_url( '/', __FILE__ ) . '/';

		parent::__construct(
			array(
				'name'            => __( 'Corner Ad', 'corner-ad' ),
				'description'     => __( 'Inserts an Ad', 'corner-ad' ),
				'group'           => __( 'Corner Ad', 'corner-ad' ),
				'category'        => __( 'Corner Ad', 'corner-ad' ),
				'dir'             => $modules_dir,
				'url'             => $modules_url,
				'partial_refresh' => true,
			)
		);
	}
}
