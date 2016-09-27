<label for="<?php echo $field['id']; ?>"><?php echo esc_html( $field['label'] ); ?></label>
<div class="salt-fa-holder">
	<div class="salt-fa-preview">
		<i class="fa fa-<?php echo $meta; ?> fa-2x"></i>
	</div>
	<a href="#TB_inline?width=600&height=550&inlineId=salt-fontawesome-modal-<?php echo $field['id']; ?>" title="<?php _e('Select Icon', 'salt'); ?>" class="button thickbox"><?php _e('Select Icon'); ?></a>
	<div id="salt-fontawesome-modal-<?php echo $field['id']; ?>" style="display:none;">
		<div class="salt-fa-modal">
			<div class="salt-fa-clear">
				<a href="javascript:" data-unicode="<?php echo $ico['unicode']; ?>"></a></div>
			<?php 
			if ( $field['options']["icons"] ) {
				foreach ( $field['options']["icons"] as $ico ) { ?>					
				<div class="salt-fa <?php echo ( $meta==$ico['id'] ) ? 'selected' : ''; ?>">
					<input class="radio" type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $ico['id']; ?>" <?php echo ( $meta==$ico['id'] ) ? 'checked' : ''; ?> />
					<a href="javascript:" data-id="<?php echo $ico['id']; ?>"><i class="fa fa-<?php echo $ico['id']; ?> fa-2x"></i><span class="tooltip"><?php echo $ico['id']; ?></span></a></div>
				<?php 
				}
			} ?>
		</div>
	</div>
</div>
<?php if ( ! empty( $field['desc'] ) ) : ?>
<p class="salt-field-description"><?php echo $field['desc']; ?></p>
<?php endif; ?>