<div class="control-box" id="cf7_dropzone_pane">
<fieldset>
<legend>Generate a form-tag for a dropzone.</legend>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-max_filesize' ); ?>"><?php echo esc_html( __( "File size limit (Mb)", 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="max_filesize" class="filesize oneline option" id="<?php echo esc_attr( $args['content'] . '-max_filesize' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-max_files' ); ?>"><?php echo esc_html( __( "Max files", 'contact-form-7' ) ); ?></label></th>
	<td>
		<input type="number" name="max_files" class="oneline option" min="1" step="1" id="<?php echo esc_attr( $args['content'] . '-max_files' ); ?>" />
		<br />
		<span class="description"><?php _e('If empty only 1 file will be excepted by default', 'contact-form-7'); ?></span>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-accepted_files' ); ?>"><?php echo esc_html( __( 'Acceptable file types', 'contact-form-7' ) ); ?></label></th>
	<td>
		<input type="text" name="accepted_files" class="filetype oneline option" id="<?php echo esc_attr( $args['content'] . '-accepted_files' ); ?>" />
		<br />
		<span class="description"><?php _e('This is a comma separated list of mime types or file extensions. Eg.: image/*,application/pdf,.psd', 'contact-form-7'); ?></span>
	</td>
	</tr>

        <tr>
        <th scope="row"><?php echo esc_html( __( 'Options', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Options', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="add_remove_links:true" class="option" /> <?php echo esc_html( __( 'Add a link to every file preview to remove', 'contact-form-7' ) ); ?></label><br />
		<label><input type="checkbox" name="create_image_thumbnails:true" class="option" /> <?php echo esc_html( __( 'Create image thumbnails', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>
        <tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Drop area message', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /></td>
	</tr>
        <tr>
	<th scope="row"><?php echo esc_html( __( 'Preview template file', 'contact-form-7' ) ); ?></th>
        <td><input type="text" name="preview_template" class="oneline option" id="<?php echo esc_attr( $args['content'] . '-preview_template' ); ?>" /></td>
	</tr>
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To attach the file uploaded through this field to mail, you need to insert the corresponding mail-tag (%s) into the File Attachments field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>
