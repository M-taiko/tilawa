{{-- Language Toggle Component - Reusable across pages --}}
<div {{ $attributes->merge(['class' => 'flex items-center gap-1 bg-slate-100 rounded-lg p-1']) }}>
    <form method="POST" action="{{ route('locale.switch') }}" class="inline">
        @csrf
        <input type="hidden" name="locale" value="ar">
        <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            العربية
        </button>
    </form>
    <form method="POST" action="{{ route('locale.switch') }}" class="inline">
        @csrf
        <input type="hidden" name="locale" value="en">
        <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            EN
        </button>
    </form>
</div>
