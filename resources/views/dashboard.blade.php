<x-app-layout>
    <x-slot name="title">لوحة التحكم | واحة الشهداء</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="oasis-kicker">لوحة الإدارة</p>
                <h1 class="oasis-heading mt-1">لوحة التحكم</h1>
            </div>

            <a href="{{ route('front.index') }}" class="oasis-button oasis-button-outline text-xs">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>عرض الموقع العام</span>
            </a>
        </div>
    </x-slot>

    <div class="oasis-container py-8 sm:py-10">
        <!-- Welcome Band: House Green Solid Color Block (No Gradients) -->
        <section class="oasis-card-dark p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="oasis-pill-gold">
                        <i class="fa-solid fa-shield-halved"></i>
                        جلسة إدارية آمنة
                    </span>

                    <h2 class="mt-4 text-2xl sm:text-3xl font-semibold leading-tight text-white">
                        أهلاً بك، {{ Auth::user()->name ?? 'مدير النظام' }}
                    </h2>

                    <p class="mt-3 text-sm sm:text-base leading-relaxed text-white/70">
                        يمكنك من هنا إدارة سجلات الشهداء ومراجعة محتوى المنصة والوصول إلى أدوات الإدارة بسهولة وهدوء.
                    </p>
                </div>

                <div class="rounded-[12px] border border-white/15 bg-white/5 px-5 py-4 self-start lg:self-center">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-oasis-accent text-white">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-xs text-white/60">تاريخ اليوم</p>
                            <p class="mt-0.5 text-sm font-semibold text-white">
                                {{ now()->translatedFormat('l، d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="mt-8" aria-labelledby="statistics-title">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="oasis-kicker">نظرة تحليلية</p>
                    <h2 id="statistics-title" class="oasis-heading mt-1">إحصاءات السجل</h2>
                    <p class="oasis-copy mt-1">توزيع السجلات المصنّفة حسب الجنس والفئات العمرية.</p>
                </div>

                <div class="inline-flex w-fit items-center gap-3 rounded-[12px] border border-oasis-mint bg-white px-4 py-2.5 shadow-card">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-oasis-accent text-white">
                        <i class="fa-solid fa-database text-xs"></i>
                    </span>
                    <div>
                        <p class="text-[11px] font-semibold text-black/55">إجمالي السجلات</p>
                        <p class="text-lg font-bold leading-tight text-oasis-house">
                            {{ number_format($statistics['total']) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
                <!-- Gender Distribution Card -->
                <article class="oasis-card p-6 xl:col-span-2 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-black/5">
                            <div>
                                <h3 class="text-base font-semibold text-oasis-house">التوزيع حسب الجنس</h3>
                                <p class="text-xs text-black/55 mt-0.5">
                                    من أصل {{ number_format($statistics['gender']['classified']) }} سجل مصنّف
                                </p>
                            </div>
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                                <i class="fa-solid fa-chart-pie text-sm"></i>
                            </span>
                        </div>

                        <div class="grid gap-6 py-6 sm:grid-cols-[10rem_1fr] sm:items-center">
                            <div class="flex justify-center">
                                <div
                                    class="relative grid h-36 w-36 place-items-center rounded-full"
                                    style="background: {{ $statistics['gender']['classified'] > 0
                                        ? 'conic-gradient(#00754A 0 '.$statistics['gender']['male']['percentage'].'%, #cba258 '.$statistics['gender']['male']['percentage'].'% 100%)'
                                        : 'conic-gradient(#edebe9 0 100%)' }}"
                                    role="img"
                                    aria-label="الذكور {{ $statistics['gender']['male']['percentage'] }}%، الإناث {{ $statistics['gender']['female']['percentage'] }}%">
                                    <div class="grid h-24 w-24 place-items-center rounded-full bg-white text-center shadow-inner">
                                        <div>
                                            <span class="block text-xl font-bold text-oasis-house">
                                                {{ number_format($statistics['gender']['classified']) }}
                                            </span>
                                            <span class="text-[10px] font-semibold text-black/45">مصنّف</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="rounded-[12px] bg-oasis-cream p-3.5 border border-black/5">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-oasis-green">
                                        <span class="h-2.5 w-2.5 rounded-full bg-oasis-accent"></span>
                                        ذكور
                                    </div>
                                    <div class="mt-2 flex items-end justify-between">
                                        <strong class="text-lg font-bold text-oasis-house">
                                            {{ number_format($statistics['gender']['male']['count']) }}
                                        </strong>
                                        <span class="text-xs font-bold text-oasis-accent">
                                            {{ $statistics['gender']['male']['percentage'] }}%
                                        </span>
                                    </div>
                                </div>

                                <div class="rounded-[12px] bg-oasis-cream p-3.5 border border-black/5">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-[#815d20]">
                                        <span class="h-2.5 w-2.5 rounded-full bg-oasis-gold"></span>
                                        إناث
                                    </div>
                                    <div class="mt-2 flex items-end justify-between">
                                        <strong class="text-lg font-bold text-oasis-house">
                                            {{ number_format($statistics['gender']['female']['count']) }}
                                        </strong>
                                        <span class="text-xs font-bold text-oasis-gold">
                                            {{ $statistics['gender']['female']['percentage'] }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($statistics['gender']['unclassified'] > 0)
                        <div class="border-t border-black/5 pt-3 text-xs text-black/55">
                            <i class="fa-solid fa-circle-info ml-1 text-black/40"></i>
                            يوجد {{ number_format($statistics['gender']['unclassified']) }} سجل دون تصنيف جنس معتمد.
                        </div>
                    @endif
                </article>

                <!-- Age Groups Card -->
                <article class="oasis-card p-6 xl:col-span-3 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-black/5">
                            <div>
                                <h3 class="text-base font-semibold text-oasis-house">الفئات العمرية</h3>
                                <p class="text-xs text-black/55 mt-0.5">
                                    من أصل {{ number_format($statistics['age_groups']['classified']) }} سجل بعمر صالح
                                </p>
                            </div>
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                                <i class="fa-solid fa-chart-column text-sm"></i>
                            </span>
                        </div>

                        @php
                            $ageGroups = [
                                [
                                    'key' => 'children',
                                    'label' => 'الأطفال',
                                    'range' => 'أقل من 18 سنة',
                                    'icon' => 'fa-child-reaching',
                                    'bar' => 'bg-oasis-accent',
                                    'iconBg' => 'bg-oasis-mint text-oasis-green',
                                ],
                                [
                                    'key' => 'youth',
                                    'label' => 'الشباب',
                                    'range' => 'من 18 إلى 59 سنة',
                                    'icon' => 'fa-person',
                                    'bar' => 'bg-oasis-green',
                                    'iconBg' => 'bg-oasis-mint text-oasis-green',
                                ],
                                [
                                    'key' => 'elders',
                                    'label' => 'كبار السن',
                                    'range' => '60 سنة فأكثر',
                                    'icon' => 'fa-person-cane',
                                    'bar' => 'bg-oasis-gold',
                                    'iconBg' => 'bg-[#faf6ee] text-oasis-gold border border-oasis-gold/30',
                                ],
                            ];
                        @endphp

                        <div class="grid gap-4 py-5">
                            @foreach ($ageGroups as $group)
                                @php($ageData = $statistics['age_groups'][$group['key']])
                                <div class="rounded-[12px] bg-oasis-cream p-4 border border-black/5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $group['iconBg'] }}">
                                            <i class="fa-solid {{ $group['icon'] }}"></i>
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <h4 class="text-sm font-bold text-oasis-house">{{ $group['label'] }}</h4>
                                                    <p class="text-[11px] text-black/55">{{ $group['range'] }}</p>
                                                </div>
                                                <div class="shrink-0 text-left">
                                                    <strong class="block text-base font-bold text-oasis-house">
                                                        {{ number_format($ageData['count']) }}
                                                    </strong>
                                                    <span class="text-xs font-semibold text-black/50">
                                                        {{ $ageData['percentage'] }}%
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-2.5 h-2 overflow-hidden rounded-full bg-black/10" role="progressbar" aria-valuenow="{{ $ageData['percentage'] }}">
                                                <div class="h-full rounded-full {{ $group['bar'] }}" style="width: {{ $ageData['percentage'] }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($statistics['age_groups']['unclassified'] > 0)
                        <div class="border-t border-black/5 pt-3 text-xs text-black/55">
                            <i class="fa-solid fa-circle-info ml-1 text-black/40"></i>
                            استُبعد {{ number_format($statistics['age_groups']['unclassified']) }} سجلًا لعدم وجود عمر رقمي صالح.
                        </div>
                    @endif
                </article>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="mt-10">
            <div class="mb-5">
                <p class="oasis-kicker">وصول سريع</p>
                <h2 class="oasis-heading mt-1">أدوات الإدارة</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Martyrs Management -->
                <a href="{{ route('dashboard.martyr.index') }}" class="oasis-card p-6 group hover:-translate-y-0.5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-oasis-mint text-oasis-green group-hover:bg-oasis-accent group-hover:text-white transition">
                            <i class="fa-solid fa-user-shield text-base"></i>
                        </div>
                        <i class="fa-solid fa-arrow-left text-black/25 group-hover:text-oasis-accent group-hover:-translate-x-1 transition"></i>
                    </div>

                    <h3 class="mt-5 text-base font-bold text-oasis-house group-hover:text-oasis-green transition">
                        إدارة السجلات
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-black/58">
                        عرض السجلات والبحث وإضافة البيانات وتعديل التفاصيل التوثيقية.
                    </p>
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.edit') }}" class="oasis-card p-6 group hover:-translate-y-0.5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#faf6ee] text-oasis-gold border border-oasis-gold/30 group-hover:bg-oasis-gold group-hover:text-white transition">
                            <i class="fa-solid fa-user-gear text-base"></i>
                        </div>
                        <i class="fa-solid fa-arrow-left text-black/25 group-hover:text-oasis-gold group-hover:-translate-x-1 transition"></i>
                    </div>

                    <h3 class="mt-5 text-base font-bold text-oasis-house group-hover:text-oasis-green transition">
                        الملف الشخصي
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-black/58">
                        تحديث اسم المستخدم والبريد الإلكتروني وكلمة المرور للحساب.
                    </p>
                </a>

                <!-- Public Website -->
                <a href="{{ route('front.index') }}" class="oasis-card p-6 group hover:-translate-y-0.5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-oasis-cream text-oasis-house group-hover:bg-black group-hover:text-white transition">
                            <i class="fa-solid fa-globe text-base"></i>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-black/25 group-hover:text-oasis-accent transition"></i>
                    </div>

                    <h3 class="mt-5 text-base font-bold text-oasis-house group-hover:text-oasis-green transition">
                        الموقع العام
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-black/58">
                        فتح الواجهة الرئيسية الموجهة للزوار ومراجعة ظهور المحتوى.
                    </p>
                </a>
            </div>
        </section>

        <!-- Instructions & Account Overview -->
        <section class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 oasis-card p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-oasis-house">إرشادات سريعة</h2>
                        <p class="text-xs text-black/50">للحفاظ على دقة وأمان البيانات التوثيقية</p>
                    </div>
                </div>

                <div class="space-y-3 text-sm text-black/70">
                    <div class="flex items-start gap-3 rounded-[12px] bg-oasis-cream px-4 py-3 border border-black/5">
                        <i class="fa-solid fa-check mt-1 text-oasis-accent"></i>
                        <p>راجع بيانات السجل بدقة قبل الحفظ أو التعديل لضمان الأمانة التاريخية.</p>
                    </div>

                    <div class="flex items-start gap-3 rounded-[12px] bg-oasis-cream px-4 py-3 border border-black/5">
                        <i class="fa-solid fa-check mt-1 text-oasis-accent"></i>
                        <p>لا تشارك بيانات الدخول إلى لوحة الإدارة مع أي شخص غير مخول.</p>
                    </div>

                    <div class="flex items-start gap-3 rounded-[12px] bg-oasis-cream px-4 py-3 border border-black/5">
                        <i class="fa-solid fa-check mt-1 text-oasis-accent"></i>
                        <p>استخدم تسجيل الخروج عند الانتهاء، خصوصاً على الأجهزة المشتركة.</p>
                    </div>
                </div>
            </div>

            <div class="oasis-card p-6 sm:p-7 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#faf6ee] text-oasis-gold border border-oasis-gold/30">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <h2 class="text-base font-bold text-oasis-house">بيانات الحساب</h2>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-black/45">اسم المستخدم</p>
                            <p class="mt-1 font-bold text-oasis-house break-words">
                                {{ Auth::user()->name ?? '—' }}
                            </p>
                        </div>

                        <div class="border-t border-black/5"></div>

                        <div>
                            <p class="text-xs text-black/45">البريد الإلكتروني</p>
                            <p class="mt-1 font-bold text-oasis-house break-all">
                                {{ Auth::user()->email ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="oasis-button oasis-button-outline mt-6 w-full text-xs">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>تعديل البيانات</span>
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
