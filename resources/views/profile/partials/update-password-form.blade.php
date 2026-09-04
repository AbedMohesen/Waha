<section>
    <header>
        <h2 class="font-serif text-lg font-bold text-oasis-house">
            تحديث كلمة المرور
        </h2>

        <p class="oasis-copy mt-1 text-xs">
            تأكد من استخدام كلمة مرور طويلة وعشوائية للحفاظ على أمان حسابك.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="oasis-label">كلمة المرور الحالية</label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="oasis-input mt-1 block w-full {{ $errors->updatePassword->has('current_password') ? '!border-red-400 focus:!border-red-500' : '' }}"
                autocomplete="current-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="oasis-label">كلمة المرور الجديدة</label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="oasis-input mt-1 block w-full {{ $errors->updatePassword->has('password') ? '!border-red-400 focus:!border-red-500' : '' }}"
                autocomplete="new-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="oasis-label">تأكيد كلمة المرور الجديدة</label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="oasis-input mt-1 block w-full {{ $errors->updatePassword->has('password_confirmation') ? '!border-red-400 focus:!border-red-500' : '' }}"
                autocomplete="new-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="oasis-button oasis-button-primary !min-h-[44px] !px-6 !text-xs">
                حفظ كلمة المرور
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-semibold text-oasis-accent"
                >تم الحفظ بنجاح.</p>
            @endif
        </div>
    </form>
</section>
