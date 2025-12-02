<?php
/**
 * Main Plugin Class
 *
 * Initializes all plugin components.
 *
 * @package FFF_Danse
 */

namespace FFF_Danse;

use FFF_Danse\Includes\Ajax;
use FFF_Danse\Includes\Fields;
use FFF_Danse\Includes\Importer;
use FFF_Danse\Includes\WPCLI;

/**
 * Main plugin class.
 */
class Plugin {

	const CPT = 'danse';
	const VERSION = '1.0.0';

	/**
	 * Track if WP-CLI commands have been registered.
	 *
	 * @var bool
	 */
	private static $wpcli_registered = false;

	/**
	 * Initialize the plugin.
	 */
	public function __construct() {
		$this->register_hooks();

		// Register WP-CLI commands immediately if WP-CLI is running
		// (fallback for early registration before cli_init hook fires)
		if ( defined( 'WP_CLI' ) && WP_CLI && class_exists( '\WP_CLI' ) ) {
			$this->register_wpcli_commands();
		}
	}

	/**
	 * Register all WordPress hooks.
	 */
	private function register_hooks() {
		// Core registration
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'init', [ $this, 'register_meta_fields' ] );

		// AJAX
		Ajax::init();

		// WP-CLI commands (using action hook)
		add_action( 'cli_init', [ $this, 'register_wpcli_commands' ] );

		// Admin assets will be enqueued by meta boxes class
	}

	/**
	 * Register the custom post type.
	 */
	public function register_post_type() {
		$labels = [
			'name'               => __( 'Danse', 'fff-danse' ),
			'singular_name'      => __( 'Dans', 'fff-danse' ),
			'add_new'            => __( 'Tilføj dans', 'fff-danse' ),
			'add_new_item'       => __( 'Tilføj ny dans', 'fff-danse' ),
			'edit_item'          => __( 'Rediger dans', 'fff-danse' ),
			'new_item'           => __( 'Ny dans', 'fff-danse' ),
			'view_item'          => __( 'Vis dans', 'fff-danse' ),
			'search_items'       => __( 'Søg danse', 'fff-danse' ),
			'not_found'          => __( 'Ingen danse fundet', 'fff-danse' ),
			'not_found_in_trash' => __( 'Ingen danse fundet i papirkurven', 'fff-danse' ),
			'all_items'          => __( 'Alle danse', 'fff-danse' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_rest'        => true,
			'rest_base'           => 'danse',
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-tickets-alt',
			'hierarchical'        => false,
			'rewrite'             => [
				'slug' => 'danse',
			],
		];

		register_post_type( self::CPT, $args );
	}

	/**
	 * Register all meta fields with WordPress.
	 */
	public function register_meta_fields() {
		$fields = Fields::get_fields();

		foreach ( $fields as $key => $field ) {
			register_post_meta(
				self::CPT,
				$key,
				[
					'show_in_rest'  => true,
					'single'        => true,
					'type'          => 'string',
					'auth_callback' => function() {
						return current_user_can( 'edit_posts' );
					},
					'sanitize_callback' => 'sanitize_textarea_field',
				]
			);
		}
	}

	/**
	 * Register WP-CLI commands.
	 */
	public function register_wpcli_commands() {
		// Prevent duplicate registration
		if ( self::$wpcli_registered ) {
			return;
		}

		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		if ( ! class_exists( '\WP_CLI' ) ) {
			return;
		}

		\WP_CLI::add_command(
			'fff import',
			[ WPCLI::class, 'cmd_import' ],
			[
				'shortdesc' => __( 'Import a YouTube video into a post', 'fff-danse' ),
			]
		);

		\WP_CLI::add_command(
			'fff import-all',
			[ WPCLI::class, 'cmd_import_all' ],
			[
				'shortdesc' => __( 'Import multiple YouTube videos', 'fff-danse' ),
			]
		);

		self::$wpcli_registered = true;
	}
}


