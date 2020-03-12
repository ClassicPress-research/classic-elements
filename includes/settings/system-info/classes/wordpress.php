<?php
namespace Elementor\System_Info\Classes;

use Elementor\System_Info\Classes\Abstracts\Base_Reporter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Classic Elements ClassicPress environment report.
 *
 * Classic Elements system report handler class responsible for generating a report for
 * the ClassicPress environment.
 *
 * @since 1.0.0
 */
class ClassicPress_Reporter extends Base_Reporter {

	/**
	 * Get ClassicPress environment reporter title.
	 *
	 * Retrieve ClassicPress environment reporter title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Reporter title.
	 */
	public function get_title() {
		return 'ClassicPress Environment';
	}

	/**
	 * Get ClassicPress environment report fields.
	 *
	 * Retrieve the required fields for the ClassicPress environment report.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Required report fields with field ID and field label.
	 */
	public function get_fields() {
		return [
			'version' => 'Version',
			'site_url' => 'Site URL',
			'home_url' => 'Home URL',
			'is_multisite' => 'WP Multisite',
			'max_upload_size' => 'Max Upload Size',
			'memory_limit' => 'Memory limit',
			'permalink_structure' => 'Permalink Structure',
			'language' => 'Language',
			'timezone' => 'Timezone',
			'admin_email' => 'Admin Email',
			'debug_mode' => 'Debug Mode',
		];
	}

	/**
	 * Get ClassicPress memory limit.
	 *
	 * Retrieve the ClassicPress memory limit.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value          ClassicPress memory limit.
	 *    @type string $recommendation Recommendation memory limit.
	 *    @type bool   $warning        Whether to display a warning. True if the limit
	 *                                 is below the recommended 64M, False otherwise.
	 * }
	 */
	public function get_memory_limit() {
		$result = [
			'value' => ini_get( 'memory_limit' ),
		];

		$min_recommended_memory = '64M';

		$memory_limit_bytes = wp_convert_hr_to_bytes( $result['value'] );

		$min_recommended_bytes = wp_convert_hr_to_bytes( $min_recommended_memory );

		if ( $memory_limit_bytes < $min_recommended_bytes ) {
			$result['recommendation'] = sprintf(
				/* translators: 1: Minimum recommended_memory, 2: Codex URL */
				_x( 'We recommend setting memory to at least %1$s. For more information, read about <a href="%2$s">how to Increase memory allocated to PHP</a>.', 'System Info', 'elementor' ),
				$min_recommended_memory,
				'https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP'
			);

			$result['warning'] = true;
		}

		return $result;
	}

	/**
	 * Get ClassicPress version.
	 *
	 * Retrieve the ClassicPress version.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress version.
	 * }
	 */
	public function get_version() {
		return [
			'value' => get_bloginfo( 'version' ),
		];
	}

	/**
	 * Is multisite.
	 *
	 * Whether multisite is enabled or not.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value Yes if multisite is enabled, No otherwise.
	 * }
	 */
	public function get_is_multisite() {
		return [
			'value' => is_multisite() ? 'Yes' : 'No',
		];
	}

	/**
	 * Get site URL.
	 *
	 * Retrieve ClassicPress site URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress site URL.
	 * }
	 */
	public function get_site_url() {
		return [
			'value' => get_site_url(),
		];
	}

	/**
	 * Get home URL.
	 *
	 * Retrieve ClassicPress home URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress home URL.
	 * }
	 */
	public function get_home_url() {
		return [
			'value' => get_home_url(),
		];
	}

	/**
	 * Get permalink structure.
	 *
	 * Retrieve the permalink structure
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress permalink structure.
	 * }
	 */
	public function get_permalink_structure() {
		global $wp_rewrite;

		$structure = $wp_rewrite->permalink_structure;

		if ( ! $structure ) {
			$structure = 'Plain';
		}

		return [
			'value' => $structure,
		];
	}

	/**
	 * Get site language.
	 *
	 * Retrieve the site language.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress site language.
	 * }
	 */
	public function get_language() {
		return [
			'value' => get_bloginfo( 'language' ),
		];
	}

	/**
	 * Get PHP `max_upload_size`.
	 *
	 * Retrieve the value of maximum upload file size defined in `php.ini` configuration file.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value Maximum upload file size allowed.
	 * }
	 */
	public function get_max_upload_size() {
		return [
			'value' => size_format( wp_max_upload_size() ),
		];
	}

	/**
	 * Get ClassicPress timezone.
	 *
	 * Retrieve ClassicPress timezone.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress timezone.
	 * }
	 */
	public function get_timezone() {
		$timezone = get_option( 'timezone_string' );
		if ( ! $timezone ) {
			$timezone = get_option( 'gmt_offset' );
		}

		return [
			'value' => $timezone,
		];
	}

	/**
	 * Get ClassicPress administrator email.
	 *
	 * Retrieve ClassicPress administrator email.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value ClassicPress administrator email.
	 * }
	 */
	public function get_admin_email() {
		return [
			'value' => get_option( 'admin_email' ),
		];
	}

	/**
	 * Get debug mode.
	 *
	 * Whether ClassicPress debug mode is enabled or not.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Report data.
	 *
	 *    @type string $value Active if debug mode is enabled, Inactive otherwise.
	 * }
	 */
	public function get_debug_mode() {
		return [
			'value' => WP_DEBUG ? 'Active' : 'Inactive',
		];
	}
}
