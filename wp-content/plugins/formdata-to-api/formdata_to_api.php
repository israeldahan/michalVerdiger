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


add_action( 'ninja_forms_after_submission', 'my_ninja_forms_after_submission' );

function my_ninja_forms_after_submission( $form_data ){
  // Do stuff.
  $form_title = $form_data['settings'][ 'title' ];

  if ( $form_title == 'send_to_feedback' ) {
      echo("send_to_feedback");
      $fields_by_key = $form_data["fields_by_key"];

      $full_name = $fields_by_key["fullname"]["value"];
      $email = $fields_by_key["email"]["value"];
      $phone = $fields_by_key["phone"]["value"];
      $subject = $fields_by_key["subject"]["value"];
      $message = $fields_by_key["message"]["value"];
      $fields_by_key["req_id"]["value"] = wp_generate_uuid4();

      $url = 'https://app.seker.live/fm1/form-data';
      $data = array(
        'externalId' => 'KEfdt', 
        'fullName' => $full_name, 
        'email' => $email, 
        'phone' => $phone, 
        'subject' => $subject,
        'details' => $message, 
        'sourceName' => 'landingPage' + ' ' + $fields_by_key["req_id"]["value"]
      );

      // use key 'http' even if you send the request to https://...
      $options = [
          'http' => [
              'header' => "Content-type: application/x-www-form-urlencoded\r\n",
              'method' => 'POST',
              'content' => http_build_query($data),
          ],
      ];

      $context = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      
      if ($result === false) {
          /* Handle error */
          
      }
  }
}

