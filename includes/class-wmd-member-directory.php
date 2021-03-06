<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WMD_Member_Directory
 */
class WMD_Member_Directory {

	/**
	 * Add the name of the expected directory name that will store the template files outside of the plugin
	 *
	 * @var string
	 */
	public static $template_dir = 'member-directory';

	/**
	 * Run our actions
	 */
	public function __construct() {
		add_filter( 'template_include',  array( __CLASS__, 'member_directory_templates' ) );
		add_filter( 'cmb_meta_boxes',    array( __CLASS__, 'add_meta_boxes' ) );
	}

	/**
	 * Registers our Meta Boxes
	 *
	 * @param array $meta_boxes The array of meta boxes that will be loaded through CMB
	 *
	 * @return array
	 */
	public static function add_meta_boxes( $meta_boxes ) {
		$prefix = '_wmd_'; // Prefix for all fields

		$meta_boxes['member-directory-data'] = array(
			'id'         => 'member-directory-data',
			'title'      => 'Details',
			'pages'      => array( 'member-directory' ),
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => array(
				array(
					'name'  => 'Company Logo',
					'id'    => $prefix . 'company_logo',
					'type'  => 'file',
					'allow' => array( 'url', 'attachment' ),
				),
				array(
					'name'  => 'Portfolio',
					'id'    => $prefix . 'portfolio_items',
					'type'  => 'file_list',
				),
			),
		);

		return $meta_boxes;
	}

	/**
	 * Allows us to locate for the right template file to serve for the Member Directory post type
	 */
	public static function member_directory_templates( $template ) {
		if ( get_query_var( 'member-directory' ) && is_single() ) {
			$template = self::locate_template( 'single-member-directory.php', true );
		} elseif ( is_post_type_archive( 'member-directory' ) ) {
			$template = self::locate_template( 'archive-member-directory.php', true );
		}
		return $template;
	}

	/**
	 * Locates the requested template
	 *
	 * Searches through child themes, parent themes and the plugin for the requested template in that order.
	 * If one exists, that will be used, allowing maximum customizations without needing to mess with the plugin.
	 *
	 * @param string $template_names The name of the template
	 * @param bool   $load           Allows us to just return the template path or actually load the template
	 * @param bool   $require_once   Allows us to require one or require
	 *
	 * @return bool|string
	 */
	public static function locate_template( $template_names, $load = false, $require_once = true ) {
		$path = false;

		// Loop through each template name and find them.
		foreach ( (array) $template_names as $template_name ) {

			if ( empty( $template_name ) ) {
				continue;
			}

			// Remove any trailing slashes if they exist
			$template_path  = '/' . trailingslashit( self::$template_dir ) . sanitize_file_name( untrailingslashit( $template_name ) );
			$stylesheet_template_path = get_stylesheet_directory() . $template_path;
			$theme_template_path  = get_template_directory() . $template_path;
			$plugin_template_path = WMD_TEMPLATES . sanitize_file_name( untrailingslashit( $template_name ) );

			// Check if child theme has template
			if ( file_exists( $stylesheet_template_path ) ) {
				$path = $stylesheet_template_path;
				break;

			// Check if parent theme has template
			} elseif ( file_exists( $theme_template_path ) ) {
				$path = $theme_template_path;
				break;

			// Check if plugin has it
			} elseif ( file_exists( $plugin_template_path ) ) {
				$path = $plugin_template_path;
				break;

			}
		}

		return $path;
	}
}
$wmd_member_directory = new WMD_Member_Directory();