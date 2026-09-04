<x-app-layout>
    <x-slot name="header">
        <div dir="rtl">
            <p class="oasis-kicker">تخصيص الواجهة</p>
            <h1 class="oasis-heading mt-1">إدارة محتوى الصفحة الرئيسية</h1>
            <p class="oasis-copy mt-1">اختر السجلات والقصص والصور التي تظهر في أقسام الواجهة الرئيسية للزوار.</p>
        </div>
    </x-slot>

    <div class="oasis-container py-8 sm:py-12 space-y-10" dir="rtl">

        <!-- ===================================================== -->
        <!-- SECTION 1: FEATURED MARTYRS (أبرز الشهداء)            -->
        <!-- ===================================================== -->
        <section class="oasis-card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/[0.06] bg-oasis-mint/20 px-6 py-5">
                <div>
                    <h2 class="font-serif text-lg font-bold text-oasis-house">أبرز الشهداء</h2>
                    <p class="mt-0.5 text-xs text-oasis-black/60">تظهر البطاقات الأحدث اختيارًا أولًا (الحد الأقصى 4).</p>
                </div>
                <span class="oasis-pill {{ $selectedMartyrs->count() >= 4 ? 'oasis-pill-gold' : 'oasis-pill-house' }}">
                    {{ $selectedMartyrs->count() }} من 4
                </span>
            </div>

            <div class="space-y-6 p-6 sm:p-8">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($selectedMartyrs as $assignment)
                        @if ($assignment->martyr)
                            @php
                                $profilePath = $assignment->martyr->profileImg?->img_path;
                                $profileExists = filled($profilePath)
                                    && \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($profilePath);
                                $profileUrl = $profileExists
                                    ? asset('assets/img/' . ltrim(str_replace('\\', '/', $profilePath), '/'))
                                    : asset('assets/img/No-photo-m.png');
                            @endphp

                            <article class="flex flex-col overflow-hidden rounded-xl border border-black/[0.06] bg-white shadow-sm transition hover:shadow-card">
                                <div class="relative aspect-square w-full overflow-hidden bg-oasis-cream">
                                    <img src="{{ $profileUrl }}" alt="{{ $assignment->martyr->name_ar }}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex flex-1 flex-col justify-between p-4">
                                    <div>
                                        <h3 class="font-serif text-sm font-bold text-oasis-house">{{ $assignment->martyr->name_ar }}</h3>
                                        <p class="mt-1 text-[11px] text-oasis-black/50">
                                            أضيف في {{ $assignment->created_at->format('Y-m-d H:i') }}
                                        </p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-black/[0.05]">
                                        <form method="POST" action="{{ route('dashboard.homepage-content.destroy', $assignment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="oasis-button oasis-button-outline w-full !min-h-[34px] !py-1 !text-xs !border-red-200 !text-red-700 hover:!bg-red-50">
                                                <i class="fa-solid fa-trash ml-1 text-[10px]"></i>
                                                إزالة
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-black/10 bg-oasis-cream/40 p-8 text-center text-sm text-oasis-black/60">
                            <i class="fa-regular fa-id-badge mb-2 block text-2xl text-oasis-black/30"></i>
                            لم يتم اختيار شهداء للصفحة الرئيسية بعد.
                        </div>
                    @endforelse
                </div>

                @if ($selectedMartyrs->count() >= 4)
                    <div class="oasis-card-gold p-4 text-xs font-bold text-oasis-black/80">
                        <i class="fa-solid fa-circle-info ml-1.5 text-oasis-gold"></i>
                        تم الوصول إلى الحد الأقصى لأبرز الشهداء (4). يمكنك إزالة أحد السجلات لاختيار سجل آخر.
                    </div>
                @else
                    <div class="rounded-xl border border-black/[0.06] bg-oasis-cream/30 p-5">
                        <h4 class="font-serif text-xs font-bold uppercase tracking-wider text-oasis-black/60 mb-3">إضافة شهيد إلى القائمة</h4>
                        <form method="GET" action="{{ route('dashboard.homepage-content.index') }}" class="flex flex-col gap-3 sm:flex-row">
                            <input
                                name="martyrs_q"
                                value="{{ $martyrSearch }}"
                                placeholder="ابحث باسم الشهيد أو الرقم الوطني..."
                                class="oasis-input flex-1"
                            >
                            <button type="submit" class="oasis-button oasis-button-outline !min-h-[44px] !px-5 !text-xs">
                                <i class="fa-solid fa-magnifying-glass ml-1.5"></i>
                                بحث
                            </button>
                        </form>

                        <form method="POST" action="{{ route('dashboard.homepage-content.store', \App\Models\FeaturedContent::SECTION_MARTYRS) }}" class="mt-4 flex flex-col gap-3 sm:flex-row">
                            @csrf
                            <select name="record_id" class="oasis-input flex-1">
                                <option value="">اختر شهيدًا من النتائج...</option>
                                @foreach ($availableMartyrs as $martyr)
                                    <option value="{{ $martyr->id }}">{{ $martyr->name_ar }}{{ $martyr->national_id ? ' — '.$martyr->national_id : '' }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="oasis-button oasis-button-primary !min-h-[44px] !px-6 !text-xs">
                                <i class="fa-solid fa-plus ml-1.5"></i>
                                إضافة إلى أبرز الشهداء
                            </button>
                        </form>
                        <x-input-error :messages="$errors->getBag(\App\Models\FeaturedContent::SECTION_MARTYRS)->get('record_id')" class="mt-2" />
                    </div>
                @endif
            </div>
        </section>

        <!-- ===================================================== -->
        <!-- SECTION 2: FEATURED STORIES (أبرز القصص)              -->
        <!-- ===================================================== -->
        <section class="oasis-card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/[0.06] bg-oasis-mint/20 px-6 py-5">
                <div>
                    <h2 class="font-serif text-lg font-bold text-oasis-house">أبرز القصص</h2>
                    <p class="mt-0.5 text-xs text-oasis-black/60">تظهر فقط القصص المرتبطة بشهيد (الحد الأقصى 3).</p>
                </div>
                <span class="oasis-pill {{ $selectedStories->count() >= 3 ? 'oasis-pill-gold' : 'oasis-pill-house' }}">
                    {{ $selectedStories->count() }} من 3
                </span>
            </div>

            <div class="space-y-6 p-6 sm:p-8">
                <div class="grid gap-5 md:grid-cols-3">
                    @forelse ($selectedStories as $assignment)
                        @if ($assignment->story)
                            <article class="flex flex-col justify-between rounded-xl border border-black/[0.06] bg-white p-5 shadow-sm transition hover:shadow-card">
                                <div>
                                    <p class="text-xs font-bold text-oasis-accent">{{ $assignment->story->martyr->name_ar }}</p>
                                    <h3 class="mt-1 font-serif text-base font-bold text-oasis-house">{{ $assignment->story->title }}</h3>
                                    <p class="mt-3 line-clamp-3 text-xs leading-6 text-oasis-black/70">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($assignment->story->content), 150) }}
                                    </p>
                                    <p class="mt-3 text-[11px] text-oasis-black/50">
                                        أضيفت في {{ $assignment->created_at->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                                <div class="mt-5 pt-3 border-t border-black/[0.05]">
                                    <form method="POST" action="{{ route('dashboard.homepage-content.destroy', $assignment) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="oasis-button oasis-button-outline w-full !min-h-[34px] !py-1 !text-xs !border-red-200 !text-red-700 hover:!bg-red-50">
                                            <i class="fa-solid fa-trash ml-1 text-[10px]"></i>
                                            إزالة
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endif
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-black/10 bg-oasis-cream/40 p-8 text-center text-sm text-oasis-black/60">
                            <i class="fa-solid fa-book-open mb-2 block text-2xl text-oasis-black/30"></i>
                            لم يتم اختيار قصص للصفحة الرئيسية بعد.
                        </div>
                    @endforelse
                </div>

                @if ($selectedStories->count() >= 3)
                    <div class="oasis-card-gold p-4 text-xs font-bold text-oasis-black/80">
                        <i class="fa-solid fa-circle-info ml-1.5 text-oasis-gold"></i>
                        تم الوصول إلى الحد الأقصى لأبرز القصص (3). يمكنك إزالة إحدى القصص لاختيار قصة أخرى.
                    </div>
                @else
                    <div class="rounded-xl border border-black/[0.06] bg-oasis-cream/30 p-5">
                        <h4 class="font-serif text-xs font-bold uppercase tracking-wider text-oasis-black/60 mb-3">إضافة قصة إلى القائمة</h4>
                        <form method="GET" action="{{ route('dashboard.homepage-content.index') }}" class="flex flex-col gap-3 sm:flex-row">
                            <input
                                name="stories_q"
                                value="{{ $storySearch }}"
                                placeholder="ابحث باسم الشهيد أو عنوان القصة..."
                                class="oasis-input flex-1"
                            >
                            <button type="submit" class="oasis-button oasis-button-outline !min-h-[44px] !px-5 !text-xs">
                                <i class="fa-solid fa-magnifying-glass ml-1.5"></i>
                                بحث
                            </button>
                        </form>

                        <form method="POST" action="{{ route('dashboard.homepage-content.store', \App\Models\FeaturedContent::SECTION_STORIES) }}" class="mt-4 flex flex-col gap-3 sm:flex-row">
                            @csrf
                            <select name="record_id" class="oasis-input flex-1">
                                <option value="">اختر قصة من النتائج...</option>
                                @foreach ($availableStories as $story)
                                    <option value="{{ $story->id }}">{{ $story->martyr->name_ar }} — {{ $story->title }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="oasis-button oasis-button-primary !min-h-[44px] !px-6 !text-xs">
                                <i class="fa-solid fa-plus ml-1.5"></i>
                                إضافة إلى أبرز القصص
                            </button>
                        </form>
                        <x-input-error :messages="$errors->getBag(\App\Models\FeaturedContent::SECTION_STORIES)->get('record_id')" class="mt-2" />
                    </div>
                @endif
            </div>
        </section>

        <!-- ===================================================== -->
        <!-- SECTION 3: FEATURED MEMORIES (أبرز صور الذكريات)       -->
        <!-- ===================================================== -->
        <section class="oasis-card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/[0.06] bg-oasis-mint/20 px-6 py-5">
                <div>
                    <h2 class="font-serif text-lg font-bold text-oasis-house">أبرز صور الذكريات</h2>
                    <p class="mt-0.5 text-xs text-oasis-black/60">يمكن اختيار عدة صور للشهيد نفسه (الحد الأقصى 4).</p>
                </div>
                <span class="oasis-pill {{ $selectedMemoryImages->count() >= 4 ? 'oasis-pill-gold' : 'oasis-pill-house' }}">
                    {{ $selectedMemoryImages->count() }} من 4
                </span>
            </div>

            <div class="space-y-6 p-6 sm:p-8">
                <div class="grid grid-cols-2 gap-5 lg:grid-cols-4">
                    @forelse ($selectedMemoryImages as $assignment)
                        @if ($assignment->memoryImage)
                            @php
                                $memoryPath = $assignment->memoryImage->img_path;
                                $memoryExists = filled($memoryPath)
                                    && \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($memoryPath);
                                $memoryUrl = $memoryExists
                                    ? asset('assets/img/' . ltrim(str_replace('\\', '/', $memoryPath), '/'))
                                    : asset('assets/img/No-photo-m.png');
                            @endphp

                            <article class="flex flex-col overflow-hidden rounded-xl border border-black/[0.06] bg-white shadow-sm transition hover:shadow-card">
                                <div class="relative aspect-square w-full overflow-hidden bg-oasis-cream">
                                    <img src="{{ $memoryUrl }}" alt="{{ $assignment->memoryImage->caption ?: 'صورة ذكرى' }}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex flex-1 flex-col justify-between p-4">
                                    <div>
                                        <h3 class="font-serif text-xs font-bold text-oasis-house">{{ $assignment->memoryImage->martyr->name_ar }}</h3>
                                        @if (filled($assignment->memoryImage->caption))
                                            <p class="mt-1 line-clamp-2 text-xs text-oasis-black/70">{{ $assignment->memoryImage->caption }}</p>
                                        @endif
                                        <p class="mt-2 text-[11px] text-oasis-black/50">
                                            أضيفت في {{ $assignment->created_at->format('Y-m-d H:i') }}
                                        </p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-black/[0.05]">
                                        <form method="POST" action="{{ route('dashboard.homepage-content.destroy', $assignment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="oasis-button oasis-button-outline w-full !min-h-[34px] !py-1 !text-xs !border-red-200 !text-red-700 hover:!bg-red-50">
                                                <i class="fa-solid fa-trash ml-1 text-[10px]"></i>
                                                إزالة
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-black/10 bg-oasis-cream/40 p-8 text-center text-sm text-oasis-black/60">
                            <i class="fa-regular fa-image mb-2 block text-2xl text-oasis-black/30"></i>
                            لم يتم اختيار صور ذكريات للصفحة الرئيسية بعد.
                        </div>
                    @endforelse
                </div>

                @if ($selectedMemoryImages->count() >= 4)
                    <div class="oasis-card-gold p-4 text-xs font-bold text-oasis-black/80">
                        <i class="fa-solid fa-circle-info ml-1.5 text-oasis-gold"></i>
                        تم الوصول إلى الحد الأقصى لأبرز صور الذكريات (4). يمكنك إزالة إحدى الصور لاختيار صورة أخرى.
                    </div>
                @else
                    <div class="rounded-xl border border-black/[0.06] bg-oasis-cream/30 p-5">
                        <h4 class="font-serif text-xs font-bold uppercase tracking-wider text-oasis-black/60 mb-3">إضافة صورة إلى القائمة</h4>
                        <form method="GET" action="{{ route('dashboard.homepage-content.index') }}" class="flex flex-col gap-3 sm:flex-row">
                            <input
                                name="memories_q"
                                value="{{ $memorySearch }}"
                                placeholder="ابحث باسم الشهيد أو وصف الصورة..."
                                class="oasis-input flex-1"
                            >
                            <button type="submit" class="oasis-button oasis-button-outline !min-h-[44px] !px-5 !text-xs">
                                <i class="fa-solid fa-magnifying-glass ml-1.5"></i>
                                بحث
                            </button>
                        </form>

                        <form method="POST" action="{{ route('dashboard.homepage-content.store', \App\Models\FeaturedContent::SECTION_MEMORY_IMAGES) }}" class="mt-5">
                            @csrf
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                                @foreach ($availableMemoryImages as $memoryImage)
                                    @php
                                        $optionPath = $memoryImage->img_path;
                                        $optionExists = filled($optionPath)
                                            && \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($optionPath);
                                        $optionUrl = $optionExists
                                            ? asset('assets/img/' . ltrim(str_replace('\\', '/', $optionPath), '/'))
                                            : asset('assets/img/No-photo-m.png');
                                    @endphp
                                    <label class="group relative cursor-pointer overflow-hidden rounded-xl border border-black/[0.08] bg-white transition hover:shadow-card has-[:checked]:border-oasis-accent has-[:checked]:ring-2 has-[:checked]:ring-oasis-mint">
                                        <img src="{{ $optionUrl }}" alt="{{ $memoryImage->caption ?: 'صورة ذكرى' }}" class="aspect-square w-full object-cover">
                                        <span class="block space-y-1 p-3">
                                            <span class="block text-xs font-bold text-oasis-house">{{ $memoryImage->martyr->name_ar }}</span>
                                            @if (filled($memoryImage->caption))
                                                <span class="block line-clamp-2 text-[11px] text-oasis-black/60">{{ $memoryImage->caption }}</span>
                                            @endif
                                            <span class="mt-2 flex items-center gap-2 pt-1 border-t border-black/[0.05]">
                                                <input type="radio" name="record_id" value="{{ $memoryImage->id }}" class="text-oasis-accent focus:ring-oasis-mint">
                                                <span class="text-[11px] font-medium text-oasis-black/70">تحديد هذه الصورة</span>
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-5 flex justify-end">
                                <button type="submit" class="oasis-button oasis-button-primary !min-h-[44px] !px-6 !text-xs">
                                    <i class="fa-solid fa-plus ml-1.5"></i>
                                    إضافة إلى أبرز صور الذكريات
                                </button>
                            </div>
                        </form>
                        <x-input-error :messages="$errors->getBag(\App\Models\FeaturedContent::SECTION_MEMORY_IMAGES)->get('record_id')" class="mt-2" />
                    </div>
                @endif
            </div>
        </section>

    </div>
</x-app-layout>
