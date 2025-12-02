<?php
/**
 * Helper Functions
 *
 * Global helper functions for field retrieval.
 *
 * @package FFF_Danse
 */

namespace FFF_Danse;

use FFF_Danse\Includes\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a single field value.
 *
 * Wrapper around Fields::get_field() for easier template usage.
 *
 * @param string $field_key Field key.
 * @param int    $post_id   Post ID. Defaults to current post.
 * @param mixed  $default   Default value if field is empty.
 * @return mixed Field value or default.
 */
function get_field( $field_key, $post_id = null, $default = '' ) {
	return Fields::get_field( $field_key, $post_id, $default );
}

/**
 * Get all field values for a post.
 *
 * @param int  $post_id         Post ID. Defaults to current post.
 * @param bool $only_with_values Only return fields that have values.
 * @return array Array of field_key => value pairs.
 */
function get_all_fields( $post_id = null, $only_with_values = false ) {
	return Fields::get_all_fields( $post_id, $only_with_values );
}

/**
 * Get field label.
 *
 * @param string $field_key Field key.
 * @return string Field label or field key if not found.
 */
function get_field_label( $field_key ) {
	return Fields::get_field_label( $field_key );
}

/**
 * Check if a field exists.
 *
 * @param string $field_key Field key.
 * @return bool True if field exists.
 */
function field_exists( $field_key ) {
	return Fields::field_exists( $field_key );
}

/**
 * Get fields by group.
 *
 * @param string $group_key      Group key.
 * @param int    $post_id        Post ID. Defaults to current post.
 * @param bool   $only_with_values Only return fields that have values.
 * @return array Array of field_key => value pairs for the group.
 */
function get_fields_by_group( $group_key, $post_id = null, $only_with_values = false ) {
	return Fields::get_fields_by_group( $group_key, $post_id, $only_with_values );
}

/**
 * Check if a field has a value.
 *
 * @param string $field_key Field key.
 * @param int    $post_id   Post ID. Defaults to current post.
 * @return bool True if field has a value.
 */
function has_field( $field_key, $post_id = null ) {
	$value = get_field( $field_key, $post_id );
	return ! empty( $value );
}


