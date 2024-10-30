<?php


class Jackmail_Woocommerce_Email_Notification extends Jackmail_Woocommerce_Email_Notification_Core {

	public function __construct( Jackmail_Core $core ) {

		$this->core = $core;

		add_action( 'jackmail_cron_woocommerce_email_notification', array( $this, 'cron_woocommerce_email_notification_call' ) );

		if ( $this->core->is_admin() ) {

			add_action( 'woocommerce_admin_field_email_notification', array( $this, 'woocommerce_email_notification_hook' ) );

			add_action( 'wp_ajax_jackmail_scenario_woocommerce_email_notification_choice_page', array( $this, 'scenario_woocommerce_email_notification_choice_page_callback' ) );

			add_action( 'wp_ajax_jackmail_scenario_woocommerce_email_notification_page', array( $this, 'scenario_woocommerce_email_notification_page_callback' ) );

			add_action( 'wp_ajax_jackmail_get_woocommerce_emails', array( $this, 'get_woocommerce_emails_callback' ) );

			add_action( 'wp_ajax_jackmail_get_woocommerce_email', array( $this, 'get_woocommerce_email_callback' ) );

			add_action( 'wp_ajax_jackmail_get_woocommerce_default_email', array( $this, 'get_woocommerce_default_email_callback' ) );

			add_action( 'wp_ajax_jackmail_save_woocommerce_email', array( $this, 'save_woocommerce_email_callback' ) );

			add_action( 'wp_ajax_jackmail_activate_woocommerce_email', array( $this, 'activate_woocommerce_email_callback' ) );

			add_action( 'wp_ajax_jackmail_deactivate_woocommerce_email', array( $this, 'deactivate_woocommerce_email_callback' ) );

		}

	}

	public function woocommerce_email_notification_hook() {
		?>
		<div class="jackmail jackmail_woocommerce_email_notification_hook">
			<h2><?php _e( 'Jackmail', 'jackmail-newsletters' ) ?></h2>
			<p><?php _e( 'Improve your conversion rate by editing WooCommerce emails with our EmailBuilder.', 'jackmail-newsletters' ) ?></p>
			<p>
				<input type="button"
					   class="jackmail_woocommerce_button"
					   value="<?php esc_attr_e( 'Edit my WooCommerce emails easily', 'jackmail-newsletters' ) ?>"
					   onclick="window.location.href = 'admin.php?page=jackmail_scenario_woocommerce_email_notification_choice'"/>
			</p>
			<script>
				jQuery( function() {
					jQuery( 'table.wc_emails td.wc-email-settings-table-actions' ).append(
						'<span class="jackmail">' +
						'	<input type="button"' +
						'			class="jackmail_woocommerce_mini_button"' +
						'			title="<?php esc_attr_e( 'Edit my WooCommerce emails easily with Jackmail', 'jackmail-newsletters' ) ?>"' +
						'			onclick="window.location.href = \'admin.php?page=jackmail_scenario_woocommerce_email_notification_choice\'"/>' +
						'</span>'
					);
				} );
			</script>
		</div>
		<?php
	}

	public function scenario_woocommerce_email_notification_choice_page_callback() {
		$this->core->check_auth();
		$this->core->include_html_file( 'woocommerce_email_notification_choice' );
		die;
	}

	public function scenario_woocommerce_email_notification_page_callback() {
		$this->core->check_auth();
		$this->core->include_html_file( 'woocommerce_email_notification' );
		die;
	}

	public function get_woocommerce_emails_callback() {
		$this->core->check_auth();
		$events = $this->get_woocommerce_emails();
		wp_send_json( $events );
		die;
	}

	public function get_woocommerce_email_callback() {
		$result = array(
			'title'              => '',
			'content_email_json' => '',
			'status'             => 'DRAFT'
		);
		if ( isset( $_POST['email_id'] ) ) {
			$email_id = $this->core->request_text_data( $_POST['email_id'] );
			$result   = $this->get_woocommerce_email( $email_id );
		}
		wp_send_json( $result );
		die;
	}

	public function get_woocommerce_default_email_callback() {
		$result = array(
			'content_email_json' => $this->get_woocommerce_default_email()
		);
		wp_send_json( $result );
		die;
	}

	public function save_woocommerce_email_callback() {
		$result = array(
			'success'            => false,
			'content_email_json' => ''
		);
		if ( isset( $_POST['email_id'], $_POST['content_email_json'] ) ) {
			$email_id           = $this->core->request_text_data( $_POST['email_id'] );
			$content_email_json = $this->core->request_stripslashes( $_POST['content_email_json'] );
			$result             = $this->save_woocommerce_email( $email_id, $content_email_json );
		}
		wp_send_json( $result );
		die;
	}

	public function activate_woocommerce_email_callback() {
		$result = array(
			'success' => false,
			'message' => ''
		);
		if ( isset( $_POST['email_id'], $_POST['content_email_json'], $_POST['html_export'] ) ) {
			$email_id           = $this->core->request_text_data( $_POST['email_id'] );
			$content_email_json = $this->core->request_stripslashes( $_POST['content_email_json'] );
			$html_export        = $this->core->request_stripslashes( $_POST['html_export'] );
			$result             = $this->activate_woocommerce_email( $email_id, $content_email_json, $html_export );
		}
		wp_send_json( $result );
		die;
	}

	public function deactivate_woocommerce_email_callback() {
		$result = array(
			'success' => false,
			'message' => ''
		);
		if ( isset( $_POST['email_id'] ) ) {
			$email_id = $this->core->request_text_data( $_POST['email_id'] );
			$result   = $this->deactivate_woocommerce_email( $email_id );
		}
		wp_send_json( $result );
		die;
	}

	public function cron_woocommerce_email_notification_call() {
		$this->cron_woocommerce_email_notification();
	}

}
