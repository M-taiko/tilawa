<?php

return [
    // Navigation
    'nav' => [
        'dashboard' => 'لوحة التحكم',
        'teachers' => 'المعلمون',
        'students' => 'الطلاب',
        'classes' => 'الحلقات',
        'schedules' => 'الجداول',
        'reports' => 'التقارير',
        'payments' => 'الرسوم والمدفوعات',
        'holidays' => 'الإجازات',
        'announcements' => 'الإعلانات',
        'activity_logs' => 'سجل النشاط',
        'users' => 'المستخدمون',
        'analytics' => 'الإحصائيات',
        'tenants' => 'إدارة المراكز',
        'tenant_admins' => 'مديرو المراكز',
        'profile' => 'الملف الشخصي',
        'memorization' => 'الحفظ والمتابعة',
        'sessions' => 'الجلسات',
    ],

    // Common actions
    'common' => [
        'save' => 'حفظ',
        'update' => 'تحديث',
        'delete' => 'حذف',
        'cancel' => 'إلغاء',
        'edit' => 'تعديل',
        'create' => 'إنشاء',
        'back' => 'رجوع',
        'search' => 'بحث',
        'clear' => 'مسح',
        'close' => 'إغلاق',
        'confirm' => 'تأكيد',
        'actions' => 'الإجراءات',
        'status' => 'الحالة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
        'email' => 'البريد الإلكتروني',
        'name' => 'الاسم',
        'password' => 'كلمة المرور',
        'phone' => 'رقم الهاتف',
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'لوحة التحكم',
        'welcome' => 'أهلاً وسهلاً',
        'welcome_message' => 'أهلاً وسهلاً بك في تلاوة',
        'total_teachers' => 'إجمالي المعلمين',
        'total_students' => 'إجمالي الطلاب',
        'total_classes' => 'إجمالي الحلقات',
        'today_sessions' => 'الجلسات اليوم',
    ],

    // Teachers
    'teachers' => [
        'title' => 'المعلمون',
        'create' => 'إضافة معلم',
        'edit' => 'تعديل المعلم',
        'name' => 'اسم المعلم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'status' => 'الحالة',
        'workload' => 'الأعباء التدريسية',
        'toggle_status' => 'تغيير الحالة',
    ],

    // Students
    'students' => [
        'title' => 'الطلاب',
        'create' => 'إضافة طالب',
        'edit' => 'تعديل الطالب',
        'name' => 'اسم الطالب',
        'parent_name' => 'اسم ولي الأمر',
        'parent_phone' => 'هاتف ولي الأمر',
        'group' => 'المجموعة',
        'class' => 'الحلقة',
        'status' => 'الحالة',
        'progress' => 'التقدم',
        'transfer' => 'تحويل',
        'graduate' => 'تخريج',
        'regenerate_token' => 'إعادة إنشاء الرابط',
    ],

    // Classes
    'classes' => [
        'title' => 'الحلقات',
        'create' => 'إضافة حلقة',
        'edit' => 'تعديل الحلقة',
        'name' => 'اسم الحلقة',
        'teacher' => 'المعلم',
        'level' => 'المستوى',
        'capacity' => 'السعة',
        'schedule' => 'الجدول الزمني',
    ],

    // Schedules
    'schedules' => [
        'title' => 'الجداول الزمنية',
        'create' => 'إضافة جدول',
        'edit' => 'تعديل الجدول',
        'class' => 'الحلقة',
        'day' => 'اليوم',
        'start_time' => 'وقت البداية',
        'end_time' => 'وقت النهاية',
        'room' => 'الفصل',
    ],

    // Reports
    'reports' => [
        'title' => 'التقارير',
        'inactive_students' => 'الطلاب غير النشطين',
        'teacher_reports' => 'تقارير المعلمين',
        'progress_reports' => 'تقارير التقدم',
        'attendance_reports' => 'تقارير الحضور',
        'payments_reports' => 'تقارير الرسوم',
    ],

    // Payments
    'payments' => [
        'title' => 'الرسوم والمدفوعات',
        'student_fees' => 'رسوم الطلاب',
        'payments' => 'المدفوعات',
        'monthly_fees' => 'الرسوم الشهرية',
        'amount' => 'المبلغ',
        'paid' => 'مدفوع',
        'overdue' => 'متأخر',
        'pending' => 'قيد الانتظار',
    ],

    // Quran
    'quran' => [
        'index' => 'المصحف الكريم',
        'surahs' => 'السور',
        'juzs' => 'الأجزاء',
        'pages' => 'الصفحات',
        'surah' => 'السورة',
        'juz' => 'الجزء',
        'page' => 'الصفحة',
        'verse' => 'الآية',
        'verses' => 'الآيات',
        'open_quran' => 'فتح المصحف',
        'search' => 'البحث في القرآن',
        'tafsir' => 'التفسير',
        'asbab' => 'أسباب النزول',
        'bookmark' => 'حفظ موضع القراءة',
        'copy_verse' => 'نسخ الآية',
        'index_label' => 'الفهرس',
        'translation' => 'الترجمة',
    ],

    // Auth
    'auth' => [
        'login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'remember_me' => 'تذكرني',
        'forgot_password' => 'هل نسيت كلمة المرور؟',
        'login_button' => 'دخول',
    ],

    // Misc
    'home' => 'الرئيسية',
    'language' => 'اللغة',
    'arabic' => 'العربية',
    'english' => 'English',
];
