<?php
/**
 * Widget Extension API
 *
 * To create a widget, you only need to extend the standard WP_Widget class and some of its functions. That base class also contains information about the functions that must be extended to get a working widget. 
 * 
 * @link http://codex.wordpress.org/Widgets_API 
 * @since Salt 1.0
 */ 

/**
 * A widget to display social media links
 *
 * @Since Salt 1.0
 */
class Salt_Social_Connect extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'salt_social_connect_widget', // Base ID
			__('Social Icon Widget', 'salt'), // Name
			array( 'description' => __( 'a widget to proudly display your social media pages. Add links to your social accounts under Appearance > Customize', 'salt' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$title = apply_filters( 'widget_title', $instance['title'] );
		$desc = $instance['desc'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
		echo '<p>'.$desc.'</p>';

		global $_salt_registered_social;
		$accounts = $_salt_registered_social;
		
		if ($accounts) : 
			
			echo '<ul class="ico-social color circle">';
			
			foreach ($accounts as $account => $name) :
				
				if ($url=cmb_get_option( 'social_'.$account )) {
					echo '<li><a class="ico-'.$account.'" href="'.$url.'" title="'.$account.'">'.$name.'</a></li>';
				}
				
			endforeach;
			
			echo '</ul>';
			
		endif;
			
			
		echo $args['after_widget'];
		
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title 		= $instance[ 'title' ];
		$desc 		= $instance[ 'desc' ];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'salt' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e( 'Description:', 'salt' ); ?></label> 
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>"><?php echo esc_attr( $desc ); ?></textarea>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['desc'] = ( ! empty( $new_instance['desc'] ) ) ? strip_tags( $new_instance['desc'] ) : '';
		
		return $instance;
	}
}

// register Salt_Social_Connect widget
function register_salt_social_connect_widget() {
    register_widget( 'Salt_Social_Connect' );
}
add_action( 'widgets_init', 'register_salt_social_connect_widget' );
?>