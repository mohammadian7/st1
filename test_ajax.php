<?php
/**
 * Simple AJAX Test
 * 
 * This file helps test if AJAX is working properly
 */

// Include WordPress
require_once('wp-config.php');

// Check if user is logged in and has permissions
if (!current_user_can('manage_options')) {
    wp_die('دسترسی غیرمجاز');
}

// Test database connection
global $wpdb;
$test_query = $wpdb->get_var("SELECT 1");
if ($test_query !== '1') {
    echo "خطا در اتصال به دیتابیس\n";
    exit;
}

// Test file upload directory
$upload_dir = wp_upload_dir();
if (!is_dir($upload_dir['basedir'])) {
    echo "خطا در دسترسی به پوشه آپلود\n";
    exit;
}

// Test AJAX URL
$ajax_url = admin_url('admin-ajax.php');
echo "AJAX URL: " . $ajax_url . "\n";

// Test nonce creation
$test_nonce = wp_create_nonce('steelalborz_test_nonce');
echo "Test nonce: " . $test_nonce . "\n";

// Test plugin directory
if (!defined('STEELALBORZ_CLUB_PLUGIN_DIR')) {
    echo "خطا: STEELALBORZ_CLUB_PLUGIN_DIR تعریف نشده\n";
    exit;
}

// Test import class
$import_file = STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-data-import.php';
if (!file_exists($import_file)) {
    echo "خطا: فایل class-data-import.php یافت نشد\n";
    exit;
}

echo "همه تست‌ها موفق بودند!\n";
echo "سیستم آماده است.\n";
?>