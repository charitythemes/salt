<?php
if ( ! class_exists( 'Salt_Post_Type_Shortcodes' ) ) :
/**
 * Salt Post Type Shortcodes
 *
 * Includes all the functions to add shortcodes for each post type.
 *
 * @package		Salt
 * @since		1.5.2
 */
class Salt_Post_Type_Shortcodes {
	
	/**
	 * User defined shortcode id assigned within each post type.
	 *
	 * @var string Holds the submitted shortcode id.
	 */
	public $id;
		
	/**
	 * User defined shortcode options assigned within each post type.
	 *
	 * @var array Holds the submitted shortcode options.
	 */
	public $options;
	
	/**
	 * User defined string for the post type assigned on __construct(). 
	 *
	 * @var string Hold the post type that this meta box is added to.
	 */
	public $post_type;
	
	/**
	 * Constructor
	 *
	 * Initiate the class.
	 *
	 * @param array $options Holds the options for this particular shortcode
	 * @param string $post_type The post type this shortcode will be used to display
	 */
	public function __construct( $id, $options ) {
		
		// Set the user submitted options to the object.
		$this->id = $id;
		$this->options = $options;
		$this->post_type  = $options['post_type'];
		
		// Ajax function to create a shortcode and place it in the mce editor.
		add_action( 'wp_ajax_salt_create_shortcode', array( &$this, 'create_shortcode' ));
		
		// Add a tab on the media pop-up with the different post types.
		add_filter( 'media_upload_tabs', array( &$this, 'add_tab') );
		
		// Enqueue the CSS and JS for the appropriate tab.
		add_action( "media_upload_{$this->id}", array( &$this, "enqueue") );

		// Add an 'Insert Content' button on pages.
		add_action( 'media_buttons', array( &$this, 'add_insert_button' ), 20);

		// Add in the tinymce code to change custom post type shortcodes into images.
		add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ));
		add_filter( 'tiny_mce_before_init', array( &$this, 'add_tinymce_vars' ));
		
		// Add a new media template for our custom post type shortcodes.
		add_action('print_media_templates', array( &$this, 'mce_view_shortcodes'));

	}

	/**
	 * Create Shortcode
	 *
	 * Ajax function to create the shortcode from the data parsed.
	 *
	 * @return Json_encoded string The shortcode to be added to the mce editor.
	 */
	function create_shortcode() {
		
		// Get the data posted when clicking insert.
		$posted = $_POST['data'];
		
		// Parse the data into and array.
		parse_str($posted, $parsed_data);
		
		// Add the shortcode value into the output.
		$output = '[' . $parsed_data['shortcode'];

		// Cycle through each of the additional values.
		foreach ($parsed_data as $key => $value) {
			// If the value is not equal to 'shortcode'.
			if (($value) && ($key != 'shortcode')) {
				// Add this key and value as a shortcode attribute.
				$output .= ' ' . $key . '="' . $value .'"';
			}
		}
		// Close off the shortcode.
		$output .= ']';
		
		// Use Json encode to return the outputed shortcode string
		echo json_encode(
			array(
				'response' 	=> 'success', 
				'shortcode' => $output
			)
		);
		
		// Exit the ajax request.
		exit;
	}

	/**
	 * Add Tab
	 *
	 * Adds the tab to the left side of the media pop-up window
	 *
	 * @return string Returns the id and the title of the tab to be added
	 */	
	function add_tab( $tabs ) {

		// Use the shortcode id and title to create the tab
	    $tabs[$this->id] = $this->options['title'];
	    return $tabs;
	}

	/**
	 * Enqueue
	 *
	 * Enqueues the required jQuery and CSS to the iframe window 
	 * in the media popup.
	 */		
	function enqueue() {
		
		// Enqueue the JS and CSS to insert a post type in the MCE editor.
		wp_enqueue_script( 'salt-insert-shortcode', SALT_TEMPLATE_URI . '/core/assets/js/insert-content.js', array( 'jquery' ), '1.5.0');
	    wp_enqueue_style( 'salt-insert-shortcode', SALT_TEMPLATE_URI . '/core/assets/css/insert-content.css', false, '1.5.0' );

		// Add an iframe function to display content for the media upload page.
		wp_iframe( array( &$this, 'render') );		
	}

	/**
	 * MCE View Shortcodes
     * 
	 * Add the HTML media template for our custom post type shortcodes. 
	 */	
	function mce_view_shortcodes() {
		?>
		<script type="text/html" id="tmpl-editor-apps">
		   <div class="mce-app-wrapper">
	            <h2><i class="dashicons {{ data.menu_icon }}"></i>{{ data.title }}</h2>
		   </div>
		</script>
		<?php
	}

	/**
	 * Add Insert Button
	 *
	 * Adds a button that inserts shortcode to show apps.
	 *
	 * @return string HTML for the button.
	 */	
	public function add_insert_button() {
		
		// This button should only display when editing a page.
		if ( get_post_type() == 'page' ) {
			echo '<button type="button" id="insert-post-type-button" href="#" class="button insert-post-type add_post_type add_media" data-editor="iframe:'.$this->id.'" title="' . __("Add", 'salt') . ' '.$this->options['plural'].'"><span class="dashicons '.$this->options['menu_icon'].'"></span></button>';
		}
	} 
	
	/**
	 * Add TinyMCE Plugin
	 *
	 * Include the tinymce javascript plugin.
	 *
	 * @return array Holds the JS scripts to be added to the TinyMCE editor, including ours.
	 */
    function add_tinymce_plugin($plugin_array) 
    {
       $plugin_array['salt'] = SALT_TEMPLATE_URI . '/core/assets/js/mce-editor-plugin.js';
       return $plugin_array;
    }

	/**
	 * Add TinyMCE Vars
	 *
	 * Include the variables we need into the editor.
	 *
	 * @return array 
	 */
    function add_tinymce_vars( $settings )
    {

		$shortcode = array(
			'id' 		=> $this->id,
			'menu_item' => $this->options['menu_icon'],
			'title'		=> $this->options['plural']
		);
		
        $settings['salt_sc_params'] = json_encode( $shortcode );  		
		$settings['content_css'] .= ",". SALT_TEMPLATE_URI . '/core/assets/css/mce-editor-style.css'; 

		return $settings;
    }
    
	/**
	 * Render
	 *
	 * HTML structure for the media popup content area.
	 */
	function render() 
	{ ?>
	    <form action="" id="salt_form_create_shortcode" class="insert-post-form">
		    <div class="media-frame-content">
			    <div class="media-content">
					<?php					
					echo $this->views(); 
					?>
			    </div>
				<div class="media-sidebar" >
					<div class="media-uploader-status" style="display: none;">
						<h3><?php _e('Content Details', 'salt'); ?></h3>
						<?php					
						echo $this->fields(); 
						?>
					</div>
				</div>
				<div class="media-frame-toolbar">
					<div class="media-toolbar">
						<div class="media-toolbar-secondary">
							
						</div>
						<div class="media-toolbar-primary">
							<input type="hidden" id="view_id" name="display" value="" />
							<input type="hidden" name="shortcode" value="<?php echo $this->id; ?>">
							<input type="submit" class="button media-button button-primary button-large media-button-select" value="<?php _e('Insert into page', 'salt'); ?>" disabled="disabled" />
						</div>
					</div>
				</div>
		    </div>
	    </form>
	    <?php
	}
	
	/**
	 * Views
	 *
	 * List out the display options for a particular shortcode.
	 *
	 * @return string
	 */		
	function views() {
		?>		
		<ul class="attachments">
		<?php 
		if ( $this->options['views'] ) { 
			
			foreach ( $this->options['views'] as $id => $view ) {
				?>
				<li class="attachment view-select" data-id=<?php echo $id; ?>>
					<img src="<?php echo $view['screenshot']; ?>" />
					<a class="check" href="#" title="Deselect"><div class="media-modal-icon"></div></a>
					<h3 class="view-name"><?php echo $view['label']; ?></h3>
				</li>
			<?php	
			}
		}			
		?>
		</ul>		
		<?php
	}	

	/**
	 * Process Options
	 * 
	 * Process the options array into a usable form
	 *
	 * @return string The options turned into a form.
	 */
	 function fields() {
		 
		$options = $this->options['fields'];
		
		// Make sure the output is cleared.
		$output  = '';
		
		// If optins exist.
		if ( $options ) {
			// Cyclce through the options.
			foreach ( $options as $value ) {

				// Make sure the temp value is cleared at the start of each cycle.
				$val = '';
				
				// Check if this setting is only for specific views.
				if ( $value['views']!=NULL ) 
				{
					// Use Json to encode those views.
					$views = json_encode( $value['views'] );
				
				// If no views have been specified, we need to do it through code.
				} else {

					// Cycle through all the available views for this post type.
					if ( $this->options['display'] ) { 
						
						foreach ( $this->options['display'] as $view ) 
						{
							// Create an array of the available views.
							$views_arr[] = $view['id'];
						}
					} else {
						
						// Setup a default view as fail safe.
						$views_arr = array('default');						
					}
					
					// Use Json to encode those views.
					$views = json_encode($views_arr);
				}
				
				// Output the div and label wrappers of this setting.
				$output .= "<div class='attachment-display-settings hidden' data-views='".$views."'>";
				$output .= "<label class='setting'><span>".$value["name"]."</span>";
				
				// Switch the output beteeen the different field types.
				switch ( $value['type'] ) {
					
					case 'text':
					
						if ( $value['std'] != "")
							$val = $value['std'];

						$output .= '<input class="meta-input" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
						break;
					
					case "checkbox":
					
						$std 	 = $value['std'];
						$checked = '';
						
						if( $std == 'true') {
							$checked = 'checked="checked"';
							$val = 'true';
						} else {
							$checked = $val = '';
						}
						
						$output .= '<input type="checkbox" class="checkbox meta-input" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';
					
						break;
					
					case 'textarea':
						
						$cols = '8';
						
						if( $value['std'] != "") {
							$ta_value = stripslashes( $std );
						}
						$output .= '<textarea class="meta-input" name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
						
						break;
					
					case 'select':
					
						$output .= '<select class="meta-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
					
						if ($value['options']) { 
							foreach ($value['options'] as $key => $option) {
								
								$selected = '';
								
								if ( isset($value['std']) && $value['std'] == $key) {
									$selected = ' selected="selected"';
								}

								$output .= '<option'. $selected .' value="'. $key .'">';
								$output .= $option;
								$output .= '</option>';
							
							}
						}
						$output .= '</select>';
					
						break;
					
					case 'multiselect':
					
						$output .= '<select multiple="multiple" class="meta-input" name="'. $value['id'] .'[]" id="'. $value['id'] .'">';

						if ($value['options']) {
							foreach ($value['options'] as $key => $option) {
								$selected = '';

								if ( isset($value['std']) ) {
									if ($value['std'] == $key) {
										$selected = 'selected="selected"';
									}
								}

								$output .= '<option ' . $selected . ' value="'. $key .'">' . $option . '</option>' . "\n";
							}
						}
						$output .= '</select>';
					
						break;
					
					case "radio":
						
						foreach ($value['options'] as $key => $option) {
							$checked = '';

							if ($value['std'] == $key) {
								$checked = ' checked';
							}

							$output .= '<input class="meta-input meta-radio" type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
						}

						break;
					
					case 'post_select':
						
						$args = $value['options'];
						$the_query = new WP_Query( $args );

						if ( $the_query->have_posts() ) {
						
							$output .= '<select class="meta-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
							$output .= '<option value="">'.__('--- Select ---', 'salt').'</option>';
												
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								
								$selected = '';
								
								if ( isset($value['std']) && $value['std'] == get_the_ID()) {
									$selected = ' selected="selected"';
								}

								$output .= '<option'. $selected .' value="'. get_the_ID() .'">';
								$output .= get_the_title();
								$output .= '</option>';
							
							}

							$output .= '</select>';
						
						} else {
							
							$output .= '<span>'.__('Oops! Nothing Found', 'salt').'</span>';
							
						}
						
						// Restore original Post Data
						wp_reset_postdata();

						break;

					case 'term_select':
						
						$terms = get_terms( $value['options'][0], $value['options'][1] );

						if ( !empty( $terms ) && !is_wp_error( $terms ) ){
						
							$output .= '<select class="meta-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
							$output .= '<option value="">'.__('--- Select ---', 'salt').'</option>';
							
							foreach ( $terms as $term ) {

								$selected = '';
								
								if ( isset($value['std']) && $value['std'] == get_the_ID()) {
									$selected = ' selected="selected"';
								}

								$output .= '<option'. $selected .' value="'. $term->slug .'">';
								$output .= $term->name;
								$output .= '</option>';
							}

							$output .= '</select>';
						
						} else {
							
							$output .= '<span>'.__('Oops! Nothing Found', 'salt').'</span>';
							
						}

						break;
												
					default:
						break;
				}
				
				// If type is a checkbox add a line break.
				if ( $value['type'] != "checkbox" ) {
					$output .= '<br/>';
				}
				
				// Close off the label tag
				$output .= '</label>'."\n";
				
				// Add a description text if available.
				if (isset($value['desc']))
					$output .= '<div class="description">'. $value['desc'] .'</div>'."\n";
				
				// Close off the attachment display setting.
				$output .= '</div><!-- .attachment-display-settings -->'."\n";
			}
			
			// Clear the floats.
			$output .= '<div class="clear"></div>';
			$output .= '</div>';
	
			// Return the HTML to display.
			return $output;
		}
	}
}
endif;