<?php
/**
 * Field Definitions
 *
 * @package FFF_Danse
 */

namespace FFF_Danse\Includes;

/**
 * Central field definitions for the plugin.
 */
class Fields {

	/**
	 * Get all field definitions with labels and groups.
	 *
	 * @return array Field definitions.
	 */
	public static function get_fields() {
		return [
			// AUDIO
			'audio_lydfil'        => [ 'label' => __( 'Lydfil', 'fff-danse' ),            'group' => 'audio' ],
			'audio_kommentar1'    => [ 'label' => __( 'Lydkommentar 1', 'fff-danse' ),    'group' => 'audio' ],
			'audio_kommentar2'    => [ 'label' => __( 'Lydkommentar 2', 'fff-danse' ),    'group' => 'audio' ],
			'audio_note'          => [ 'label' => __( 'Lydnote', 'fff-danse' ),           'group' => 'audio' ],
			'audio_dato'          => [ 'label' => __( 'Lyddato', 'fff-danse' ),           'group' => 'audio' ],

			// BESKRIVELSE / PDF
			'BES_pdffil'          => [ 'label' => __( 'PDF', 'fff-danse' ),                'group' => 'beskrivelse' ],
			'BES_opstil'          => [ 'label' => __( 'PDF-opstilling', 'fff-danse' ),     'group' => 'beskrivelse' ],
			'BES_forklar'         => [ 'label' => __( 'PDF-forklaring', 'fff-danse' ),     'group' => 'beskrivelse' ],
			'BES_dato'            => [ 'label' => __( 'PDF-dato', 'fff-danse' ),           'group' => 'beskrivelse' ],

			// DANSE-INFO
			'danse_navn'          => [ 'label' => __( 'Dansenavn', 'fff-danse' ),          'group' => 'danseinfo' ],
			'danse_egn'           => [ 'label' => __( 'Egn', 'fff-danse' ),                'group' => 'danseinfo' ],
			'danse_ex_navn'       => [ 'label' => __( 'Alternativt navn', 'fff-danse' ),   'group' => 'danseinfo' ],
			'danse_ex_egn'        => [ 'label' => __( 'Alternativ egn', 'fff-danse' ),     'group' => 'danseinfo' ],
			'danse_topo'          => [ 'label' => __( 'Topografi', 'fff-danse' ),          'group' => 'danseinfo' ],
			'danse_hefte'         => [ 'label' => __( 'Hæfte', 'fff-danse' ),              'group' => 'danseinfo' ],
			'danse_side'          => [ 'label' => __( 'Side', 'fff-danse' ),               'group' => 'danseinfo' ],
			'danse_opstilling'    => [ 'label' => __( 'Danseopstilling', 'fff-danse' ),    'group' => 'danseinfo' ],
			'danse_trin'          => [ 'label' => __( 'Trinbeskrivelse', 'fff-danse' ),    'group' => 'danseinfo' ],
			'danse_musik'         => [ 'label' => __( 'Musikinfo', 'fff-danse' ),          'group' => 'danseinfo' ],
			'danse_takt'          => [ 'label' => __( 'Takt', 'fff-danse' ),               'group' => 'danseinfo' ],
			'danse_figur'         => [ 'label' => __( 'Figurbeskrivelse', 'fff-danse' ),   'group' => 'danseinfo' ],
			'danse_niveau'        => [ 'label' => __( 'Niveau', 'fff-danse' ),             'group' => 'danseinfo' ],
			'danse_dato'          => [ 'label' => __( 'Dansedato', 'fff-danse' ),          'group' => 'danseinfo' ],

			// HISTORIE (LYD)
			'historie_fil'        => [ 'label' => __( 'Historiefil', 'fff-danse' ),        'group' => 'historie_audio' ],
			'historie_kommentar1' => [ 'label' => __( 'Historiekommentar 1', 'fff-danse' ),'group' => 'historie_audio' ],
			'historie_kommentar2' => [ 'label' => __( 'Historiekommentar 2', 'fff-danse' ),'group' => 'historie_audio' ],
			'historie_note'       => [ 'label' => __( 'Historienote', 'fff-danse' ),       'group' => 'historie_audio' ],
			'historie_dato'       => [ 'label' => __( 'Historiedato', 'fff-danse' ),       'group' => 'historie_audio' ],

			// HISTORIE (TEKST)
			'historie_txt_text'   => [ 'label' => __( 'Historietekst', 'fff-danse' ),      'group' => 'historie_text' ],
			'historie_txt_note'   => [ 'label' => __( 'Tekstnote', 'fff-danse' ),          'group' => 'historie_text' ],
			'historie_txt_dato'   => [ 'label' => __( 'Tekstdato', 'fff-danse' ),          'group' => 'historie_text' ],

			// KOMMENTAR
			'kommentar_komm'      => [ 'label' => __( 'Kommentar', 'fff-danse' ),          'group' => 'kommentar' ],

			// LIGNENDE DANSE
			'ligner_ens'          => [ 'label' => __( 'Lignende danse', 'fff-danse' ),     'group' => 'lignende' ],

			// NODE
			'node_nodefil'        => [ 'label' => __( 'Nodefil', 'fff-danse' ),            'group' => 'node' ],
			'node_kommentar1'     => [ 'label' => __( 'Nodekommentar 1', 'fff-danse' ),    'group' => 'node' ],
			'node_kommentar2'     => [ 'label' => __( 'Nodekommentar 2', 'fff-danse' ),    'group' => 'node' ],
			'node_note'           => [ 'label' => __( 'Nodenote', 'fff-danse' ),           'group' => 'node' ],
			'node_dato'           => [ 'label' => __( 'Nodedato', 'fff-danse' ),           'group' => 'node' ],

			// NOTER
			'noter_niveau'        => [ 'label' => __( 'Noteniveau', 'fff-danse' ),         'group' => 'noter' ],
			'noter_instruk'       => [ 'label' => __( 'Instruktion', 'fff-danse' ),        'group' => 'noter' ],
			'noter_video'         => [ 'label' => __( 'Notevideo', 'fff-danse' ),          'group' => 'noter' ],
			'noter_andre'         => [ 'label' => __( 'Andre noter', 'fff-danse' ),        'group' => 'noter' ],
			'noter_dato'          => [ 'label' => __( 'Notedato', 'fff-danse' ),           'group' => 'noter' ],

			// TRIN / FIGUR VIDEO
			'trin_figur_video_trin'      => [ 'label' => __( 'Videotrin', 'fff-danse' ),            'group' => 'trin_figur' ],
			'trin_figur_video_figur'     => [ 'label' => __( 'Videfigur', 'fff-danse' ),            'group' => 'trin_figur' ],
			'trin_figur_video_videofil'  => [ 'label' => __( 'Trin/figur-video', 'fff-danse' ),     'group' => 'trin_figur' ],
			'trin_figur_video_kommentar1'=> [ 'label' => __( 'Trin/figur-komm. 1', 'fff-danse' ),   'group' => 'trin_figur' ],
			'trin_figur_video_kommentar2'=> [ 'label' => __( 'Trin/figur-komm. 2', 'fff-danse' ),   'group' => 'trin_figur' ],
			'trin_figur_video_dato'      => [ 'label' => __( 'Trin/figur-dato', 'fff-danse' ),      'group' => 'trin_figur' ],

			// VIDEO (INTRO / HOVED)
			'video_videofil'             => [ 'label' => __( 'Video', 'fff-danse' ),                'group' => 'video' ],
			'video_kommentar1'           => [ 'label' => __( 'Videokommentar 1', 'fff-danse' ),     'group' => 'video' ],
			'video_kommentar2'           => [ 'label' => __( 'Videokommentar 2', 'fff-danse' ),     'group' => 'video' ],
			'video_note'                 => [ 'label' => __( 'Videonote', 'fff-danse' ),            'group' => 'video' ],
			'video_dato'                 => [ 'label' => __( 'Videodato', 'fff-danse' ),            'group' => 'video' ],

			// VIDEO – alternativ version
			'video_ååååmmdd_videofil'    => [ 'label' => __( 'Alternativ video', 'fff-danse' ),     'group' => 'video_alt' ],
			'video_ååååmmdd_kommentar1'  => [ 'label' => __( 'Alt. kommentar 1', 'fff-danse' ),     'group' => 'video_alt' ],
			'video_ååååmmdd_kommentar2'  => [ 'label' => __( 'Alt. kommentar 2', 'fff-danse' ),     'group' => 'video_alt' ],
			'video_ååååmmdd_note'        => [ 'label' => __( 'Alt. note', 'fff-danse' ),            'group' => 'video_alt' ],
			'video_ååååmmdd_dato'        => [ 'label' => __( 'Alt. dato', 'fff-danse' ),            'group' => 'video_alt' ],
			
			// VIDEO – intro + sekvenser
			'video_intro'  => [ 'label' => __( 'Intro', 'fff-danse' ),  'group' => 'video_groups' ],
			'video_se1'    => [ 'label' => __( 'SE1', 'fff-danse' ),    'group' => 'video_groups' ],
			'video_se2'    => [ 'label' => __( 'SE2', 'fff-danse' ),    'group' => 'video_groups' ],
			'video_se3'    => [ 'label' => __( 'SE3', 'fff-danse' ),    'group' => 'video_groups' ],
			'video_lær1'   => [ 'label' => __( 'LÆR1', 'fff-danse' ),   'group' => 'video_groups' ],
			'video_lær2'   => [ 'label' => __( 'LÆR2', 'fff-danse' ),   'group' => 'video_groups' ],
			'video_lær3'   => [ 'label' => __( 'LÆR3', 'fff-danse' ),   'group' => 'video_groups' ],
			'video_dans1'  => [ 'label' => __( 'DANS1', 'fff-danse' ),  'group' => 'video_groups' ],
			'video_dans2'  => [ 'label' => __( 'DANS2', 'fff-danse' ),  'group' => 'video_groups' ],
			'video_dans3'  => [ 'label' => __( 'DANS3', 'fff-danse' ),  'group' => 'video_groups' ],
		];
	}

	/**
	 * Get group labels for display.
	 *
	 * @return array Group labels.
	 */
	public static function get_group_labels() {
		return [
			'danseinfo'      => __( 'Danseinfo', 'fff-danse' ),
			'video'          => __( 'Video', 'fff-danse' ),
			'video_alt'      => __( 'Alternativ video', 'fff-danse' ),
			'video_groups'   => __( 'Video-sekvenser', 'fff-danse' ),
			'trin_figur'     => __( 'Trin & figur-video', 'fff-danse' ),
			'audio'          => __( 'Lyd', 'fff-danse' ),
			'beskrivelse'    => __( 'Beskrivelse / PDF', 'fff-danse' ),
			'historie_audio' => __( 'Historie (lyd)', 'fff-danse' ),
			'historie_text'  => __( 'Historie (tekst)', 'fff-danse' ),
			'node'           => __( 'Noder', 'fff-danse' ),
			'noter'          => __( 'Noter', 'fff-danse' ),
			'lignende'       => __( 'Lignende danse', 'fff-danse' ),
			'kommentar'      => __( 'Kommentar', 'fff-danse' ),
		];
	}

	/**
	 * Group fields by their group key.
	 *
	 * @return array Grouped fields.
	 */
	public static function get_grouped_fields() {
		$fields = self::get_fields();
		$groups = [];

		foreach ( $fields as $key => $field ) {
			$group_key = $field['group'];
			if ( ! isset( $groups[ $group_key ] ) ) {
				$groups[ $group_key ] = [];
			}
			$groups[ $group_key ][ $key ] = $field;
		}

		return $groups;
	}

	/**
	 * Get a single field value for a post.
	 *
	 * Similar to ACF's get_field() function.
	 *
	 * @param string $field_key Field key.
	 * @param int    $post_id   Post ID. Defaults to current post.
	 * @param mixed  $default   Default value if field is empty.
	 * @return mixed Field value or default.
	 */
	public static function get_field( $field_key, $post_id = null, $default = '' ) {
		// Use current post ID if not provided
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		$post_id = absint( $post_id );
		if ( ! $post_id ) {
			return $default;
		}

		// Validate field key exists
		$fields = self::get_fields();
		if ( ! isset( $fields[ $field_key ] ) ) {
			return $default;
		}

		// Get field value
		$value = get_post_meta( $post_id, $field_key, true );

		// Return default if empty
		if ( '' === $value || null === $value ) {
			return $default;
		}

		return $value;
	}

	/**
	 * Get all field values for a post.
	 *
	 * @param int  $post_id Post ID. Defaults to current post.
	 * @param bool $only_with_values Only return fields that have values.
	 * @return array Array of field_key => value pairs.
	 */
	public static function get_all_fields( $post_id = null, $only_with_values = false ) {
		// Use current post ID if not provided
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		$post_id = absint( $post_id );
		if ( ! $post_id ) {
			return [];
		}

		$fields = self::get_fields();
		$values = [];

		foreach ( $fields as $key => $field ) {
			$value = get_post_meta( $post_id, $key, true );

			if ( $only_with_values && ( '' === $value || null === $value ) ) {
				continue;
			}

			$values[ $key ] = $value;
		}

		return $values;
	}

	/**
	 * Get field label.
	 *
	 * @param string $field_key Field key.
	 * @return string Field label or field key if not found.
	 */
	public static function get_field_label( $field_key ) {
		$fields = self::get_fields();
		return isset( $fields[ $field_key ]['label'] ) ? $fields[ $field_key ]['label'] : $field_key;
	}

	/**
	 * Check if a field exists.
	 *
	 * @param string $field_key Field key.
	 * @return bool True if field exists.
	 */
	public static function field_exists( $field_key ) {
		$fields = self::get_fields();
		return isset( $fields[ $field_key ] );
	}

	/**
	 * Get fields by group.
	 *
	 * @param string $group_key Group key.
	 * @param int    $post_id   Post ID. Defaults to current post.
	 * @param bool   $only_with_values Only return fields that have values.
	 * @return array Array of field_key => value pairs for the group.
	 */
	public static function get_fields_by_group( $group_key, $post_id = null, $only_with_values = false ) {
		// Use current post ID if not provided
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		$post_id = absint( $post_id );
		if ( ! $post_id ) {
			return [];
		}

		$fields = self::get_fields();
		$values = [];

		foreach ( $fields as $key => $field ) {
			if ( $field['group'] !== $group_key ) {
				continue;
			}

			$value = get_post_meta( $post_id, $key, true );

			if ( $only_with_values && ( '' === $value || null === $value ) ) {
				continue;
			}

			$values[ $key ] = $value;
		}

		return $values;
	}
}


