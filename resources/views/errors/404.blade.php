<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" href="{{ asset('assets/img/icon1.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Script / CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        arabic: ['"Noto Kufi Arabic"', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        amber: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                        },
                        slate: {
                            600: '#475569',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <title>الصفحة غير موجودة | واحة الشهداء</title>
</head>
<body class="font-arabic min-h-screen m-0 overflow-x-hidden text-slate-900 bg-[#f8fafc] bg-[radial-gradient(circle_at_12%_15%,rgba(245,158,11,0.12),transparent_24rem),radial-gradient(circle_at_88%_85%,rgba(4,120,87,0.12),transparent_28rem)]">

    <main class="isolate relative grid min-h-screen place-items-center p-4 sm:p-8">
        <!-- Background Pattern -->
        <div class="fixed inset-0 -z-10 opacity-[0.035] pointer-events-none bg-[linear-gradient(30deg,#022c22_12%,transparent_12.5%,transparent_87%,#022c22_87.5%,#022c22),linear-gradient(150deg,#022c22_12%,transparent_12.5%,transparent_87%,#022c22_87.5%,#022c22)] bg-[position:0_0,2rem_3.5rem] bg-[size:4rem_7rem]" aria-hidden="true"></div>

        <!-- Main Card -->
        <section class="w-full max-w-[62rem] overflow-hidden rounded-[2rem] border border-slate-200/85 bg-white/88 shadow-[0_2rem_5rem_rgba(15,23,42,0.12)] backdrop-blur-2xl" aria-labelledby="page-title">
            <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_minmax(18rem,0.85fr)]">

                <!-- Text Content -->
                <div class="p-8 sm:p-12 lg:p-16 text-center md:text-right flex flex-col justify-center">
                    <a class="inline-flex items-center justify-center md:justify-start gap-3 text-emerald-900 text-sm font-extrabold" href="{{ url('/') }}" aria-label="العودة إلى واحة الشهداء">
                        <img class="w-12 h-12 rounded-full border-2 border-amber-500/28 object-cover shadow-[0_0.5rem_1.5rem_rgba(2,44,34,0.10)]" src="{{ asset('assets/img/icon.png') }}" alt="">
                        <span>واحة الشهداء</span>
                    </a>

                    <div class="inline-flex items-center justify-center md:justify-start gap-2 mt-10 text-emerald-700 text-xs font-extrabold tracking-widest before:content-[''] before:w-8 before:h-[2px] before:rounded-full before:bg-amber-500">
                        الصفحة غير موجودة
                    </div>

                    <h1 id="page-title" class="mt-3 text-emerald-950 text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-snug">
                        يبدو أنك وصلت إلى طريق غير متاح
                    </h1>

                    <p class="max-w-xl mt-4 text-slate-600 text-sm sm:text-base leading-loose mx-auto md:mx-0">
                        قد يكون الرابط قديمًا أو تمت كتابة العنوان بصورة غير صحيحة.
                        يمكنك العودة إلى الصفحة الرئيسية أو استخدام البحث للوصول إلى السجل المطلوب.
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-8">
                        <a class="inline-flex min-h-[3rem] items-center justify-center gap-2.5 px-5 py-3 rounded-2xl text-white bg-emerald-800 font-bold text-sm shadow-[0_0.8rem_1.5rem_rgba(6,95,70,0.20)] transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-[0_1rem_2rem_rgba(6,95,70,0.25)] focus-visible:outline focus-visible:outline-3 focus-visible:outline-amber-500/35 focus-visible:outline-offset-3" href="{{ url('/') }}">
                            <span aria-hidden="true">←</span>
                            العودة إلى الرئيسية
                        </a>

                        <a class="inline-flex min-h-[3rem] items-center justify-center gap-2.5 px-5 py-3 rounded-2xl text-emerald-900 bg-white border border-[#dbe4e1] font-bold text-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-[#a7d6c6] hover:bg-emerald-50 focus-visible:outline focus-visible:outline-3 focus-visible:outline-amber-500/35 focus-visible:outline-offset-3" href="{{ url('/martyr_search') }}">
                            <span aria-hidden="true">⌕</span>
                            البحث في السجل
                        </a>
                    </div>
                </div>

                <!-- Visual Section -->
                <div class="relative grid min-h-[15rem] md:min-h-[31rem] place-items-center p-8 text-white bg-[radial-gradient(circle_at_78%_18%,rgba(251,191,36,0.28),transparent_11rem),linear-gradient(145deg,#064e3b,#022c22)] overflow-hidden order-first md:order-last" aria-hidden="true">

                    <!-- Decorative Circles -->
                    <div class="absolute w-[16rem] h-[16rem] md:w-[22rem] md:h-[22rem] border border-white/10 rounded-full"></div>
                    <div class="absolute w-[12rem] h-[12rem] md:w-[16rem] md:h-[16rem] border border-white/10 rounded-full"></div>

                    <div class="relative z-10 text-center">
                        <strong class="block text-amber-400 text-8xl md:text-9xl font-extrabold leading-none tracking-tighter drop-shadow-[0_1rem_2.5rem_rgba(0,0,0,0.18)]">
                            404
                        </strong>
                        <span class="inline-flex items-center gap-2 mt-4 px-4 py-2 border border-white/12 rounded-full text-emerald-100 bg-white/7 text-xs font-semibold backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_0_0.3rem_rgba(251,191,36,0.12)]"></span>
                            لم نعثر على الصفحة المطلوبة
                        </span>
                    </div>
                </div>

            </div>
        </section>
    </main>

</body>
</html>
