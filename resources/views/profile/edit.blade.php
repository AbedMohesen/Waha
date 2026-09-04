<x-app-layout>
    <x-slot name="header">
        <div dir="rtl">
            <p class="oasis-kicker">إعدادات الحساب</p>
            <h1 class="oasis-heading mt-1">الملف الشخصي</h1>
            <p class="oasis-copy mt-1">إدارة بيانات حسابك وكلمة المرور والأمان.</p>
        </div>
    </x-slot>

    <div class="oasis-container py-8 sm:py-12 space-y-8" dir="rtl">
        <div class="oasis-card p-6 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="oasis-card p-6 sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="oasis-card p-6 sm:p-8 border-red-100 bg-red-50/20">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
