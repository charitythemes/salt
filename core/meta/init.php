<?php
if ( ! class_exists( 'Salt_Meta_Box' ) ) :
/**
 * Class to create a meta box on a post type.
 *
 * This class is called within the Salt_Post_Types class
 * the options are set within extension class in the metabox_options function.
 *
 * @package		Salt
 * @since		1.5.0
 */
class Salt_Meta_Box {
	
	/**
	 * User defined options assigned on __construct().
	 *
	 * @var array Holds the submitted meta box options and fields
	 */
	public $settings;
	
	/**
	 * User defined string for the post type assigned on __construct(). 
	 *
	 * @var string Hold the post type that this meta box is added to.
	 */	
	public $post_type;
	
	/**
	 * @var bool Used to check if we can show a default value for a metabox.
	 */
	public $saved = false;
	
	/**
	 * Constructor
	 *
	 * Initiate the class.
	 *
	 * @param string $post_type The name of the post type.
	 * @param array $settings User submitted options.
	 */
	public function __construct( $post_type = 'post', $settings=array() ) {
		
		$this->post_type = $post_type;
		$this->settings  = $settings;

		$this->init();
	}
	
	/**
	 * Init
	 *
	 * Register the hooks to add the meta and save it.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_action
	 */
	public function init() {
		
		// Enqueue scripts used with the default meta boxes.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Add the meta box using WordPress function.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

		// Save the post when it's updated.
		add_action( 'save_post', array( $this, 'save_post_meta' ) );		
	}

	/**
	 * Enqeue
	 * 
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		
		// Enqueue the stylesheet for the color picker.
		wp_enqueue_style( 'wp-color-picker' );
	}
	
   /**
	 * Adds the meta box on the edit post screen
	 * 
	 * @link https://codex.wordpress.org/Function_Reference/add_meta_box
	 */
	public function add_meta_box() {
		
		// Add the meta box using the WordPress function
		
		if ( isset( $this->settings["template"] ) ) {
			
			global $post;

		    if ( get_page_template_slug( $post->ID ) == $this->settings["template"] ) {
				
				add_meta_box (
			        $this->settings['id'],
			        $this->settings["title"],
					array( $this, "render" ),
					$this->post_type,
					$this->settings["context"],
					$this->settings["priority"] );
			
			}
			
		} else {

			add_meta_box (
		        $this->settings['id'],
		        $this->settings["title"],
				array( $this, "render" ),
				$this->post_type,
				$this->settings["context"],
				$this->settings["priority"] );
		
		}
	}
	
	/**
	 * Render
	 *
	 * Renders the input wrapper and calls $this->render_field() for the fields.
	 */
	public function render() {

		global $post;
		
		// Check if this post meta has been saved before.
		$this->saved = $this->has_been_saved( $post->ID, $this->settings['fields'] );
				
		// Create a nonce field for security.
		wp_nonce_field( SALT_TEMPLATE_DIR, 'salt_metabox_nonce' );

		// Set the section to be closed before we start looping through the fields.
		$section = 'closed';
		
		// Render the section tabs, if any sections exist.
		// @return - boolean if we have created tabs or not.
		$has_tabs = $this->render_section( $this->settings['fields'] );

		// If there are no tabs, we need to open a list.	
		if ( ! $has_tabs ) {
			echo '<ul class="salt-metabox-list">';
		}
		
		foreach ( $this->settings['fields'] as $field ) {

			// If the section is closed and the type is a section
			if ( $field['type'] == 'section' ) { 
				
				// Check if the section is already opened.
				if ( $section == 'open' ) {
					
					// Close off the current section.
					echo '</ul><!-- salt-metabox-list -->';	
					
					// Notify the section is closed.
					$section = 'closed';
				}
				
				// Open a new section. ?>
				<ul id="<?php echo $field['id']; ?>" class="salt-metabox-list salt-metabox-section">
					<?php if ( $field['desc'] ) echo '<p>' . $field['desc'] . '</p>'; ?>
								
			<?php
				// Notify the section is opened.
				$section = 'open';
			} else { 
			
				$id    = 'salt-metabox-' . str_replace( '[', '-', str_replace( ']', '', $field['id'] ) );
				$class = 'salt-metabox salt-metabox-' . $field['type'];		

				?>
				<li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
					<?php $this->render_field( $post->ID, $field ); ?>
				</li><?php	
			}
		}
		
		// If there is a section still open or there were no tabs, we need to close the list off.
		if ( $section == 'open' || ! $has_tabs ) {
			// Close off the current section.
			echo '</ul><!-- salt-metabox-list -->';				
		}
	}
	
	/**
	 * Render Section Tabs
	 *
	 * Renders the tabs that are used to navigate the different sections.
	 */
	public function render_section( $fields ) {
		
		// Loop through the fields
		foreach ( $fields as $field ) {
			
			// If the type is a section, we need to create a tab.
			if ( $field['type'] == 'section' ) {
				// Create a tab item.
				$tabs .= '<li><a href="#'.$field['id'].'">'.$field['label'].'</a></li>';
			}
		}	
		
		// If any items are created, we can wrap that in a ul tag list.
		if ( $tabs ) {
			
			// Create tabs list.
			echo '<ul class="salt-metabox-tabs">'.$tabs.'</ul><!-- salt-metabox-tabs -->';
			
			// Return true, we have created tabs!
			return true;
		
		} else {
			
			// If no sections exist, we return false.
			return false;
		}
		
	}
	 
	/**
	 * Render Field.
	 *
	 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
	 *
	 * @param int   $post_id
	 * @param $field Supports basic input types `text`, `checkbox`, `textarea` and `select`.
	 */
	public function render_field( $post_id, $field ) {
		
		// get value of this field if it exists for this post
		$meta = ( get_post_meta( $post_id, $field['id'], true ) );

		if ( ! $this->saved && $meta == '' && isset( $field['std'] ) ) {
			$meta = $field['std'];
		}

		switch ( $field['type'] ) {	
			case 'text' :
				?>
				<label>
					<?php if ( ! empty( $field['label'] ) ) : ?>
						<span class="salt-metabox-title"><?php echo esc_html( $field['label'] ); ?></span>
					<?php endif; ?>
					<input type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta; ?>" size="30" /> 
					<?php if ( ! empty( $field['desc'] ) ) : ?>
						<br /><span class="description salt-metabox-description"><?php echo $field['desc']; ?></span>
					<? endif; ?>
				</label>
				<?php
				break;
				
			case 'textarea' :
				?>
				<label>
					<?php if ( ! empty( $field['label'] ) ) : ?>
						<span class="salt-metabox-title"><?php echo esc_html( $field['label'] ); ?></span>
					<?php endif; ?>
					<textarea rows="5" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>"><?php echo esc_textarea( $meta ); ?></textarea>
					<?php if ( ! empty( $field['description'] ) ) : ?>
						<span class="description salt-metabox-description"><?php echo $field['description']; ?></span>
					<?php endif; ?>
				</label>
				<?php
				break;

			case 'checkbox' :
				?>
				<label>
					<?php if ( ! empty( $field['label'] ) ) : ?>
						<span class="salt-metabox-title"><?php echo esc_html( $field['label'] ); ?></span>
					<?php endif; ?>
						<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" size="30" <?php echo ( $meta=='on' ) ? 'checked' : ''; ?> /> 
					<?php if ( ! empty( $field['desc'] ) ) : ?>
						<span class="description salt-metabox-description"><?php echo $field['desc']; ?></span>
					<? endif; ?>
				</label>
				<?php
				break;

			case 'select' :
				?>
				<label>
					<?php if ( ! empty( $field['label'] ) ) : ?>
						<span class="salt-metabox-title"><?php echo esc_html( $field['label'] ); ?></span>
					<?php endif; ?>
					<select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">
						<?php if ( is_array( $field['options'] ) ) foreach ( $field['options'] as $key => $value ) : ?>
					    	<option value="<?php echo $key; ?>" <?php echo ( $meta==$key ) ? 'selected' : ''; ?>><?php echo $value; ?></option>
						<?php endforeach; ?>
					</select>
						<?php if ( ! empty( $field['desc'] ) ) : ?>
						<br /><span class="description salt-metabox-description"><?php echo $field['desc']; ?></span>
					<? endif; ?>
				</label>
				<?php
				break;

			case 'color' :
				?>
				<label>
					<?php if ( ! empty( $field['label'] ) ) : ?>
						<span class="salt-metabox-title"><?php echo esc_html( $field['label'] ); ?></span>
					<?php endif; ?>
				</label>	
				<input type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta; ?>" size="30" class="salt-select-color" /> 
				<?php if ( ! empty( $field['desc'] ) ) : ?>
					<br /><span class="description salt-metabox-description"><?php echo $field['desc']; ?></span>
				<? endif; ?>
				
				<?php
				break;
			
			case 'image' :
				?>
				<label>
					<?php if ( ! empty( $field['label'] ) ) : ?>
						<span class="salt-metabox-title"><?php echo esc_html( $field['label'] ); ?></span>
					<?php endif; ?>
				</label>
				<div class="salt-image-upload-wrapper">
					<div class="salt-image-display salt-image-upload-button">
						<!-- Image -->
						<?php if ( isset( $meta ) && $meta != '' ) : ?>
						<?php $img = wp_get_attachment_image_src( $meta, 'medium' ); ?>
						<div class="salt-background-image-holder">
							<img src="<?php echo $img[0]; ?>" class="salt-background-image-preview" />
						</div>
						<a class="salt-image-remove" href="#"><span class="dashicons dashicons-no"></span></a>
						<?php else : ?>
						<div class="placeholder"><span class="dashicons dashicons-format-image"></span></div>
						<!-- Remove button -->
						<a class="salt-image-remove hidden" href="#"><span class="dashicons dashicons-no"></span></a>
						<?php endif; ?>
					</div>
					<input type="hidden" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php if ( isset ( $meta ) ) echo $meta; ?>" class="salt-image-upload-field" />
					<input type="button" class="button button-primary salt-image-button salt-choose-image" value="<?php _e('Choose Image', 'salt'); ?>" />
				</div>
				<?php
				break;
			case 'section' :
				continue;
								
				break;
			default :
				echo __('Sorry this field type doesn\'t exist', 'salt');
				
		}
	}
	
	/**
	 * Verify Post Meta
	 *
	 * Safety net for the post_meta save
	 *
	 * @param integer $post_id Pass the id of the current post.
	 */	
	public function verify_post_meta( $post_id ) {
		
		// Verify the nonce field exists - won't on quick edit.
		if ( ! isset( $_POST['salt_metabox_nonce'] ) )
			return $post_id;
			
		// Verify the nonce field that is added in the metabox.
	    if ( ! wp_verify_nonce( $_POST['salt_metabox_nonce'], SALT_TEMPLATE_DIR ) )
	        return $post_id;
	    
	    // Make sure we are not doing an Auto Save.
	    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
	        return $post_id;

		// Verify if this post type is the same as the current post.
		if ( $this->post_type != $_POST['post_type'] )
			return $post_id;
					
		// Check if we are editing a page.
	    if ( 'page' == $_POST['post_type'] ) {
	    
	    	// Check if this user is allowed to edit pages.
	        if ( !current_user_can( 'edit_page', $post_id ) ) 
	        	return $post_id;
		
		// Check if this user can edit other post types.
	    } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
		
			return $post_id;
	    }	
	}
	
	/**
	 * Save Post Meta
	 *
	 * Save the post meta fields we have added using settings
	 * 
	 * @param integer $post_id Pass the ID of the post we are saving
	 */	
	public function save_post_meta( $post_id ) {
		
		// Call the function to verify we should be here.
	    if ( ! $this->verify_post_meta( $post_id ) ) :
		    	    
		    // Check if there are any fields to save.
		    if ( $this->settings['fields'] ) :
		    
			    // Cycle through the settings
			    foreach ( $this->settings['fields'] as $field ) :
				
					// Get the post meta for the current field
			        $old = get_post_meta( $post_id, $field['id'], true );
			        
			        // Get the new posted data for this field
			        $new = ( isset( $_POST[ $field['id'] ] ) ) ? $_POST[ $field['id'] ] : '';
					
			        // If there is a difference between the 'new' posted field and the 'old' saved field
			        if ( $new && $new != $old ) :
						
						// Save the post meta as the newly posted data
			            update_post_meta( $post_id, $field['id'], $new );
		
					// If the old field exists, but the new field is empty
			        elseif ( '' == $new && $old ) :
		
				        // Delete the old post meta
			            delete_post_meta( $post_id, $field['id'], $old );
			        
			        endif;
			    endforeach;
		    endif; 
	    endif;
    }
 
	/**
	 * Check if meta box has been saved
	 * This helps saving empty value in meta fields (for text box, check box, etc.)
	 *
	 * @param int   $post_id
	 * @param array $fields
	 *
	 * @return bool
	 */   
    static function has_been_saved( $post_id, $fields ) {

		// Cylce through the fields to check if any have been saved before for this post.
		foreach ( $fields as $field ) {
			$value = get_post_meta( $post_id, $field['id'], true );
			if ( '' !== $value ) {
				return true;
			}
		}

		return false;
    }
}

/**
 * Class to extend the main Meta Box class with one specifically to add background images.
 *
 * This class is called within the Salt_Post_Types class with predefined options.
 *
 * @package		Salt
 * @since		1.5.0
 */
class Salt_Background_Meta_Box extends Salt_Meta_Box {
	
	/**
	 * User defined options assigned on __construct().
	 *
	 * @var stinrg The prefix used on the id for the fields
	 */
	public $prefix = '_background_';

	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type  The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		// Create the settings for this meta box 
		$this->settings = array(
			'id'		 => 'background',
			'title'      => __('Background', 'salt'),
			'pages'      => array('slider'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(	
				array(
			        'label'	=> __('Image', 'salt'),
			        'id'    => $this->prefix.'image',
			        'type'  => ''
				),
				array(
			        'label'	=> __('Height (px)', 'salt'),
			        'std' 	=> '450',
			        'id'    => $this->prefix.'height',
			        'type'  => 'text'
				),
				array(
			        'label'	=> __('Color', 'salt'),
			        'id'    => $this->prefix.'color',
			        'type'  => 'color'
				),
				array(
			        'label'	=> __('Repeat', 'salt'),
			        'id'    => $this->prefix.'repeat',
			        'type'  => 'select',
			        'options' => array(
				        'no-repeat' => 'No Repeat',
				        'repeat'	=> 'Repeat',
				        'repeat-x'  => 'Repeat Horizontally',
				        'repeat-y'  => 'Repeat Vertically'
			        )
				),
				array(
			        'label'	=> __('Position', 'salt'),
			        'id'    => $this->prefix.'position',
			        'type'  => 'select',
			        'options' => array(
				        'center' => 'Center',
				        'top' 	 => 'Top',
				        'bottom' => 'Bottom',
				        'left' 	 => 'Left',
				        'right'  => 'Right'
			        )
				),
				array(
			        'label'	=> __('Stretch', 'salt'),
			        'id'    => $this->prefix.'stretch',
			        'type'  => 'checkbox',
				),
				array(
			        'label'	=> __('Darken', 'salt'),
			        'id'    => $this->prefix.'darken',
			        'type'  => 'checkbox',
				)
			)
		);
		
		// Initiate the meta box
		$this->init();
	}
	
	/**
	 * Enqeue
	 * 
	 * Enqueue background iamge related scripts.
	 */
	function enqueue() {
	
		// Enqueue the media panel.
        wp_enqueue_media();
        
        // Enqueue metabox styling
		wp_enqueue_style(  'salt-admin', SALT_TEMPLATE_URI.'/core/assets/css/meta.css', false, '1.5.0' );

		// Enqueue the stylesheet for the color picker.
		wp_enqueue_style( 'wp-color-picker' );
		
		// Enqueue the JS to initiate the color picker if it is displayed.
        wp_enqueue_script( 'salt-meta-js', SALT_TEMPLATE_URI . '/core/assets/js/meta.js', array( 'jquery', 'wp-color-picker' ) );		
        // Register and enqueue the JS to add a background image.
        // wp_register_script( 'salt-background-image', SALT_TEMPLATE_URI . '/core/assets/js/background-image.js', array( '' ) );
        wp_localize_script( 'salt-meta-js', 'meta_image',
            array(
                'title' => __( 'Set background image', 'salt' ),
                'button' => __( 'Set background image', 'salt' ),
            )
        );
        wp_enqueue_script( 'salt-meta-js' );
	}

	/**
	 * Render
	 *
	 * Renders the field wrapper, background image and calls $this->render_field() for the fields.
	 */	
	function render() {
		global $post;
		
		// Check if this post meta has been saved before.
		$this->saved = $this->has_been_saved( $post->ID, $this->settings['fields'] );		
		
		// Create a nonce field for security.
		wp_nonce_field( SALT_TEMPLATE_DIR, 'salt_metabox_nonce' );

		$meta = get_post_meta($post->ID, '_background_image', true);
		?>
		<div class="floatleft col-2">
			
			<h4 class="salt-metabox-title"><?php _e('Choose Your Image', 'salt'); ?></h4>
			<div class="salt-image-display salt-image-upload-button">
				<!-- Image -->
				<?php if ( isset( $meta ) && $meta != '' ) : ?>
				<?php $img = wp_get_attachment_image_src( $meta, 'medium' ); ?>
				<div class="salt-background-image-holder">
					<img src="<?php echo $img[0]; ?>" id="preview-background-img" class="salt-background-image" />
				</div>
				<a id="remove-background-img" class="salt-image-remove" href="#"><span class="dashicons dashicons-no"></span></a>
				<?php else : ?>
				<div class="placeholder"><span class="dashicons dashicons-format-image"></span></div>
				<!-- Remove button -->
				<a id="remove-background-img" class="salt-image-remove hidden" href="#"><span class="dashicons dashicons-no"></span></a>
				<?php endif; ?>
			</div>
			<input type="hidden" name="_background_image" id="_background_image" class="salt-image-upload-field" value="<?php if ( isset ( $meta ) ) echo $meta; ?>" />
			<input type="button" class="button button-primary salt-image-button salt-choose-image" value="<?php _e('Choose Image', 'salt'); ?>" />
		</div>
	
		<div class="floatleft col-2">
			<ul class="salt-metabox-list">
			<?php 
			// Cycle through the pre-defined field settings	
			foreach ( $this->settings['fields'] as $field ) {
				
				if ( $field['id'] == '_background_image' )
					continue;
					
				// Create ID's and Classes based on the field type and id.
				$id    = 'salt-metabox-' . str_replace( '[', '-', str_replace( ']', '', $field['id'] ) );
				$class = 'salt-metabox salt-metabox-' . $field['type'];		
	
				?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
					<?php $this->render_field( $post->ID, $field ); ?>
				</li><?php	
			
			} ?>
			</ul>
		</div>
		<div class="clear"></div>
		<?php
	}
}
endif;