<x-guest-layout>
    <x-slot name="title">إعادة تعيين كلمة المرور | واحة الشهداء</x-slot>

    <!-- Header -->
    <div class="mb-6 text-center" dir="rtl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-oasis-mint/40 text-oasis-green shadow-sm">
            <i class="fa-solid fa-lock-open text-2xl"></i>
        </div>
        <p class="oasis-kicker">تأمين الحساب</p>
        <h1 class="oasis-heading mt-1 text-2xl">إعادة تعيين كلمة المرور</h1>
        <p class="oasis-copy mt-1 text-xs">أدخل كلمة المرور الجديدة لحسابك للمتابعة.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5" dir="rtl">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="oasis-label">
                البريد الإلكتروني
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                class="oasis-input mt-1 {{ $errors->has('email') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="oasis-label">
                كلمة المرور الجديدة
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="oasis-input mt-1 {{ $errors->has('password') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="oasis-label">
                تأكيد كلمة المرور الجديدة
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="oasis-input mt-1 {{ $errors->has('password_confirmation') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="oasis-button oasis-button-primary w-full !min-h-[46px] !text-xs">
                حفظ كلمة المرور الجديدة
            </button>
        </div>
    </form>
</x-guest-layout>
