<x-app-layout>
    <x-slot name="title">لوحة التحكم | واحة الشهداء</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-xs font-bold text-amber-600 tracking-widest">
                    لوحة الإدارة
                </span>
                <h1 class="mt-1 text-xl sm:text-2xl font-bold text-slate-800">
                    لوحة التحكم
                </h1>
            </div>

            <a href="{{ route('front.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm hover:border-emerald-200 hover:bg-emerald-50 transition">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>عرض الموقع العام</span>
            </a>
        </div>
    </x-slot>

    <!-- ========================================== -->
    <!-- START: DASHBOARD CONTENT -->
    <!-- ========================================== -->
    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Welcome Hero -->
            <section
                class="relative overflow-hidden rounded-2xl bg-emerald-950 px-6 py-8 sm:px-8 sm:py-10 text-white shadow-lg">
                <div class="absolute inset-0 bg-gradient-to-l from-emerald-900/40 to-transparent"></div>
                <div class="absolute -top-16 -left-16 w-64 h-64 bg-amber-500/20 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-7">
                    <div class="max-w-2xl">
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1.5 text-xs font-bold text-amber-300">
                            <i class="fa-solid fa-shield-halved"></i>
                            جلسة إدارية آمنة
                        </span>

                        <h2 class="mt-4 text-2xl sm:text-3xl font-bold leading-tight">
                            أهلاً بك، {{ Auth::user()->name ?? 'مدير النظام' }}
                        </h2>

                        <p class="mt-3 max-w-xl text-sm sm:text-base leading-relaxed text-gray-300">
                            يمكنك من هنا إدارة سجلات الشهداء ومراجعة محتوى المنصة والوصول إلى أهم أدوات الإدارة.
                        </p>
                    </div>

                    <div
                        class="w-full lg:w-auto rounded-2xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500 text-emerald-950">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">تاريخ اليوم</p>
                                <p class="mt-1 text-sm font-bold text-white">
                                    {{ now()->translatedFormat('l، d F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="mt-8">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-amber-600 tracking-widest">
                            وصول سريع
                        </span>
                        <h2 class="mt-1 text-xl font-bold text-slate-800">
                            أدوات الإدارة
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                    <!-- Martyrs Management -->
                    <a href="{{ route('dashboard.martyr.index') }}"
                        class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:border-emerald-200 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 group-hover:bg-emerald-700 group-hover:text-white transition">
                                <i class="fa-solid fa-user-shield text-lg"></i>
                            </div>

                            <i
                                class="fa-solid fa-arrow-left text-gray-300 group-hover:text-amber-500 group-hover:-translate-x-1 transition"></i>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-800 group-hover:text-emerald-700 transition">
                            إدارة الشهداء
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed text-gray-500">
                            عرض السجلات الحالية وإضافة البيانات وتعديلها وإدارتها.
                        </p>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('profile.edit') }}"
                        class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:border-emerald-200 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 border border-amber-100 text-amber-700 group-hover:bg-amber-500 group-hover:text-emerald-950 transition">
                                <i class="fa-solid fa-user-gear text-lg"></i>
                            </div>

                            <i
                                class="fa-solid fa-arrow-left text-gray-300 group-hover:text-amber-500 group-hover:-translate-x-1 transition"></i>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-800 group-hover:text-emerald-700 transition">
                            الملف الشخصي
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed text-gray-500">
                            تحديث اسم المستخدم والبريد الإلكتروني وكلمة المرور.
                        </p>
                    </a>

                    <!-- Public Website -->
                    <a href="{{ route('front.index') }}"
                        class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:border-emerald-200 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-700 group-hover:bg-slate-800 group-hover:text-white transition">
                                <i class="fa-solid fa-globe text-lg"></i>
                            </div>

                            <i
                                class="fa-solid fa-arrow-up-right-from-square text-gray-300 group-hover:text-amber-500 transition"></i>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-800 group-hover:text-emerald-700 transition">
                            الموقع العام
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed text-gray-500">
                            فتح الواجهة الرئيسية ومراجعة ظهور المحتوى للزوار.
                        </p>
                    </a>
                </div>
            </section>

            <!-- Account Overview -->
            <section class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div
                    class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-5">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800">إرشادات سريعة</h2>
                            <p class="mt-0.5 text-xs text-gray-500">للحفاظ على دقة وأمان البيانات</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 px-4 py-3">
                            <i class="fa-solid fa-check mt-0.5 text-emerald-600"></i>
                            <p>راجع بيانات السجل جيداً قبل الحفظ أو التعديل.</p>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 px-4 py-3">
                            <i class="fa-solid fa-check mt-0.5 text-emerald-600"></i>
                            <p>لا تشارك بيانات الدخول إلى لوحة الإدارة مع أي شخص غير مخول.</p>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl bg-gray-50 px-4 py-3">
                            <i class="fa-solid fa-check mt-0.5 text-emerald-600"></i>
                            <p>استخدم تسجيل الخروج عند الانتهاء، خصوصاً على الأجهزة المشتركة.</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-5">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <h2 class="font-bold text-slate-800">بيانات الحساب</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-400">اسم المستخدم</p>
                            <p class="mt-1 text-sm font-bold text-slate-700 break-words">
                                {{ Auth::user()->name ?? '—' }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div>
                            <p class="text-xs text-gray-400">البريد الإلكتروني</p>
                            <p class="mt-1 text-sm font-bold text-slate-700 break-all">
                                {{ Auth::user()->email ?? '—' }}
                            </p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="mt-2 flex items-center justify-center gap-2 rounded-xl bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-700 hover:bg-emerald-100 transition">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>تعديل البيانات</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- ========================================== -->
    <!-- END: DASHBOARD CONTENT -->
    <!-- ========================================== -->
</x-app-layout>
