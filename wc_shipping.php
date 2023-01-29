<?php

/**
 * Plugin Name: HSN Shipping
 */

include 'class.php';
$new_file = wp_upload_dir()['basedir'] . '/hsn_class.php';
if (file_exists($new_file)) {
    // unlink($new_file);
    include $new_file;
}

$original_file = plugin_dir_path(__FILE__) . '/class.php';
$new_file = wp_upload_dir()['basedir'] . '/hsn_class.php';
$original_class_name = ['HSN', 'hsn', 'Hsn'];
$new_class_name = ['SM_HSN', 'sm_hsn', 'Sm_hsn'];
$contents = file_get_contents($original_file);
$contents = str_replace($original_class_name, $new_class_name, $contents);
file_put_contents($new_file, $contents);
