<?php

return [
    'auth' => [
        'usernameType'                     => 'الگوی اطلاعات تماس معتبر نیست',
        'user_exist'                       => 'کاربر وجود دارد',
        'user_not_found'                   => 'کاربر یافت نشد',
        'contact_is_used_before'           => ':attribute وارد شده قبلاً توسط کاربر دیگری استفاده شده است یا توسط سیستم پذیرفته نمی‌شود',
        'invalid_token'                    => 'توکن نامعتبر است، لطفاً دوباره تلاش کنید',
        'conflict_password_code'           => 'در روش احراز هویت مشکلی رخ داده است',
        'conflict_password_code_existence' => 'رمز عبور و کد نباید همزمان استفاده شوند',
        'credentials'                      => 'اطلاعات ورود مطابقت ندارد',
        'reset_password'                   => 'بازنشانی رمز عبور با موفقیت انجام شد',
        'contact_verified_before'          => ':attribute وارد شده قبلاً تأیید شده است',
        'contact_verified_success'         => ':attribute وارد شده با موفقیت تأیید شد',
        'contact_is_not_verified'          => ':attribute تأیید نشده است',
        'contact_verified_failed'          => ':attribute وارد شده تأیید نشده است، دوباره تلاش کنید',
        'contact_failed'                   => 'در روش احراز هویت مشکلی رخ داده است',
    ],

    'verification_code' => [
        'sent_successfully' => 'کد تأیید با موفقیت از طریق :attribute ارسال شد',
        'sent_failed'       => 'ارسال کد تأیید از طریق :attribute با خطا مواجه شد، لطفاً بعداً دوباره تلاش کنید',
        'wait'              => 'می‌توانید پس از :seconds ثانیه دوباره تلاش کنید',
        'email_subject'     => 'کد تأیید',
        'not_valid'         => 'کد تأیید معتبر نیست',
        'valid'             => 'کد تأیید با موفقیت تأیید شد',
        'wrong_auth_token'  => 'توکن احراز هویت معتبر نیست',
    ],
];
