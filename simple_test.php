<?php
/**
 * Simple Test File
 * 
 * This file tests basic PHP functionality without WordPress
 */

echo "=== تست اولیه PHP ===\n\n";

// Test 1: PHP version
echo "1. نسخه PHP: " . phpversion() . "\n";

// Test 2: File system
echo "2. تست سیستم فایل:\n";
$current_dir = __DIR__;
echo "   مسیر فعلی: " . $current_dir . "\n";
echo "   قابل نوشتن: " . (is_writable($current_dir) ? 'بله' : 'خیر') . "\n";

// Test 3: Required files
echo "3. تست فایل‌های مورد نیاز:\n";
$required_files = [
    'steelalborz-club.php',
    'includes/class-data-import.php',
    'includes/class-admin-menu.php',
    'templates/admin/import.php'
];

foreach ($required_files as $file) {
    $full_path = $current_dir . '/' . $file;
    echo "   " . $file . ": " . (file_exists($full_path) ? 'موجود' : 'غایب') . "\n";
}

// Test 4: Create test directory
echo "4. تست ایجاد پوشه:\n";
$test_dir = $current_dir . '/test-uploads';
if (!is_dir($test_dir)) {
    if (mkdir($test_dir, 0755, true)) {
        echo "   پوشه test-uploads ایجاد شد\n";
    } else {
        echo "   خطا در ایجاد پوشه test-uploads\n";
    }
} else {
    echo "   پوشه test-uploads موجود است\n";
}

// Test 5: File upload simulation
echo "5. تست آپلود فایل:\n";
$test_file = $current_dir . '/test_import_small.csv';
if (file_exists($test_file)) {
    $file_size = filesize($test_file);
    echo "   فایل تست موجود: " . $file_size . " بایت\n";
    
    // Test reading CSV
    $handle = fopen($test_file, 'r');
    if ($handle) {
        $headers = fgetcsv($handle);
        $row_count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $row_count++;
        }
        fclose($handle);
        echo "   تعداد رکوردها: " . $row_count . "\n";
    } else {
        echo "   خطا در خواندن فایل CSV\n";
    }
} else {
    echo "   فایل تست موجود نیست\n";
}

// Test 6: Memory and execution time
echo "6. تست حافظه و زمان اجرا:\n";
echo "   حافظه استفاده شده: " . round(memory_get_usage() / 1024 / 1024, 2) . " MB\n";
echo "   حافظه حداکثر: " . ini_get('memory_limit') . "\n";
echo "   زمان اجرای حداکثر: " . ini_get('max_execution_time') . " ثانیه\n";

// Test 7: PHP extensions
echo "7. تست افزونه‌های PHP:\n";
$required_extensions = ['json', 'mbstring', 'pdo', 'pdo_mysql'];
foreach ($required_extensions as $ext) {
    echo "   " . $ext . ": " . (extension_loaded($ext) ? 'فعال' : 'غیرفعال') . "\n";
}

echo "\n=== پایان تست ===\n";
echo "اگر همه موارد 'موجود' و 'فعال' هستند، سیستم آماده است.\n";
?>