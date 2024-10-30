'use strict';

function jackmail_widget_get_id_list_selected( id ) {
	return document.getElementById( id + 'id_list' ).options[ document.getElementById( id + 'id_list' ).selectedIndex ].value;
}

function jackmail_widget_select_list( id ) {
	var id_list_selected = jackmail_widget_get_id_list_selected( id );
	var new_selected_fields = [];
	var i;

	var lists_fields = document.getElementsByClassName( id + 'lists_fields' );
	var nb_lists_fields = lists_fields.length;
	for ( i = 0 ; i < nb_lists_fields ; i++ ) {
		lists_fields[ i ].style.display = 'none';
	}

	var all_fields = document.getElementsByClassName( id + 'list_field' );
	var nb_fields = all_fields.length;
	for ( i = 0 ; i < nb_fields ; i++ ) {
		all_fields[ i ].checked = false;
	}

	if ( id_list_selected > 0 ) {
		var all_lists_fields = document.getElementsByClassName( id + 'list_' + id_list_selected + '_field' );
		var nb_all_lists_fields = all_lists_fields.length;
		for ( i = 0; i < nb_all_lists_fields; i++ ) {
			all_lists_fields[ i ].checked = true;
			new_selected_fields.push( parseInt( all_lists_fields [ i ].value ) );
		}
		document.getElementById( id + 'list_' + id_list_selected + '_fields' ).style.display = 'block';
		document.getElementById( id + 'fields_container' ).style.display = 'block';
	} else {
		document.getElementById( id + 'fields_container' ).style.display = 'none';
	}

	new_selected_fields.sort();
	document.getElementById( id + 'fields' ).value = JSON.stringify( new_selected_fields );
}

function jackmail_widget_select_list_field( id ) {
	var id_list_selected = jackmail_widget_get_id_list_selected( id );
	var new_selected_fields = [];
	var i;

	var all_lists_fields = document.getElementsByClassName( id + 'list_' + id_list_selected + '_field' );
	var nb_all_lists_fields = all_lists_fields.length;
	for ( i = 0; i < nb_all_lists_fields; i++ ) {
		if ( all_lists_fields[ i ].checked === true ) {
			new_selected_fields.push( parseInt( all_lists_fields[ i ].value ) );
		}
	}
	new_selected_fields.sort();
	document.getElementById( id + 'fields' ).value = JSON.stringify( new_selected_fields );
}

function jackmail_widget_select_confirmation_type( id ) {
	if ( jackmail_emailbuilder_installed( id ) ) {
		var style_display = 'none';
		if ( document.getElementById( id + 'double_optin_confirmation_type' ).value === 'url' ) {
			style_display = 'block';
		}
		document.getElementById( id + 'double_optin_confirmation_url' ).style.display = style_display;
	}
}

function jackmail_widget_select_double_optin( id ) {
	if ( jackmail_emailbuilder_installed( id ) ) {
		var style_display = 'none';
		if ( document.getElementById( id + 'double_optin_configuration' ).style.display === 'none') {
			style_display = 'block';
		}
		document.getElementById( id + 'double_optin_configuration' ).style.display = style_display;
	}
}

function jackmail_widget_select_gdpr( id ) {
	var style_display = 'none';
	if ( document.getElementById( id + 'gdpr_content' ).style.display === 'none' ) {
		style_display = 'block';
	}
	document.getElementById( id + 'gdpr_content' ).style.display = style_display;
}

function jackmail_emailbuilder_installed( id ) {
	if ( document.getElementById( id + 'jackmail_emailbuilder_installed' ) ) {
		return true;
	}
	return false;
}
