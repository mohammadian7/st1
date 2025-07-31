# مدیریت کارمندان - باشگاه نمایندگان استیل البرز

## ویژگی‌های جدید

### 1. اتصال خودکار کارمندان به مدیران

هنگام افزودن کاربر جدید از طریق پنل مدیریت یا فرم ثبت‌نام، اگر کاربر فعلی مدیر یا مدیر نمایندگی باشد و کاربر جدید کارمند نمایندگی یا نماینده باشد، به طور خودکار به مدیر فعلی متصل می‌شود.

**نحوه کار:**
- فیلد `manager_user_id` به عنوان user meta ذخیره می‌شود
- فقط مدیران (`administrator`) و مدیران نمایندگی (`agency_manager`) می‌توانند کارمندان را مدیریت کنند
- کارمندان (`agency_employee`) و نمایندگان (`representative`) می‌توانند به مدیران متصل شوند

### 2. پشتیبانی از افزونه Digits

اگر افزونه Digits نصب و فعال باشد، شماره تماس کاربران به صورت خودکار برای این افزونه تنظیم می‌شود:

**فیلدهای ذخیره شده:**
- `digits_phone`: شماره تماس با فرمت بین‌المللی
- `digt_countrycode`: کد کشور ایران (98)

**فرمت شماره تماس:**
- شماره‌های ایرانی به صورت خودکار با پیشوند `+98` فرمت می‌شوند
- کاراکترهای غیر عددی حذف می‌شوند (به جز +)

### 3. شورت‌کد مدیریت کارمندان

شورت‌کد جدید `[steelalborz_manage_employees]` برای مدیریت کارمندان:

**ویژگی‌ها:**
- نمایش آمار کارمندان (کارمند نمایندگی، نماینده، کل)
- لیست کارمندان تحت مدیریت
- امکان تغییر نقش کارمندان
- تأیید امنیتی قبل از تغییر نقش
- طراحی ریسپانسیو

**دسترسی:**
- فقط مدیران (`administrator`) و مدیران نمایندگی (`agency_manager`)
- هر مدیر فقط می‌تواند کارمندان تحت مدیریت خود را تغییر دهد

### 4. AJAX Handlers جدید

**`steelalborz_change_employee_role`:**
- تغییر نقش کارمندان
- بررسی دسترسی و امنیت
- اعتبارسنجی نقش جدید

## نحوه استفاده

### 1. افزودن کارمند جدید

```php
// از طریق پنل مدیریت
// یا استفاده از شورت‌کد [steelalborz_user_registration]
```

### 2. مدیریت کارمندان

```php
// استفاده از شورت‌کد در صفحه
[steelalborz_manage_employees]
```

### 3. بررسی کارمندان تحت مدیریت

```php
$user_roles = new SteelAlborzClubUserRoles();
$employees = $user_roles->get_managed_employees($manager_id);
$stats = $user_roles->get_employee_statistics($manager_id);
```

## امنیت

### Nonce Verification
- تمام درخواست‌های AJAX با nonce محافظت می‌شوند
- `steelalborz_user_nonce` برای عملیات کاربر
- `steelalborz_employee_nonce` برای تغییر نقش کارمندان

### دسترسی
- بررسی نقش کاربر قبل از هر عملیات
- مدیران فقط می‌توانند کارمندان تحت مدیریت خود را تغییر دهند
- اعتبارسنجی نقش‌های مجاز

### Sanitization
- تمام ورودی‌ها sanitize می‌شوند
- استفاده از `sanitize_text_field`, `sanitize_email`, etc.

## مثال کد

### تغییر نقش کارمند
```javascript
$.ajax({
    url: steelalborz_ajax.ajax_url,
    type: 'POST',
    data: {
        action: 'steelalborz_change_employee_role',
        employee_id: employeeId,
        new_role: newRole,
        nonce: steelalborz_ajax.nonce
    },
    success: function(response) {
        if (response.success) {
            alert('نقش کاربر با موفقیت تغییر یافت.');
        } else {
            alert('خطا: ' + response.data);
        }
    }
});
```

### بررسی کارمندان تحت مدیریت
```php
$user_roles = new SteelAlborzClubUserRoles();
$employees = $user_roles->get_managed_employees();

foreach ($employees as $employee) {
    echo $employee->display_name . ' - ' . $employee->roles[0];
}
```

## نکات مهم

1. **فعال‌سازی ثبت‌نام:** برای استفاده از فرم ثبت‌نام عمومی، گزینه `enable_registration` را در تنظیمات فعال کنید.

2. **Digits Plugin:** برای استفاده کامل از ویژگی‌های Digits، افزونه را نصب و فعال کنید.

3. **نقش‌های کاربری:** اطمینان حاصل کنید که نقش‌های سفارشی ایجاد شده‌اند.

4. **امنیت:** تمام درخواست‌ها با nonce محافظت می‌شوند و دسترسی بررسی می‌شود.

## تغییرات در نسخه 1.0.1

- اضافه شدن سیستم اتصال خودکار کارمندان به مدیران
- پشتیبانی از افزونه Digits
- شورت‌کد مدیریت کارمندان
- بهبود امنیت و اعتبارسنجی
- مستندات کامل 