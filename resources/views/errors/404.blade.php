<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1E3932">

    <link rel="icon" href="{{ asset('assets/img/icon1.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title>الصفحة غير موجودة | واحة الشهداء</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="oasis-page grid min-h-screen place-items-center p-4 sm:p-6 lg:p-8">

    <main class="w-full max-w-4xl overflow-hidden rounded-[12px] border border-black/[0.08] bg-white shadow-card">
        <div class="grid grid-cols-1 md:grid-cols-2">

            <!-- Content Area -->
            <div class="flex flex-col justify-center p-8 sm:p-12 lg:p-16 text-right order-2 md:order-1">
                <a class="inline-flex items-center gap-3 text-oasis-house text-sm font-bold" href="{{ url('/') }}" aria-label="العودة إلى واحة الشهداء">
                    <img class="h-10 w-10 rounded-full border border-oasis-mint bg-white p-1" src="{{ asset('assets/img/icon.png') }}" alt="">
                    <span>واحة الشهداء</span>
                </a>

                <div class="mt-8">
                    <span class="oasis-pill oasis-pill-mint text-xs">الصفحة غير موجودة</span>
                </div>

                <h1 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-bold text-oasis-house mt-3 leading-snug">
                    يبدو أنك وصلت إلى صفحة غير متاحة
                </h1>

                <p class="oasis-copy mt-4 text-sm sm:text-base leading-relaxed">
                    قد يكون الرابط قديمًا أو نُقل المحتوى أو تمت كتابة العنوان بصورة غير صحيحة. يمكنك العودة إلى الصفحة الرئيسية أو استخدام البحث للوصول إلى السجل المطلوب.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ url('/') }}" class="oasis-button oasis-button-primary !min-h-[48px] !px-6 !text-xs">
                        <i class="fa-solid fa-house ml-2"></i>
                        العودة إلى الرئيسية
                    </a>

                    <a href="{{ url('/martyr_search') }}" class="oasis-button oasis-button-outline !min-h-[48px] !px-6 !text-xs">
                        <i class="fa-solid fa-magnifying-glass ml-2"></i>
                        البحث في السجل
                    </a>
                </div>
            </div>

            <!-- Visual Block (House Green) -->
            <div class="oasis-band relative flex min-h-[220px] md:min-h-full flex-col items-center justify-center p-8 text-center order-1 md:order-2">
                <span class="font-serif text-7xl sm:text-8xl lg:text-9xl font-extrabold tracking-tight text-oasis-gold drop-shadow-sm">
                    404
                </span>
                <span class="mt-3 text-xs font-semibold tracking-wider text-oasis-mint/90 uppercase">
                    Page Not Found
                </span>
            </div>

        </div>
    </main>

</body>
</html>
