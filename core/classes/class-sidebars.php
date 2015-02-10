<?php
/**
 * Salt Unique Page Sidebar class
 *
 * Give opportunity to make special sidebar of current page.
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class to control sidebars on pages
 *
 * @package Salt Customize Theme
 * @subpackage Unique Page Sidebars (UPS)
 */

//General Variables
define("SALT_SHORT_NAME",  'salt');
define('SIDEBAR_PREFIX_A', 'salt-a-');
define('SIDEBAR_PREFIX_B', 'salt-b-');
define('SALT_SIDEBAR_URL',  get_template_directory_uri() . '/admin' );

//Primary Widget Area Variables
define('SIDEBAR_KEY_PRIMARY_REPLACED',     '_salt-primary-replace');
define('SIDEBAR_KEY_PRIMARY_POSITION',     '_salt-primary-position');
define('SIDEBAR_KEY_PRIMARY_NEW',          '_salt-primary-new');
define('SIDEBAR_HOST_PRIMARY_WIDGET_AREA', 'primary-widget-area');

//Secondary Widget Area Variables
define('SIDEBAR_KEY_SECONDARY_REPLACED',     '_salt-secondary-replace');
define('SIDEBAR_KEY_SECONDARY_POSITION',     '_salt-secondary-position');
define('SIDEBAR_KEY_SECONDARY_NEW',          '_salt-secondary-new');
define('SIDEBAR_HOST_SECONDARY_WIDGET_AREA', 'secondary-widget-area');

if(! class_exists('Salt_Unique_Page_Sidebar')) {
	class Salt_Unique_Page_Sidebar{
		/**
		 * Set up the hooks for the Customize Sidebar Options
		 *
		 * @since 1.0
		 */
		function __construct() {
			global $pagenow;
	
			// Add the edit field to the Page Edit screens
			add_action('admin_menu', array(&$this, 'add_meta_box'));
			
			// Process the custom EUPS values submitted when saving pages.
			add_action('save_post', array(&$this, 'save_postdata'));
			
			// Register the custom sidebars that have been created for the pages.
			add_action('admin_init', array(&$this, 'reg_sidebars'));

			// Register the custom sidebars that have been created for the pages.
			add_action('admin_init', array(&$this, 'reg_single_post_sidebars'));
			
			// When a page is dipslayed, check for a custom sidebar and if it exists hijack the standard sidebar
			add_filter('wp', array(&$this, 'hijack_sidebars'));

			// When a single post is dipslayed, check for a custom sidebar and if it exists hijack the standard sidebars
			add_filter('wp', array(&$this, 'hijack_single_post_sidebars'));
		}
	
		/**
		 * Get Post ID's for the posts with sidebars
		 *
		 * @since 1.0
		 */
		function get_primary_posts_ids() {
			global $wpdb;
			return $wpdb->get_col( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", SIDEBAR_KEY_PRIMARY_NEW) );
		}
	
		/**
		 * Get Post ID's for the posts with sidebars
		 *
		 * @since 1.1
		 */
		function get_secondary_posts_ids() {
			global $wpdb;
			return $wpdb->get_col( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", SIDEBAR_KEY_SECONDARY_NEW) );
		}
		
		/**
		 * Used to mark a selection as checked in HTML.
		 *
		 * @since 1.0
		 */
		function add_checked($testvalue, $value) {
			if ($testvalue == $value) {
				return ' checked="checked" ';
			}
		}
		
		/**
		 * Used to mark a selection as checked in HTML..
		 *
		 * @since 1.0
		 */
		function add_selected($testvalue, $value) {
			if ($testvalue == $value) {
				return ' selected="selected" ';
			}
		}
		
		/**
		 * Used to mark something as disabled in HTML.
		 *
		 * @since 1.0
		 */
		function add_disabled($testvalue, $value) {
			if ($testvalue == $value) {
				return ' disabled="disabled" ';
			}
		}
		
		/**
		 * This adds the sidebar replacement selection box to the Edit->Page screens.
		 *
		 * @since 1.0
		 */
		function add_meta_box() {
			// This security check is also verified upon save.
			if ( current_user_can('edit_theme_options') ) {
				add_meta_box(
					'eups_sectionid',
					__( 'Sidebar Options', 'salt' ),
					array(&$this, 'inner_meta_box'),
					'page',
					'side',
					'low'
				);
			}
		}
		
		// TODO This function is never used
		function get_primary_sidebar_options(){
			global $wp_registered_sidebars, $post;
			
			$options[] = array();
			
			foreach ($wp_registered_sidebars as $id => $sidebar) {
				$name = isset($sidebar['name']) ? $sidebar['name'] : $id;
				
				// Eliminate any self created sidebars or this could get messy.
				if (substr($name, 0, strlen(SIDEBAR_PREFIX_A) ) != SIDEBAR_PREFIX_A) {
					$options[$id] = $name;
				}
			}
			
			return $options;
		}
		
		/**
		 * This displays the sidebar replacement selection box
		 *
		 * @since 1.0
		 */
		function inner_meta_box($post) {
			global $wp_registered_sidebars;
			
			$post_meta = get_post_custom($post->ID);
			
			/* Begin Primary Sidebar Options */
			$sidebar_position = $post_meta[SIDEBAR_KEY_PRIMARY_POSITION][0];
			$replaced_sidebar = $post_meta[SIDEBAR_KEY_PRIMARY_REPLACED][0];
			$new_sidebar      = $post_meta[SIDEBAR_KEY_PRIMARY_NEW][0];
			$active_on_post   = ( isset($wp_registered_sidebars[$new_sidebar]) );
			
			echo '<input type="hidden" id="ups_nonce" name="ups_nonce" value="' . wp_create_nonce(plugin_basename(__FILE__) ) . '" />';
			echo '<div>';
			//echo '<p><strong>Primary Sidebar</strong></p>';
			echo '<p>' . __('Select an existing sidebar for this page', 'salt' ) . '</p>';
			echo '<select name="ed-ups-primary-replace" ' . self::add_disabled($active_on_post, true) . '>';
	
			echo '<option value="">('. __('Select sidebar', 'salt') . ')</option>';
			// Print the list of sidebars in the drop down
			foreach ($wp_registered_sidebars as $id => $sidebar) {
				$description = $sidebar['description'];
				if ( isset($sidebar['name']) ) {
					$name = $sidebar['name'];
				} else {
					$name = $id;
				}
		
				// Eliminate any self created sidebars or this could get messy.
				if (substr($name, 0, strlen(SIDEBAR_PREFIX_A) ) != SIDEBAR_PREFIX_A) {
					echo '<option '  . self::add_selected($replaced_sidebar, $id) . ' value="' . $id . '">' . $name . '</option>';
				}
			}
			echo '</select>';
			echo '</div>';
			
			echo '<p><input type="checkbox" value="1" id="ed-ups-primary-active" name="ed-ups-primary-active" class="tcc-eups-checkbox" '
				. self::add_checked($active_on_post, true) . '/> <label for="ed-ups-primary-active">'
				. __('Activate a new sidebar for this page', 'salt' ) . '</label></p>';
			/* End Primary Sidebar Options */
			
			echo '<hr />';
			
			/* Begin Secondary Sidebar Options */
			$sidebar_position = $post_meta[SIDEBAR_KEY_SECONDARY_POSITION][0];
			$replaced_sidebar = $post_meta[SIDEBAR_KEY_SECONDARY_REPLACED][0];
			$new_sidebar      = $post_meta[SIDEBAR_KEY_SECONDARY_NEW][0];
			$active_on_post   = ( isset($wp_registered_sidebars[$new_sidebar]) );
	
			echo '<div>';
			echo '<p><strong>Secondary Sidebar</strong></p>';
			echo '<p>' . __('Select an existing sidebar for this page', 'salt' ) . '</p>';
			echo '<select name="ed-ups-secondary-replace" ' . self::add_disabled($active_on_post, true) . '>';
	
			echo '<option value="">('. __('Select sidebar', 'salt') . ')</option>';
			// Print the list of sidebars in the drop down
			foreach ($wp_registered_sidebars as $id => $sidebar) {
				$description = $sidebar['description'];
				$name = isset($sidebar['name']) ? $sidebar['name'] : $id;
				
				// Eliminate any self created sidebars or this could get messy.
				if (substr($name, 0, strlen(SIDEBAR_PREFIX_B) ) != SIDEBAR_PREFIX_B) {
					echo '<option ' . self::add_selected($replaced_sidebar, $id) . ' value="' . $id . '">' . $name . '</option>';
				}
			}
			
			echo '</select>';
			echo '</div>';
			
			echo '<p><input type="checkbox" value="1" id="ed-ups-secondary-active" name="ed-ups-secondary-active" class="tcc-eups-checkbox" '
				. self::add_checked($active_on_post, true) . '/> <label for="ed-ups-secondary-active">'
				. __('Activate a new sidebar for this page', 'salt' ) . '</label></p>';
			/* End Secondary Sidebar Options */
			echo '<p> ' . __('Activating a new sidebar allows you to add or remove widgets for this page. <a href="widgets.php">Click here</a> to edit your sidebars.', 'salt' ) . ' </p>';
		}
		
		/**
		 * Recieves the sidebar replacement data when a page is saved.
		 *
		 * @since 1.0
		 */
		function save_postdata($post_id) {
			global $wp_registered_sidebars;
			
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}
			
			// This will eventualy need to be adjusted to deal 'edit_CUSTOMPOSTTYPE' permissions as the core does.
			// Assuming, of course, I extend the plugin to handle more than pages.
			if (('page' == $_POST['post_type'] ) && current_user_can('edit_theme_options')) {
				if(!current_user_can('edit_page', $post_id)) return $post_id;
			} else {
				return $post_id;
			}
		
			// Return with no errors if the NONCE fails. This both blocks hack attacks AND prevents a save from removing
			// the data if plugin modification adds security checks on meta box display that aren't duplicated here.
			if (!wp_verify_nonce($_POST['ups_nonce'], plugin_basename(__FILE__))) {
				return $post_id;
			}
			
			/* Save the sidebar layout settings */
			if ($_POST[SALT_SHORT_NAME . '_post_layout']) {
				update_post_meta($post_id, '_post_layout', $_POST[SALT_SHORT_NAME . '_post_layout']);
			} else {
				delete_post_meta($post_id, '_post_layout');
			}
			
			/* Save the primary sidebar settings */
			$sidebar_position = $_POST['ed-ups-primary-position'];
			$replaced_sidebar = $_POST['ed-ups-primary-replace'];
			$active_on_post   = $_POST['ed-ups-primary-active'] == 1;
			$active_on_registered = isset($wp_registered_sidebars[$replaced_sidebar]);
			
			if ($active_on_post) {
				update_post_meta($post_id, SIDEBAR_KEY_PRIMARY_NEW, SIDEBAR_HOST_PRIMARY_WIDGET_AREA);
				delete_post_meta($post_id, SIDEBAR_KEY_PRIMARY_REPLACED);
			} else if ($active_on_registered ) {
				update_post_meta($post_id, SIDEBAR_KEY_PRIMARY_REPLACED, $replaced_sidebar);
				delete_post_meta($post_id, SIDEBAR_KEY_PRIMARY_NEW);
			} else {
				delete_post_meta($post_id, SIDEBAR_KEY_PRIMARY_REPLACED);
				delete_post_meta($post_id, SIDEBAR_KEY_PRIMARY_NEW);
			}
			
			if ($sidebar_position) {
				update_post_meta($post_id, SIDEBAR_KEY_PRIMARY_POSITION, $sidebar_position);
			}
			
			/* Save the secondary sidebar settings */
			$sidebar_position = $_POST['ed-ups-secondary-position'];
			$replaced_sidebar = $_POST['ed-ups-secondary-replace'];
			$active_on_post   = $_POST['ed-ups-secondary-active'] == 1;
			$active_on_registered = isset($wp_registered_sidebars[$replaced_sidebar]);

			if ($active_on_post) {
				update_post_meta($post_id, SIDEBAR_KEY_SECONDARY_NEW, SIDEBAR_HOST_SECONDARY_WIDGET_AREA);
				delete_post_meta($post_id, SIDEBAR_KEY_SECONDARY_REPLACED);
			} else if ($active_on_registered ) {
				update_post_meta($post_id, SIDEBAR_KEY_SECONDARY_REPLACED, $replaced_sidebar);
				delete_post_meta($post_id, SIDEBAR_KEY_SECONDARY_NEW);
			} else {
				delete_post_meta($post_id, SIDEBAR_KEY_SECONDARY_REPLACED);
				delete_post_meta($post_id, SIDEBAR_KEY_SECONDARY_NEW);
			}
			
			if ($sidebar_position) {
				update_post_meta($post_id, SIDEBAR_KEY_SECONDARY_POSITION, $sidebar_position);
			}
	
			return $post_id;
		}
		
		/**
		 * Registers the custom sidebars for the admin pages.
		 *
		 * @since 1.0
		 */
		function reg_sidebars() {
			global $wp_registered_sidebars;
			
			$primary_posts = self::get_primary_posts_ids();
			$secondary_posts = self::get_secondary_posts_ids();

			$merged_posts = array_merge($primary_posts, $secondary_posts);
			$merged_posts = array_unique($merged_posts);

			foreach ($merged_posts as $post_id) {
				$post = get_post($post_id);
				
				if (in_array($post_id, $primary_posts)) {
					register_sidebar(array(
						'name' => $post->post_title . ' ' . __('Primary Sidebar'),
						'id' => SIDEBAR_PREFIX_A . $post->post_name,
						'description' => __('Primary sidebar for the page ') . '"' . $post->post_title . '"')
					);
				}
				
				if (in_array($post_id, $secondary_posts)) {
					register_sidebar(array(
						'name' => $post->post_title . ' ' . __('Secondary Sidebar'),
						'id' => SIDEBAR_PREFIX_B . $post->post_name,
						'description' => __('Secondary sidebar for the page ') . '"' . $post->post_title . '"')
					);
				}
			}
		}
		/**
		 * Registers the custom sidebars for the admin pages.
		 *
		 * @since 1.0
		 */
		function reg_single_post_sidebars() {
			global $wp_registered_sidebars;
			
			$post_types = get_post_types('', 'names');
			
			foreach ($post_types as $type) {

				if ($type == 'attachment' || $type == 'revision' || $type == 'nav_menu_item' || $type == 'page' )
					continue;

				$primary_sidebar 	= get_option('salt_'.$type.'_primary_sidebar');
				$secondary_sidebar 	= get_option('salt_'.$type.'_secondary_sidebar');

				if ($primary_sidebar == 'create_new') {
					register_sidebar(array(
						'name' => ucfirst($type) .' '.__('Primary Sidebar'),
						'id' => SIDEBAR_PREFIX_A . $type,
						'description' => __('Primary sidebar for') . ' "' . $type . 's"')
					);
				}
				
				if ($secondary_sidebar == 'create_new') {
					register_sidebar(array(
						'name' => ucfirst($type) . ' ' . __('Secondary Sidebar'),
						'id' => SIDEBAR_PREFIX_B . $type,
						'description' => __('Secondary sidebar for') . ' "' . $type . 's"')
					);
				}
			}
		}
		
		/**
		 * Registers the custom sidebars for the admin pages.
		 *
		 * @since 1.0
		 */
		function reg_custompost_sidebars() {
			
		}
		
		/**
		 * Replaces the content but not the design of sidebars during the page display.
		 *
		 * @since 1.0
		 */
		function hijack_sidebars($query) {
			global $post, $wp_registered_sidebars, $_wp_sidebars_widgets, $wp_query;

			$page_for_posts = (int)get_option('page_for_posts');
			$queried_object = get_queried_object();
			
			if ($queried_object) {
				$blog_id = $queried_object->ID;
			} else {
				$blog_id = $wp_query->queried_object->ID;
			}
			
			//If this is the blog page, then set the blog ID, post_name
			if ($blog_id == $page_for_posts) {
				$hostpost = $wp_query->queried_object;
				$post_id = $blog_id;
				$post_name = $wp_query->queried_object->post_name;
			//If this is the single blog page, then set the parent blog ID, post_name
			} elseif (is_single() && get_post_type( $post ) == 'post') {
				$hostpost = get_post($page_for_posts);
				$post_id = $page_for_posts;
				$post_name = $hostpost->post_name;
			} else {
				$hostpost = $post;
				$post_id = $hostpost->ID;
				$post_name = $hostpost->post_name;
			}
	
			$post_meta = get_post_custom($hostpost->ID);

			if (isset($post_meta[SIDEBAR_KEY_PRIMARY_NEW][0]) && $post_meta[SIDEBAR_KEY_PRIMARY_NEW][0]) {
		
				$new_sidebar = SIDEBAR_PREFIX_A . $post_name;
				$_wp_sidebars_widgets[SIDEBAR_HOST_PRIMARY_WIDGET_AREA] = $_wp_sidebars_widgets[$new_sidebar];
			
			} else if (isset($post_meta[SIDEBAR_KEY_PRIMARY_REPLACED][0]) && $post_meta[SIDEBAR_KEY_PRIMARY_REPLACED][0]) {
			
				$new_sidebar = $post_meta[SIDEBAR_KEY_PRIMARY_REPLACED][0];
				$_wp_sidebars_widgets[SIDEBAR_HOST_PRIMARY_WIDGET_AREA] = $_wp_sidebars_widgets[$new_sidebar];
			
			}
			
			if (isset($post_meta[SIDEBAR_KEY_SECONDARY_NEW][0]) && $post_meta[SIDEBAR_KEY_SECONDARY_NEW][0]) {
	
				$new_sidebar = SIDEBAR_PREFIX_B . $post_name;
				$_wp_sidebars_widgets[SIDEBAR_HOST_SECONDARY_WIDGET_AREA] = $_wp_sidebars_widgets[$new_sidebar];

			} else if (isset($post_meta[SIDEBAR_KEY_SECONDARY_REPLACED][0]) && $post_meta[SIDEBAR_KEY_SECONDARY_REPLACED][0]) {
			
				$new_sidebar = $post_meta[SIDEBAR_KEY_SECONDARY_REPLACED][0];
				$_wp_sidebars_widgets[SIDEBAR_HOST_SECONDARY_WIDGET_AREA] = $_wp_sidebars_widgets[$new_sidebar];
			
			}
		}
		
		/**
		 * Replaces the content but not the design of sidebars during the single post display.
		 *
		 * @since 1.0
		 */
		function hijack_single_post_sidebars($query) {
			global $post, $wp_registered_sidebars, $_wp_sidebars_widgets, $wp_query;
			
			if (!is_single()) return false;
			
			$post_type = get_post_type($post); 
			
			$primary_sidebar = get_option('salt_'.$post_type.'_primary_sidebar');
			$secondary_sidebar = get_option('salt_'.$post_type.'_secondary_sidebar');

			if ($primary_sidebar == 'create_new') {

				$_wp_sidebars_widgets[SIDEBAR_HOST_PRIMARY_WIDGET_AREA] = $_wp_sidebars_widgets[SIDEBAR_PREFIX_A . $post_type];

			} elseif ($primary_sidebar) {
				
				$_wp_sidebars_widgets[SIDEBAR_HOST_PRIMARY_WIDGET_AREA] = $_wp_sidebars_widgets[$primary_sidebar];
				
			}

			if ($secondary_sidebar == 'create_new') {

				$_wp_sidebars_widgets[SIDEBAR_HOST_SECONDARY_WIDGET_AREA] = $_wp_sidebars_widgets[SIDEBAR_PREFIX_B . $post_type];

			} elseif ($secondary_sidebar) {
				
				$_wp_sidebars_widgets[SIDEBAR_HOST_SECONDARY_WIDGET_AREA] = $_wp_sidebars_widgets[$secondary_sidebar];
				
			}
		} 
	}
}

$GLOBALS['ed_unique_page_sidebars'] = new Salt_Unique_Page_Sidebar();
