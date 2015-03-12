<?php
/**
 * Adds in new options to be used in the admin panel
 *
 * CMB uses hooks to allow external options to be added for different types of 
 * metaboxes. In this class we add a bunch of useful one
 *
 * @package		WordPress
 * @subpack		Salt
 * @since		Salt 1.0
 * @link https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Salt_CMB_Actions' ) ) :
/**
 * The function and action for 'cmb_render_images'
 *
 * @param array $field
 * @param string $meta
 *
 * @return void
 *
 * @access public
 */
class Salt_CMB_Actions { 

	function __construct() {
		
		add_action( 'cmb_render_images', array(&$this, 'salt_cmb_render_images'), 10, 2 );
		add_filter( 'cmb_validate_images', array(&$this, 'salt_cmb_validate_images') );
		
		add_action( 'cmb_render_post_select', array(&$this, 'salt_cmb_render_post_select'), 10, 2 );
		add_action( 'cmb_render_post_multicheck', array(&$this, 'salt_cmb_render_post_multicheck'), 10, 2 );

		add_action( 'cmb_render_post_multiselect', array(&$this, 'salt_cmb_render_post_multiselect'), 10, 2 );
		add_action( 'cmb_render_term_select', array(&$this, 'salt_cmb_render_term_select'), 10, 2 );		

		add_action( 'cmb_before_table', array(&$this, 'salt_cmb_before_table'), 10, 3);	
		add_action( 'cmb_after_table', array(&$this, 'salt_cmb_after_table'), 10, 3);
	}

	/**
	 * The function and action for 'cmb_render_images'
	 *
	 * @param array $field
	 * @param string $meta
	 *
	 * @return void
	 *
	 * @access public
	 */
	function salt_cmb_render_images( $field, $meta ) {
		$i = 0;

		foreach ($field['options'] as $option) {
			$i++;
	
			$checked = '';
			$selected = '';
			$meta = ($meta=='') ? 'one-col' : $meta;
			
			if($meta != '') {
			
				if ( $meta == $option["name"] ) {
					$checked = ' checked';
					$selected = 'salt-radio-img-selected';
				}
			
			} else {
				
				if ($option['std'] == $key) {
					$checked = ' checked';
					$selected = 'salt-radio-img-selected';
				} elseif ($i == 1  && !isset($select_value)) {
					$checked = ' checked'; $selected = 'salt-radio-img-selected';
				} elseif ($i == 1  && $option['std'] == '') {
					$checked = ' checked';
					$selected = 'salt-radio-img-selected';
				} else {
					$checked = '';
				}
			}
			?>
			<span>
				<label>
					<input type="radio" id="salt-radio-img-<?php echo $field['id'] . $i; ?>" class="checkbox salt-radio-img-radio" value="<?php echo $option['name']; ?>" name="<?php echo $field['id']; ?>" <?php echo $checked; ?> />
	
					<img alt="<?php echo $option["name"]; ?>" title="<?php echo $option["name"]; ?>" src="<?php echo esc_url( $option['value'] ); ?>" alt="" class="salt-radio-img-img <?php echo $selected; ?>" onClick="document.getElementById('cmb-metabox-radio-img-<?php echo esc_js($field['id'] . $i); ?>').checked = true;" />
				</label>
			</span>
		<?php
		}
		
		echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
	}

	/**
	 * The function and filter for 'cmb_validate_images'
	 *
	 * @param array $new
	 *
	 * @return array $new
	 *
	 * @access public
	 */
	function salt_cmb_validate_images( $new ) {
	    return $new;
	}

	/**
	 * The function and action for 'cmb_render_post_select'
	 *
	 * @param array $field
	 * @param string $meta
	 *
	 * @return void
	 *
	 * @access public
	 */
	function salt_cmb_render_post_select( $field, $meta ) {
		global $post;
		$original_post = $post;
	
		$i = 0;
		
		$posts = new WP_Query( $field['options'] );
		$options = array( array('name' => __('--- Select ---', 'salt'), 'value' => 0) );
		if($posts->have_posts()) {
			while($posts->have_posts()) {
				$posts->the_post();
				
				$options[] = array('name' => get_the_title(), 'value' => get_the_ID());
			}
		}
		wp_reset_postdata();
		
		if( empty( $meta ) && !empty( $field['std'] ) ) {
			$meta = $field['std'];
		}
		
		echo '<select name="', $field['id'], '" id="', $field['id'], '">';
		foreach ($options as $option) {
			echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
		}
		echo '</select>';
		echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
		
		$post = $original_post;
	}

	/**
	 * The function and action for 'cmb_render_post_multicheck'
	 *
	 * @param array $field
	 * @param string $meta
	 *
	 * @return void
	 *
	 * @access public
	 */
	function salt_cmb_render_post_multicheck( $field, $meta ) {
		global $post;
		$original_post = $post;
		
		$field['multiple'] = true;
		$meta = get_post_meta( $post->ID, $field['id']);
		
		$posts = new WP_Query( $field['options'] );
		$options = array();
		if($posts->have_posts()) {
			while($posts->have_posts()) {
				$posts->the_post();
				
				$options[ get_the_ID() ] = get_the_title();
			}
		}
		wp_reset_postdata();
		
		echo '<ul>';
		$i = 1;
		foreach ( $options as $value => $name ) {
			// Append `[]` to the name to get multiple values
			// Use in_array() to check whether the current option should be checked
			echo '<li><input type="checkbox" name="', $field['id'], '[]" id="', $field['id'], $i, '" value="', $value, '"', in_array( $value, (array)$meta[0] ) ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $name, '</label></li>';	
			$i++;
		}
		echo '</ul>';
		echo '<span class="cmb_metabox_description">', $field['desc'], '</span>';
		
		$post = $original_post;
	}

	/**
	 * The function and action for 'cmb_render_post_multiselect'
	 *
	 * @param array $field
	 * @param string $meta
	 *
	 * @return void
	 *
	 * @access public
	 */
	function salt_cmb_render_post_multiselect( $field, $meta ) {
		global $post;
		$original_post = $post;
		
		$field['multiple'] = true;
		$meta = get_post_meta( $post->ID, $field['id']);
		$posts = new WP_Query( $field['options'] );
	
		$options = array();
		if($posts->have_posts()) {
			while($posts->have_posts()) {
				$posts->the_post();
				
				$options[ get_the_ID() ] = get_the_title();
			}
		}
		wp_reset_postdata();
		
		echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
		echo '<select multiple name="', $field['id'], '[]">';
		$i = 1;
		foreach ( $options as $value => $name ) {
			// Append `[]` to the name to get multiple values
			// Use in_array() to check whether the current option should be checked
			echo '<option id="', $field['id'], $i, '" value="', $value, '"', in_array( $value, (array)$meta[0] ) ? ' selected' : '', '>', $name, '</option>';	
			$i++;
		}
		echo '</select>';
		
		$post = $original_post;
	}

	/**
	 * The function and action for 'cmb_render_term_select'
	 *
	 * @param array $field
	 * @param string $meta
	 *
	 * @return void
	 *
	 * @access public
	 */
	function salt_cmb_render_term_select( $field, $meta ) {
	
		$terms = get_terms( $field['options'][0], $field['options'][1] );
		$term_options[] = array('name' => __('--- Select ---', 'salt'), 'value' => '');
		if($terms) {
			foreach($terms as $term) {
				$term_options[] = array('name' => $term->name, 'value' => $term->slug);
			}
		}
	
		if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
		echo '<select name="', $field['id'], '" id="', $field['id'], '">';
		foreach ($term_options as $option) {
			echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
		}
		echo '</select>';
		echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
	}


	/**
	 * The function and action for 'salt_cmb_before_table'
	 *
	 * @param array $field
	 * @param string $meta
	 *
	 * @return void
	 *
	 * @access public
	 */	
	function salt_cmb_before_table($meta_box, $object_id, $object_type) {
		
		if ($object_id=='theme_options' && $object_type=='options-page') {
			
			$i=-1;
			$menu_items = array(); 
			
			if ($meta_box)  {
				
				echo '<div class="wp-filter">';
				echo '<ul class="filter-links theme-options-menu">';
				
				foreach ($meta_box as $options) {
					
					if (is_array($options) && !empty($options)) {
						
						foreach ($options as $option) {
	
							if (!empty($option["type"]) && $option["type"]=='title') {
								$i++;
								$class=($i==0)?'current':'';
								$menu_items[$i] = '<li><a data-ids="%1s" class="'.$class.'">'.$option["name"].'</a></li>';
								
								$data_ids[$i] = $option["id"] . ' ';
								
							} elseif (!empty($option["type"])) {
								
								$data_ids[$i] .= $option["id"] . ' ';
									
							}

							if (!empty($menu_items[$i-1]) && !empty($data_ids[$i-1]) && $option["type"]=='title') {
								$output .= sprintf($menu_items[$i-1], $data_ids[$i-1]);							
							}
						}
					}
				}
				$output .= sprintf($menu_items[$i], $data_ids[$i]);	
				echo $output;
				echo '</ul>';
				echo '</div>';
			}
		}
	}
	
	function salt_cmb_after_table( $meta_box, $object_id, $object_type ){

		if ($object_id=='theme_options' && $object_type=='options-page') {
		?>
		<script>
			jQuery(document).ready(function ($) {
				
				$('.theme-options-menu li a').click(function(e) {
					e.preventDefault();
					menu_click(this); 
				});
			
				var menu_click = function( node ){ 
					$('.form-table.cmb_metabox tbody tr').hide();
					$(node).parent().parent().find('li a').removeClass('current');
					$.each($(node).data('ids').split(" ").slice(0,-1), function(index, item) {
						$('.cmb_id_'+item).fadeIn(); 
					});
					$(node).addClass('current');
				}
				
				menu_click('.theme-options-menu li:first-child a');
			});			
		</script>
		<?php
		}
	}	
}
endif;

$cmb_actions = new Salt_CMB_Actions();