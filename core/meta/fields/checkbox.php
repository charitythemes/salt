<label for="<?php echo $field['id']; ?>">
	<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" size="30" <?php echo ( $meta=='on' ) ? 'checked' : ''; ?> /><?php echo esc_html( $field['label'] ); ?>
</label>					
<?php if ( ! empty( $field['desc'] ) ) : ?>
	<p class="salt-field-description"><?php echo $field['desc']; ?></p>
<?php endif; ?>