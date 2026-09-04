<section>
    <header>
        <h2 class="font-serif text-lg font-bold text-oasis-house">
            بيانات الملف الشخصي
        </h2>

        <p class="oasis-copy mt-1 text-xs">
            تحديث اسم الحساب وعنوان البريد الإلكتروني الخاص بك.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="oasis-label">الاسم</label>
            <input
                id="name"
                name="name"
                type="text"
                class="oasis-input mt-1 block w-full {{ $errors->has('name') ? '!border-red-400 focus:!border-red-500' : '' }}"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="oasis-label">البريد الإلكتروني</label>
            <input
                id="email"
                name="email"
                type="email"
                class="oasis-input mt-1 block w-full {{ $errors->has('email') ? '!border-red-400 focus:!border-red-500' : '' }}"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-xs text-oasis-black/70">
                        عنوان بريدك الإلكتروني غير مؤكد.

                        <button form="send-verification" class="text-xs font-bold text-oasis-accent hover:text-oasis-green hover:underline">
                            انقر هنا لإعادة إرسال بريد التأكيد.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-semibold text-emerald-700">
                            تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="oasis-button oasis-button-primary !min-h-[44px] !px-6 !text-xs">
                حفظ التعديلات
            </button>

            @if (session('status') === 'profile-updated')
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
