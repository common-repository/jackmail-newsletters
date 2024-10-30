<?php if ( defined( 'ABSPATH' ) ) { ?>
<script>
	function submit_jackmail_widget_form( id, url, action, nonce, widget_id ) {
		var fields_name_values = document.getElementsByClassName( id + 'name_field' );
		var fields_values = document.getElementsByClassName( id + 'field' );
		var fields = [];
		var i;
		var nb_fields_name_values = fields_values.length;
		var nb_fields_values = fields_values.length;
		if ( nb_fields_name_values === nb_fields_values ) {
			for ( i = 0; i < nb_fields_values; i++ ) {
				fields.push( {
					'field': fields_name_values[ i ].value,
					'value': fields_values[ i ].value
				} );
			}
		}
		var data = {
			action: action,
			nonce: nonce,
			jackmail_widget_id: widget_id,
			jackmail_widget_email: document.getElementById( id + 'email' ).value,
			jackmail_widget_fields: JSON.stringify( fields )
		};
		document.getElementById( id + 'submit' ).disabled = true;
		query_jackmail_widget_form(
			id,
			url,
			data,
			function( data ) {
				data = JSON.parse( data );
				document.getElementById( id + 'email' ).value = '';
				for ( i = 0; i < nb_fields_values; i++ ) {
					fields_values[ i ].value = '';
				}
				document.getElementById( id + 'confirmation' ).innerHTML = data.message;
				alert( data.message );
				document.getElementById( id + 'submit' ).disabled = false;
			}
		);
	}
	function query_jackmail_widget_form( id, url, data, success ) {
		var params = Object.keys( data ).map(
			function( k ) {
				return encodeURIComponent( k ) + '=' + encodeURIComponent( data[ k ] );
			}
		).join( '&' );
		var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject( 'Microsoft.XMLHTTP' );
		xhr.open( 'POST', url );
		xhr.onreadystatechange = function() {
			if ( xhr.readyState > 3 && xhr.status === 200 ) {
				success( xhr.responseText );
			}
		};
		xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		xhr.send( params );
		return xhr;
	}
</script>
<?php } ?>