<?php if ( defined( 'ABSPATH' ) ) { ?>
<div class="jackmail_center">
	<div>
		<p class="jackmail_title">
			<span ng-show="$root.show_account_connection_popup_form === 'create' || $root.show_account_connection_popup_form === 'create_step2'">
				<?php _e( 'Create an account', 'jackmail-newsletters' ) ?>
			</span>
			<span ng-show="$root.show_account_connection_popup_form !== 'create' && $root.show_account_connection_popup_form !== 'create_step2'">
				<?php _e( 'Connect to my account', 'jackmail-newsletters' ) ?>
			</span>
		</p>
		<?php if ( ! $is_authenticated ) { ?>
		<p class="jackmail_grey jackmail_m_b_25">
			<span ng-show="$root.show_account_connection_popup_form === 'create' || $root.show_account_connection_popup_form === 'create_step2'">
				<?php _e( 'Create an account, use our high deliverability servers and get statistics of your campaigns.', 'jackmail-newsletters' ) ?>
				<br>
				<?php _e( 'Get <span class="jackmail_bold">100 free credits per day</span> and send your campaign with us.', 'jackmail-newsletters' ) ?>
			</span>
			<span ng-show="$root.show_account_connection_popup_form === 'connection'">
				<?php _e( 'Sign in with your Jackmail IDs and reach your data easily.', 'jackmail-newsletters' ) ?>
			</span>
		</p>
		<?php } ?>
		<div class="jackmail_settings_login_container jackmail_settings_login_installation_container">
			<?php if ( ! $is_authenticated ) { ?>
			<div ng-show="$root.show_account_connection_popup_form === 'create'" class="jackmail_settings_login">
				<p class="jackmail_input_create_account_container"
				   ng-class="ac.create_step1_error.email ? 'jackmail_input_create_account_container_error' : ''">
					<input type="text" ng-model="ac.email" placeholder="<?php esc_attr_e( 'Email', 'jackmail-newsletters' ) ?>"
					       ng-keyup="ac.recheck_creation_step1()"/>
					<span class="dashicons dashicons-editor-help"
					      title="<?php esc_attr_e( 'Email not valid', 'jackmail-newsletters' ) ?>">
					</span>
				</p>
				<div class="jackmail_input_create_account_container_two_fields">
					<p class="jackmail_input_create_account_container"
					   ng-class="ac.create_step1_error.password ? 'jackmail_input_create_account_container_error' : ''">
						<input type="password" ng-model="ac.create_step1_login.password"
						       placeholder="<?php esc_attr_e( 'Password', 'jackmail-newsletters' ) ?>"
						       ng-keyup="ac.recheck_creation_step1()"/>
						<span class="dashicons dashicons-editor-help"
						      title="<?php esc_attr_e( 'Password length must be at least 8 characters (with number, lowercase, uppercase, special characters)', 'jackmail-newsletters' ) ?>">
						</span>
					</p>
					<p class="jackmail_input_create_account_container"
					   ng-class="ac.create_step1_error.password ? 'jackmail_input_create_account_container_error' : ''">
						<input type="password" ng-model="ac.create_step1_login.password_confirmation"
						       placeholder="<?php esc_attr_e( 'Confirmation', 'jackmail-newsletters' ) ?>"
						       ng-keyup="ac.recheck_creation_step1()"/>
						<span class="dashicons dashicons-editor-help"
						      title="<?php esc_attr_e( 'Please insert the same password', 'jackmail-newsletters' ) ?>">
						</span>
					</p>
				</div>
				<p class="jackmail_center jackmail_bold">
					<span jackmail-checkbox="ac.terms" ng-click="ac.check_uncheck_terms()"></span>
					<span ng-click="ac.check_uncheck_terms()">
						<?php _e( 'I agree with the Jackmail <a href="https://www.jackmail.com/terms-conditions" target="_blank">terms and conditions</a>', 'jackmail-newsletters' ) ?>
					</span>
				</p>
				<p class="jackmail_center">
					<input ng-click="ac.account_creation()" type="button"
					       class="jackmail_green_button"
					       value="<?php esc_attr_e( 'Create my account', 'jackmail-newsletters' ) ?>"/>
					<input ng-click="ac.show_login_form( 'connection' )" type="button"
					       class="jackmail_green_transparent_button jackmail_m_l_10"
					       value="<?php esc_attr_e( 'Sign in', 'jackmail-newsletters' ) ?>"/>
				</p>
			</div>
			<div ng-show="$root.show_account_connection_popup_form === 'create_step2'" class="jackmail_settings_login">
				<div class="jackmail_input_create_account_container_two_fields">
					<p class="jackmail_input_create_account_container">
						<select ng-model="ac.create_step2_login.type">
							<option value="PROFESSIONAL"><?php _e( 'Professional', 'jackmail-newsletters' ) ?></option>
							<option value="STUDENT"><?php _e( 'Student', 'jackmail-newsletters' ) ?></option>
						</select>
					</p>
					<p class="jackmail_input_create_account_container" ng-show="ac.create_step2_login.type === 'PROFESSIONAL'"
					   ng-class="ac.create_step2_error.company ? 'jackmail_input_create_account_container_error' : ''">
						<input type="text" ng-model="ac.create_step2_login.company"
							   placeholder="<?php esc_attr_e( 'Company', 'jackmail-newsletters' ) ?>"
							   ng-keyup="ac.recheck_creation_step2()"/>
						<span class="dashicons dashicons-editor-help"
							  title="<?php esc_attr_e( 'Please enter your company', 'jackmail-newsletters' ) ?>">
						</span>
					</p>
				</div>
				<div class="jackmail_input_create_account_container_two_fields">
					<p class="jackmail_input_create_account_container"
					   ng-class="ac.create_step2_error.firstname ? 'jackmail_input_create_account_container_error' : ''">
						<input type="text" ng-model="ac.create_step2_login.firstname"
							   placeholder="<?php esc_attr_e( 'First name', 'jackmail-newsletters' ) ?>"
							   ng-keyup="ac.recheck_creation_step2()"/>
						<span class="dashicons dashicons-editor-help"
							  title="<?php esc_attr_e( 'Please enter your first name', 'jackmail-newsletters' ) ?>">
						</span>
					</p>
					<p class="jackmail_input_create_account_container"
					   ng-class="ac.create_step2_error.lastname ? 'jackmail_input_create_account_container_error' : ''">
						<input type="text" ng-model="ac.create_step2_login.lastname"
							   placeholder="<?php esc_attr_e( 'Last name', 'jackmail-newsletters' ) ?>"
							   ng-keyup="ac.recheck_creation_step2()"/>
						<span class="dashicons dashicons-editor-help"
							  title="<?php esc_attr_e( 'Please enter your last name', 'jackmail-newsletters' ) ?>">
						</span>
					</p>
				</div>
				<div class="jackmail_input_create_account_container_two_fields">
					<p class="jackmail_input_create_account_container">
						<select ng-model="ac.create_step2_login.country">
							<option value="AD">Andorra</option>
							<option value="AR">Argentina</option>
							<option value="AU">Australia</option>
							<option value="BE">België</option>
							<option value="BZ">Belize</option>
							<option value="BO">Bolivia</option>
							<option value="BR">Brasil</option>
							<option value="CM">Cameroun</option>
							<option value="CA">Canada</option>
							<option value="CZ">Česká republika</option>
							<option value="CL">Chile</option>
							<option value="CO">Colombia</option>
							<option value="CR">Costa Rica</option>
							<option value="CI">Côte d'Ivoire</option>
							<option value="DK">Danmark</option>
							<option value="DE">Deutschland</option>
							<option value="EC">Ecuador</option>
							<option value="EE">Eesti</option>
							<option value="EG">Egypt</option>
							<option value="SV">El Salvador</option>
							<option value="ES">España</option>
							<option value="FR">France</option>
							<option value="GT">Guatemala</option>
							<option value="GN">Guinée</option>
							<option value="HN">Honduras</option>
							<option value="HR">Hrvatska</option>
							<option value="IN">India</option>
							<option value="ID">Indonesia</option>
							<option value="IE">Ireland</option>
							<option value="IL">Israel</option>
							<option value="IT">Italia</option>
							<option value="JM">Jamaica</option>
							<option value="JO">Jordan</option>
							<option value="LV">Latvija</option>
							<option value="LI">Liechtenstein</option>
							<option value="LT">Lietuva</option>
							<option value="LU">Luxembourg</option>
							<option value="MG">Madagascar</option>
							<option value="HU">Magyarország</option>
							<option value="MY">Malaysia</option>
							<option value="ML">Mali</option>
							<option value="MA">Maroc</option>
							<option value="MU">Maurice</option>
							<option value="MX">México</option>
							<option value="NL">Nederland</option>
							<option value="NZ">New Zealand</option>
							<option value="NI">Nicaragua</option>
							<option value="NO">Norge</option>
							<option value="AT">Österreich</option>
							<option value="PA">Panamá</option>
							<option value="PY">Paraguay</option>
							<option value="PE">Perú</option>
							<option value="PH">Philippines</option>
							<option value="PL">Polska</option>
							<option value="PT">Portugal</option>
							<option value="PR">Puerto Rico</option>
							<option value="DO">República Dominicana</option>
							<option value="CF">République Centrafricaine</option>
							<option value="RO">România</option>
							<option value="SA">Saudi Arabia</option>
							<option value="CH">Schweiz</option>
							<option value="SN">Sénégal</option>
							<option value="SG">Singapore</option>
							<option value="SI">Slovenia</option>
							<option value="SK">Slovensko</option>
							<option value="ZA">South Africa</option>
							<option value="FI">Suomi</option>
							<option value="SE">Sverige</option>
							<option value="SZ">Swaziland</option>
							<option value="BS">The Bahamas</option>
							<option value="TT">Trinidad &amp; Tobago</option>
							<option value="TN">Tunisie</option>
							<option value="TR">Türkiye</option>
							<option value="GB">United Kingdom</option>
							<option value="US">United States</option>
							<option value="UY">Uruguay</option>
							<option value="VE">Venezuela</option>
							<option value="VN">Vietnam</option>
							<option value="GR">Ελλάδα</option>
							<option value="BG">България</option>
							<option value="RU">Россия</option>
							<option value="AE">دولة الإمارات العربية المتحدة</option>
							<option value="TH">ไทย</option>
							<option value="CN">中国</option>
							<option value="TW">台灣</option>
							<option value="JP">日本</option>
							<option value="HK">香港</option>
							<option value="KR">대한민국</option>
						</select>
					</p>
					<p class="jackmail_input_create_account_container"
					   ng-class="ac.create_step2_error.phone ? 'jackmail_input_create_account_container_error' : ''">
						<input type="text" ng-model="ac.create_step2_login.phone"
							   placeholder="<?php esc_attr_e( 'Phone', 'jackmail-newsletters' ) ?>"
							   ng-keyup="ac.recheck_creation_step2()"/>
						<span class="dashicons dashicons-editor-help"
							  title="<?php esc_attr_e( 'Please enter your phone', 'jackmail-newsletters' ) ?>">
						</span>
					</p>
				</div>
				<p class="jackmail_center">
					<input ng-click="ac.account_create()" type="button"
						   class="jackmail_green_button"
						   value="<?php esc_attr_e( 'Use Jackmail', 'jackmail-newsletters' ) ?>"/>
				</p>
			</div>
			<?php } ?>
			<div class="jackmail_settings_login">
				<div ng-show="$root.show_account_connection_popup_form === 'connection'">
					<p ng-show="ac.account_created" class="jackmail_message_info">
						<?php _e( 'Your account has been created successfully. You will receive an activation email shortly', 'jackmail-newsletters' ) ?>
					</p>
					<p ng-show="ac.account_not_actived" class="jackmail_message_info jackmail_message_info_error">
						<?php _e( 'Your account hasn\'t been activated.<br/>In case you haven\'t received the activation email, you can always ask to receive it again:', 'jackmail-newsletters' ) ?>
						<input ng-click="ac.resend_activation_email()" class="jackmail_white_button"
						       type="button"
						       value="<?php esc_attr_e( 'Send the email again', 'jackmail-newsletters' ) ?>"/>
					</p>
					<p ng-show="ac.account_ids_not_valid" class="jackmail_message_info jackmail_message_info_error">
						<?php _e( 'Invalid username or password', 'jackmail-newsletters' ) ?>
					</p>
					<p ng-show="ac.account_resend_activation_email" class="jackmail_message_info jackmail_message_info_error">
						<?php _e( 'Please check your email and click the confirmation link.', 'jackmail-newsletters' ) ?>
					</p>
					<p>
						<input ng-model="ac.email" type="text"
						       placeholder="<?php esc_attr_e( 'Email', 'jackmail-newsletters' ) ?>"/>
					</p>
					<p class="jackmail_m_b_5">
						<input ng-model="ac.login.password" type="password"
						       placeholder="<?php esc_attr_e( 'Password', 'jackmail-newsletters' ) ?>"/>
					</p>
					<p ng-click="ac.show_login_form( 'new_password' )"
					   class="jackmail_green jackmail_pointer jackmail_m_t_5">
						<?php esc_attr_e( 'Lost your password?', 'jackmail-newsletters' ) ?>
					</p>
					<p class="jackmail_center jackmail_m_t_20">
						<input ng-click="ac.account_connection()" type="button"
						       class="jackmail_green_button"
						       value="<?php esc_attr_e( 'Sign in to my account', 'jackmail-newsletters' ) ?>"/>
						<?php if ( ! $is_authenticated ) { ?>
						<br/>
						<span ng-hide="ac.current_page_type === 'installation' && ac.account_created"
						      ng-click="ac.show_login_form( 'create' )" class="jackmail_connect_account">
							<?php _e( 'Create my account', 'jackmail-newsletters' ) ?>
						</span>
						<?php } ?>
					</p>
				</div>
				<div ng-show="$root.show_account_connection_popup_form === 'new_password'">
					<p ng-click="ac.show_login_form( 'connection' )" class="jackmail_settings_login_back">
						<span class="dashicons dashicons-arrow-left-alt2"></span>
						<?php _e( 'Back', 'jackmail-newsletters' ) ?>
					</p>
					<p class="jackmail_grey jackmail_center jackmail_m_b_25">
						<?php _e( 'We\'ll send you an email to reset your password', 'jackmail-newsletters' ) ?>
					</p>
					<p>
						<input ng-model="ac.email" type="text"
						       placeholder="<?php esc_attr_e( 'Email', 'jackmail-newsletters' ) ?>"/>
					</p>
					<p class="jackmail_center">
						<input ng-click="ac.account_reset()" type="button" class="jackmail_green_button"
						       value="<?php esc_attr_e( 'Reset password', 'jackmail-newsletters' ) ?>"/>
					</p>
				</div>
				<div ng-show="$root.show_account_connection_popup_form === 'new_password_confirm'"
				     class="jackmail_center jackmail_mt_50">
					<p class="jackmail_grey">
						<?php _e( 'An email has been sent to reset your password.', 'jackmail-newsletters' ) ?>
					</p>
					<br/>
					<p>
						<input ng-click="ac.show_login_form( 'connection' )" type="button"
						       class="jackmail_green_button"
						       value="<?php esc_attr_e( 'Connect', 'jackmail-newsletters' ) ?>"/>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>