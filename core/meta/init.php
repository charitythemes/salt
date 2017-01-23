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
	
	public $post = array();

		// Set the section to be closed before we start looping through the fields.
	public $section = 'closed';
		
	public $row = 'closed';
		
	public $count = 0;

	
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
		$this->settings  = (object) $settings;
				
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

		// Enqueue the JS to initiate the color picker if it is displayed.
        wp_enqueue_script( 'salt-meta-js', SALT_TEMPLATE_URI . '/core/assets/js/meta.js', array( 'jquery', 'wp-color-picker' ) );
	}
	
   /**
	 * Adds the meta box on the edit post screen
	 * 
	 * @link https://codex.wordpress.org/Function_Reference/add_meta_box
	 */
	public function add_meta_box() {
		
		// Add the meta box using the WordPress function
		if ( isset( $this->settings->template ) ) {
			
			global $post;
			if ( $this->settings->template == 'front-page' &&
				get_option('page_on_front') == $post->ID || 
			    get_page_template_slug( $post->ID ) == $this->settings->template ) {
				
				add_meta_box (
			        $this->settings->id,
			        $this->settings->title,
					array( $this, "render" ),
					$this->post_type,
					$this->settings->context,
					$this->settings->priority );
			
				add_filter( "postbox_classes_{$this->post_type}_{$this->settings->id}", array( $this, 'postbox_classes' ) );
			}
			
		} else {
			add_meta_box (
			    $this->settings->id,
		        $this->settings->title,
				array( $this, "render" ),
				$this->post_type,
				$this->settings->context,
				$this->settings->priority );
				
			add_filter( "postbox_classes_{$this->post_type}_{$this->settings->id}", array( $this, 'postbox_classes' ) );
		}
	}
	
	/**
	 * Classes to add to the post meta box
	 */
	public function postbox_classes( $classes ) {
		$classes[] = 'salt-box';
		return $classes;
	}
	
	/**
	 * Render
	 *
	 * Renders the input wrapper and calls $this->render_field() for the fields.
	 */
	public function render() {
		
		$this->post = $GLOBALS['post'];
		
		// Check if this post meta has been saved before.
		$this->saved = $this->has_been_saved( $this->post->ID, $this->settings->fields );
				
		// Create a nonce field for security.
		wp_nonce_field( SALT_TEMPLATE_DIR, 'salt_metabox_nonce' );

		// Render the section tabs, if any sections exist.
		// @return - boolean if we have created tabs or not.
		$has_tabs = $this->render_section_tabs( $this->settings->fields );
		
		$this->render_container( $has_tabs );
		
		foreach ( $this->settings->fields as $field ) {
			
			if ( $field['type'] == 'section' ) {
				
				$this->render_section( $field );
			
			} else {

				// 
				$this->render_row( $field );
				
				// Create class based on the field type.
				$class = "salt-field salt-{$field['type']}";
				$style = '';
				// 
				if ( ! empty( $field['width'] ) ) {
					$class .= " has-width width-{$field['width']}";
					$style = "width:{$field['width']}%;";
				}
				?>
				<div class="<?php echo esc_attr( $class ); ?>" style="<?php echo esc_attr( $style ); ?>">
					<?php $this->render_field( $this->post->ID, $field ); ?>
				</div><?php
			}
		}
		
		$this->render_close( $has_tabs );
	}
	
	/**
	 * Render Container
	 *
	 * .
	 */	
	public function render_container( $has_tabs = false ) {

		// If there are no tabs, we need to open a list.	
		if ( ! $has_tabs ) {
			echo '<div class="salt-fields-container">';
		}		
	}
	
	/**
	 * Render Tabs
	 *
	 * Renders the tabs that are used to navigate the different sections.
	 */
	public function render_section_tabs( $fields ) {
		$tabs='';
		// Loop through the fields
		foreach ( $fields as $field ) {
			
			// If the type is a section, we need to create a tab.
			if ( $field['type'] == 'section' ) {
				// Create a tab item.
				$tabs .= '<li><a href="javascript:" title="'.$field['label'].'" data-link="'.$field['id'].'"><span>'.$field['label'].'</span></a></li>';
			}
		}	
		
		// If any items are created, we can wrap that in a ul tag list.
		if ( $tabs ) {
			
			// Create tabs list.
			echo '<ul class="salt-tabs-nav">'.$tabs.'</ul><!-- salt-tabs-nav -->';
			
			// Return true, we have created tabs!
			return true;
		
		} else {
			
			// If no sections exist, we return false.
			return false;
		}
		
	}
	
	/**
	 * Render Section
	 *
	 * .
	 */
	function render_section( $field ) {
		
		// Check if the section is already opened.
		if ( $this->section == 'open' ) {
			
			// Close off the current section.
			echo '</div><!-- salt-fields-container -->';	
			
			// Notify the section is closed.
			$this->section = 'closed';
		}
		
		// Open a new section. ?>
		<div id="<?php echo $field['id']; ?>" class="salt-fields-container salt-fields-section">
			
			<?php
			if ( isset( $field["desc"] ) ) : ?>
				<p class="salt-section-description"><?php echo $field["desc"]; ?>		
			<?php
			endif; ?>
	
		<?php
		// Notify the section is opened.
		$this->section = 'open';		
	}

	/**
	 * Render Row
	 *
	 * .
	 */		
	function render_row( $field ) {
	
		if ( ! empty( $field['width'] ) || ( empty( $field['width'] ) && $this->row == 'open' ) ) {

			if ( $this->row == 'open' && $this->count>=99 ) {
				
				// Close off the current row.
				echo '</div><!-- salt-fields-row -->';
				
				// Notify the row is closed.
				$this->row = 'closed';
				
				$this->count=0;
			} 

			if ( $this->row == 'closed' && ! empty( $field['width'] ) ) {
				// Open a new row. ?>
				<div class="salt-fields-row">
				
				<?php 
				$this->row = 'open';				
			}
			
			if ( ! empty( $field['width'] )) 
				$this->count = $this->count + $field['width'];		
		}
	}

	/**
	 * Render Close
	 *
	 * .
	 */	
	function render_close( $has_tabs ) {
	
		if ( $this->row == 'open' ) {
			echo '</div><!-- salt-fields-row -->';
		}
		
		// If there is a section still open or there were no tabs, we need to close the list off.
		if ( $this->section == 'open' || ! $has_tabs ) {
			// Close off the current section.
			echo '</div><!-- salt-fields-container -->';				
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
		
		$field_format = get_template_directory() . "/core/meta/fields/{$field['type']}.php";

		// Include the template file.		
		if ( file_exists( $field_format )) {
			include( $field_format );
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
		    if ( $this->settings->fields ) :
		    
			    // Cycle through the settings
			    foreach ( $this->settings->fields as $field ) :
				
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
		$settings = array(
			'id'		 => 'background',
			'title'      => __('Background', 'salt'),
			'pages'      => array( $this->post_type ),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(	
				array(
			        'label'	=> __('Upload Image', 'salt'),
					'desc'	=> __('Upload Image', 'salt'),
			        'id'    => '_background_image',
			        'type'  => 'image'
				),
				array(
			        'label'	=> __('Set Image Height (px)', 'salt'),
			        'std' 	=> '450',
			        'id'    => '_background_height',
			        'type'  => 'text',
			        'width'	=> 50
				),
				array(
			        'label'	=> __('Background Color', 'salt'),
			        'id'    => '_background_color',
			        'type'  => 'color',
			        'width'	=> 50
				),
				array(
			        'label'	=> __('Repeat', 'salt'),
			        'id'    => '_background_repeat',
			        'type'  => 'select',
			        'width'	=> 25,
			        'options' => array(
				        'no-repeat' => 'No Repeat',
				        'repeat'	=> 'Repeat',
				        'repeat-x'  => 'Repeat Horizontally',
				        'repeat-y'  => 'Repeat Vertically'
			        )
				),
				array(
			        'label'	=> __('Position', 'salt'),
			        'id'    => '_background_position',
			        'type'  => 'select',
			        'width'	=> 25,
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
			        'id'    => '_background_stretch',
			        'type'  => 'checkbox',
			        'width'	=> 25
				),
				array(
			        'label'	=> __('Darken', 'salt'),
			        'id'    => '_background_darken',
			        'type'  => 'checkbox',
			        'width'	=> 25
				)
			)
		);
		
		$this->settings = (object) $settings;
		
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
        wp_localize_script( 'salt-meta-js', 'meta_image',
            array(
                'title' => __( 'Set background image', 'salt' ),
                'button' => __( 'Set background image', 'salt' ),
            )
        );
        wp_enqueue_script( 'salt-meta-js' );
	}
}

/**
 * Class to extend the main Meta Box class with one specifically to add links / buttons.
 *
 * This class is called within the Salt_Post_Types class with predefined options.
 *
 * @package		Salt
 * @since		1.5.2
 */
class Salt_Link_Meta_Box extends Salt_Meta_Box {
	
	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		// Create the settings for this meta box 
		$settings = array(
			'id'		 => 'link',
			'title'      => __('Link / Button', 'salt'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'label' => __('URL', 'salt'),
					'id'    => '_link_url',
					'type'  => 'text',
					'width' => 25
				),
				array(
					'label' => __('Label', 'salt'),
					'id'    => '_link_text',
					'type'  => 'text',
					'width' => 25
				),
				array(
					'label' => __('Type', 'salt'),
					'id'    => '_link_type',
					'type'  => 'select',
					'width' => 25,
					'options' => array(
						'' 	 	 => 'Text Link',
						'button' => 'Button'
					)
				),
				array(
					'label' => __('Open in Tab', 'salt'),
					'id'    => '_link_target',
					'type'  => 'checkbox',
					'width' => 25
				),
			)
		);
		
		$this->settings = (object) $settings;
		
		// Initiate the meta box
		$this->init();		
	}
}

/**
 * Class to extend the main Meta Box class with one specifically to add a simple text box.
 *
 * This class is called within the Salt_Post_Types class with predefined options.
 *
 * @package		Salt
 * @since		1.5.2
 */
class Salt_Text_Meta_Box extends Salt_Meta_Box {
	
	/**
	 * User defined options assigned on __construct().
	 *
	 * @var stinrg The prefix used on the id for the fields
	 */
	public $prefix = '_text_';

	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		// Create the settings for this meta box 
		$settings = array(
			'id'		 => 'text',
			'title'      => __('Text', 'salt'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'label' => '',
					'id'    => $this->prefix.'copy',
					'type'  => 'textarea'
				),
				array(
					'label' => __('Color', 'salt'),
					'id'    => $this->prefix.'color',
					'type'  => 'color',
					'width' => 50
				),
				array(
					'label' => __('Align', 'salt'),
					'id'    => $this->prefix.'align',
					'type'  => 'select',
					'options' => array(
						'left'	 => 'Left',
						'center' => 'Center',
						'right'  => 'Right'
					),
					'width' => 50
				),
				array(
					'label' => __('Font Size', 'salt'),
					'id'    => $this->prefix.'size',
					'type'  => 'select',
					'options' => array(
						'small'	 => 'Small',
						'medium' => 'Medium',
						'large'  => 'Large'
					),
					'width' => 50
				),
				array(
					'label' => __('Position', 'salt'),
					'id'    => $this->prefix.'position',
					'type'  => 'select',
					'options' => array(
						''	 	 => 'Above Featured Image',
						'image-right' => 'Left of Featured Image',
						'image-left'  => 'Right of Featured Image'
					),
					'width' => 50
				)
			)
		);
		
		$this->settings = (object) $settings;
		
		// Initiate the meta box
		$this->init();		
	}	
}

/**
 * Class to extend the main Meta Box class with one specifically to add a simple text box.
 *
 * This class is called within the Salt_Post_Types class with predefined options.
 *
Icon select
Icon position - next to title - above 
Icon color
Icon background color
Icon size 
Icon Border
 *
 * @package		Salt
 * @since		1.5.2
 */
class Salt_FontAwesome_Meta_Box extends Salt_Meta_Box {
	
	/**
	 * User defined options assigned on __construct().
	 *
	 * @var stinrg The prefix used on the id for the fields
	 */
	public $prefix = '_icon_';

	/**
	 * Constructor
	 *
	 * Register the post type meta.
	 *
	 * @param string $post_type The post type this meta box is added to
	 */
	function __construct( $post_type='post' ) {
		
		// Set the post type that this meta box should display on
		$this->post_type = $post_type;
		
		$fa_list = $this->get_fontawesome_icons_list();
		
		// Create the settings for this meta box 
		$settings = array(
			'id'		 => 'fontawesome',
			'title'      => __('Icons', 'salt'),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'label'   => __('Select an icon', 'salt'),
					'id'      => '_fontawesome_font',
					'type'    => 'fontawesome',
					'options' => $fa_list,
					'width'   => 50
				),
				array(
					'label' => __('Icon Size', 'salt'),
					'id'    => '_fontawesome_size',
					'type'  => 'select',
					'width' => 50,
					'options' => array(
						'tiny'	 => __('Very Small', 'salt'),
						'small'	 => __('Small', 'salt'),
						'medium' => __('Medium', 'salt'),
						'large'	 => __('Large', 'salt'),
						'huge'	 => __('Very Large', 'salt'),
					)
				),
				array(
					'label' => __('Background Color', 'salt'),
					'id'    => '_fontawesome_bg_color',
					'type'  => 'color',
					'width' => 50
				),
				array(
					'label' => __('Color', 'salt'),
					'id'    => '_fontawesome_color',
					'type'  => 'color',
					'width' => 50
				),
				
			)
		);
		
		$this->settings = (object) $settings;
		
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
		wp_enqueue_style( 'salt-admin', SALT_TEMPLATE_URI.'/core/assets/css/meta.css', false, '1.5.0' );

		// Enqueue the stylesheet for the color picker.
		wp_enqueue_style( 'wp-color-picker' );
		
		// Enqueue the JS to initiate the color picker if it is displayed.
        wp_enqueue_script( 'salt-meta-js', SALT_TEMPLATE_URI . '/core/assets/js/meta.js', array( 'jquery', 'wp-color-picker' ) );
        
        // Enqueue fontawesome to load on the backend.
    	wp_enqueue_style( 'fontawesome'	, get_template_directory_uri() . '/css/font-awesome.min.css', false, '4.2.1');        
	}
		
	/**
	 * FontAwesome 4.6.3 json list
	 *
	 * A helpful list FontAwesome fonts for use within the beautiful meta fields of this theme.
	 *
	 * @link https://github.com/FortAwesome/Font-Awesome/blob/master/src/icons.yml
	 * @link converted to json with help from http://yamltojson.com/
	 */
 	public function get_fontawesome_icons_list() {
 		
		$fa_json  = wp_remote_fopen( get_template_directory_uri() . '/core/assets/fonts/fontawesome.json' );
		
		return $this->json_decode( $fa_json, true );
	}
	
	/**
	 * Decode JSON
	 *
	 * Attempts to decode json into an array.
	 * This new function accounts for servers
	 * running an older version of PHP with
	 * magic quotes gpc enabled.
	 * 
	 * @param  string  $str   - JSON string to convert into an array
	 * @param  boolean $accoc [- Whether to return an associative array
	 * @return array - Decoded JSON array
	 */
	public static function json_decode( $str = '', $accoc = false ) {
		$json_string = get_magic_quotes_gpc() ? stripslashes( $str ) : $str;
		return json_decode( $json_string, $accoc );
	}    	
}
endif;