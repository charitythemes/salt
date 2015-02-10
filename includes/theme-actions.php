<?php
/**
 * General Theme Actions
 *
 * Actions are called with the function do_action()
 * 
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference
 * 
 * @package WordPress
 * @subpackage Salt
 * @since Salt 1.0
 */

/**
 * Include additional actions & Filters
 * 
 * @link http://codex.wordpress.org/Function_Reference/locate_template
 * @since Salt 1.0
 */
locate_template('includes/actions/header.php', true);
locate_template('includes/actions/footer.php', true);
locate_template('includes/actions/layout.php', true);
locate_template('includes/actions/sidebars.php', true);

/**
 * Include additional classes
 * 
 * @link http://codex.wordpress.org/Function_Reference/locate_template
 * @since Salt 1.0
 */
locate_template('includes/classes/widgets.php', true);
locate_template('includes/classes/theme-options.php', true);