# راهنمای عیب‌یابی مشکلات AJAX

## مشکلات رایج و راه‌حل‌ها

### 1. صفحه سفید بعد از کلیک دکمه درون‌ریزی

**علل احتمالی:**
- خطا در JavaScript
- مشکل در nonce
- خطا در AJAX handler
- مشکل در اتصال به دیتابیس

**راه‌حل‌ها:**

#### الف) بررسی خطاهای JavaScript
1. در مرورگر، F12 را فشار دهید
2. به تب Console بروید
3. دکمه درون‌ریزی را کلیک کنید
4. خطاهای JavaScript را بررسی کنید

#### ب) تست اتصال AJAX
1. دکمه "تست اتصال AJAX" را کلیک کنید
2. اگر موفق بود، مشکل در handler های دیگر است
3. اگر ناموفق بود، مشکل در تنظیمات AJAX است

#### ج) بررسی فایل‌های PHP
1. فایل `test_ajax.php` را اجرا کنید
2. مطمئن شوید که همه فایل‌ها موجود هستند
3. بررسی کنید که `STEELALBORZ_CLUB_PLUGIN_DIR` تعریف شده باشد

### 2. خطای "Security check failed"

**علت:** مشکل در nonce
**راه‌حل:**
1. کش مرورگر را پاک کنید
2. صفحه را refresh کنید
3. دوباره امتحان کنید

### 3. خطای "دسترسی غیرمجاز"

**علت:** کاربر لاگین نیست یا دسترسی ندارد
**راه‌حل:**
1. مطمئن شوید که به عنوان admin لاگین هستید
2. بررسی کنید که plugin فعال باشد

### 4. خطای "File upload failed"

**علل احتمالی:**
- فایل خیلی بزرگ است
- مشکل در تنظیمات PHP
- مشکل در مجوزهای فایل

**راه‌حل‌ها:**

#### الف) بررسی تنظیمات PHP
```php
// در فایل php.ini یا .htaccess
upload_max_filesize = 200M
post_max_size = 200M
max_execution_time = 3600
memory_limit = 4G
```

#### ب) بررسی مجوزهای فایل
```bash
chmod 755 wp-content/uploads
chmod 755 wp-content/uploads/steelalborz-import-logs
```

### 5. خطای دیتابیس

**علل احتمالی:**
- اتصال به دیتابیس قطع شده
- جدول‌ها وجود ندارند
- مشکل در مجوزهای دیتابیس

**راه‌حل‌ها:**

#### الف) بررسی اتصال دیتابیس
```php
global $wpdb;
$test = $wpdb->get_var("SELECT 1");
if ($test !== '1') {
    echo "مشکل در اتصال به دیتابیس";
}
```

#### ب) بررسی وجود جداول
```sql
SHOW TABLES LIKE 'wp_steelalborz_%';
```

### 6. خطای "Plugin directory not found"

**علت:** مسیر plugin درست تعریف نشده
**راه‌حل:**
1. فایل اصلی plugin را بررسی کنید
2. مطمئن شوید که `STEELALBORZ_CLUB_PLUGIN_DIR` تعریف شده

## مراحل عیب‌یابی سیستماتیک

### مرحله 1: تست اولیه
1. فایل `test_ajax.php` را اجرا کنید
2. نتایج را بررسی کنید
3. اگر همه چیز درست بود، به مرحله 2 بروید

### مرحله 2: تست AJAX
1. دکمه "تست اتصال AJAX" را کلیک کنید
2. اگر موفق بود، مشکل در handler های خاص است
3. اگر ناموفق بود، مشکل در تنظیمات کلی AJAX است

### مرحله 3: بررسی خطاهای JavaScript
1. Console مرورگر را باز کنید
2. دکمه‌ها را کلیک کنید
3. خطاها را بررسی کنید

### مرحله 4: بررسی خطاهای PHP
1. فایل error log را بررسی کنید
2. معمولاً در `/var/log/apache2/error.log` یا `/var/log/nginx/error.log`
3. خطاهای مربوط به plugin را پیدا کنید

### مرحله 5: تست جداگانه
1. هر handler را جداگانه تست کنید
2. از کوچک‌ترین فایل ممکن استفاده کنید
3. اندازه batch را کاهش دهید

## تنظیمات پیشنهادی برای سرور

### PHP Settings
```ini
upload_max_filesize = 200M
post_max_size = 200M
max_execution_time = 3600
memory_limit = 4G
max_input_vars = 100000
max_file_uploads = 100
```

### Apache Settings
```apache
<IfModule mod_php.c>
    php_value upload_max_filesize 200M
    php_value post_max_size 200M
    php_value max_execution_time 3600
    php_value memory_limit 4G
</IfModule>
```

### Nginx Settings
```nginx
client_max_body_size 200M;
proxy_read_timeout 3600s;
proxy_connect_timeout 3600s;
proxy_send_timeout 3600s;
```

## تماس با پشتیبانی

اگر هیچ‌کدام از راه‌حل‌ها کار نکرد:

1. نتایج `test_ajax.php` را ارسال کنید
2. خطاهای Console مرورگر را ارسال کنید
3. خطاهای error log را ارسال کنید
4. نسخه WordPress و PHP را ارسال کنید
5. اطلاعات سرور (Apache/Nginx) را ارسال کنید