<?php if ( defined( 'ABSPATH' ) ) { ?>
<?php
$url                            = $params['url'];
$action_submit                  = $params['action_submit'];
$action_confirm                 = $params['action_confirm'];
$widget_id                      = $params['widget_id'];
$before_widget                  = $params['before_widget'];
$title                          = $params['title'];
$after_widget                   = $params['after_widget'];
$fields                         = $params['fields'];
$list_fields                    = $params['list_fields'];
$double_optin                   = $params['double_optin'];
$double_optin_confirmation_type = $params['double_optin_confirmation_type'];
$double_optin_confirmation_url  = $params['double_optin_confirmation_url'];
$gdpr                           = $params['gdpr'];
$gdpr_content                   = $params['gdpr_content'];
$confirm_id_list                = $params['confirm_data']['id_list'];
$confirm_email                  = $params['confirm_data']['email'];
$confirm_fields                 = $params['confirm_data']['fields'];
$field_id = $this->get_field_id( '' );
$nonce_submit = wp_create_nonce( $action_submit . get_option( 'jackmail_front_nonce' ) );
$nonce_confirm = wp_create_nonce( $action_confirm . get_option( 'jackmail_front_nonce' ) );
$display_gdpr = ( $gdpr === '1' && $gdpr_content !== '' );
$display_double_optin_confirm = ( $confirm_id_list !== '' && $confirm_email !== '' && $confirm_fields !== '' );
echo $before_widget;
echo $title;
?>
<p id="<?php echo $this->get_field_id( 'confirmation' ) ?>"></p>
<p>
	<label for="<?php echo $this->get_field_id( 'email' ) ?>">
		<?php _e( 'Email', 'jackmail-newsletters' ) ?>
	</label>
	<input id="<?php echo $this->get_field_id( 'email' ) ?>"
		   name="<?php echo $this->get_field_name( 'email' ) ?>"
		   type="text"
		   autocomplete="off"/>
</p>
<?php
foreach ( $list_fields as $key => $field ) {
	$i = $key + 1;
	if ( in_array( $i, $fields ) ) {
	?>
<p>
	<label for="<?php echo $this->get_field_id( 'field' ) . $i ?>">
		<?php echo htmlentities( ucfirst( mb_strtolower( $field ) ) ) ?>
	</label>
	<input id="<?php echo $this->get_field_id( 'name_field' ) . $i ?>>"
		   class="<?php echo $this->get_field_id( 'name_field' ) ?>"
		   type="hidden"
		   value="<?php esc_attr_e( $field ) ?>"/>
	<input id="<?php echo $this->get_field_id( 'field' ) . $i ?>"
		   class="<?php echo $this->get_field_id( 'field' ) ?>"
		   name="<?php echo $this->get_field_name( 'field' ) . $i ?>"
		   type="text"
		   autocomplete="off"/>
</p>
	<?php
	}
}
?>
<?php
if ( $display_gdpr ) {
?>
<p><?php echo $gdpr_content ?></p>
<?php
}
?>
<p>
	<input id="<?php echo $this->get_field_id( 'submit' ) ?>"
		   onclick="
		   event.preventDefault();
		   submit_jackmail_widget_form(
			   '<?php esc_attr_e( $field_id ) ?>',
			   '<?php esc_attr_e( $url ) ?>',
			   '<?php esc_attr_e( $action_submit ) ?>',
			   '<?php esc_attr_e( $nonce_submit ) ?>',
			   '<?php esc_attr_e( $widget_id ) ?>'
		   )"
		   type="submit"
		   value="<?php esc_attr_e( 'OK', 'jackmail-newsletters' ) ?>"
	/>
</p>
<?php if ( $display_double_optin_confirm ) { ?>
<span id="jackmail_widget_double_optin_confirm_values">
	<input id="jackmail_widget_double_optin_confirm_id"
		   type="hidden"
		   value="<?php esc_attr_e( $field_id ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_fields"
		   type="hidden"
		   value="<?php esc_attr_e( $confirm_fields ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_action_confirm"
		   type="hidden"
		   value="<?php esc_attr_e( $action_confirm ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_nonce"
		   type="hidden"
		   value="<?php esc_attr_e( $nonce_confirm ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_id_list"
		   type="hidden"
		   value="<?php esc_attr_e( $confirm_id_list ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_email"
		   type="hidden"
		   value="<?php esc_attr_e( $confirm_email ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_url"
		   type="hidden"
		   value="<?php esc_attr_e( $url ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_confirmation_type"
		   type="hidden"
		   value="<?php esc_attr_e( $double_optin_confirmation_type ) ?>"/>
	<input id="jackmail_widget_double_optin_confirm_confirmation_url"
		   type="hidden"
		   value="<?php esc_attr_e( $double_optin_confirmation_url ) ?>"/>
</span>
<?php } ?>
<?php
echo $after_widget;
?>
<?php } ?>