<x-app-layout>
    <x-slot name="header">
        <div dir="rtl">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">إدارة محتوى الصفحة الرئيسية</h2>
            <p class="mt-1 text-sm text-gray-500">اختر المحتوى الذي سيظهر للزوار بدل الاختيارات العشوائية.</p>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-emerald-50 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-emerald-950">أبرز الشهداء</h2>
                        <p class="mt-1 text-xs text-emerald-700">تظهر البطاقات الأحدث اختيارًا أولًا.</p>
                    </div>
                    <span class="rounded-full bg-emerald-700 px-3 py-1 text-sm font-bold text-white">
                        {{ $selectedMartyrs->count() }} من 4
                    </span>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
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

                                <article class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                                    <img src="{{ $profileUrl }}" alt="{{ $assignment->martyr->name_ar }}" class="h-36 w-full object-cover">
                                    <div class="space-y-3 p-4">
                                        <h3 class="font-bold text-slate-800">{{ $assignment->martyr->name_ar }}</h3>
                                        <p class="text-[11px] text-gray-500">
                                            أضيف في {{ $assignment->created_at->format('Y-m-d H:i') }}
                                        </p>
                                        <form method="POST" action="{{ route('dashboard.homepage-content.destroy', $assignment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-700 hover:text-rose-900">
                                                إزالة
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endif
                        @empty
                            <p class="col-span-full rounded-xl bg-gray-50 p-5 text-sm text-gray-500">
                                لم يتم اختيار شهداء للصفحة الرئيسية بعد.
                            </p>
                        @endforelse
                    </div>

                    @if ($selectedMartyrs->count() >= 4)
                        <p class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-800">
                            تم الوصول إلى الحد الأقصى لأبرز الشهداء.
                        </p>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                            <form method="GET" action="{{ route('dashboard.homepage-content.index') }}" class="flex flex-col gap-3 sm:flex-row">
                                <input
                                    name="martyrs_q"
                                    value="{{ $martyrSearch }}"
                                    placeholder="ابحث باسم الشهيد"
                                    class="min-w-0 flex-1 rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                <button class="rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-bold text-emerald-700 hover:bg-emerald-50">
                                    بحث
                                </button>
                            </form>

                            <form method="POST" action="{{ route('dashboard.homepage-content.store', \App\Models\FeaturedContent::SECTION_MARTYRS) }}" class="mt-4 flex flex-col gap-3 sm:flex-row">
                                @csrf
                                <select name="record_id" class="min-w-0 flex-1 rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">اختر شهيدًا</option>
                                    @foreach ($availableMartyrs as $martyr)
                                        <option value="{{ $martyr->id }}">{{ $martyr->name_ar }}{{ $martyr->national_id ? ' — '.$martyr->national_id : '' }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-800">
                                    إضافة إلى أبرز الشهداء
                                </button>
                            </form>
                            <x-input-error :messages="$errors->getBag(\App\Models\FeaturedContent::SECTION_MARTYRS)->get('record_id')" class="mt-2" />
                        </div>
                    @endif
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-emerald-50 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-emerald-950">أبرز القصص</h2>
                        <p class="mt-1 text-xs text-emerald-700">تظهر فقط القصص الحقيقية المرتبطة بشهيد.</p>
                    </div>
                    <span class="rounded-full bg-emerald-700 px-3 py-1 text-sm font-bold text-white">
                        {{ $selectedStories->count() }} من 3
                    </span>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid gap-4 md:grid-cols-3">
                        @forelse ($selectedStories as $assignment)
                            @if ($assignment->story)
                                <article class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                                    <h3 class="font-bold text-slate-800">{{ $assignment->story->title }}</h3>
                                    <p class="mt-1 text-xs font-semibold text-emerald-700">{{ $assignment->story->martyr->name_ar }}</p>
                                    <p class="mt-3 line-clamp-3 text-xs leading-6 text-gray-600">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($assignment->story->content), 150) }}
                                    </p>
                                    <p class="mt-3 text-[11px] text-gray-500">
                                        أضيفت في {{ $assignment->created_at->format('Y-m-d H:i') }}
                                    </p>
                                    <form method="POST" action="{{ route('dashboard.homepage-content.destroy', $assignment) }}" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-700 hover:text-rose-900">
                                            إزالة
                                        </button>
                                    </form>
                                </article>
                            @endif
                        @empty
                            <p class="col-span-full rounded-xl bg-gray-50 p-5 text-sm text-gray-500">
                                لم يتم اختيار قصص للصفحة الرئيسية بعد.
                            </p>
                        @endforelse
                    </div>

                    @if ($selectedStories->count() >= 3)
                        <p class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-800">
                            تم الوصول إلى الحد الأقصى لأبرز القصص.
                        </p>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                            <form method="GET" action="{{ route('dashboard.homepage-content.index') }}" class="flex flex-col gap-3 sm:flex-row">
                                <input
                                    name="stories_q"
                                    value="{{ $storySearch }}"
                                    placeholder="ابحث باسم الشهيد أو عنوان القصة"
                                    class="min-w-0 flex-1 rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                <button class="rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-bold text-emerald-700 hover:bg-emerald-50">
                                    بحث
                                </button>
                            </form>

                            <form method="POST" action="{{ route('dashboard.homepage-content.store', \App\Models\FeaturedContent::SECTION_STORIES) }}" class="mt-4 flex flex-col gap-3 sm:flex-row">
                                @csrf
                                <select name="record_id" class="min-w-0 flex-1 rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">اختر قصة</option>
                                    @foreach ($availableStories as $story)
                                        <option value="{{ $story->id }}">{{ $story->martyr->name_ar }} — {{ $story->title }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-800">
                                    إضافة إلى أبرز القصص
                                </button>
                            </form>
                            <x-input-error :messages="$errors->getBag(\App\Models\FeaturedContent::SECTION_STORIES)->get('record_id')" class="mt-2" />
                        </div>
                    @endif
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-emerald-50 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-bold text-emerald-950">أبرز صور الذكريات</h2>
                        <p class="mt-1 text-xs text-emerald-700">يمكن اختيار عدة صور للشهيد نفسه.</p>
                    </div>
                    <span class="rounded-full bg-emerald-700 px-3 py-1 text-sm font-bold text-white">
                        {{ $selectedMemoryImages->count() }} من 4
                    </span>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
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

                                <article class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                                    <img src="{{ $memoryUrl }}" alt="{{ $assignment->memoryImage->caption ?: 'صورة ذكرى' }}" class="aspect-square w-full object-cover">
                                    <div class="space-y-2 p-4">
                                        <h3 class="text-xs font-bold text-slate-800">{{ $assignment->memoryImage->martyr->name_ar }}</h3>
                                        @if (filled($assignment->memoryImage->caption))
                                            <p class="line-clamp-2 text-xs text-gray-600">{{ $assignment->memoryImage->caption }}</p>
                                        @endif
                                        <p class="text-[11px] text-gray-500">
                                            أضيفت في {{ $assignment->created_at->format('Y-m-d H:i') }}
                                        </p>
                                        <form method="POST" action="{{ route('dashboard.homepage-content.destroy', $assignment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-700 hover:text-rose-900">
                                                إزالة
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endif
                        @empty
                            <p class="col-span-full rounded-xl bg-gray-50 p-5 text-sm text-gray-500">
                                لم يتم اختيار صور ذكريات للصفحة الرئيسية بعد.
                            </p>
                        @endforelse
                    </div>

                    @if ($selectedMemoryImages->count() >= 4)
                        <p class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-800">
                            تم الوصول إلى الحد الأقصى لأبرز صور الذكريات.
                        </p>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                            <form method="GET" action="{{ route('dashboard.homepage-content.index') }}" class="flex flex-col gap-3 sm:flex-row">
                                <input
                                    name="memories_q"
                                    value="{{ $memorySearch }}"
                                    placeholder="ابحث باسم الشهيد أو وصف الصورة"
                                    class="min-w-0 flex-1 rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                <button class="rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-bold text-emerald-700 hover:bg-emerald-50">
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
                                        <label class="cursor-pointer overflow-hidden rounded-xl border border-gray-200 bg-white has-[:checked]:border-emerald-500 has-[:checked]:ring-2 has-[:checked]:ring-emerald-200">
                                            <img src="{{ $optionUrl }}" alt="{{ $memoryImage->caption ?: 'صورة ذكرى' }}" class="aspect-square w-full object-cover">
                                            <span class="block space-y-1 p-3">
                                                <span class="block text-xs font-bold text-slate-700">{{ $memoryImage->martyr->name_ar }}</span>
                                                @if (filled($memoryImage->caption))
                                                    <span class="block line-clamp-2 text-[11px] text-gray-500">{{ $memoryImage->caption }}</span>
                                                @endif
                                                <input type="radio" name="record_id" value="{{ $memoryImage->id }}" class="text-emerald-600 focus:ring-emerald-500">
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="mt-4 rounded-lg bg-emerald-700 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-800">
                                    إضافة إلى أبرز صور الذكريات
                                </button>
                            </form>
                            <x-input-error :messages="$errors->getBag(\App\Models\FeaturedContent::SECTION_MEMORY_IMAGES)->get('record_id')" class="mt-2" />
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
