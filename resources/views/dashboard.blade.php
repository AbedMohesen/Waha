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

            <!-- Statistics -->
            <section class="mt-8" aria-labelledby="statistics-title">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="text-xs font-bold tracking-widest text-amber-600">
                            نظرة تحليلية
                        </span>
                        <h2 id="statistics-title" class="mt-1 text-xl font-bold text-slate-800 sm:text-2xl">
                            إحصاءات الشهداء
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            توزيع السجلات المصنّفة حسب الجنس والفئة العمرية.
                        </p>
                    </div>

                    <div
                        class="inline-flex w-fit items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-2.5">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-700 text-white">
                            <i class="fa-solid fa-database"></i>
                        </span>
                        <div>
                            <p class="text-[11px] font-medium text-emerald-700">إجمالي السجلات</p>
                            <p class="text-lg font-extrabold leading-tight text-emerald-950">
                                {{ number_format($statistics['total']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
                    <!-- Gender distribution -->
                    <article
                        class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-2">
                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
                            <div>
                                <h3 class="font-bold text-slate-800">التوزيع حسب الجنس</h3>
                                <p class="mt-1 text-xs text-gray-500">
                                    من أصل {{ number_format($statistics['gender']['classified']) }} سجل مصنّف
                                </p>
                            </div>
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <i class="fa-solid fa-chart-pie"></i>
                            </span>
                        </div>

                        <div class="grid gap-6 p-5 sm:grid-cols-[11rem_1fr] sm:items-center sm:p-6">
                            <div class="flex justify-center">
                                <div
                                    class="relative grid h-40 w-40 place-items-center rounded-full"
                                    style="background: {{ $statistics['gender']['classified'] > 0
                                        ? 'conic-gradient(#047857 0 '.$statistics['gender']['male']['percentage'].'%, #f59e0b '.$statistics['gender']['male']['percentage'].'% 100%)'
                                        : 'conic-gradient(#e5e7eb 0 100%)' }}"
                                    role="img"
                                    aria-label="الذكور {{ $statistics['gender']['male']['percentage'] }} بالمئة، الإناث {{ $statistics['gender']['female']['percentage'] }} بالمئة">
                                    <div
                                        class="grid h-24 w-24 place-items-center rounded-full border border-gray-100 bg-white text-center shadow-inner">
                                        <div>
                                            <span class="block text-2xl font-extrabold text-slate-800">
                                                {{ number_format($statistics['gender']['classified']) }}
                                            </span>
                                            <span class="text-[10px] font-semibold text-gray-400">مصنّف</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-1">
                                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800">
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-700"></span>
                                        ذكور
                                    </div>
                                    <div class="mt-3 flex items-end justify-between gap-2">
                                        <strong class="text-xl font-extrabold text-emerald-950">
                                            {{ number_format($statistics['gender']['male']['count']) }}
                                        </strong>
                                        <span class="text-sm font-bold text-emerald-700">
                                            {{ $statistics['gender']['male']['percentage'] }}%
                                        </span>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-amber-100 bg-amber-50 p-4">
                                    <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                        إناث
                                    </div>
                                    <div class="mt-3 flex items-end justify-between gap-2">
                                        <strong class="text-xl font-extrabold text-amber-950">
                                            {{ number_format($statistics['gender']['female']['count']) }}
                                        </strong>
                                        <span class="text-sm font-bold text-amber-700">
                                            {{ $statistics['gender']['female']['percentage'] }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($statistics['gender']['unclassified'] > 0)
                            <div
                                class="border-t border-gray-100 bg-gray-50 px-5 py-3 text-xs text-gray-500 sm:px-6">
                                <i class="fa-solid fa-circle-info ml-1 text-gray-400"></i>
                                يوجد {{ number_format($statistics['gender']['unclassified']) }} سجل دون تصنيف جنس معتمد.
                            </div>
                        @endif
                    </article>

                    <!-- Age groups -->
                    <article
                        class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-3">
                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
                            <div>
                                <h3 class="font-bold text-slate-800">الفئات العمرية</h3>
                                <p class="mt-1 text-xs text-gray-500">
                                    من أصل {{ number_format($statistics['age_groups']['classified']) }} سجل بعمر صالح
                                </p>
                            </div>
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                <i class="fa-solid fa-chart-column"></i>
                            </span>
                        </div>

                        @php
                            $ageGroups = [
                                [
                                    'key' => 'children',
                                    'label' => 'الأطفال',
                                    'range' => 'أقل من 18 سنة',
                                    'icon' => 'fa-child-reaching',
                                    'bar' => 'bg-sky-500',
                                    'iconStyle' => 'bg-sky-50 text-sky-600',
                                ],
                                [
                                    'key' => 'youth',
                                    'label' => 'الشباب',
                                    'range' => 'من 18 إلى 59 سنة',
                                    'icon' => 'fa-person',
                                    'bar' => 'bg-emerald-600',
                                    'iconStyle' => 'bg-emerald-50 text-emerald-700',
                                ],
                                [
                                    'key' => 'elders',
                                    'label' => 'كبار السن',
                                    'range' => '60 سنة فأكثر',
                                    'icon' => 'fa-person-cane',
                                    'bar' => 'bg-amber-500',
                                    'iconStyle' => 'bg-amber-50 text-amber-700',
                                ],
                            ];
                        @endphp

                        <div class="grid gap-4 p-5 sm:p-6">
                            @foreach ($ageGroups as $group)
                                @php($ageData = $statistics['age_groups'][$group['key']])
                                <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $group['iconStyle'] }}">
                                            <i class="fa-solid {{ $group['icon'] }}"></i>
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-800">{{ $group['label'] }}</h4>
                                                    <p class="mt-0.5 text-[11px] text-gray-500">{{ $group['range'] }}</p>
                                                </div>
                                                <div class="shrink-0 text-left">
                                                    <strong class="block text-lg font-extrabold text-slate-800">
                                                        {{ number_format($ageData['count']) }}
                                                    </strong>
                                                    <span class="text-xs font-bold text-gray-500">
                                                        {{ $ageData['percentage'] }}%
                                                    </span>
                                                </div>
                                            </div>

                                            <div
                                                class="mt-3 h-2.5 overflow-hidden rounded-full bg-gray-200"
                                                role="progressbar"
                                                aria-label="{{ $group['label'] }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                                aria-valuenow="{{ $ageData['percentage'] }}">
                                                <div
                                                    class="h-full rounded-full {{ $group['bar'] }} transition-all duration-500"
                                                    style="width: {{ $ageData['percentage'] }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($statistics['age_groups']['unclassified'] > 0)
                            <div
                                class="border-t border-gray-100 bg-gray-50 px-5 py-3 text-xs text-gray-500 sm:px-6">
                                <i class="fa-solid fa-circle-info ml-1 text-gray-400"></i>
                                استُبعد {{ number_format($statistics['age_groups']['unclassified']) }} سجلًا لعدم وجود عمر رقمي صالح.
                            </div>
                        @endif
                    </article>
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
