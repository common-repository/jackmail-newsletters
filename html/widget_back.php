<?php if ( defined( 'ABSPATH' ) ) { ?>
<?php
$title                          = $params['title'];
$fields                         = $params['fields'];
$double_optin                   = $params['double_optin'];
$double_optin_confirmation_type = $params['double_optin_confirmation_type'];
$double_optin_confirmation_url  = $params['double_optin_confirmation_url'];
$gdpr                           = $params['gdpr'];
$gdpr_content                   = $params['gdpr_content'];
$lists                          = $params['lists'];
$lists_details                  = $params['lists_details'];
$js_lists_details               = $params['js_lists_details'];
$id_list                        = $params['id_list'];
$double_optin_scenario_link     = $params['double_optin_scenario_link'];
$emailbuilder_installed         = $params['emailbuilder_installed'];
$field_id = $this->get_field_id( '' );
$fields_array = json_decode( $fields );
?>
<div>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ) ?>">
			<?php _e( 'Title:', 'jackmail-newsletters' ) ?>
		</label>
		<input autocomplete="off"
			   id="<?php echo $this->get_field_id( 'title' ) ?>"
			   name="<?php echo $this->get_field_name( 'title' ) ?>"
			   class="widefat"
			   type="text"
			   value="<?php esc_attr_e( $title ) ?>"/>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'id_list' ) ?>"><?php _e( 'List:', 'jackmail-newsletters' ) ?></label>
		<select autocomplete="off"
				class="widefat"
				id="<?php echo $this->get_field_id( 'id_list' ) ?>"
				name="<?php echo $this->get_field_name( 'id_list' ) ?>"
				onkeyup="jackmail_widget_select_list( '<?php esc_attr_e( $field_id ) ?>' )"
				onchange="jackmail_widget_select_list( '<?php esc_attr_e( $field_id ) ?>' )">
			<option value=""><?php _e( 'Select a list', 'jackmail-newsletters' ) ?></option>
			<?php
			foreach ( $lists as $list ) { ?>
			<option value="<?php esc_attr_e( $list->id ) ?>"<?php if ( $id_list === $list->id ) { ?> selected="selected"<?php } ?>>
				<?php echo htmlentities( $list->name ) ?>
			</option>
			<?php } ?>
		</select>
	</p>
	<p id="<?php echo $this->get_field_id( 'fields_container' ) ?>">
		<input autocomplete="off"
			   type="hidden"
			   id="<?php echo $this->get_field_id( 'fields' ) ?>"
			   name="<?php echo $this->get_field_name( 'fields' ) ?>"
			   value="<?php esc_attr_e( $fields ) ?>"/>
		<label><?php _e( 'Fields:', 'jackmail-newsletters' ) ?></label>
		<br/>
		<?php
		foreach ( $lists_details as $list_detail ) {
		?>
		<span id="<?php echo $this->get_field_id( 'list_' . $list_detail['id'] . '_fields' ) ?>"
			  class="<?php echo $this->get_field_id( 'lists_fields' ) ?>"
			  style="display:<?php if ( $id_list === $list_detail['id'] ) { ?>block<?php } else { ?>none<?php } ?>">
			<?php
			$all_fields = $list_detail['all_fields'];
			foreach ( $all_fields as $key => $field ) {
				$i = $key + 1;
			?>
			<span>
				<input autocomplete="off"<?php if ( $id_list === $list_detail['id'] && in_array( $i, $fields_array ) ) { ?> checked="checked"<?php } ?>
					   type="checkbox"
					   value="<?php esc_attr_e( $i ) ?>"
					   id="<?php echo $this->get_field_id( 'list_' . $list_detail['id'] . '_field_' . $i . '_checkbox' ) ?>"
					   class="<?php echo $this->get_field_id( 'list_' . $list_detail['id'] . '_field' ) ?> <?php echo $this->get_field_id( 'list_field' ) ?>"
					   onchange="jackmail_widget_select_list_field( '<?php esc_attr_e( $field_id ) ?>' )"/>
				<label for="<?php echo $this->get_field_id( 'list_' . $list_detail['id'] . '_field_' . $i . '_checkbox' ) ?>">
					<?php echo htmlentities( ucfirst( mb_strtolower( $field ) ) ) ?>
				</label>
			</span>
			<br/>
			<?php } ?>
		</span>
		<?php } ?>
	</p>
	<?php if ( $emailbuilder_installed ) { ?>
	<p id="<?php echo $this->get_field_id( 'jackmail_emailbuilder_installed' ) ?>">
		<span style="margin-bottom:5px;display:block">
			<input autocomplete="off"<?php if ( $double_optin ) { ?> checked="checked"<?php } ?>
				   type="checkbox"
				   onclick="jackmail_widget_select_double_optin( '<?php esc_attr_e( $field_id ) ?>' )"
				   id="<?php echo $this->get_field_id( 'double_optin' ) ?>"
				   name="<?php echo $this->get_field_name( 'double_optin' ) ?>"/>
			<label for="<?php echo $this->get_field_id( 'double_optin' ) ?>">
				<?php _e( 'Double optin', 'jackmail-newsletters' ) ?>
			</label>
		</span>
		<span id="<?php echo $this->get_field_id( 'double_optin_configuration' ) ?>"
			  style="display:<?php if ( $double_optin ) { ?>block<?php } else { ?>none<?php } ?>">
			<span style="margin-bottom:5px;display:block">
				<a href="<?php echo $double_optin_scenario_link ?>">
					<?php _e( 'Edit email content', 'jackmail-newsletters' ) ?>
				</a>
			</span>
			<select style="margin-bottom:5px;display:block"
					autocomplete="off"
					class="widefat"
					id="<?php echo $this->get_field_id( 'double_optin_confirmation_type' ) ?>"
					name="<?php echo $this->get_field_name( 'double_optin_confirmation_type' ) ?>"
					onkeyup="jackmail_widget_select_confirmation_type( '<?php esc_attr_e( $field_id ) ?>' )"
					onchange="jackmail_widget_select_confirmation_type( '<?php esc_attr_e( $field_id ) ?>' )">
				<option value="default">
					<?php _e( 'Default confirmation message', 'jackmail-newsletters' ) ?>
				</option>
				<option value="url"<?php if ( $double_optin_confirmation_type === 'url' ) { ?> selected="selected"<?php } ?>>
					<?php _e( 'Customized Url', 'jackmail-newsletters' ) ?>
				</option>
			</select>
			<input style="margin-bottom:5px;display:<?php if ( $double_optin_confirmation_type === 'url' ) { ?>block<?php } else { ?>none<?php } ?>"
				   autocomplete="off"
				   id="<?php echo $this->get_field_id( 'double_optin_confirmation_url' ) ?>"
				   name="<?php echo $this->get_field_name( 'double_optin_confirmation_url' ) ?>"
				   class="widefat"
				   placeholder="Url"
				   type="text"
				   value="<?php esc_attr_e( $double_optin_confirmation_url ) ?>"/>
		</span>
	</p>
	<?php } else { ?>
	<p>
		<span><?php _e( 'Double optin:', 'jackmail-newsletters' ) ?></span>
		<br/>
		<span>
			<?php _e( 'The double optin feature requires EmailBuilder installation in', 'jackmail-newsletters' ) ?>
			<a href="admin.php?page=jackmail_settings#/settings">"<?php _e( 'Settings', 'jackmail-newsletters' ) ?>"</a>.
		</span>
	</p>
	<?php } ?>
	<p>
		<span style="margin-bottom:5px;display:block">
			<input autocomplete="off"<?php if ( $gdpr ) { ?> checked="checked"<?php } ?>
				   type="checkbox"
				   onclick="jackmail_widget_select_gdpr( '<?php esc_attr_e( $field_id ) ?>' )"
				   id="<?php echo $this->get_field_id( 'gdpr' ) ?>"
				   name="<?php echo $this->get_field_name( 'gdpr' ) ?>"/>
			<label for="<?php echo $this->get_field_id( 'gdpr' ) ?>">
				<?php _e( 'GDPR mention', 'jackmail-newsletters' ) ?>
			</label>
		</span>
		<textarea id="<?php echo $this->get_field_id( 'gdpr_content' ) ?>"
				  name="<?php echo $this->get_field_name( 'gdpr_content' ) ?>"
				  style="width:100%;display:<?php if ( $gdpr ) { ?>block<?php } else { ?>none<?php } ?>"
				  ><?php echo htmlentities( $gdpr_content ) ?></textarea>
	</p>
</div>
<?php } ?>