<?php


class Jackmail_Woocommerce_Email_Notification_Core {

	private function get_hook_begin_delimiter() {
		return '<?php // Hook begin ?>' . "\n";
	}

	private function get_hook_end_delimiter() {
		return '<?php // Hook end ?>' . "\n";
	}

	public function delete_all_woocommerce_email_notification() {
		$sep                    = DIRECTORY_SEPARATOR;
		$woocommerce_file_path1 = get_template_directory() . $sep . 'woocommerce' . $sep;
		$woocommerce_file_path2 = $woocommerce_file_path1 . 'emails' . $sep;
		if ( file_exists( $woocommerce_file_path2 ) ) {
			if ( is_readable( $woocommerce_file_path2 ) ) {
				$files = $this->get_modified_folder_files( $woocommerce_file_path2 );
				if ( $files !== false ) {
					foreach ( $files as $file ) {
						if ( $file !== '.' && $file !== '..' && $file !== 'index.php' && $file !== '.htaccess' ) {
							$file_path = $woocommerce_file_path2 . $file;
							if ( filetype( $file_path ) === 'file' ) {
								$content = $this->get_modified_file_content( $file_path );
								if ( $content !== false ) {
									if ( $this->is_file_content_from_jackmail( $content ) ) {
										$this->delete_modified_file( $file_path );
									}
								}
							}
						}
					}
					$error = $this->try_delete_woocommerce_emails_folder( $woocommerce_file_path2 );
					if ( $error === false ) {
						$this->try_delete_woocommerce_emails_folder( $woocommerce_file_path1 );
					}
				}
			}
		}
	}

	private function try_delete_woocommerce_emails_folder( $path ) {
		$error = true;
		if ( file_exists( $path ) ) {
			if ( is_readable( $path ) ) {
				$files = $this->get_modified_folder_files( $path );
				if ( $files !== false ) {
					$nb_files = 0;
					foreach ( $files as $file ) {
						if ( $file !== '.' && $file !== '..' ) {
							$nb_files ++;
						}
					}
					if ( $nb_files === 2 ) {
						$index_path       = $path . 'index.php';
						$htaccess_path    = $path . '.htaccess';
						$index_content    = $this->get_modified_file_content( $index_path );
						$htaccess_content = $this->get_modified_file_content( $htaccess_path );
						if ( $index_content !== false && $htaccess_content !== false ) {
							if ( $this->is_file_content_from_jackmail( $index_content ) && $this->is_file_content_from_jackmail( $htaccess_content ) ) {
								$this->delete_modified_file( $index_path );
								$this->delete_modified_file( $htaccess_path );
								if ( $this->delete_modified_folder( $path ) ) {
									$error = false;
								}
							}
							if ( $error ) {
								$this->create_index( $path );
								$this->create_htaccess( $path );
							}
						}
					}
				}
			}
		}
		return $error;
	}

	protected function get_woocommerce_emails() {
		global $wpdb;
		$events = array();
		if ( function_exists( 'WC' ) ) {
			if ( method_exists( WC(), 'mailer' ) ) {
				$woocommerce = WC()->mailer();
				if ( method_exists( $woocommerce, 'get_emails' ) ) {
					$templates = $woocommerce->get_emails();
					$sql       = "
					SELECT `email_id`, `status`
					FROM `{$wpdb->prefix}jackmail_woocommerce_email_notification`";
					$results   = $wpdb->get_results( $sql );
					foreach ( $templates as $key => $template ) {
						if ( method_exists( $template, 'is_manual' ) && method_exists( $template, 'is_enabled' )
						     && method_exists( $template, 'get_title' ) && method_exists( $template, 'get_description' )
						     && method_exists( $template, 'get_email_type' ) && method_exists( $template, 'is_customer_email' ) ) {
							$woocommerce_status = 'disabled';
							if ( $template->is_manual() ) {
								$woocommerce_status = 'manual';
							} else if ( $template->is_enabled() ) {
								$woocommerce_status = 'enabled';
							}
							$email_id        = $this->core->str_to_lower( $key );
							$jackmail_status = 'NOT_MODIFIED';
							foreach ( $results as $result ) {
								if ( $result->email_id === $email_id ) {
									$jackmail_status = $result->status;
									break;
								}
							}
							$events[] = array(
								'email_id'           => $email_id,
								'title'              => $template->get_title(),
								'description'        => html_entity_decode( $template->get_description() ),
								'email_type'         => $template->get_email_type(),
								'recipient_type'     => $template->is_customer_email() ? __( 'Customer', 'jackmail-newsletters' ) : __( 'Administrator', 'jackmail-newsletters' ),
								'woocommerce_status' => $woocommerce_status,
								'status'             => $jackmail_status
							);
						}
					}
				}
			}
		}
		return $events;
	}

	protected function get_woocommerce_email( $email_id ) {
		global $wpdb;
		$result             = array(
			'title'              => '',
			'content_email_json' => '',
			'status'             => 'DRAFT'
		);
		$sql                = "
		SELECT `email_id`, `content_email_json`, `status`
		FROM `{$wpdb->prefix}jackmail_woocommerce_email_notification`
		WHERE `email_id` = %s";
		$email_notification = $wpdb->get_row( $wpdb->prepare( $sql, $email_id ) );
		if ( isset( $email_notification->email_id ) ) {
			$result['content_email_json'] = $email_notification->content_email_json;
			$result['status']             = $email_notification->status;
		} else {
			$result['content_email_json'] = $this->get_woocommerce_default_email();
		}
		if ( function_exists( 'WC' ) ) {
			if ( method_exists( WC(), 'mailer' ) ) {
				$woocommerce = WC()->mailer();
				$templates   = $woocommerce->get_emails();
				foreach ( $templates as $key => $template ) {
					$template_email_id = $this->core->str_to_lower( $key );
					if ( $email_id === $template_email_id ) {
						if ( method_exists( $template, 'get_title' ) ) {
							$result['title'] = $template->get_title();
							break;
						}
					}
				}
			}
		}
		return $result;
	}

	protected function get_woocommerce_default_email() {
		$result   = '';
		$url      = $this->core->get_jackmail_url_ws2() . 'gallery.php?product=jackmail&id=woocommerce_notification';
		$headers  = array();
		$timeout  = 30;
		$response = $this->core->remote_get( $url, $headers, $timeout );
		if ( is_array( $response ) ) {
			if ( isset( $response['body'] ) ) {
				$result = $response['body'];
			}
		}
		return $result;
	}

	protected function save_woocommerce_email( $email_id, $content_email_json ) {
		global $wpdb;
		$result             = array(
			'success'            => false,
			'content_email_json' => ''
		);
		$sql                = "
		SELECT `id`, `email_id`, `preview`
		FROM `{$wpdb->prefix}jackmail_woocommerce_email_notification`
		WHERE `email_id` = %s";
		$email_notification = $wpdb->get_row( $wpdb->prepare( $sql, $email_id ) );
		if ( isset( $email_notification->id ) ) {
			$current_id = $email_notification->id;
			$preview    = $email_notification->preview;
		} else {
			$current_id = '0';
			$preview    = $this->core->generate_jackmail_preview_filename();
		}
		$content_email = $this->core->set_content_email( 'woocommerce_email_notification', $current_id, $preview, $content_email_json, '', '' );
		if ( $content_email !== false ) {
			if ( isset( $content_email['content_email_json'], $content_email['content_email_html'],
				$content_email['content_email_txt'], $content_email['content_email_images'] ) ) {
				$content_email_json   = $content_email['content_email_json'];
				$content_email_html   = $content_email['content_email_html'];
				$content_email_txt    = $content_email['content_email_txt'];
				$content_email_images = $content_email['content_email_images'];
				$current_date_gmt     = $this->core->get_current_time_gmt_sql();
				if ( $current_id === '0' ) {
					$save_return = $this->core->insert_woocommerce_email_notification( array(
						'email_id'             => $email_id,
						'content_email_json'   => $content_email_json,
						'content_email_html'   => $content_email_html,
						'content_email_txt'    => $content_email_txt,
						'content_email_images' => $content_email_images,
						'preview'              => $preview,
						'created_date_gmt'     => $current_date_gmt,
						'updated_date_gmt'     => $current_date_gmt,
						'status'               => 'DRAFT'
					) );
				} else {
					$save_return = $this->core->update_woocommerce_email_notification( array(
						'content_email_json'   => $content_email_json,
						'content_email_html'   => $content_email_html,
						'content_email_txt'    => $content_email_txt,
						'content_email_images' => $content_email_images,
						'preview'              => $preview,
						'updated_date_gmt'     => $current_date_gmt
					), array(
						'email_id' => $email_id
					) );
				}
				if ( $save_return !== false ) {
					$result = array(
						'success'            => true,
						'content_email_json' => $content_email_json
					);
				}
			}
		}
		return $result;
	}

	protected function activate_woocommerce_email( $email_id, $content_email_json, $html_export ) {
		$result      = array(
			'success' => true,
			'message' => ''
		);
		$save_return = $this->save_woocommerce_email( $email_id, $content_email_json );
		if ( $save_return ) {
			$original_file_path = $this->get_original_file_path( $email_id );
			$modified_file_path = $this->get_modified_file_path( $email_id );
			$backup_file_path   = $this->get_backup_file_path( $email_id );
			if ( $original_file_path ) {
				if ( $modified_file_path ) {
					if ( $backup_file_path ) {
						if ( file_exists( $original_file_path ) ) {
							if ( file_exists( $modified_file_path ) ) {
								$content = $this->get_modified_file_content( $modified_file_path );
								if ( $content === false ) {
									$result['message'] = 'ERROR_1';
								} else {
									if ( ! $this->is_file_content_from_jackmail( $content ) ) {
										$rename = $this->rename_modified_file( $modified_file_path, $backup_file_path );
										if ( $rename === false ) {
											$result['message'] = 'ERROR_2';
										}
									}
								}
							}
							if ( $result['message'] === '' ) {
								$hook_content = $this->get_hook_woocommerce_email( $email_id );
								if ( $hook_content === false ) {
									$result['message'] = 'ERROR_3';
								} else {
									$hook_position = strpos( $html_export, '#~#HOOK#~#' );
									if ( $hook_position === false ) {
										$result['message'] = 'ERROR_4';
									} else {
										$hook_style = $this->get_hook_style( $content_email_json );
										if ( $hook_style === false ) {
											$result['message'] = 'ERROR_5';
										} else {
											$html_export                  = str_replace( '../?jackmail_image=', '<?php echo get_home_url() ?>?jackmail_image=', $html_export );
											$hook_content_with_delimiters =
												'<div id="hook_content">' .
												$hook_style .
												$this->get_hook_begin_delimiter() .
												$hook_content .
												$this->get_hook_end_delimiter() .
												'</div>';
											$html_export_with_hook        = str_replace( '#~#HOOK#~#', $hook_content_with_delimiters, $html_export );
											$content                      =
												'<?php' . "\n" .
												'// Generated from Jackmail' . "\n" .
												'defined( \'ABSPATH\' ) || exit;' . "\n" .
												'?>' . "\n" .
												$html_export_with_hook;
											$create                       = $this->edit_modified_file_content( $modified_file_path, $content );
											if ( $create ) {
												$current_date_gmt = $this->core->get_current_time_gmt_sql();
												$save_return      = $this->core->update_woocommerce_email_notification( array(
													'updated_date_gmt' => $current_date_gmt,
													'status'           => 'ACTIVED'
												), array(
													'email_id' => $email_id
												) );
												if ( $save_return === false ) {
													$result['message'] = 'ERROR_6';
												}
											} else {
												$result['message'] = 'ERROR_7';
											}
										}
									}
								}
							}
						} else {
							$result['message'] = 'ERROR_8';
						}
					} else {
						$result['message'] = 'ERROR_9';
					}
				} else {
					$result['message'] = 'ERROR_10';
				}
			} else {
				$result['message'] = 'ERROR_11';
			}
		} else {
			$result['message'] = 'ERROR_12';
		}
		if ( $result['message'] !== '' ) {
			$result['success'] = false;
		}
		return $result;
	}

	private function get_hook_style( $content_email_json ) {
		$content_email_json_object = json_decode( $content_email_json, true );
		if ( isset( $content_email_json_object['globalSettings'], $content_email_json_object['themeSettings'] ) ) {
			$globalSettings = $content_email_json_object['globalSettings'];
			$themeSettings  = $content_email_json_object['themeSettings'];
			if ( isset( $globalSettings['fontNameParagraph'], $globalSettings['fontNameH1'],
				$globalSettings['fontNameH2'], $globalSettings['fontSize'],
				$globalSettings['fontSizeH1'], $globalSettings['fontSizeH2'],
				$globalSettings['lineHeightParagraph'], $globalSettings['lineHeightH1'],
				$globalSettings['lineHeightH2'], $themeSettings['paragraph'],
				$themeSettings['h1Title'], $themeSettings['h2Title'],
				$themeSettings['link'], $themeSettings['structureBorder'] ) ) {
				$font_name_paragraph   = $globalSettings['fontNameParagraph'];
				$font_name_h1          = $globalSettings['fontNameH1'];
				$font_name_h2          = $globalSettings['fontNameH2'];
				$font_size             = $globalSettings['fontSize'];
				$font_size_h1          = $globalSettings['fontSizeH1'];
				$font_size_h2          = $globalSettings['fontSizeH2'];
				$line_height_paragraph = $globalSettings['lineHeightParagraph'];
				$line_height_h1        = $globalSettings['lineHeightH1'];
				$line_height_h2        = $globalSettings['lineHeightH2'];
				$color_paragraph       = $themeSettings['paragraph'];
				$color_h1_title        = $themeSettings['h1Title'];
				$color_h2_title        = $themeSettings['h2Title'];
				$color_link            = $themeSettings['link'];
				$color_border          = $themeSettings['structureBorder'];
				$text_align            = is_rtl() ? 'right' : 'left';
				return '
					<style>
						#hook_content *,
						#hook_content * h1, #hook_content h1,
						#hook_content * h2, #hook_content h2,
						#hook_content * h3, #hook_content h3,
						#hook_content * p, #hook_content p,
						#hook_content * a, #hook_content a,
						#hook_content * span, #hook_content span,
						#hook_content * table.td td, #hook_content table.td td,
						#hook_content * table.td th, #hook_content table.td th,
						#hook_content * .address, #hook_content .address {
							text-align: ' . htmlentities( $text_align ) . '!important;
							color: ' . htmlentities( $color_paragraph ) . '!important;
							font-family:' . htmlentities( $font_name_paragraph ) . '!important;
							font-size:' . htmlentities( $font_size ) . 'px!important;
							line-height:' . htmlentities( $line_height_paragraph ) . 'px!important;
						}
						#hook_content * a, #hook_content a {
							color: ' . htmlentities( $color_link ) . '!important;
						}
						#hook_content * h1, #hook_content h1 {
							color: ' . htmlentities( $color_h1_title ) . '!important;
							font-family:' . htmlentities( $font_name_h1 ) . '!important;
							font-size:' . htmlentities( $font_size_h1 ) . 'px!important;
							line-height:' . htmlentities( $line_height_h1 ) . 'px!important;
						}
						#hook_content * h2, #hook_content h2,
						#hook_content * h3, #hook_content h3 {
							color: ' . htmlentities( $color_h2_title ) . '!important;
							font-family:' . htmlentities( $font_name_h2 ) . '!important;
							font-size:' . htmlentities( $font_size_h2 ) . 'px!important;
							line-height:' . htmlentities( $line_height_h2 ) . 'px!important;
						}
						#hook_content * table.td td, #hook_content table.td td,
						#hook_content * table.td th, #hook_content table.td th,
						#hook_content * .address, #hook_content .address {
							padding: 12px!important;
							border: 1px solid ' . htmlentities( $color_border ) . '!important;
						}
					</style>';
			}
		}
		return false;
	}

	protected function deactivate_woocommerce_email( $email_id ) {
		$result             = array(
			'success' => true,
			'message' => ''
		);
		$modified_file_path = $this->get_modified_file_path( $email_id );
		if ( $modified_file_path ) {
			if ( file_exists( $modified_file_path ) ) {
				$content = $this->get_modified_file_content( $modified_file_path );
				if ( $content !== false ) {
					if ( $this->is_file_content_from_jackmail( $content ) ) {
						$delete_modified_file = $this->delete_modified_file( $modified_file_path );
						if ( $delete_modified_file ) {
							$current_date_gmt = $this->core->get_current_time_gmt_sql();
							$save_return      = $this->core->update_woocommerce_email_notification( array(
								'updated_date_gmt' => $current_date_gmt,
								'status'           => 'DRAFT'
							), array(
								'email_id' => $email_id
							) );
							if ( $save_return === false ) {
								$result['message'] = 'ERROR_1';
							}
						} else {
							$result['message'] = 'ERROR_2';
						}
					} else {
						$result['message'] = 'ERROR_3';
					}
				} else {
					$result['message'] = 'ERROR_4';
				}
			} else {
				$result['message'] = 'ERROR_5';
			}
		} else {
			$result['message'] = 'ERROR_6';
		}
		if ( $result['message'] !== '' ) {
			$result['success'] = false;
		}
		return $result;
	}

	private function get_original_file_path( $email_id ) {
		$sep              = DIRECTORY_SEPARATOR;
		$woocommerce_path = $this->get_woocommerce_path();
		$filename         = $this->get_email_file_name( $email_id );
		if ( $woocommerce_path && $filename ) {
			return $woocommerce_path . 'templates' . $sep . 'emails' . $sep . $filename;
		}
		return false;
	}

	private function get_modified_file_path( $email_id ) {
		$path1    = $this->get_modified_files_path1();
		$path2    = $this->get_modified_files_path2();
		$filename = $this->get_email_file_name( $email_id );
		if ( $path1 && $path2 && $filename ) {
			if ( ! file_exists( $path2 ) ) {
				$path1_file1  = true;
				$path1_file2  = true;
				$create_path1 = false;
				if ( ! file_exists( $path1 ) ) {
					$create_path1 = true;
					$this->create_modified_folder( $path1 );
					$path1_file1 = $this->create_index( $path1 );
					$path1_file2 = $this->create_htaccess( $path1 );
					if ( $path1_file1 === false || $path1_file2 === false ) {
						$this->delete_modified_file( $path1 . $path1_file1 );
						$this->delete_modified_file( $path1 . $path1_file2 );
						$this->delete_modified_folder( $path1 );
					}
				}
				if ( $path1_file1 !== false && $path1_file2 !== false ) {
					$this->create_modified_folder( $path2 );
					$path2_file1 = $this->create_index( $path2 );
					$path2_file2 = $this->create_htaccess( $path2 );
					if ( $path2_file1 === false || $path2_file2 === false ) {
						$this->delete_modified_file( $path2 . $path2_file1 );
						$this->delete_modified_file( $path2 . $path2_file2 );
						$this->delete_modified_folder( $path2 );
						if ( $create_path1 ) {
							$this->delete_modified_file( $path1 . $path1_file1 );
							$this->delete_modified_file( $path1 . $path1_file2 );
							$this->delete_modified_folder( $path1 );
						}
					}
				}
			}
			return $path2 . $filename;
		}
		return false;
	}

	private function get_backup_file_path( $email_id ) {
		$filename = $this->get_email_file_name( $email_id );
		if ( $filename ) {
			$sep = DIRECTORY_SEPARATOR;
			return get_template_directory() . $sep . 'woocommerce' . $sep . 'emails' . $sep . 'backup-' . time() . '-' . $filename;
		}
		return false;
	}

	private function get_woocommerce_path() {
		if ( function_exists( 'WC' ) ) {
			if ( method_exists( WC(), 'plugin_path' ) ) {
				$sep = DIRECTORY_SEPARATOR;
				return WC()->plugin_path() . $sep;
			}
		}
		return false;
	}

	private function get_modified_files_path1() {
		if ( function_exists( 'WC' ) ) {
			if ( method_exists( WC(), 'template_path' ) ) {
				$sep = DIRECTORY_SEPARATOR;
				return get_template_directory() . $sep . WC()->template_path();
			}
		}
		return false;
	}

	private function get_modified_files_path2() {
		$path1 = $this->get_modified_files_path1();
		if ( $path1 ) {
			$sep = DIRECTORY_SEPARATOR;
			return $path1 . 'emails' . $sep;
		}
		return false;
	}

	private function get_email_file_name( $email_id ) {
		if ( function_exists( 'WC' ) ) {
			if ( method_exists( WC(), 'mailer' ) ) {
				$woocommerce = WC()->mailer();
				if ( method_exists( $woocommerce, 'get_emails' ) ) {
					$templates = $woocommerce->get_emails();
					foreach ( $templates as $key => $template ) {
						if ( $email_id === $this->core->str_to_lower( $key ) ) {
							$filename = str_replace( 'wc_email_', '', $email_id );
							$filename = str_replace( '_', '-', $filename );
							if ( method_exists( $template, 'is_customer_email' ) ) {
								if ( $template->is_customer_email() ) {
									return $filename . '.php';
								} else {
									return 'admin-' . $filename . '.php';
								}
							}
						}
					}
				}
			}
		}
		return false;
	}

	private function get_hook_woocommerce_email( $email_id ) {
		$hook_content       = false;
		$original_file_path = $this->get_original_file_path( $email_id );
		if ( $original_file_path ) {
			if ( file_exists( $original_file_path ) ) {
				$content = $this->get_original_file_content( $original_file_path );
				if ( $content === false ) {
					return false;
				}
				$content = str_replace( "\r\n", "\n", $content );
				$content = str_replace( "\r", "\n", $content );
				$content = str_replace( 'woocommerce_email_header', 'woocommerce_email_header_jackmail_ignored', $content );
				$content = str_replace( 'woocommerce_email_footer', 'woocommerce_email_footer_jackmail_ignored', $content );
				$content = trim( $content );
				$lines   = explode( "\n", $content );
				$lines   = array_filter( $lines );
				foreach ( $lines as $line ) {
					$hook_content .= $line . "\n";
				}
				$nb_php_open_tag = substr_count( $hook_content, '<?php' );
				$nb_php_end_tag  = substr_count( $hook_content, '?>' );
				if ( $nb_php_open_tag > $nb_php_end_tag ) {
					$hook_content .= '?>' . "\n";
				}
			}
		}
		return $hook_content;
	}

	private function get_modified_folder_files( $path ) {
		if ( $this->check_modified_path( $path ) ) {
			return @scandir( $path );
		}
		return false;
	}

	private function create_modified_folder( $path ) {
		if ( $this->check_modified_path( $path ) ) {
			return @mkdir( $path );
		}
		return false;
	}

	private function delete_modified_folder( $path ) {
		if ( $this->check_modified_path( $path ) ) {
			return @rmdir( $path );
		}
		return false;
	}

	private function delete_modified_file( $file_path ) {
		if ( $this->check_modified_path( $file_path ) ) {
			return @unlink( $file_path );
		}
		return false;
	}

	private function get_original_file_content( $file_path ) {
		if ( $this->check_original_path( $file_path ) ) {
			return @file_get_contents( $file_path );
		}
		return false;
	}

	private function get_modified_file_content( $file_path ) {
		if ( $this->check_modified_path( $file_path ) ) {
			return @file_get_contents( $file_path );
		}
		return false;
	}

	private function edit_modified_file_content( $file_path, $content ) {
		if ( $this->check_modified_path( $file_path ) ) {
			return @file_put_contents( $file_path, $content );
		}
		return false;
	}

	private function rename_modified_file( $modified_file_path, $backup_file_path ) {
		if ( $this->check_modified_path( $modified_file_path ) && $this->check_modified_path( $backup_file_path ) ) {
			return @rename( $modified_file_path, $backup_file_path );
		}
		return false;
	}

	private function check_modified_path( $path ) {
		$path1 = $this->get_modified_files_path1();
		$path2 = $this->get_modified_files_path2();
		if ( $path1 === false || $path2 === false ) {
			return false;
		}
		if ( strpos( $path, $path1 ) !== false || strpos( $path, $path2 ) !== false ) {
			return true;
		}
		return false;
	}

	private function check_original_path( $path ) {
		$woocommerce_path = $this->get_woocommerce_path();
		if ( strpos( $path, $woocommerce_path ) !== false ) {
			return true;
		}
		return false;
	}

	private function create_index( $path ) {
		if ( $path !== $this->get_modified_files_path1() && $path !== $this->get_modified_files_path2() ) {
			return false;
		}
		$file = $path . 'index.php';
		if ( ! file_exists( $file ) ) {
			return $this->edit_modified_file_content( $file, '<?php' . "\n" . '// Generated from Jackmail' );
		}
		return true;
	}

	private function create_htaccess( $path ) {
		if ( $path !== $this->get_modified_files_path1() && $path !== $this->get_modified_files_path2() ) {
			return false;
		}
		$file = $path . '.htaccess';
		if ( ! file_exists( $file ) ) {
			return $this->edit_modified_file_content( $file, '# Generated from Jackmail' . "\n" . 'Deny from all' );
		}
		return true;
	}

	private function is_file_content_from_jackmail( $content ) {
		return strpos( $content, 'Generated from Jackmail' ) !== false;
	}

	public function cron_woocommerce_email_notification() {
		global $wpdb;
		$sql    = "
		SELECT `email_id`
		FROM `{$wpdb->prefix}jackmail_woocommerce_email_notification`
		WHERE `status` = 'ACTIVED'";
		$emails = $wpdb->get_results( $sql );
		foreach ( $emails as $key => $email ) {
			$email_id           = $email->email_id;
			$modified_file_path = $this->get_modified_file_path( $email_id );
			if ( $modified_file_path ) {
				if ( file_exists( $modified_file_path ) ) {
					$current_content = $this->get_modified_file_content( $modified_file_path );
					if ( $current_content !== false ) {
						if ( $this->is_file_content_from_jackmail( $current_content ) ) {
							$hook_begin_delimiter       = $this->get_hook_begin_delimiter();
							$hook_end_delimiter         = $this->get_hook_end_delimiter();
							$current_hook_content_begin = strpos( $current_content, $hook_begin_delimiter );
							$current_hook_content_end   = strpos( $current_content, $hook_end_delimiter );
							if ( $current_hook_content_begin !== false && $current_hook_content_end !== false ) {
								$new_hook_content = $this->get_hook_woocommerce_email( $email_id );
								if ( $new_hook_content !== false ) {
									$current_hook_content = substr(
										$current_content,
										$current_hook_content_begin + strlen( $hook_begin_delimiter ),
										$current_hook_content_end - $current_hook_content_begin - strlen( $hook_begin_delimiter )
									);
									if ( $current_hook_content !== $new_hook_content ) {
										$new_content = str_replace( $current_hook_content, $new_hook_content, $current_content );
										$this->edit_modified_file_content( $modified_file_path, $new_content );
									}
								}
							}
						}
					}
				}
			}
		}
	}

}
