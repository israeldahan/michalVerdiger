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
require 'vendor/autoload.php';

add_filter('ninja_forms_submit_data', 'send_data_to_api');

function send_data_to_api($form_data)
{

  $full_name = '';
  $email = '';
  $phone = '';
  $subject = '';
  $message = '';

  $form_id = $form_data['id']; // Form ID.
  
  $susbjectArr = array(
    'btl' => 'ביטוח לאומי',
    'bnf' => 'בריאות הנפש',
    'edu' => 'חינוך',
    'war' => 'חרבות ברזל',
    'grows' => 'קליטה ועליה',
    'pros' => 'רווחה',
    'svc' => 'שירות לאומי/צבאי',
    'other' => 'אחר',
  );

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
        $subject = $susbjectArr[$field_value];
      }
      if ($field_key == 'message') {
        $message = $field_value;
      }
      if ($field_key == 'file_upload') {
        $file_upload   = $field['files'][0]['tmp_name'];
      }
    }

    $file_path = wp_get_upload_dir()['basedir'] . '/ninja-forms/tmp/' . $file_upload;

    
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

    $client = new GuzzleHttp\Client();
    $options = [
      'multipart' => [
        [
          'name' => 'file',
          'contents' => GuzzleHttp\Psr7\Utils::tryFopen($file_path, 'r'),
          'filename' => $file_path,
          'headers'  => [
            'Content-Type' => 'application/image'
          ]
        ]
    ]];
    $request = new \GuzzleHttp\Psr7\Request('POST', $url);
    $res = $client->sendAsync($request, $options)->wait(); 
    echo $res->getBody();

      }
  return $form_data;
}
