<x-guest-layout>
    <x-slot name="title">تأكيد كلمة المرور | واحة الشهداء</x-slot>

    <!-- Header -->
    <div class="mb-6 text-center" dir="rtl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-oasis-mint/40 text-oasis-green shadow-sm">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>
        <p class="oasis-kicker">إجراء أمني</p>
        <h1 class="oasis-heading mt-1 text-2xl">تأكيد كلمة المرور</h1>
        <p class="oasis-copy mt-2 text-xs leading-5">
            هذه المنطقة محمية. يرجى تأكيد كلمة المرور الخاصة بك للمتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5" dir="rtl">
        @csrf

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
                autocomplete="current-password"
                placeholder="أدخل كلمة المرور"
                class="oasis-input mt-1 {{ $errors->has('password') ? '!border-red-400 focus:!border-red-500' : '' }}"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="oasis-button oasis-button-primary w-full !min-h-[46px] !text-xs">
                تأكيد ومتابعة
            </button>
        </div>
    </form>
</x-guest-layout>
