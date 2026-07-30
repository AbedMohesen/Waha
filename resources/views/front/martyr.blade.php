<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/img/icon.png') }}">
    <title>الشهيد {{ explode(" ", $d->name_ar)[0] . " " . explode(" ", $d->name_ar)[count(explode(" ", $d->name_ar)) - 1]}}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome للأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- خطوط جوجل (أميري للآيات وNoto Kufi للنصوص) -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital@1&family=Noto+Kufi+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
        .quran-text {
            font-family: 'Amiri', serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 scroll-smooth">

    <!-- 1. القسم الرئيسي (Hero Section) -->
    <header
        class="relative bg-gradient-to-br from-green-600 to-teal-700 text-white overflow-hidden py-16 px-4 text-center shadow-md">
        <!-- خلفية حيوية خفيفة ناعمة -->
        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px]">
        </div>

        <div class="relative z-10 max-w-3xl mx-auto">
            <!-- الآية القرآنية بوضوح مشرق -->
            <p class="quran-text text-xl md:text-2xl text-sky-100 mb-8 font-medium drop-shadow-sm">
                "وَلَا تَحْسَبَنَّ الَّذِينَ قُتِلُوا فِي سَبِيلِ اللَّهِ أَمْوَاتًا ۚ بَلْ أَحْيَاءٌ عِندَ رَبِّهِمْ
                يُرْزَقُونَ"
            </p>

            <!-- إطار الصورة الشخصية بألوان طبيعية حية وبدون رمادي -->
            <div
                class="w-36 h-36 mx-auto rounded-full border-4 border-white/40 p-1 bg-white/20 shadow-2xl mb-6 overflow-hidden transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('assets/img/' . ($d->profileImg->img_path ?? 'No-photo-m.png')) }}" alt="صورة الشهيد"
                    class="w-full h-full object-cover">
            </div>

            <!-- أسماء الشهيد (العربي والإنجليزي) -->
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-2 drop-shadow-md">{{ $d->name_ar }}</h1>
            <p class="text-sky-100/90 text-lg font-medium tracking-wide mb-8 font-sans drop-shadow-sm">{{ $d->name_en }}</p>

            <!-- بطاقة معلومات الشهيد منسقة بشكل متناسق ومشرق -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 max-w-xl mx-auto border border-white/20 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center text-sm">

                    <!-- رقم الهوية -->
                    <div class="bg-white/5 rounded-xl p-3 border border-white/5 col-span-2 md:col-span-1">
                        <span class="text-sky-200 block text-xs mb-1"><i class="fa-solid fa-id-card ml-1 text-sky-300"></i>
                            رقم الهوية</span>
                        <span
                            class="font-semibold text-white font-mono tracking-wider text-xs md:text-sm">{{ $d->national_id }}</span>
                    </div>

                    <!-- الجنس -->
                    <div class="bg-white/5 rounded-xl p-3 border border-white/5 col-span-2 md:col-span-1">
                        <span class="text-sky-200 block text-xs mb-1"><i
                                class="fa-solid fa-venus-mars ml-1 text-sky-300"></i> الجنس</span>
                        <span class="font-semibold text-white">{{ $d->sex == 'm' ? 'ذكر' : 'انثى' }}</span>
                    </div>

                    <!-- تاريخ الميلاد -->
                    <div class="bg-white/5 rounded-xl p-3 border border-white/5 col-span-2 md:col-span-1">
                        <span class="text-sky-200 block text-xs mb-1"><i
                                class="fa-solid fa-cake-candles ml-1 text-sky-300"></i> تاريخ الميلاد</span>
                        <span class="font-semibold text-white font-mono">{{ $d->born }}</span>
                    </div>

                    <!-- العمر عند الوفاة -->
                    <div
                        class="bg-white/5 rounded-xl p-3 border border-white/5 col-span-2 md:col-span-1 flex flex-col justify-center">
                        <span class="text-sky-200 block text-xs mb-1"><i
                                class="fa-solid fa-hourglass-half ml-1 text-sky-300"></i> العمر</span>
                        <span class="font-semibold text-amber-300">{{ $d->age }}{{ is_numeric($d->age) ? ' سنة' : ' ' }}</span>
                    </div>

                </div>
            </div>

        </div>
    </header>

    <!-- قائمة التنقل السريع -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto flex justify-center space-x-reverse gap-8 py-3.5 text-sm font-medium">
            <a href="#biography" class="text-slate-600 hover:text-slate-900 transition-colors">عن الشهيد</a>
            <a href="#gallery" class="text-slate-600 hover:text-slate-900 transition-colors">معرض الصور</a>
            <a href="#messages" class="text-slate-600 hover:text-slate-900 transition-colors">دفتر التعازي</a>
        </div>
    </nav>

    <!-- المحتوى الرئيسي للمعلومات اللاحقة -->
    <main class="max-w-4xl mx-auto px-4 py-12 space-y-16">

        <!-- قسم عن حياة الشهيد وأثره -->
        @if ($d->story->id)
            <section id="biography" class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold border-r-4 border-slate-700 pr-3 mb-4 text-slate-900">{{ $d->story->title }}</h2>
                <p class="text-slate-600 leading-relaxed text-sm md:text-base">{{ $d->story->content }}</p>
            </section>
        @endif
        @if ($d->momeriesImg->count())

                            <!-- معرض الصور والذكريات -->
                            <section id="gallery">
                                <h2 class="text-xl font-bold border-r-4 border-slate-700 pr-3 mb-6 text-slate-900">معرض الذاكرة</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach ($d->momeriesImg as $img)
                                        <div
                                            class="overflow-hidden rounded-xl bg-slate-200 aspect-square border border-slate-300 shadow-sm hover:shadow-md transition-shadow">
                                            <img src="{{ asset('assets/img/' . ($img->img_path ?? 'no-img.jpg')) }}" alt="صورة من الذاكرة"
                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        </div>

                                    @endforeach

                                </div>
                            </section>
        @endif
        <!-- جدار التعازي والمواساة -->
        <section id="messages" class="space-y-6">
            <h2 class="text-xl font-bold border-r-4 border-slate-700 pr-3 text-slate-900">التعزيات</h2>

            <div class="space-y-4">
                @forelse ($approvedCondolences as $condolence)
                    <article class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-2 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <h3 class="font-bold text-slate-800">{{ $condolence->author_name ?: 'أحد الزوار' }}</h3>
                            <time
                                datetime="{{ $condolence->created_at->toIso8601String() }}"
                                class="text-xs text-slate-400"
                            >
                                {{ $condolence->created_at->format('Y-m-d H:i') }}
                            </time>
                        </div>
                        <p class="mt-4 whitespace-pre-line break-words text-sm leading-7 text-slate-600">{{ $condolence->content }}</p>
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 text-sm text-slate-500 shadow-sm">
                        لا توجد تعزيات منشورة حتى الآن.
                    </div>
                @endforelse
            </div>

            @if ($approvedCondolences->hasPages())
                <div dir="rtl">
                    {{ $approvedCondolences->links() }}
                </div>
            @endif

            @if ($errors->condolence->has('content'))
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->condolence->first('content') }}
                </div>
            @endif

            @if ($hasSubmittedCondolence)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-sm leading-7 text-emerald-800 shadow-sm">
                    لقد أرسلت تعزية لهذا الشهيد مسبقًا.
                </div>
            @else
                <form
                    method="POST"
                    action="{{ route('martyr.condolences.store', $d) }}"
                    class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm"
                >
                    @csrf

                    <div>
                        <h3 class="text-base font-bold text-slate-800">إرسال تعزية</h3>
                        <p class="mt-2 text-xs leading-6 text-slate-500">
                            يمكنك كتابة رسالة تعزية، وستظهر بعد مراجعتها.
                        </p>
                    </div>

                    <div>
                        <label for="condolence_author_name" class="mb-2 block text-xs font-semibold text-slate-700">
                            الاسم الكريم (اختياري)
                        </label>
                        <input
                            id="condolence_author_name"
                            name="author_name"
                            type="text"
                            value="{{ old('author_name') }}"
                            maxlength="255"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-xs focus:border-slate-500 focus:outline-none md:text-sm"
                        >
                        @if ($errors->condolence->has('author_name'))
                            <p class="mt-2 text-xs text-rose-600">{{ $errors->condolence->first('author_name') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="condolence_content" class="mb-2 block text-xs font-semibold text-slate-700">
                            رسالة التعزية
                        </label>
                        <textarea
                            id="condolence_content"
                            name="content"
                            rows="5"
                            maxlength="1000"
                            required
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 p-2.5 text-xs leading-7 focus:border-slate-500 focus:outline-none md:text-sm"
                            placeholder="اكتب رسالة التعزية هنا..."
                        >{{ old('content') }}</textarea>
                    </div>

                    <button
                        type="submit"
                        class="rounded-lg bg-slate-800 px-6 py-2.5 text-xs font-medium text-white shadow-sm transition-colors hover:bg-slate-900"
                    >
                        إرسال التعزية
                    </button>
                </form>
            @endif
        </section>
    </main>

    <!-- تذييل الصفحة -->
    <footer class="bg-slate-900 text-slate-400 text-center py-8 text-xs border-t border-slate-800">
        <p class="mb-2">إنّا لله وإنّا إليه راجعون</p>
        <p class="text-slate-600">تمت هذه الصفحة تخليداً وتكريماً لروح الشهيد.</p>
    </footer>

</body>
</html>
