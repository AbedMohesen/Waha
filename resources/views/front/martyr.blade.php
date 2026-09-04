@extends('front.layout')

@section('title', ($d->name_ar ?? 'السجل').' | واحة الشهداء')

@section('content')
    @php
        $profilePath = $d->profileImg?->img_path;
        $profileUrl = filled($profilePath)
            ? asset('assets/img/'.ltrim(str_replace('\\', '/', $profilePath), '/'))
            : asset('assets/img/No-photo-m.png');
    @endphp

    <!-- Top Feature Band: House Green Solid Surface (#1E3932) -->
    <section class="oasis-band">
        <div class="oasis-container py-10 sm:py-14">
            <!-- Breadcrumbs -->
            <nav class="mb-6 flex items-center gap-2 text-xs text-white/60" aria-label="مسار التصفح">
                <a href="{{ route('front.index') }}" class="hover:text-white transition">الرئيسية</a>
                <span class="text-white/30">/</span>
                <a href="{{ route('front.search') }}" class="hover:text-white transition">السجل</a>
                <span class="text-white/30">/</span>
                <span class="text-white/90 font-medium">{{ $d->name_ar }}</span>
            </nav>

            <div class="grid gap-8 md:grid-cols-[auto_1fr] md:items-center">
                <div class="relative mx-auto md:mx-0">
                    <img class="h-36 w-36 rounded-full border-4 border-white/20 object-cover shadow-card" src="{{ $profileUrl }}" alt="صورة {{ $d->name_ar }}">
                </div>

                <div class="text-center md:text-right">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <span class="oasis-pill-gold">
                            <i class="fa-solid fa-certificate text-[10px]"></i>
                            السجل التوثيقي الوطني
                        </span>
                        <a href="{{ route('front.search') }}" class="text-xs font-semibold text-white/70 hover:text-white transition">
                            <i class="ml-1 fa-solid fa-arrow-right"></i>
                            العودة للبحث
                        </a>
                    </div>

                    <h1 class="mt-3 text-3xl font-semibold leading-tight text-white sm:text-4xl lg:text-5xl">
                        {{ $d->name_ar }}
                    </h1>

                    @if($d->name_en)
                        <p class="mt-1.5 text-sm font-medium text-white/70" dir="ltr">{{ $d->name_en }}</p>
                    @endif

                    <div class="mt-5 flex flex-wrap justify-center gap-2 md:justify-start">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3.5 py-1 text-xs font-medium text-white/90">
                            <i class="fa-solid fa-id-card text-[10px] text-white/60"></i>
                            الرقم الوطني: {{ $d->national_id ?: 'غير مسجل' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3.5 py-1 text-xs font-medium text-white/90">
                            <i class="fa-solid fa-hourglass-half text-[10px] text-white/60"></i>
                            العمر: {{ $d->age ? $d->age . ' سنة' : 'غير مسجل' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3.5 py-1 text-xs font-medium text-white/90">
                            <i class="fa-solid fa-calendar-day text-[10px] text-white/60"></i>
                            تاريخ الميلاد: {{ $d->born ?: 'غير مسجل' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area: Warm Neutral Canvas (#f2f0eb) -->
    <div class="oasis-container oasis-section">
        <div class="grid gap-8 lg:grid-cols-[1.4fr_.85fr]">
            <!-- Left Column: Story & Photo Album -->
            <div class="space-y-8">
                <!-- Story Card -->
                <article class="oasis-card p-6 sm:p-8">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                            <i class="fa-solid fa-book-open"></i>
                        </span>
                        <p class="oasis-kicker">سيرة وحكاية</p>
                    </div>

                    <h2 class="oasis-heading mt-3">{{ $d->story?->title ?: 'قصة لم تُضف بعد' }}</h2>

                    <div class="oasis-copy mt-5 whitespace-pre-line text-base leading-8 text-black/75">
                        {{ $d->story?->content ?: 'لا توجد قصة منشورة لهذا السجل في الوقت الحالي.' }}
                    </div>
                </article>

                <!-- Memories Images -->
                <section>
                    <div class="flex items-end justify-between gap-4 pb-2 border-b border-black/5">
                        <div>
                            <p class="oasis-kicker">ألبوم الذاكرة</p>
                            <h2 class="oasis-heading mt-1">صور وذكريات</h2>
                        </div>
                        <span class="oasis-pill-mint">{{ $d->momeriesImg->count() }} صورة</span>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4 md:grid-cols-3">
                        @forelse($d->momeriesImg as $memory)
                            @php
                                $memoryPath = $memory->img_path;
                                $memoryUrl = filled($memoryPath)
                                    ? asset('assets/img/'.ltrim(str_replace('\\', '/', $memoryPath), '/'))
                                    : asset('assets/img/No-photo-m.png');
                            @endphp
                            <figure class="oasis-card overflow-hidden group">
                                <img class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105" src="{{ $memoryUrl }}" alt="{{ $memory->caption ?: 'صورة ذكرى' }}" loading="lazy">
                                @if($memory->caption)
                                    <figcaption class="p-3 text-xs leading-6 text-black/60 bg-white">
                                        {{ $memory->caption }}
                                    </figcaption>
                                @endif
                            </figure>
                        @empty
                            <p class="oasis-empty col-span-full">لا توجد صور ذكريات مضافة لهذا السجل.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Right Column: Condolences & Form -->
            <aside class="space-y-6">
                <!-- Published Condolences Card -->
                <section class="oasis-card p-6 sm:p-7">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                            <i class="fa-solid fa-heart"></i>
                        </span>
                        <p class="oasis-kicker">تعزيات منشورة</p>
                    </div>

                    <h2 class="oasis-heading mt-2">رسائل تذكارية</h2>

                    <div class="mt-6 space-y-4">
                        @forelse($approvedCondolences as $condolence)
                            <article class="rounded-[12px] bg-oasis-cream p-4 border border-black/5">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-sm font-bold text-oasis-house">{{ $condolence->author_name }}</h3>
                                    <time class="text-[11px] text-black/45" datetime="{{ $condolence->created_at->toIso8601String() }}">
                                        {{ $condolence->created_at->format('Y-m-d') }}
                                    </time>
                                </div>
                                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-black/70">
                                    {{ $condolence->content }}
                                </p>
                            </article>
                        @empty
                            <div class="py-6 text-center text-sm leading-7 text-black/55">
                                <i class="fa-regular fa-comment-dots text-2xl text-black/20 mb-2 block"></i>
                                لا توجد تعزيات منشورة حتى الآن.
                            </div>
                        @endforelse
                    </div>

                    @if($approvedCondolences->hasPages())
                        <div class="mt-6">
                            {{ $approvedCondolences->links() }}
                        </div>
                    @endif
                </section>

                <!-- Condolence Form -->
                @if ($hasSubmittedCondolence)
                    <div class="oasis-alert-success flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-oasis-accent"></i>
                        <div>
                            <p class="font-bold">لقد أرسلت تعزية لهذا الشهيد مسبقًا.</p>
                            <p class="mt-1 text-xs leading-6">ستظهر رسالتك بعد مراجعتها واعتمادها.</p>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('martyr.condolences.store', $d) }}" class="oasis-card p-6 sm:p-7">
                        @csrf
                        <p class="oasis-kicker">مشاركة رسالة</p>
                        <h2 class="oasis-heading mt-1">إرسال تعزية</h2>
                        <p class="mt-1 text-xs leading-6 text-black/55">يمكنك كتابة رسالة تعزية، وستظهر بعد مراجعتها.</p>

                        @if($errors->condolence->has('content'))
                            <div class="oasis-alert-error mt-4">
                                {{ $errors->condolence->first('content') }}
                            </div>
                        @endif

                        <div class="mt-5">
                            <label class="oasis-label" for="condolence_author_name">
                                الاسم الكريم <span class="font-normal text-black/45">(اختياري)</span>
                            </label>
                            <input id="condolence_author_name" name="author_name" value="{{ old('author_name') }}" maxlength="255" class="oasis-input" type="text" placeholder="اسمك الكريم">
                        </div>

                        <div class="mt-4">
                            <label class="oasis-label" for="condolence_content">
                                رسالة التعزية
                            </label>
                            <textarea id="condolence_content" name="content" rows="4" maxlength="1000" required class="oasis-input resize-y" placeholder="اكتب رسالتك الصادقة هنا...">{{ old('content') }}</textarea>
                        </div>

                        <button type="submit" class="oasis-button oasis-button-primary mt-5 w-full">
                            <span>إرسال للمراجعة</span>
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                @endif
            </aside>
        </div>
    </div>
@endsection
