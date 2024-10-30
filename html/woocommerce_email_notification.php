<?php if ( defined( 'ABSPATH' ) ) { ?>
<div ng-controller="WoocommerceEmailNotificationController as w">
	<div class="jackmail_header_container">
		<div class="jackmail_header">
			<div>
				<div class="jackmail_header_menu" jackmail-header-menu></div>
				<div class="jackmail_name">
					<span>{{w.email.title}}</span>
				</div>
				<div class="jackmail_header_buttons">
					<div jackmail-search></div>
					<div class="jackmail_campaign_save" jackmail-dropdown-button
						 button-value="<?php esc_attr_e( 'Actions', 'jackmail-newsletters' ) ?>"
						 titles-clicks-array="w.action_choices"
						 titles-clicks-array-event="w.action_selected_choice( key )">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div ng-hide="$root.show_help2">
		<div class="jackmail_content_email_emailbuilder">
			<div class="jackmail_current_content_email_type_choice_container">
				<div>
					<div class="jackmail_left">
						<input ng-click="w.go_to_woocommerce_emails_list()" class="jackmail_white_button"
							   type="button" value="<" title="<?php esc_attr_e( 'Go to WooCommerce emails list', 'jackmail-newsletters' ) ?>"/>
						<input ng-click="w.reset_emailbuilder_content()" class="jackmail_white_button"
							   type="button" value="<?php esc_attr_e( 'Reset content', 'jackmail-newsletters' ) ?>"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>