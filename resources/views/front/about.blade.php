@extends('front.layout')

@section('title', 'عن الواحة | واحة الشهداء')

@section('content')
    <!-- Hero Feature Band: House Green (#1E3932) -->
    <section class="oasis-band">
        <div class="oasis-container py-14 text-center sm:py-20">
            <span class="oasis-pill-gold">
                <i class="fa-solid fa-certificate text-[10px]"></i>
                عن المنصة
            </span>
            <h1 class="mx-auto mt-4 max-w-3xl text-4xl font-semibold leading-tight text-white sm:text-5xl lg:text-6xl">
                مساحة رقمية تحفظ الذاكرة باحترام.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-white/75 sm:text-lg">
                واحة الشهداء منصة توثيقية إنسانية تجمع السجل والقصص والذكريات في تجربة هادئة، رصينة، ومصممة بأعلى معايير التوثيق.
            </p>
        </div>
    </section>

    <!-- Core Pillars: White Section with 12px Cards -->
    <section class="bg-white">
        <div class="oasis-container oasis-section">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <p class="oasis-kicker">المبادئ والركائز</p>
                <h2 class="oasis-heading mt-1">قيم العمل التوثيقي</h2>
                <p class="oasis-copy mt-1">المعايير التي توجّه كل خطوة في بناء وتطوير منصة الواحة.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <article class="oasis-card p-6 sm:p-8 flex flex-col justify-between hover:-translate-y-1">
                    <div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                            <i class="fa-solid fa-book-open text-base"></i>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-oasis-house">رسالتنا</h3>
                        <p class="oasis-copy mt-3">
                            تنظيم السجل الوطني والقصص التوثيقية في منصة سهلة الاستخدام تحفظ الأسماء وتسهّل الوصول إليها للأسر والباحثين والأجيال القادمة.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-black/5 text-xs font-semibold text-oasis-accent">
                        دقة وتخليد مستمر
                    </div>
                </article>

                <article class="oasis-card p-6 sm:p-8 flex flex-col justify-between hover:-translate-y-1">
                    <div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-oasis-house">توثيق باحترام</h3>
                        <p class="oasis-copy mt-3">
                            نقدّم المحتوى بلغة هادئة ومحترمة، مع الالتزام التام بقدسية الذكرى ودقة البيانات بعيدًا عن أي توظيف عابر.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-black/5 text-xs font-semibold text-oasis-accent">
                        أمانة تاريخية وإنسانية
                    </div>
                </article>

                <article class="oasis-card p-6 sm:p-8 flex flex-col justify-between hover:-translate-y-1">
                    <div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-oasis-mint text-oasis-green">
                            <i class="fa-solid fa-people-group text-base"></i>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-oasis-house">خدمة المجتمع</h3>
                        <p class="oasis-copy mt-3">
                            مساحة تلتقي فيها رسائل التقدير والتعازي النبيلة، وتتيح للجميع المساهمة في استكمال البيانات والذكريات الموثقة.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-black/5 text-xs font-semibold text-oasis-accent">
                        تكامل وترابط إنساني
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Contribution / Collaboration Section: Warm Gold Lightest Surface (#faf6ee) -->
    <section class="bg-oasis-cream">
        <div class="oasis-container oasis-section">
            <div class="oasis-card-gold p-8 sm:p-12 grid gap-8 lg:grid-cols-[1.2fr_.8fr] items-center">
                <div>
                    <span class="oasis-pill-gold">
                        <i class="fa-solid fa-hands-holding-circle text-[10px]"></i>
                        تواصل ومساهمة
                    </span>
                    <h2 class="oasis-heading mt-3 text-oasis-house">كل معلومة موثقة تصنع فرقًا.</h2>
                    <p class="oasis-copy mt-4 max-w-xl text-black/70">
                        إذا كانت لديك صور تذكارية، تفاصيل سيرة، أو أي معلومة تفيد في تدقيق وتحديث السجل، يسعدنا استقبالها ومراجعتها بعناية فائقة.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 lg:justify-end">
                    <a href="{{ route('front.contact') }}" class="oasis-button oasis-button-primary">
                        <span>تواصل معنا للمساهمة</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </a>
                    <a href="{{ route('front.search') }}" class="oasis-button oasis-button-outline">
                        تصفح السجل
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
