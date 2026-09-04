@extends('front.layout')

@section('title', 'الرئيسية | واحة الشهداء')

@section('content')
    <!-- Hero Feature Band: House Green Solid Surface (#1E3932) -->
    <section class="oasis-band overflow-hidden">
        <div class="oasis-container grid min-h-[30rem] items-center gap-10 py-12 sm:py-16 lg:grid-cols-[1.2fr_.8fr] lg:py-20">
            <div class="max-w-2xl">
                <span class="oasis-pill-gold">
                    <i class="fa-solid fa-certificate text-[10px]"></i>
                    سجل الذاكرة الوطنية
                </span>
                <h1 class="mt-4 text-4xl font-semibold leading-tight text-white sm:text-5xl lg:text-6xl">
                    ذاكرة تُحفظ، وقصصٌ تبقى حيّة.
                </h1>
                <p class="mt-6 max-w-xl text-base leading-8 text-white/70 sm:text-lg">
                    منصة هادئة للبحث في السجل التوثيقي، قراءة القصص، وحفظ الذكريات باحترام ووضوح للأسر والباحثين.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('front.search') }}" class="oasis-button oasis-button-light">
                        <span>ابحث في السجل</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </a>
                    <a href="#featured-stories" class="oasis-button oasis-button-on-dark-outline">
                        اكتشف القصص
                    </a>
                </div>
            </div>

            <!-- Right Column Visual Graphic -->
            <div class="relative mx-auto grid aspect-square w-full max-w-xs place-items-center rounded-full border border-white/15 bg-oasis-uplift p-8 sm:max-w-sm shadow-2xl">
                <div class="absolute inset-5 rounded-full border border-white/10"></div>
                <div class="absolute inset-12 rounded-full border border-white/10"></div>
                <div class="relative text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-oasis-gold shadow-sm">
                        <i class="fa-solid fa-book-open text-3xl"></i>
                    </div>
                    <p class="mt-4 text-xl font-bold tracking-tight text-white">واحة الشهداء</p>
                    <p class="mt-1 text-xs text-white/70">مساحة توثيقية إنسانية</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Martyrs Section: Pure White Canvas -->
    <section class="bg-white">
        <div class="oasis-container oasis-section">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <p class="oasis-kicker">مختارات موثقة</p>
                    <h2 class="oasis-heading mt-1">أبرز السجلات</h2>
                    <p class="oasis-copy mt-1">سجلات مختارة من قاعدة البيانات التوثيقية الوطنية.</p>
                </div>
                <a href="{{ route('front.search') }}" class="oasis-button oasis-button-outline self-start text-xs sm:self-auto">
                    <span>عرض السجل كاملًا</span>
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($featuredMartyrs as $martyr)
                    @php
                        $path = $martyr->profileImg?->img_path;
                        $image = filled($path)
                            ? asset('assets/img/'.ltrim(str_replace('\\', '/', $path), '/'))
                            : asset('assets/img/No-photo-m.png');
                    @endphp
                    <article class="oasis-card overflow-hidden group flex flex-col justify-between hover:-translate-y-1">
                        <div class="flex flex-col items-center justify-center bg-oasis-cream p-6 text-center border-b border-black/5">
                            <div class="relative">
                                <img class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-card transition duration-300 group-hover:scale-105" src="{{ $image }}" alt="صورة {{ $martyr->name_ar }}" loading="lazy">
                            </div>
                            <h3 class="mt-4 text-base font-bold text-oasis-house">{{ $martyr->name_ar }}</h3>
                            @if($martyr->name_en)
                                <p class="mt-0.5 text-xs text-black/50" dir="ltr">{{ $martyr->name_en }}</p>
                            @endif
                            <div class="mt-4">
                                <span class="oasis-pill-mint text-xs">
                                    العمر: {{ $martyr->age ? $martyr->age . ' سنة' : 'غير مسجل' }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('martyr', $martyr) }}" class="flex items-center justify-between px-5 py-4 text-xs font-bold text-oasis-accent transition hover:bg-oasis-mint/40 hover:text-oasis-green">
                            <span>عرض السجل التوثيقي</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </article>
                @empty
                    <p class="oasis-empty col-span-full">لم يتم اختيار سجلات مميزة حتى الآن.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Stories Section: Warm Neutral Canvas (#f2f0eb) -->
    <section id="featured-stories" class="bg-oasis-cream">
        <div class="oasis-container oasis-section">
            <div class="max-w-2xl">
                <p class="oasis-kicker">سيرة وحكاية</p>
                <h2 class="oasis-heading mt-1">قصص تستحق أن تُروى</h2>
                <p class="oasis-copy mt-1">لمحات إنسانية منتقاة بعناية من أرشيف الواحة لحفظ الذكرى.</p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @forelse ($featuredStories as $story)
                    <article class="oasis-card flex min-h-64 flex-col p-6 sm:p-7 hover:-translate-y-1">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                            <i class="fa-solid fa-quote-right text-sm"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-oasis-house">{{ $story->title }}</h3>
                        <p class="mt-3 line-clamp-3 text-sm leading-7 text-black/60">
                            {{ Str::limit(strip_tags($story->content), 180) }}
                        </p>
                        <div class="mt-auto flex items-center justify-between border-t border-black/5 pt-5 text-xs">
                            <span class="font-semibold text-black/55">{{ $story->martyr?->name_ar }}</span>
                            <a class="font-bold text-oasis-accent hover:text-oasis-green transition" href="{{ route('martyr', $story->martyr) }}">
                                اقرأ القصة ←
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="oasis-empty col-span-full">لا توجد قصص مميزة لعرضها الآن.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Search Callout Feature Band: House Green (#1E3932) -->
    <section class="oasis-band">
        <div class="oasis-container oasis-section grid items-center gap-8 lg:grid-cols-[1fr_1.1fr]">
            <div>
                <span class="oasis-pill-gold">
                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                    البحث في السجل
                </span>
                <h2 class="mt-3 text-3xl font-semibold leading-tight text-white sm:text-4xl">
                    الوصول إلى المعلومة يجب أن يكون هادئًا وبسيطًا.
                </h2>
                <p class="mt-4 max-w-xl text-base leading-8 text-white/70">
                    ابحث بالاسم العربي أو الرقم الوطني، ثم انتقل مباشرة إلى الصفحة التي تجمع تفاصيل السجل والقصص والذكريات.
                </p>
                <a href="{{ route('front.search') }}" class="oasis-button oasis-button-light mt-7">
                    <span>ابدأ البحث المتقدم</span>
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
            </div>

            <div class="oasis-card p-6 sm:p-8">
                <form action="{{ route('front.search') }}" method="GET" class="space-y-4">
                    <label for="home_search" class="oasis-label">
                        ابحث بالاسم أو الرقم الوطني
                    </label>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input id="home_search" type="search" name="q" class="oasis-input !mt-0 flex-1" placeholder="مثال: الاسم الرباعي أو الرقم الوطني">
                        <button type="submit" class="oasis-button oasis-button-primary shrink-0">
                            <span>بحث</span>
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </div>
                    <p class="text-xs leading-6 text-black/50">
                        ستنتقل إلى صفحة البحث لإظهار النتائج مع إمكانية التصفح المباشر.
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Memories Photo Album: Ceramic / White -->
    <section class="bg-white">
        <div class="oasis-container oasis-section">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <p class="oasis-kicker">ألبوم الذاكرة</p>
                    <h2 class="oasis-heading mt-1">صور من الذكريات</h2>
                    <p class="oasis-copy mt-1">صور تذكارية موثقة تحكي لمحات من السيرة.</p>
                </div>
                <a href="{{ route('front.search') }}" class="oasis-button oasis-button-outline self-start text-xs sm:self-auto">
                    <span>اكتشف السجلات</span>
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-4">
                @forelse ($featuredMemoryImages as $memory)
                    @php
                        $memoryPath = $memory->img_path;
                        $memoryUrl = filled($memoryPath)
                            ? asset('assets/img/'.ltrim(str_replace('\\', '/', $memoryPath), '/'))
                            : asset('assets/img/No-photo-m.png');
                    @endphp
                    <a href="{{ route('martyr', $memory->martyr) }}" class="group oasis-card overflow-hidden hover:-translate-y-1">
                        <div class="overflow-hidden aspect-square">
                            <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ $memoryUrl }}" alt="{{ $memory->caption ?: 'صورة ذكرى' }}" loading="lazy">
                        </div>
                        <div class="p-4 bg-white">
                            <p class="truncate text-xs font-bold text-oasis-house">{{ $memory->caption ?: $memory->martyr?->name_ar }}</p>
                            <p class="mt-1 text-[11px] text-black/50">{{ $memory->martyr?->name_ar }}</p>
                        </div>
                    </a>
                @empty
                    <p class="oasis-empty col-span-full">لا توجد صور مميزة لعرضها الآن.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
