<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Advanced_Import_Server' ) ) {
	/**
	 * Advanced Import
	 * @package Gutentor
	 * @since 1.0.1
	 *
	 */
	class Gutentor_Advanced_Import_Server extends WP_Rest_Controller {

		/**
		 * Rest route namespace.
		 *
		 * @var Gutentor_Advanced_Import_Server
		 */
		public $namespace = 'gutentor-advanced-import/';

		/**
		 * Rest route version.
		 *
		 * @var Gutentor_Advanced_Import_Server
		 */
		public $version = 'v1';

		/**
		 * Initialize the class
		 */
		public function run() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
        }

		/**
		 * Register REST API route
		 */
		public function register_routes() {
			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/fetch_templates',
				array(
					array(
						'methods'	=> \WP_REST_Server::READABLE,
						'callback'	=> array( $this, 'fetch_templates' ),
                        'permission_callback' => function () {
                            return current_user_can( 'edit_posts' );
                        },
                        'args'		=> array(
                            'reset'	=> array(
                                'type'        => 'boolean',
                                'required'    => false,
                                'description' => __( 'Reset True or False', 'gutentor' ),
                            ),
                        ),

					),
				)
			);

			register_rest_route(
				$namespace,
				'/import_template',
				array(
					array(
						'methods'	=> \WP_REST_Server::READABLE,
						'callback'	=> array( $this, 'import_template' ),
                        'permission_callback' => function () {
                            return current_user_can( 'edit_posts' );
                        },
						'args'		=> array(
							'url'	=> array(
								'type'        => 'string',
								'required'    => true,
								'description' => __( 'URL of the JSON file.', 'gutentor' ),
							),
						),
					),
				)
			);
		}

        /**
         * Function to delete templates and bock json transient
         *
         * @since 2.0.9
         * @return void
         */
        public function delete_transient() {
            /*Delete Template Library Transient*/
            delete_transient( 'gutentor_get_template_library' );

            /*Delete Block Json Transient*/
            global $wpdb;
            $transients = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_gutentor_get_block_json_%'" );

            if ( $transients ) {
                foreach ( $transients as $transient ) {
                    $transient = preg_replace( '/^_transient_/i', '', $transient );
                    delete_transient( $transient );
                }
            }
        }

		/**
		 * Function to fetch templates.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function fetch_templates( \WP_REST_Request $request ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}
            if ( $request->get_param( 'reset' ) ) {
                $this->delete_transient();
            }

            $templates_list = get_transient( 'gutentor_get_template_library' );

            /*Get/Fetch templates*/
            if ( empty( $templates_list ) ) {
                if( !function_exists('run_gutentor_template_library') ){
                    /*if gutentor template library is not installed
                fetch template library data from live*/
                    $url = 'https://www.demo.gutentor.com/wp-json/gutentor-tlapi/v1/fetch_templates/';
                    $body_args = [
                        /*API version*/
                        'api_version' => wp_get_theme()['Version'],
                        /*lang*/
                        'site_lang' => get_bloginfo( 'language' ),
                    ];
                    $raw_json = wp_safe_remote_get( $url, [
                        'timeout' => 100,
                        'body' => $body_args,
                    ] );

                    if ( ! is_wp_error( $raw_json ) ) {
                        $demo_server = json_decode( wp_remote_retrieve_body( $raw_json ), true );
                        if (json_last_error() === JSON_ERROR_NONE) {
                            if( is_array( $demo_server )){
                                $templates_list = $demo_server;
                            }
                        }
                    }
                }
                else{
                    /*if gutentor template library is installed
                    fetch template library data from the plugin gutentor-template-library
                    special hooks for gutentor-template-library plugin*/
                    $templates_list = apply_filters( 'gutentor_advanced_import_gutentor_template_library', array() );
                }

                /*Store on transient*/
                set_transient( 'gutentor_get_template_library', $templates_list, DAY_IN_SECONDS );
            }

            $templates = apply_filters( 'gutentor_advanced_import_templates', $templates_list );

            return rest_ensure_response( $templates );
		}

		/**
		 * Function to fetch template JSON.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function import_template( $request ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			$url = $request->get_param( 'url' );

            $url_array = explode('/', $url);
            $block_id=  $url_array[count($url_array)-2];
            $block_json = get_transient( 'gutentor_get_block_json_'.$block_id );

            /*Get/Fetch templates*/
            if ( empty( $block_json ) ) {
                if ( $url ) {
                    $body_args = [
                        /*API version*/
                        'api_version' => GUTENTOR_VERSION,
                        /*lang*/
                        'site_lang' => get_bloginfo( 'language' ),
                    ];
                    $raw_json = wp_safe_remote_get( $url, [
                        'timeout' => 100,
                        'body' => $body_args,
                    ] );

                    if ( ! is_wp_error( $raw_json ) ) {
                        $block_json = json_decode( wp_remote_retrieve_body( $raw_json ) );
                        /*Store on transient*/
                        set_transient( 'gutentor_get_block_json_'.$block_id , $block_json, DAY_IN_SECONDS );
                    }
                }
            }
            if ( $block_json ) {
                return rest_ensure_response( $block_json );
            }
			return false;
		}

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @return object
		 */
		public static function get_instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}
	}

}
Gutentor_Advanced_Import_Server::get_instance()->run();