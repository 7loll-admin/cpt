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
		register_rest_route( $this->namespace, $this->resource_name . '/getPostMetas', array(
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_post_metas' ],
			'permission_callback' => [ $this, 'login_permission_callback' ],
		) );
		register_rest_route( $this->namespace, $this->resource_name . '/clearCache', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'clear_data_cache' ],
			'permission_callback' => [ $this, 'login_permission_callback' ],
		) );
	}

	/**
	 * @return true
	 */
	public function clear_data_cache() {
		$result = [
			'updated' => false,
			'message' => esc_html__( 'Action Failed ', 'cptwooint-media-tools' )
		];
		$prefix = '_transient_cptwooint_';
		// Get all transients with the specified prefix
		global $wpdb;
		$query = $wpdb->prepare(
			"SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s",
			$wpdb->esc_like($prefix) . '%'
		);
		$transients = $wpdb->get_results($query);
		// Delete transients
		$delete = [];
		foreach ($transients as $transient) {
			$trans = str_replace('_transient_', '', $transient->option_name);
			if( delete_transient( $trans ) ){
				$delete[] = $trans;
			}
		}
		$result['updated'] = count( $transients ) === count( $delete );
		if( $result['updated'] ){
			$result['message'] =  esc_html__( 'Cache Cleared.', 'boilerplate-media-tools' );
		}
		return $result;
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
		return wp_json_encode( $post_type_array );
	}

	/**
	 * @return false|string
	 */
	public function get_post_metas( $request_data ) {

		$parameters = $request_data->get_params();
		$post_metas = [];
		if( ! empty( $parameters['post_type'] ) ){
			$post_type = $parameters['post_type'];
			// Get all meta keys saved in posts of the specified post type
			$transient_key        = 'cptwooint_meta_query_' . $post_type;
			$post_metas      = get_transient( $transient_key );
			if ( empty( $post_metas ) ) {
				//delete_transient( $key );
				global $wpdb;
				$post_metas = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT DISTINCT meta_key 
				        FROM $wpdb->postmeta 
				        INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID 
				        WHERE $wpdb->posts.post_type = %s",
						$post_type
					)
				);
				set_transient( $transient_key, $post_metas, DAY_IN_SECONDS );
			}

		}

		$the_metas = [];
		if( ! empty( $post_metas ) ){
			$remove_wp_default = [
				'_edit_lock',
				'_edit_last',
				'_pingme',
				'_encloseme'
			];
			foreach ( $post_metas as $result ) {
				if( in_array( $result->meta_key, $remove_wp_default ) ) continue;
				$the_metas[] = [
					'value' => $result->meta_key,
					'label' => $result->meta_key,
				];
			}
		}

		return wp_json_encode( $the_metas );
	}


}




