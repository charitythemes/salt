<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
<textarea rows="5" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>"><?php echo esc_textarea( $meta ); ?></textarea>
<?php if ( ! empty( $field['description'] ) ) : ?>
	<p class="salt-field-description"><?php echo $field['description']; ?></p>
<?php endif; ?>