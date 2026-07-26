<x-guest-layout>
    <x-slot name="title">تسجيل الدخول | واحة الشهداء</x-slot>

    <!-- ========================================== -->
    <!-- START: LOGIN HEADER -->
    <!-- ========================================== -->
    <div class="mb-7 text-center">
        <div
            class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-800">
            <i class="fa-solid fa-user-shield text-xl"></i>
        </div>

        <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
            تسجيل الدخول
        </h2>

        <p class="mt-2 text-sm text-gray-500">
            أدخل بيانات حساب الإدارة للمتابعة
        </p>
    </div>
    <!-- ========================================== -->
    <!-- END: LOGIN HEADER -->
    <!-- ========================================== -->

    <!-- Session Status -->
    @if (session('status'))
        <div
            class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <div class="flex items-start gap-2">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- START: LOGIN FORM -->
    <!-- ========================================== -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block mb-2 text-sm font-bold text-slate-700">
                البريد الإلكتروني
            </label>

            <div class="relative">
                <span
                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 pointer-events-none">
                    <i class="fa-solid fa-envelope"></i>
                </span>

                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="name@example.com"
                    class="block w-full h-12 rounded-xl border bg-white pr-11 pl-4 text-sm text-slate-700 placeholder:text-gray-400 shadow-sm transition focus:outline-none focus:ring-2
                    {{ $errors->has('email')
                        ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20'
                        : 'border-gray-200 focus:border-emerald-600 focus:ring-emerald-600/20' }}">
            </div>

            @error('email')
                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="mb-2 flex items-center justify-between gap-3">
                <label for="password" class="text-sm font-bold text-slate-700">
                    كلمة المرور
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 hover:underline transition">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <div class="relative">
                <span
                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 pointer-events-none">
                    <i class="fa-solid fa-lock"></i>
                </span>

                <input id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="أدخل كلمة المرور"
                    class="block w-full h-12 rounded-xl border bg-white pr-11 pl-12 text-sm text-slate-700 placeholder:text-gray-400 shadow-sm transition focus:outline-none focus:ring-2
                    {{ $errors->has('password')
                        ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20'
                        : 'border-gray-200 focus:border-emerald-600 focus:ring-emerald-600/20' }}">

                <button id="toggle-password"
                    type="button"
                    aria-label="إظهار أو إخفاء كلمة المرور"
                    class="absolute inset-y-0 left-0 flex items-center px-4 text-gray-400 hover:text-emerald-700 transition">
                    <i id="toggle-password-icon" class="fa-solid fa-eye"></i>
                </button>
            </div>

            @error('password')
                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <label for="remember_me"
            class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-600">
            <input id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-gray-300 text-emerald-700 shadow-sm focus:ring-emerald-600">
            <span>تذكرني على هذا الجهاز</span>
        </label>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full h-12 flex items-center justify-center gap-2 rounded-xl bg-emerald-800 px-5 text-sm font-bold text-white shadow-md transition hover:bg-emerald-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>تسجيل الدخول</span>
        </button>
    </form>
    <!-- ========================================== -->
    <!-- END: LOGIN FORM -->
    <!-- ========================================== -->

    <div class="mt-6 border-t border-gray-100 pt-5 text-center">
        <a href="{{ route('front.index') }}"
            class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة إلى الصفحة الرئيسية</span>
        </a>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('toggle-password');
        const togglePasswordIcon = document.getElementById('toggle-password-icon');

        togglePasswordButton.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';
            togglePasswordIcon.classList.toggle('fa-eye', !isPassword);
            togglePasswordIcon.classList.toggle('fa-eye-slash', isPassword);
        });
    </script>
</x-guest-layout>
