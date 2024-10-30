<?php if ( defined( 'ABSPATH' ) ) { ?>
<script>
	function confirm_jackmail_widget_form_init() {
		var div = 'jackmail_widget_double_optin_confirm_';
		if ( document.getElementById( div + 'values' ) ) {
			var div_id = div + 'id';
			var div_confirm_fields = div + 'fields';
			var div_action_confirm = div + 'action_confirm';
			var div_nonce = div + 'nonce';
			var div_confirm_id_list = div + 'id_list';
			var div_confirm_email = div + 'email';
			var div_url = div + 'url';
			var div_double_optin_confirmation_type = div + 'confirmation_type';
			var div_double_optin_confirmation_url = div + 'confirmation_url';
			if ( document.getElementById( div_id ) && document.getElementById( div_confirm_fields ) &&
				document.getElementById( div_action_confirm ) && document.getElementById( div_nonce ) &&
				document.getElementById( div_confirm_id_list ) && document.getElementById( div_confirm_email ) &&
				document.getElementById( div_url ) && document.getElementById( div_double_optin_confirmation_type ) &&
				document.getElementById( div_double_optin_confirmation_url ) ) {
				var id = document.getElementById( div_id ).value;
				var confirm_fields = document.getElementById( div_confirm_fields ).value;
				var action_confirm = document.getElementById( div_action_confirm ).value;
				var nonce = document.getElementById( div_nonce ).value;
				var confirm_id_list = document.getElementById( div_confirm_id_list ).value;
				var confirm_email = document.getElementById( div_confirm_email ).value;
				var url = document.getElementById( div_url ).value;
				var double_optin_confirmation_type = document.getElementById( div_double_optin_confirmation_type ).value;
				var double_optin_confirmation_url = document.getElementById( div_double_optin_confirmation_url ).value;
				confirm_jackmail_widget_form(
					id, confirm_fields, action_confirm, nonce, confirm_id_list, confirm_email,
					url, double_optin_confirmation_type, double_optin_confirmation_url
				);
			}
		}
	}
	function confirm_jackmail_widget_form( id, confirm_fields, action_confirm, nonce, confirm_id_list, confirm_email,
										  url, double_optin_confirmation_type, double_optin_confirmation_url ) {
		var data = {
			action: action_confirm,
			nonce: nonce,
			jackmail_widget_id_list: confirm_id_list,
			jackmail_widget_email: confirm_email,
			jackmail_widget_fields: confirm_fields
		};
		query_jackmail_widget_form(
			id,
			url,
			data,
			function( data ) {
				data = JSON.parse( data );
				document.getElementById( id + 'confirmation' ).innerHTML = data.message;
				if ( double_optin_confirmation_type === 'url' && double_optin_confirmation_url !== '' ) {
					if ( data.success === false ) {
						alert( data.message );
					} else {
						window.location.href = double_optin_confirmation_url;
					}
				} else {
					alert( data.message );
				}
			}
		);
	}
	setTimeout( function() {
		confirm_jackmail_widget_form_init();
	} );
</script>
<?php } ?>