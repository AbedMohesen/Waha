<x-guest-layout>
    <x-slot name="title">تسجيل الدخول | واحة الشهداء</x-slot>

    <!-- Header -->
    <div class="mb-8 text-center" dir="rtl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-oasis-mint/40 text-oasis-green shadow-sm">
            <i class="fa-solid fa-user-shield text-2xl"></i>
        </div>
        <p class="oasis-kicker">منطقة الإدارة</p>
        <h1 class="oasis-heading mt-1 text-2xl">تسجيل الدخول</h1>
        <p class="oasis-copy mt-1 text-xs">أدخل بيانات الحساب للمتابعة إلى لوحة التحكم.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="oasis-alert-success mb-6" dir="rtl">
            <i class="fa-solid fa-circle-check mt-0.5"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5" dir="rtl">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="oasis-label">
                البريد الإلكتروني
            </label>
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-oasis-black/40">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="name@example.com"
                    class="oasis-input !pr-11 {{ $errors->has('email') ? '!border-red-400 focus:!border-red-500' : '' }}"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="mb-1 flex items-center justify-between">
                <label for="password" class="oasis-label">
                    كلمة المرور
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-oasis-accent hover:text-oasis-green hover:underline">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-oasis-black/40">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="أدخل كلمة المرور"
                    class="oasis-input !pl-11 !pr-11 {{ $errors->has('password') ? '!border-red-400 focus:!border-red-500' : '' }}"
                >
                <button
                    id="toggle-password"
                    type="button"
                    aria-label="إظهار أو إخفاء كلمة المرور"
                    class="absolute inset-y-0 left-0 flex items-center px-4 text-oasis-black/40 hover:text-oasis-accent transition"
                >
                    <i id="toggle-password-icon" class="fa-solid fa-eye text-sm"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <label for="remember_me" class="flex cursor-pointer items-center gap-2 text-xs font-medium text-oasis-black/70">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-oasis-ceramic text-oasis-green shadow-sm focus:ring-oasis-mint"
            >
            <span>تذكرني على هذا المتصفح</span>
        </label>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="oasis-button oasis-button-primary w-full !min-h-[50px] !text-sm">
                <i class="fa-solid fa-right-to-bracket ml-2"></i>
                تسجيل الدخول
            </button>
        </div>
    </form>

    <div class="mt-8 border-t border-black/[0.06] pt-6 text-center" dir="rtl">
        <a href="{{ route('front.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-oasis-black/60 hover:text-oasis-accent transition">
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
            <span>العودة إلى الصفحة الرئيسية</span>
        </a>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('toggle-password');
        const togglePasswordIcon = document.getElementById('toggle-password-icon');

        if (togglePasswordButton && passwordInput && togglePasswordIcon) {
            togglePasswordButton.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                togglePasswordIcon.classList.toggle('fa-eye', !isPassword);
                togglePasswordIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    </script>
</x-guest-layout>
