<?php if ( defined( 'ABSPATH' ) ) { ?>
<div ng-controller="WoocommerceEmailNotificationChoiceController as w">
	<div class="jackmail_header_container">
		<div class="jackmail_header">
			<div>
				<div class="jackmail_header_menu" jackmail-header-menu></div>
				<div class="jackmail_header_buttons">
					<div jackmail-search></div>
				</div>
			</div>
		</div>
	</div>
	<div ng-hide="$root.show_help2">
		<div class="jackmail_content">
			<p class="jackmail_title jackmail_center"><?php _e( 'Emails configuration', 'jackmail-newsletters' ) ?></p>
			<div class="jackmail_woocommerce_email_notification_choice_container">
				<div class="jackmail_woocommerce_email_notification_choice_left">
					<div ng-repeat="email in w.woocommerce_emails
									| filter:{ recipient_type: w.recipient_type_selected !== $root.translations.all ? w.recipient_type_selected : undefined }
									: true track by $index">
						<div class="jackmail_woocommerce_email_notification_email jackmail_woocommerce_email_notification_email_{{email.status | lowercase}}">
							<div class="jackmail_woocommerce_email_notification_email_line"></div>
							<p class="jackmail_woocommerce_email_notification_email_edit" ng-click="w.editWooCommerceEmail( email.email_id )">
								<?php _e( 'Edit', 'jackmail-newsletters' ) ?>
							</p>
							<p class="jackmail_bold">{{email.title}}</p>
							<p>
								<span class="jackmail_bold"><?php _e( 'Type:', 'jackmail-newsletters' ) ?></span>
								<span>{{email.email_type}}</span>
							</p>
							<p>
								<span class="jackmail_bold"><?php _e( 'Recipient:', 'jackmail-newsletters' ) ?></span>
								<span>{{email.recipient_type}}</span>
							</p>
						</div>
					</div>
				</div>
				<div class="jackmail_woocommerce_email_notification_choice_right">
					<div class="jackmail_woocommerce_email_notification_choice_right_logo"></div>
					<div class="jackmail_woocommerce_email_notification_choice_right_content">
						<p class="jackmail_bold"><?php _e( 'Trigger:', 'jackmail-newsletters' ) ?><p>
						<p class="jackmail_grey"><?php _e( 'Improve your conversion rate by editing WooCommerce emails with our EmailBuilder.', 'jackmail-newsletters' ) ?></p>
						<br/>
						<p class="jackmail_bold"><?php _e( 'Recipient:', 'jackmail-newsletters' ) ?><p>
						<div jackmail-dropdown-button dropdown-left="true"
							 button-value="{{w.recipient_type_selected}}"
							 titles-clicks-array="w.recipients_type"
							 titles-clicks-array-event="w.select_recipient_type( key )">
						</div>
						<br/>
						<p class="jackmail_bold"><?php _e( 'Status:', 'jackmail-newsletters' ) ?></p>
						<div class="jackmail_woocommerce_email_notification_choice_status">
							<div class="jackmail_woocommerce_email_notification_choice_status_not_modified"><?php _e( 'Unedited', 'jackmail-newsletters' ) ?></div>
							<div class="jackmail_woocommerce_email_notification_choice_status_draft"><?php _e( 'Being edited', 'jackmail-newsletters' ) ?></div>
							<div class="jackmail_woocommerce_email_notification_choice_status_actived"><?php _e( 'Edited', 'jackmail-newsletters' ) ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>