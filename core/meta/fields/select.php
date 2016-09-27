<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
<div class="salt-field-holder">	
	<select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">
		<?php if ( is_array( $field['options'] ) ) foreach ( $field['options'] as $key => $value ) : ?>
	    	<option value="<?php echo $key; ?>" <?php echo ( $meta==$key ) ? 'selected' : ''; ?>><?php echo $value; ?></option>
		<?php endforeach; ?>
	</select>
	<?php if ( ! empty( $field['desc'] ) ) : ?>
		<p class="salt-field-description"><?php echo $field['desc']; ?></p>
	<?php endif; ?>
</div>