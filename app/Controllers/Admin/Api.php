<?php

namespace TinySolutions\cptwooint\Controllers\Admin;

use TinySolutions\cptwooint\Helpers\Fns;
use TinySolutions\cptwooint\Traits\SingletonTrait;

class Api {

	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Construct
	 */
	private function __construct() {
		$this->namespace     = 'TinySolutions/cptwooint/v1';
		$this->resource_name = '/cptwooint';
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register our routes.
	 * @return void
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, $this->resource_name . '/getOptions', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_options' ],
			'permission_callback' => [ $this, 'login_permission_callback' ],
		) );
		register_rest_route( $this->namespace, $this->resource_name . '/updateOptions', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'update_option' ],
			'permission_callback' => [ $this, 'login_permission_callback' ],
		) );
		register_rest_route( $this->namespace, $this->resource_name . '/getPostTypes', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_post_types' ],
			'permission_callback' => [ $this, 'login_permission_callback' ],
		) );
	}

	/**
	 * @return true
	 */
	public function login_permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * @return false|string
	 */
	public function update_option( $request_data ) {

		$result = [
			'updated' => false,
			'message' => esc_html__( 'Update failed. Maybe change not found. ', 'cptwooint-media-tools' )
		];

		$parameters = $request_data->get_params();

		$the_settings = get_option( 'cptwooint_settings', [] );

		$the_settings['selected_post_types'] = isset( $parameters['selected_post_types'] ) ? $parameters['selected_post_types'] : [];

		$options = update_option( 'cptwooint_settings', $the_settings );

		$result['updated'] =  boolval( $options );

		if( $result['updated'] ){
			$result['message'] =  esc_html__( 'Updated.', 'boilerplate-media-tools' );
		}

		return $result;
	}

	/**
	 * @return false|string
	 */
	public function get_options() {
		$options = Fns::get_options();
//
//		$options['selected_post_types'] = [
//			'post'       => 'metaValue',
//		];

		// postType

		return wp_json_encode( $options );
	}

	/**
	 * @return false|string
	 */
	public function get_post_types() {
		$cpt_args = [
			'public' => true,
			'_builtin' => false
		];
		$post_types = get_post_types( $cpt_args, 'objects' );
		$post_type_array = [
			[
				'value' => 'post',
				'label' => 'Posts',
			]
		];
		foreach ( $post_types as $key => $post_type ) {
			if( 'product' === $key ) continue;
			$post_type_array[] = [
				'value' => $post_type->name,
				'label' => $post_type->label,
			];
		}
		//error_log( print_r( $post_type_array, true ) . "\n\n", 3, __DIR__ . '/the_log.txt' );
		return wp_json_encode( $post_type_array );
	}

}




