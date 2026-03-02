@extends('layouts.auth')

@section('title', 'تسجيل الدخول - تلاوة')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-8"
     style="background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 40%, #0f172a 100%); min-height: 100vh;">

    {{-- زخرفة خلفية --}}
    <div style="position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0;">
        <div style="position:absolute;top:-20%;right:-10%;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,0.08),transparent 70%);"></div>
        <div style="position:absolute;bottom:-20%;left:-10%;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,0.1),transparent 70%);"></div>
    </div>

    <div style="position:relative;z-index:1;width:100%;max-width:440px;">

        {{-- الشعار --}}
        <div style="text-align:center;margin-bottom:32px;">
            <div style="width:72px;height:72px;border-radius:20px;overflow:hidden;margin:0 auto 16px;box-shadow:0 8px 32px rgba(201,168,76,0.3);">
                <img src="/images/logo.png" alt="Masar Soft"
                     style="width:100%;height:100%;object-fit:cover;"
                     onerror="this.parentElement.innerHTML='<div style=\'width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#c9a84c,#8b6914);display:flex;align-items:center;justify-content:center;\'><span style=\'font-size:2rem;font-family:Amiri,serif;color:#fff;font-weight:700;\'>ت</span></div>'">
            </div>
            <h1 style="font-family:'Tajawal',sans-serif;font-size:1.75rem;font-weight:800;color:#f8fafc;margin-bottom:4px;">Masar Soft Tilawa</h1>
            <p style="font-family:'Tajawal',sans-serif;font-size:0.9rem;color:#94a3b8;">منصة تحفيظ القرآن الكريم</p>
        </div>

        {{-- زر فتح المصحف (بارز - قبل الـ login) --}}
        <a href="{{ route('quran.index') }}"
           style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:16px;border-radius:14px;background:linear-gradient(135deg,#c9a84c,#8b6914);color:#fff;font-family:'Tajawal',sans-serif;font-size:1rem;font-weight:700;text-decoration:none;margin-bottom:20px;box-shadow:0 4px 20px rgba(201,168,76,0.35);transition:opacity 0.2s;"
           onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            فتح المصحف الكريم
        </a>

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <div style="flex:1;height:1px;background:rgba(255,255,255,0.1);"></div>
            <span style="font-family:'Tajawal',sans-serif;font-size:0.78rem;color:#64748b;">أو سجّل دخولك للإدارة</span>
            <div style="flex:1;height:1px;background:rgba(255,255,255,0.1);"></div>
        </div>

        {{-- بطاقة الـ Login --}}
        <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:28px;backdrop-filter:blur(12px);">

            {{-- رسالة الخطأ --}}
            @if($errors->any())
            <div style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.4);border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#f87171" viewBox="0 0 24 24" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span style="font-family:'Tajawal',sans-serif;font-size:0.85rem;color:#fca5a5;">{{ $errors->first('login') ?? $errors->first('password') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                {{-- البريد الإلكتروني --}}
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-family:'Tajawal',sans-serif;font-size:0.82rem;font-weight:600;color:#cbd5e1;margin-bottom:6px;">البريد الإلكتروني</label>
                    <div style="position:relative;">
                        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#64748b;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="login" value="{{ old('login') }}" required
                               placeholder="example@email.com"
                               style="width:100%;padding:11px 40px 11px 14px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:#f1f5f9;font-family:'Tajawal',sans-serif;font-size:0.9rem;outline:none;direction:ltr;text-align:right;transition:border-color 0.2s;"
                               onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='rgba(255,255,255,0.15)'">
                    </div>
                </div>

                {{-- كلمة المرور --}}
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-family:'Tajawal',sans-serif;font-size:0.82rem;font-weight:600;color:#cbd5e1;margin-bottom:6px;">كلمة المرور</label>
                    <div style="position:relative;">
                        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#64748b;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" required
                               placeholder="••••••••"
                               style="width:100%;padding:11px 40px 11px 14px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:#f1f5f9;font-family:'Tajawal',sans-serif;font-size:0.9rem;outline:none;direction:ltr;transition:border-color 0.2s;"
                               onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='rgba(255,255,255,0.15)'">
                    </div>
                </div>

                {{-- زر الدخول --}}
                <button type="submit"
                        style="width:100%;padding:13px;border:none;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;font-family:'Tajawal',sans-serif;font-size:1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:opacity 0.2s;box-shadow:0 4px 16px rgba(99,102,241,0.3);"
                        onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    دخول لوحة الإدارة
                </button>
            </form>
        </div>

        {{-- زرار تثبيت PWA --}}
        <div id="pwa-section" style="margin-top:20px;">

            {{-- زرار native (Android/Desktop Chrome) --}}
            <button id="pwa-native-btn"
                    onclick="installPWA()"
                    style="display:none;width:100%;padding:12px;border:1px solid rgba(201,168,76,0.5);border-radius:12px;background:rgba(201,168,76,0.08);color:#c9a84c;font-family:'Tajawal',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;align-items:center;justify-content:center;gap:8px;transition:background 0.2s;">
                📲 تثبيت التطبيق على جهازك
            </button>

            {{-- تعليمات iOS (دايماً ظاهرة على iOS Safari) --}}
            <div id="pwa-ios-hint"
                 style="display:none;padding:14px 16px;border:1px solid rgba(201,168,76,0.3);border-radius:12px;background:rgba(201,168,76,0.06);text-align:center;">
                <p style="font-family:'Tajawal',sans-serif;font-size:0.82rem;color:#c9a84c;margin-bottom:8px;font-weight:600;">📲 لتثبيت التطبيق على iPhone</p>
                <p style="font-family:'Tajawal',sans-serif;font-size:0.78rem;color:#94a3b8;line-height:1.8;">
                    اضغط على <strong style="color:#c9a84c;">زرار المشاركة</strong> ⬆️<br>
                    ثم اختر <strong style="color:#c9a84c;">"إضافة للشاشة الرئيسية"</strong>
                </p>
            </div>

            {{-- نص صغير --}}
            <p style="text-align:center;margin-top:14px;font-family:'Tajawal',sans-serif;font-size:0.75rem;color:#475569;">
                المصحف الكريم متاح للجميع بدون تسجيل دخول
            </p>

            {{-- جملة الاشتراك --}}
            <div style="margin-top:16px;padding:14px 16px;border:1px solid rgba(201,168,76,0.25);border-radius:12px;background:rgba(201,168,76,0.05);text-align:center;">
                <p style="font-family:'Tajawal',sans-serif;font-size:0.8rem;color:#94a3b8;line-height:1.7;">
                    للاشتراك في برنامج المعلم لمراجعة الطلاب<br>
                    يرجى التواصل على
                    <a href="mailto:sales@masarsoft.io"
                       style="color:#c9a84c;font-weight:700;text-decoration:none;">
                        sales@masarsoft.io
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>

<script>
// ===== كشف النظام =====
const isIOS     = /iphone|ipad|ipod/i.test(navigator.userAgent);
const isInApp   = window.navigator.standalone || window.matchMedia('(display-mode: standalone)').matches;
let deferredPrompt = null;

// لو مثبت بالفعل → اخفي كل حاجة
if (!isInApp) {
    if (isIOS) {
        document.getElementById('pwa-ios-hint').style.display = 'block';
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        const btn = document.getElementById('pwa-native-btn');
        btn.style.display = 'flex';
    });
}

function installPWA() {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(() => {
        deferredPrompt = null;
        document.getElementById('pwa-native-btn').style.display = 'none';
    });
}
</script>
@endsection
