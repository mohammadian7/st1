<?php
/**
 * Check Existing Serials Script
 * 
 * This script helps check how many serials already exist in the database
 * Usage: php check_existing_serials.php
 */

// Include WordPress
require_once('wp-config.php');

// Connect to database
global $wpdb;

echo "=== بررسی سریال‌های موجود در دیتابیس ===\n\n";

try {
    // Count total rewards
    $total_rewards = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}steelalborz_installation_rewards");
    echo "کل پاداش‌های ثبت شده: " . number_format($total_rewards) . "\n";
    
    // Count by status
    $status_counts = $wpdb->get_results("
        SELECT status, COUNT(*) as count 
        FROM {$wpdb->prefix}steelalborz_installation_rewards 
        GROUP BY status
    ");
    
    echo "\nتوزیع بر اساس وضعیت:\n";
    foreach ($status_counts as $status) {
        echo "- {$status->status}: " . number_format($status->count) . "\n";
    }
    
    // Count by product group
    $group_counts = $wpdb->get_results("
        SELECT product_group, COUNT(*) as count 
        FROM {$wpdb->prefix}steelalborz_installation_rewards 
        WHERE product_group IS NOT NULL AND product_group != ''
        GROUP BY product_group
        ORDER BY count DESC
    ");
    
    echo "\nتوزیع بر اساس گروه محصول:\n";
    foreach ($group_counts as $group) {
        echo "- {$group->product_group}: " . number_format($group->count) . "\n";
    }
    
    // Show some sample serials
    $sample_serials = $wpdb->get_results("
        SELECT product_serial, product_group, status, created_at 
        FROM {$wpdb->prefix}steelalborz_installation_rewards 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    
    echo "\nنمونه سریال‌های اخیر:\n";
    foreach ($sample_serials as $serial) {
        echo "- {$serial->product_serial} ({$serial->product_group}) - {$serial->status} - {$serial->created_at}\n";
    }
    
    // Check for duplicates
    $duplicates = $wpdb->get_results("
        SELECT product_serial, COUNT(*) as count 
        FROM {$wpdb->prefix}steelalborz_installation_rewards 
        GROUP BY product_serial 
        HAVING COUNT(*) > 1
    ");
    
    if (!empty($duplicates)) {
        echo "\n⚠️  سریال‌های تکراری یافت شد:\n";
        foreach ($duplicates as $dup) {
            echo "- {$dup->product_serial}: {$dup->count} بار\n";
        }
    } else {
        echo "\n✅ هیچ سریال تکراری یافت نشد.\n";
    }
    
    // Database table info
    $table_info = $wpdb->get_results("
        SELECT 
            TABLE_NAME,
            TABLE_ROWS,
            ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) AS 'Size_MB'
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME LIKE '{$wpdb->prefix}steelalborz_%'
    ");
    
    echo "\nاطلاعات جداول:\n";
    foreach ($table_info as $table) {
        echo "- {$table->TABLE_NAME}: " . number_format($table->TABLE_ROWS) . " رکورد, {$table->Size_MB} MB\n";
    }
    
} catch (Exception $e) {
    echo "خطا: " . $e->getMessage() . "\n";
}

echo "\n=== پایان بررسی ===\n";
?>