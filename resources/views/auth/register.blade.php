<x-guest-layout>
    <x-slot name="title">إنشاء حساب جديد | واحة الشهداء</x-slot>

    <!-- Header -->
    <div class="mb-8 text-center" dir="rtl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-oasis-mint/40 text-oasis-green shadow-sm">
            <i class="fa-solid fa-user-plus text-2xl"></i>
        </div>
        <p class="oasis-kicker">حساب جديد</p>
        <h1 class="oasis-heading mt-1 text-2xl">إنشاء حساب جديد</h1>
        <p class="oasis-copy mt-1 text-xs">أدخل البيانات لإنشاء حساب مسؤول جديد في النظام.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" dir="rtl">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="oasis-label">
                الاسم الكامل
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="الاسم الثلاثي أو الرباعي"
                class="oasis-input mt-1 {{ $errors->has('name') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="oasis-label">
                البريد الإلكتروني
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="name@example.com"
                class="oasis-input mt-1 {{ $errors->has('email') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="oasis-label">
                كلمة المرور
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="كلمة مرور قوية (8 أحرف على الأقل)"
                class="oasis-input mt-1 {{ $errors->has('password') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="oasis-label">
                تأكيد كلمة المرور
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="أعد إدخال كلمة المرور"
                class="oasis-input mt-1 {{ $errors->has('password_confirmation') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Actions -->
        <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-oasis-accent hover:text-oasis-green hover:underline">
                لديك حساب بالفعل؟ تسجيل الدخول
            </a>

            <button type="submit" class="oasis-button oasis-button-primary !min-h-[46px] !px-6 !text-xs">
                <i class="fa-solid fa-user-plus ml-2"></i>
                إنشاء الحساب
            </button>
        </div>
    </form>
</x-guest-layout>
