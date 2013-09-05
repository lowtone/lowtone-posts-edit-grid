<?php
/*
 * Plugin Name: Grid Helper for Post Editor
 * Plugin URI: http://wordpress.lowtone.nl/plugins/posts-edit-grid/
 * Description: Add grid support to the post editor.
 * Version: 1.0
 * Author: Lowtone <info@lowtone.nl>
 * Author URI: http://lowtone.nl
 * License: http://wordpress.lowtone.nl/license
 */
/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2013, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\plugins\lowtone\posts\edit\grid
 */

namespace lowtone\posts\edit\grid {

	use lowtone\content\packages\Package,
		lowtone\style\styles\Grid;

	// Includes
	
	if (!include_once WP_PLUGIN_DIR . "/lowtone-content/lowtone-content.php") 
		return trigger_error("Lowtone Content plugin is required", E_USER_ERROR) && false;

	// Init

	Package::init(array(
			Package::INIT_PACKAGES => array("lowtone", "lowtone\\style"),
			Package::INIT_MERGED_PATH => __NAMESPACE__,
			Package::INIT_SUCCESS => function() {

				if (!defined("LOWTONE_POSTS_EDIT_GRID_REGISTER_CLEARFIX_SHORTCODE"))
					define("LOWTONE_POSTS_EDIT_GRID_REGISTER_CLEARFIX_SHORTCODE", true);

				if (!defined("LOWTONE_POSTS_EDIT_GRID_REGISTER_COLUMN_SHORTCODE"))
					define("LOWTONE_POSTS_EDIT_GRID_REGISTER_COLUMN_SHORTCODE", false);

				if (!defined("LOWTONE_POSTS_EDIT_GRID_NUM_COLUMNS"))
					define("LOWTONE_POSTS_EDIT_GRID_NUM_COLUMNS", 16);

				// Register shortcodes

				Grid::registerShortcodes(LOWTONE_POSTS_EDIT_GRID_NUM_COLUMNS);

				// Clearfix
				
				if (LOWTONE_POSTS_EDIT_GRID_REGISTER_CLEARFIX_SHORTCODE)
					add_shortcode("clear", function() {
						return '<div class="clearfix"></div>';
					});

				// Column
				
				if (LOWTONE_POSTS_EDIT_GRID_REGISTER_COLUMN_SHORTCODE)
					add_shortcode("column", function($atts, $content) {
						return Grid::shortcode($atts, $content);
					});

				// Add button

				add_action("admin_init", function() {

					if ((current_user_can("edit_posts") || current_user_can("edit_pages")) && "true" == get_user_option("rich_editing")) {

						wp_enqueue_style("lowtone_posts_edit_grid", plugins_url("/assets/styles/editor_plugin.css", __FILE__));

						add_filter("mce_external_plugins", function($plugins) {
							$plugins["LowtonePostsEditGrid"] = plugins_url("/assets/scripts/editor_plugin.js", __FILE__);

							return $plugins;
						});

						add_filter("mce_buttons", function($buttons) {
							array_push($buttons, "|", "lowtone_posts_edit_grid_shortcodes");

							return $buttons;
						});

						add_filter("mce_external_languages", function($languages) {
							$languages['LowtonePostsEditGrid'] = __DIR__ . '/assets/scripts/editor_plugin_lang.php';
						
							return $languages;
						});

					}

				});

			}
		));

}