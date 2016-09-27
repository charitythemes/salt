<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
<div class="salt-field-holder">
	<input type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta; ?>" size="30" /> 
</div>
<?php if ( ! empty( $field['desc'] ) ) : ?>
<p class="salt-field-description"><?php echo $field['desc']; ?></p>
<?php endif; ?>