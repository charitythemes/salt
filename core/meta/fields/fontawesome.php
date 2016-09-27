<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
<div class="salt-fa-holder">
	<?php add_thickbox(); ?>
	<div class="salt-fa-preview">
		<i class="fa fa-<?php echo $meta; ?> fa-2x"></i>
	</div>
	<a href="#TB_inline&width=600&height=550&inlineId=salt-fontawesome-modal-<?php echo $field['id']; ?>" title="<?php _e('Select Icon', 'salt'); ?>" class="button thickbox"><?php _e('Select Icon', 'salt'); ?></a>
	
	<a href="javascript:" class="fa-clear"><?php _e('Clear Selection', 'salt'); ?></a>

	<?php 
	$cat_arr = array();
	$cat_select = '<select name="fa_select" id="salt-change-category" class="fa-select">';
	$ico_list	= '';
	
	if ( $field['options']["icons"] ) {
		foreach ( $field['options']["icons"] as $ico ) {
		
			foreach ( $ico["categories"] as $cat ) {
				if ( ! in_array($cat, $cat_arr) ) 
					$cat_select .= '<option value="'.$cat.'">'.$cat.'</option>';
					$cat_arr[] = $cat;
			}
			$categories = implode(';', $ico["categories"] );					

			$ico_list .= '<div class="salt-fa '.( ( $meta==$ico['id'] ) ? 'selected' : '' ).'" data-category="'.$categories.'" '.( ( ! in_array( 'Web Application Icons', $ico["categories"] ) ) ? 'style="display:none;"' : '' ).'>';
			$ico_list .= '<input class="radio" type="radio" name="'.$field['id'].'" value="'.$ico['id'].'" '.( ( $meta==$ico['id'] ) ? 'checked' : '' ).' />';
			$ico_list .= '<a href="javascript:" data-id="'.$ico['id'].'"><i class="fa fa-'.$ico['id'].' fa-2x"></i><span class="tooltip">'.$ico['id'].'</span></a>';
			$ico_list .= '</div>';
		}		
		$cat_select .= '</select>';
	}
	?>
	<div id="salt-fontawesome-modal-<?php echo $field['id']; ?>" style="display:none;">
		<?php echo $cat_select; ?>
		<div class="salt-fa-modal">
			<div class="salt-fa-clear">
				<?php echo $ico_list; ?>
			</div>
		</div>
	</div>
</div>
<?php if ( ! empty( $field['desc'] ) ) : ?>
<p class="salt-field-description"><?php echo $field['desc']; ?></p>
<?php endif; ?>