<x-guest-layout>
    <x-slot name="title">استعادة كلمة المرور | واحة الشهداء</x-slot>

    <!-- Header -->
    <div class="mb-6 text-center" dir="rtl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-oasis-mint/40 text-oasis-green shadow-sm">
            <i class="fa-solid fa-key text-2xl"></i>
        </div>
        <p class="oasis-kicker">استعادة الحساب</p>
        <h1 class="oasis-heading mt-1 text-2xl">نسيت كلمة المرور؟</h1>
        <p class="oasis-copy mt-2 text-xs leading-5">
            أدخل بريدك الإلكتروني المسجل وسنرسل لك رابطًا لإعادة تعيين كلمة المرور واختيار كلمة مرور جديدة.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" dir="rtl">
        @csrf

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
                autofocus
                placeholder="name@example.com"
                class="oasis-input mt-1 {{ $errors->has('email') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-oasis-accent hover:text-oasis-green hover:underline">
                العودة إلى تسجيل الدخول
            </a>

            <button type="submit" class="oasis-button oasis-button-primary !min-h-[46px] !px-6 !text-xs">
                إرسال رابط الاستعادة
            </button>
        </div>
    </form>
</x-guest-layout>
