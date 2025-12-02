<?php
/**
 * YouTube Description Parser
 *
 * Parses structured YouTube video descriptions into field values.
 *
 * @package FFF_Danse
 */

namespace FFF_Danse\Includes;

use FFF_Danse\Includes\Fields;

/**
 * Parser for YouTube video descriptions.
 */
class Parser {

	/**
	 * Mapping of structured block headers to field keys.
	 *
	 * @var array
	 */
	private static $block_mappings = [
		'intro'  => 'video_intro',
		'se1'    => 'video_se1',
		'se2'    => 'video_se2',
		'se3'    => 'video_se3',
		'lær1'   => 'video_lær1',
		'lær2'   => 'video_lær2',
		'lær3'   => 'video_lær3',
		'dans1'  => 'video_dans1',
		'dans2'  => 'video_dans2',
		'dans3'  => 'video_dans3',
	];

	/**
	 * Parse YouTube description into field values.
	 *
	 * @param string $description YouTube video description.
	 * @param string $title       Video title.
	 * @param string $video_id    YouTube video ID.
	 * @return array Parsed field values keyed by field name.
	 */
	public static function parse( $description, $title = '', $video_id = '' ) {
		$parsed = [];

		if ( empty( $description ) ) {
			return $parsed;
		}

		// Normalize line endings
		$description = str_replace( [ "\r\n", "\r" ], "\n", $description );
		$lines       = explode( "\n", $description );

		// First, extract structured blocks (Intro:, SE1:, LÆR1:, etc.)
		$blocks = self::extract_structured_blocks( $lines );

		// Map blocks to field keys
		foreach ( $blocks as $block_key => $block_content ) {
			$field_key = self::map_block_to_field( $block_key );
			if ( $field_key ) {
				$parsed[ $field_key ] = trim( $block_content );
			}
		}

		// Then parse key:value pairs from remaining lines
		$key_value_pairs = self::extract_key_value_pairs( $lines, array_keys( $blocks ) );
		$parsed          = array_merge( $parsed, $key_value_pairs );

		// If title provided and not already parsed, store it
		if ( ! empty( $title ) && ! isset( $parsed['danse_navn'] ) ) {
			$parsed['danse_navn'] = wp_strip_all_tags( $title );
		}

		// If video_id provided and main video field not set, store it
		if ( ! empty( $video_id ) && ! isset( $parsed['video_videofil'] ) ) {
			$parsed['video_videofil'] = $video_id;
		}

		return $parsed;
	}

	/**
	 * Extract structured blocks from description lines.
	 *
	 * Looks for patterns like:
	 * - Intro: content here
	 * - SE1: content here
	 * - LÆR1: content here
	 * - DANS1: content here
	 *
	 * @param array $lines Description lines.
	 * @return array Blocks keyed by block identifier (lowercase, normalized).
	 */
	private static function extract_structured_blocks( $lines ) {
		$blocks     = [];
		$current_block = null;
		$current_content = [];

		foreach ( $lines as $line ) {
			$line = trim( $line );

			// Check if line starts a new structured block
			$block_match = self::match_block_header( $line );
			if ( $block_match ) {
				// Save previous block if exists
				if ( $current_block !== null && ! empty( $current_content ) ) {
					$blocks[ $current_block ] = implode( "\n", $current_content );
				}

				// Start new block
				$current_block   = $block_match['key'];
				$current_content = [];

				// If there's inline content after the header, add it
				if ( ! empty( $block_match['content'] ) ) {
					$current_content[] = $block_match['content'];
				}
			} elseif ( $current_block !== null ) {
				// Continue current block content
				// Stop if we hit another block-like pattern or empty line followed by key:value
				if ( self::is_block_boundary( $line ) ) {
					if ( ! empty( $current_content ) ) {
						$blocks[ $current_block ] = implode( "\n", $current_content );
					}
					$current_block   = null;
					$current_content = [];
				} else {
					$current_content[] = $line;
				}
			}
		}

		// Save final block
		if ( $current_block !== null && ! empty( $current_content ) ) {
			$blocks[ $current_block ] = implode( "\n", $current_content );
		}

		return $blocks;
	}

	/**
	 * Match a block header pattern.
	 *
	 * @param string $line Line to check.
	 * @return array|false Matched block info or false.
	 */
	private static function match_block_header( $line ) {
		// Pattern: Optional whitespace, block identifier, colon, optional whitespace, optional content
		// Supports: Intro:, SE1:, LÆR1:, DANS1:, etc.
		$pattern = '/^\s*([A-ZÆØÅ][A-ZÆØÅ0-9]{0,10}):\s*(.*)$/u';
		
		if ( preg_match( $pattern, $line, $matches ) ) {
			$block_key = strtolower( $matches[1] );
			
			// Normalize variations
			$block_key = self::normalize_block_key( $block_key );

			return [
				'key'     => $block_key,
				'content' => isset( $matches[2] ) ? trim( $matches[2] ) : '',
			];
		}

		return false;
	}

	/**
	 * Normalize block key to standard format.
	 *
	 * @param string $key Raw block key.
	 * @return string Normalized key.
	 */
	private static function normalize_block_key( $key ) {
		$normalizations = [
			'lære1' => 'lær1',
			'laer1' => 'lær1',
			'lære2' => 'lær2',
			'laer2' => 'lær2',
			'lære3' => 'lær3',
			'laer3' => 'lær3',
		];

		$key = strtolower( trim( $key ) );

		return isset( $normalizations[ $key ] ) ? $normalizations[ $key ] : $key;
	}

	/**
	 * Check if a line indicates a block boundary.
	 *
	 * @param string $line Line to check.
	 * @return bool True if boundary detected.
	 */
	private static function is_block_boundary( $line ) {
		// Empty line followed by key:value pattern suggests boundary
		if ( empty( trim( $line ) ) ) {
			return false; // Empty line doesn't break block
		}

		// If line matches key:value pattern (simple field), it's a boundary
		if ( preg_match( '/^\s*[a-z0-9_]+\s*:\s*.+$/ui', $line ) ) {
			return true;
		}

		// If line matches another block header, it's a boundary
		if ( self::match_block_header( $line ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Extract key:value pairs from remaining lines.
	 *
	 * @param array $lines          All description lines.
	 * @param array $block_keys     Already processed block keys.
	 * @return array Key-value pairs.
	 */
	private static function extract_key_value_pairs( $lines, $block_keys = [] ) {
		$pairs = [];
		$all_fields = Fields::get_fields();

		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( empty( $line ) ) {
				continue;
			}

			// Skip if this line was part of a structured block
			$block_match = self::match_block_header( $line );
			if ( $block_match && in_array( $block_match['key'], $block_keys, true ) ) {
				continue;
			}

			// Match key:value pattern
			// More flexible: allows field names with underscores, numbers, and special chars
			if ( preg_match( '/^\s*([A-Za-z0-9_æøåÆØÅ]+)\s*:\s*(.+)$/u', $line, $matches ) ) {
				$key   = strtolower( trim( $matches[1] ) );
				$value = trim( $matches[2] );

				// Only include if it's a valid field key
				if ( isset( $all_fields[ $key ] ) ) {
					// If key already exists (from block), append with newline
					if ( isset( $pairs[ $key ] ) ) {
						$pairs[ $key ] .= "\n" . $value;
					} else {
						$pairs[ $key ] = $value;
					}
				}
			}
		}

		return $pairs;
	}

	/**
	 * Map block identifier to field key.
	 *
	 * @param string $block_key Block identifier (e.g., 'intro', 'se1').
	 * @return string|false Field key or false if not mapped.
	 */
	private static function map_block_to_field( $block_key ) {
		if ( isset( self::$block_mappings[ $block_key ] ) ) {
			return self::$block_mappings[ $block_key ];
		}

		return false;
	}

	/**
	 * Extract video ID from YouTube URL or raw ID.
	 *
	 * @param string $input YouTube URL or video ID.
	 * @return string|false Video ID or false on failure.
	 */
	public static function extract_video_id( $input ) {
		$input = trim( $input );

		if ( empty( $input ) ) {
			return false;
		}

		// If it looks like a raw ID (11 characters, alphanumeric + _ -)
		if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $input ) ) {
			return $input;
		}

		// Try to extract from URL
		if ( filter_var( $input, FILTER_VALIDATE_URL ) ) {
			$parts = wp_parse_url( $input );

			// Standard YouTube URL: youtube.com/watch?v=VIDEO_ID
			if ( ! empty( $parts['query'] ) ) {
				parse_str( $parts['query'], $query );
				if ( ! empty( $query['v'] ) && preg_match( '/^[A-Za-z0-9_-]{11}$/', $query['v'] ) ) {
					return $query['v'];
				}
			}

			// Short youtu.be URL: youtu.be/VIDEO_ID
			if ( ! empty( $parts['host'] ) && $parts['host'] === 'youtu.be' && ! empty( $parts['path'] ) ) {
				$video_id = ltrim( $parts['path'], '/' );
				if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $video_id ) ) {
					return $video_id;
				}
			}

			// YouTube embed URL: youtube.com/embed/VIDEO_ID
			if ( ! empty( $parts['path'] ) && strpos( $parts['path'], '/embed/' ) === 0 ) {
				$video_id = str_replace( '/embed/', '', $parts['path'] );
				if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $video_id ) ) {
					return $video_id;
				}
			}
		}

		return false;
	}
}



