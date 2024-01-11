<?php

/**
 * @package Formdata_to_api
 * @version 0.0.1
 */
/*
Plugin Name: Form Data to API
Plugin URI: http://wordpress.org/plugins/
Description:  This plugin will send form data to an API endpoint.
Author: Dahan Israel
Version: 0.0.1
Author URI: http://israeldahan.co.il/
*/

// function formdata_to_api_install() {
// 	global $wpdb;

// 	$table_name = $wpdb->prefix . 'formdata_to_api';
// 	$charset_collate = $wpdb->get_charset_collate();
// 	$sql = "CREATE TABLE $table_name (
// 		id mediumint(9) NOT NULL AUTO_INCREMENT,
// 		form_id mediumint(9) NOT NULL,
// 		form_data text NOT NULL,
// 		PRIMARY KEY  (id)
// 	) $charset_collate;";
// 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
// 	dbDelta( $sql );
// }

add_filter('ninja_forms_submit_data', 'send_data_to_api');

function send_data_to_api($form_data)
{

  $full_name = '';
  $email = '';
  $phone = '';
  $subject = '';
  $message = '';

  $form_id = $form_data['id']; // Form ID.
  if ($form_id == 2) { // Form ID.
    foreach ($form_data['fields'] as $field) { // Field settigns, including the field key and value.
      $field_id = $field['id']; // Field ID.
      $field_key = $field['key']; // Field key.
      $field_value = $field['value']; // Field value.
      if ($field_key == 'fullname') {
        $full_name = $field_value;
      }
      if ($field_key == 'email') {
        $email = $field_value;
      }
      if ($field_key == 'phone') {
        $phone = $field_value;
      }
      if ($field_key == 'subject') {
        $subject = $field_value;
      }
      if ($field_key == 'message') {
        $message = $field_value;
      }
    }

    $url = 'https://app.seker.live/fm1/form-data';
    $data = array(
      'externalId' => 'KEfdt',
      'fullName' => $full_name,
      'email' => $email,
      'phone' => $phone,
      'subject' => $subject,
      'details' => $message,
      'sourceName' => 'landingPageWordpress'
    );
    $url = add_query_arg($data, $url);

    $args = array(
      'headers'     => array(
        'Content-Type' => 'application/json'
      ),
      'httpversion' => '1.0',
      'sslverify'   => false,
    );

    $wp_http = new WP_Http();
    $response = $wp_http->post($url, $args);

    if (is_wp_error($response)) {
      error_log($response->get_error_message());
    } else {
      // handle the response
    }
  }
  return $form_data;
}
