<?php
/**
 * Unique Slug validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Unique_Slug', false ) ) {

	/**
	 * Class Redux_Validation_Unique_Slug
	 */
	class Redux_Validation_Unique_Slug extends Redux_Validate {

		/**
		 * Field Validation Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 3.0.0
		 */
		public function validate() {
			global $wpdb, $wp_rewrite;

			$this->field['msg']              = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : esc_html__( 'That URL slug is in use, please choose another.', 'redux-framework' );
			$this->field['flush_permalinks'] = ( isset( $this->field['flush_permalinks'] ) ) ? $this->field['flush_permalinks'] : false;

			$slug = $this->value;

			$feeds = $wp_rewrite->feeds;
			if ( ! is_array( $feeds ) ) {
				$feeds = array();
			}

			// Post slugs must be unique across all posts.
			$result = wp_cache_get( 'redux-post-name' );
			if ( false === $result ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$post_name_check = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_name = %s LIMIT 1", $slug ) );

				wp_cache_set( 'redux-post-name', $result );
			}

			/**
			 * Filter whether the post slug would be bad as a flat slug.
			 *
			 * @since 3.1.0
			 *
			 * @param bool   $bad_slug  Whether the post slug would be bad as a flat slug.
			 * @param string $slug      The post slug.
			 * @param string $post_type Post type.
			 */
			if ( $post_name_check || in_array( $slug, $feeds, true ) || apply_filters( 'wp_unique_post_slug_is_bad_attachment_slug', false, $slug ) ) {
				$suffix = 2;

				do {
					$alt_post_name = _truncate_post_slug( $slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";

					$result = wp_cache_get( 'redux-alt-post-name' );
					if ( false === $result ) {
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$post_name_check = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_name = %s LIMIT 1", $alt_post_name ) );

						wp_cache_set( 'redux-alt-post-name', $result );
					}

					$suffix ++;
				} while ( $post_name_check );

				$slug                   = $alt_post_name;
				$this->value            = ( isset( $this->current ) ) ? $this->current : '';
				$this->field['msg']     = sprintf( $this->field['msg'], $slug );
				$this->field['current'] = $this->value;
				$this->error            = $this->field;
			} elseif ( isset( $this->field['flush_permalinks'] ) && true === $this->field['flush_permalinks'] ) {
				add_action( 'init', array( $this, 'flush_permalinks' ), 99 );
			}
		}

		/**
		 * Flush WordPress permalinks.
		 */
		public function flush_permalinks() {
			flush_rewrite_rules();
		}
	}
}
