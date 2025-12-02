<?php
/**
 * WP-CLI Commands for FFF Danse
 *
 * @package FFF_Danse
 */

namespace FFF_Danse\Includes;

use FFF_Danse\Includes\Importer;
use FFF_Danse\Includes\Parser;

/**
 * WP-CLI command handler.
 */
class WPCLI {

	/**
	 * Import a single YouTube video.
	 *
	 * ## OPTIONS
	 *
	 * <video_id>
	 * : YouTube video ID or URL
	 *
	 * <post_id>
	 * : WordPress post ID to import into
	 *
	 * [--overwrite]
	 * : Overwrite existing field values
	 * ---
	 * default: true
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp fff import dQw4w9WgXcQ 123
	 *     wp fff import "https://www.youtube.com/watch?v=dQw4w9WgXcQ" 123 --overwrite=true
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 */
	public static function cmd_import( $args, $assoc_args ) {
		if ( count( $args ) < 2 ) {
			\WP_CLI::error( __( 'Usage: wp fff import <video_id> <post_id>', 'fff-danse' ) );
			return;
		}

		list( $video_input, $post_id ) = $args;
		$post_id = absint( $post_id );

		$overwrite = isset( $assoc_args['overwrite'] ) ? filter_var( $assoc_args['overwrite'], FILTER_VALIDATE_BOOLEAN ) : true;

		\WP_CLI::line( sprintf( __( 'Importing YouTube video %s into post %d...', 'fff-danse' ), $video_input, $post_id ) );

		$result = Importer::import_video( $post_id, $video_input, $overwrite );

		if ( is_wp_error( $result ) ) {
			\WP_CLI::error( sprintf( __( 'Import failed: %s', 'fff-danse' ), $result->get_error_message() ) );
			return;
		}

		\WP_CLI::success(
			sprintf(
				__( 'Import successful! Updated %d fields: %s', 'fff-danse' ),
				count( $result['updated_fields'] ),
				implode( ', ', $result['updated_fields'] )
			)
		);

		\WP_CLI::line( sprintf( __( 'Video title: %s', 'fff-danse' ), $result['title'] ) );
		\WP_CLI::line( sprintf( __( 'Video ID: %s', 'fff-danse' ), $result['video_id'] ) );
	}

	/**
	 * Import all videos from a list or search existing posts.
	 *
	 * ## OPTIONS
	 *
	 * [--file=<file>]
	 * : Path to file containing video IDs (one per line)
	 *
	 * [--create]
	 * : Create new posts if they don't exist
	 * ---
	 * default: false
	 * ---
	 *
	 * [--limit=<limit>]
	 * : Limit number of posts to process (for testing)
	 *
	 * ## EXAMPLES
	 *
	 *     wp fff import-all --file=/path/to/videos.txt --create
	 *     wp fff import-all --limit=10
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 */
	public static function cmd_import_all( $args, $assoc_args ) {
		$video_ids = [];
		$create_posts = isset( $assoc_args['create'] ) ? filter_var( $assoc_args['create'], FILTER_VALIDATE_BOOLEAN ) : false;
		$limit = isset( $assoc_args['limit'] ) ? absint( $assoc_args['limit'] ) : 0;

		// If file provided, read video IDs from file
		if ( ! empty( $assoc_args['file'] ) ) {
			$file_path = $assoc_args['file'];
			if ( ! file_exists( $file_path ) ) {
				\WP_CLI::error( sprintf( __( 'File not found: %s', 'fff-danse' ), $file_path ) );
				return;
			}

			$file_content = file_get_contents( $file_path );
			$lines = explode( "\n", $file_content );

			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( empty( $line ) || strpos( $line, '#' ) === 0 ) {
					continue; // Skip empty lines and comments
				}

				$video_id = Parser::extract_video_id( $line );
				if ( $video_id ) {
					$video_ids[] = $video_id;
				}
			}

			\WP_CLI::line( sprintf( __( 'Found %d video IDs in file.', 'fff-danse' ), count( $video_ids ) ) );
		} else {
			// Import all existing posts that have video_videofil meta
			\WP_CLI::line( __( 'Searching for posts with video_videofil meta...', 'fff-danse' ) );

			$query_args = [
				'post_type'      => Importer::CPT,
				'posts_per_page' => $limit > 0 ? $limit : -1,
				'fields'         => 'ids',
				'meta_query'     => [
					[
						'key'     => 'video_videofil',
						'compare' => 'EXISTS',
					],
				],
			];

			$posts = get_posts( $query_args );

			foreach ( $posts as $post_id ) {
				$video_id = get_post_meta( $post_id, 'video_videofil', true );
				if ( ! empty( $video_id ) && Parser::extract_video_id( $video_id ) ) {
					$video_ids[] = [
						'post_id'  => $post_id,
						'video_id' => $video_id,
					];
				}
			}

			\WP_CLI::line( sprintf( __( 'Found %d posts with video IDs.', 'fff-danse' ), count( $video_ids ) ) );
		}

		if ( empty( $video_ids ) ) {
			\WP_CLI::warning( __( 'No video IDs found to import.', 'fff-danse' ) );
			return;
		}

		// Process videos
		\WP_CLI::line( sprintf( __( 'Starting import of %d videos...', 'fff-danse' ), count( $video_ids ) ) );

		$progress = \WP_CLI\Utils\make_progress_bar( __( 'Importing videos', 'fff-danse' ), count( $video_ids ) );
		$success_count = 0;
		$error_count = 0;

		foreach ( $video_ids as $item ) {
			$video_input = is_array( $item ) ? $item['video_id'] : $item;
			$post_id = is_array( $item ) ? $item['post_id'] : null;

			if ( $post_id ) {
				// Import into existing post
				$result = Importer::import_video( $post_id, $video_input, true );
			} else {
				// Need to create post or find existing
				$posts = get_posts(
					[
						'post_type'  => Importer::CPT,
						'meta_key'   => 'video_videofil',
						'meta_value' => $video_input,
						'posts_per_page' => 1,
						'fields'     => 'ids',
					]
				);

				if ( ! empty( $posts ) ) {
					$post_id = $posts[0];
					$result = Importer::import_video( $post_id, $video_input, true );
				} elseif ( $create_posts ) {
					$post_id = wp_insert_post(
						[
							'post_type'   => Importer::CPT,
							'post_status' => 'publish',
							'post_title'  => __( 'Importing...', 'fff-danse' ),
						],
						true
					);

					if ( ! is_wp_error( $post_id ) ) {
						$result = Importer::import_video( $post_id, $video_input, true );
					} else {
						$result = $post_id;
					}
				} else {
					$result = new \WP_Error( 'no_post', __( 'Post not found and --create not specified.', 'fff-danse' ) );
				}
			}

			if ( is_wp_error( $result ) ) {
				$error_count++;
			} else {
				$success_count++;
			}

			$progress->tick();
		}

		$progress->finish();

		\WP_CLI::line( '' );
		\WP_CLI::success(
			sprintf(
				__( 'Import complete! %d succeeded, %d failed.', 'fff-danse' ),
				$success_count,
				$error_count
			)
		);
	}
}



