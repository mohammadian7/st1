<?php
/**
 * Detailed Import Report Generator
 * 
 * This script generates detailed reports from import logs
 * Usage: php import_detailed_report.php [log_file]
 */

if ($argc < 2) {
    echo "Usage: php import_detailed_report.php <log_file>\n";
    echo "Example: php import_detailed_report.php import_log.json\n";
    exit(1);
}

$log_file = $argv[1];

if (!file_exists($log_file)) {
    echo "Error: Log file '$log_file' not found.\n";
    exit(1);
}

$log_data = json_decode(file_get_contents($log_file), true);

if (!$log_data) {
    echo "Error: Invalid JSON log file.\n";
    exit(1);
}

echo "=== گزارش تفصیلی درون‌ریزی ===\n\n";

// Overall statistics
echo "آمار کلی:\n";
echo "- کل رکوردها: " . number_format($log_data['total_records']) . "\n";
echo "- موفق: " . number_format($log_data['successful']) . "\n";
echo "- ناموفق: " . number_format($log_data['failed']) . "\n";
echo "- تکراری: " . number_format($log_data['duplicates']) . "\n";
echo "- کاربران ایجاد شده: " . number_format($log_data['users_created']) . "\n";
echo "- پاداش‌های ثبت شده: " . number_format($log_data['rewards_created']) . "\n";
echo "- گروه‌های جدید: " . number_format($log_data['new_groups']) . "\n\n";

// Decision summary
if (isset($log_data['decision_summary'])) {
    echo "خلاصه تصمیمات:\n";
    foreach ($log_data['decision_summary'] as $decision => $count) {
        $label = getDecisionLabel($decision);
        echo "- {$label}: " . number_format($count) . " رکورد\n";
    }
    echo "\n";
}

// Detailed log analysis
if (isset($log_data['detailed_log'])) {
    echo "تحلیل تفصیلی:\n";
    
    $decision_details = array();
    foreach ($log_data['detailed_log'] as $entry) {
        $decision = $entry['decision'] ?? 'unknown';
        if (!isset($decision_details[$decision])) {
            $decision_details[$decision] = array();
        }
        $decision_details[$decision][] = $entry;
    }
    
    foreach ($decision_details as $decision => $entries) {
        $label = getDecisionLabel($decision);
        echo "\n{$label} (" . count($entries) . " رکورد):\n";
        
        // Show first 5 examples
        $examples = array_slice($entries, 0, 5);
        foreach ($examples as $entry) {
            $details = $entry['details'] ?? '';
            $serial = $entry['serial'] ?? '';
            $username = $entry['username'] ?? '';
            
            echo "  - سریال: {$serial}, کاربر: {$username}\n";
            if ($details) {
                echo "    جزئیات: {$details}\n";
            }
        }
        
        if (count($entries) > 5) {
            echo "  ... و " . (count($entries) - 5) . " رکورد دیگر\n";
        }
    }
}

// Error analysis
if (!empty($log_data['errors'])) {
    echo "\nتحلیل خطاها:\n";
    $error_counts = array();
    foreach ($log_data['errors'] as $error) {
        if (!isset($error_counts[$error])) {
            $error_counts[$error] = 0;
        }
        $error_counts[$error]++;
    }
    
    foreach ($error_counts as $error => $count) {
        echo "- {$error}: {$count} بار\n";
    }
}

function getDecisionLabel($decision) {
    $labels = array(
        'processed_successfully' => 'پردازش موفق',
        'duplicate_serial_skipped' => 'سریال تکراری نادیده گرفته شد',
        'new_user_and_reward_created' => 'کاربر جدید و پاداش ایجاد شد',
        'existing_user_reward_created' => 'پاداش برای کاربر موجود ایجاد شد',
        'skipped_empty_username' => 'نام کاربری خالی نادیده گرفته شد',
        'skipped_empty_serial' => 'سریال خالی نادیده گرفته شد',
        'skipped_empty_product_group' => 'گروه محصول خالی نادیده گرفته شد',
        'user_creation_failed' => 'خطا در ایجاد کاربر',
        'reward_creation_failed' => 'خطا در ایجاد پاداش',
        'error' => 'خطا در پردازش',
        'unknown' => 'نامشخص'
    );
    return $labels[$decision] ?? $decision;
}

echo "\n=== پایان گزارش ===\n";
?>