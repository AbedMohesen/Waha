<x-app-layout>
    <x-slot name="header"><p class="oasis-kicker">إدارة السجل</p><h1 class="oasis-heading mt-1">إضافة سجل جديد</h1><p class="oasis-copy mt-1">أدخل البيانات الأساسية، ثم أضف القصة والصور من صفحة السجل.</p></x-slot>
    <div class="oasis-container py-8 sm:py-12"><form method="POST" action="{{ route('dashboard.martyr.store') }}" class="oasis-card mx-auto max-w-3xl p-6 sm:p-8">@csrf
        <div class="grid gap-5 md:grid-cols-2">
            <div><x-input-label for="national_id" value="الرقم الوطني" /><x-text-input id="national_id" name="national_id" type="text" :value="old('national_id')" required autofocus /><x-input-error :messages="$errors->get('national_id')" /></div>
            <div><x-input-label for="name_en" value="الاسم بالإنجليزية" /><x-text-input id="name_en" name="name_en" type="text" :value="old('name_en')" /><x-input-error :messages="$errors->get('name_en')" /></div>
            <div class="md:col-span-2"><x-input-label for="name_ar" value="الاسم بالعربية" /><x-text-input id="name_ar" name="name_ar" type="text" :value="old('name_ar')" required /><x-input-error :messages="$errors->get('name_ar')" /></div>
            <div><x-input-label for="sex" value="الجنس" /><select id="sex" name="sex" required class="oasis-input"><option value="m" @selected(old('sex') === 'm')>ذكر</option><option value="f" @selected(old('sex') === 'f')>أنثى</option></select><x-input-error :messages="$errors->get('sex')" /></div>
            <div><x-input-label for="age" value="العمر" /><x-text-input id="age" name="age" type="text" :value="old('age')" required /><x-input-error :messages="$errors->get('age')" /></div>
            <div><x-input-label for="date_barth" value="تاريخ الميلاد" /><x-text-input id="date_barth" name="date_barth" type="date" :value="old('date_barth')" required /><x-input-error :messages="$errors->get('date_barth')" /></div>
        </div>
        <div class="mt-8 flex flex-col-reverse gap-3 border-t border-black/5 pt-6 sm:flex-row sm:justify-end"><a href="{{ route('dashboard.martyr.index') }}" class="oasis-button oasis-button-outline">إلغاء</a><x-primary-button>حفظ السجل</x-primary-button></div>
    </form></div>
</x-app-layout>
