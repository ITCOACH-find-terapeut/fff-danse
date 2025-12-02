<?php
/**
 * AJAX Handler
 *
 * Handles AJAX requests for YouTube import.
 *
 * @package FFF_Danse
 */

namespace FFF_Danse\Includes;

use FFF_Danse\Includes\Importer;

/**
 * AJAX handler for YouTube imports.
 */
class Ajax {

	/**
	 * Register AJAX hooks.
	 */
	public static function init() {
		add_action( 'wp_ajax_fff_danse_fetch_youtube', [ __CLASS__, 'handle_fetch_youtube' ] );
	}

	/**
	 * Handle AJAX request to fetch YouTube data.
	 */
	public static function handle_fetch_youtube() {
		// Check permissions
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'fff-danse' ) ], 403 );
			return;
		}

		// Verify nonce
		check_ajax_referer( 'fff_danse_fetch', 'nonce' );

		// Get parameters
		$post_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$video_raw = isset( $_POST['video'] ) ? trim( wp_unslash( $_POST['video'] ) ) : '';

		if ( ! $post_id || empty( $video_raw ) ) {
			wp_send_json_error(
				[ 'message' => __( 'Missing post ID or video ID/URL.', 'fff-danse' ) ],
				400
			);
			return;
		}

		// Check API key
		$api_key = Importer::get_api_key();
		if ( empty( $api_key ) ) {
			wp_send_json_error(
				[ 'message' => __( 'YouTube API key not configured. Please set it in Settings → FFF Danse.', 'fff-danse' ) ],
				400
			);
			return;
		}

		// Import video
		$result = Importer::import_video( $post_id, $video_raw, true );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				[ 'message' => $result->get_error_message() ],
				500
			);
			return;
		}

		// Success
		$message = sprintf(
			__( 'YouTube data imported successfully. Updated %d field(s): %s', 'fff-danse' ),
			count( $result['updated_fields'] ),
			implode( ', ', $result['updated_fields'] )
		);

		wp_send_json_success(
			[
				'message'       => $message,
				'updated'       => $result['updated_fields'],
				'video_id'      => $result['video_id'],
				'video_title'   => $result['title'],
			]
		);
	}
}



