<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
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
<?php if ( ! empty( $field['desc'] ) ) : ?>
	<p class="salt-field-description"><?php echo $field['desc']; ?></p>
<?php endif; ?>