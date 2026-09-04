<x-guest-layout>
    <x-slot name="title">تأكيد البريد الإلكتروني | واحة الشهداء</x-slot>

    <!-- Header -->
    <div class="mb-6 text-center" dir="rtl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-oasis-mint/40 text-oasis-green shadow-sm">
            <i class="fa-solid fa-envelope-circle-check text-2xl"></i>
        </div>
        <p class="oasis-kicker">التحقق من الحساب</p>
        <h1 class="oasis-heading mt-1 text-2xl">تأكيد البريد الإلكتروني</h1>
        <p class="oasis-copy mt-2 text-xs leading-5">
            شكرًا لتسجيلك! قبل البدء، يرجى تأكيد عنوان بريدك الإلكتروني عبر النقر على الرابط الذي أرسلناه إليك. إذا لم يصلك البريد، يمكننا إرسال رابط آخر بكل سرور.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="oasis-alert-success mb-5" dir="rtl">
            <i class="fa-solid fa-circle-check mt-0.5"></i>
            <span>تم إرسال رابط تأكيد جديد إلى عنوان البريد الإلكتروني الذي سجلت به.</span>
        </div>
    @endif

    <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" dir="rtl">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="oasis-button oasis-button-primary !min-h-[44px] !px-5 !text-xs">
                إعادة إرسال بريد التأكيد
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="oasis-button oasis-button-outline !min-h-[44px] !px-5 !text-xs">
                تسجيل الخروج
            </button>
        </form>
    </div>
</x-guest-layout>
