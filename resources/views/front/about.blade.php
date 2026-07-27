@extends('front.layout')

@section('title', 'من نحن | واحة الشهداء')

@section('content')
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <section class="overflow-hidden rounded-3xl bg-emerald-950 text-white shadow-lg">
            <div class="px-6 py-12 sm:px-12 sm:py-16 text-center">
                <span class="inline-flex items-center gap-2 rounded-full bg-amber-400/15 px-4 py-2 text-xs font-bold text-amber-300">
                    <i class="fa-solid fa-landmark"></i>
                    واحة الشهداء
                </span>
                <h1 class="mt-6 text-3xl sm:text-4xl font-bold">من نحن</h1>
                <p class="mt-5 max-w-3xl mx-auto text-sm sm:text-base leading-8 text-emerald-50">
                    واحة الشهداء منصة توثيقية تحفظ أسماء الشهداء وسيرهم وذكرياتهم، وتسهّل على العائلات والباحثين الوصول إلى المعلومات الموثقة باحترام وخصوصية.
                </p>
            </div>
        </section>

        <section class="grid md:grid-cols-3 gap-6 mt-10">
            <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <i class="fa-solid fa-book-open text-2xl text-emerald-700"></i>
                <h2 class="mt-4 text-lg font-bold text-slate-800">رسالتنا</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">تقديم سجل رقمي منظم يخلّد الأسماء والقصص، ويجعل الذاكرة الوطنية متاحة للأجيال القادمة.</p>
            </article>
            <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <i class="fa-solid fa-shield-halved text-2xl text-emerald-700"></i>
                <h2 class="mt-4 text-lg font-bold text-slate-800">التوثيق باحترام</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">نحرص على عرض المعلومات بصورة لائقة، دقيقة، وسهلة الاستخدام مع مراعاة خصوصية البيانات.</p>
            </article>
            <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <i class="fa-solid fa-people-group text-2xl text-emerald-700"></i>
                <h2 class="mt-4 text-lg font-bold text-slate-800">للمجتمع</h2>
                <p class="mt-3 text-sm leading-7 text-gray-600">نجمع بين العائلات والباحثين والمجتمع في مساحة تحفظ الذكرى وتدعم الوصول إلى السجل.</p>
            </article>
        </section>

        <section class="mt-10 rounded-2xl border border-amber-200 bg-amber-50 p-6 sm:p-8 text-center">
            <h2 class="text-xl font-bold text-emerald-950">هل لديك ملاحظة أو معلومة موثقة؟</h2>
            <p class="mt-2 text-sm text-gray-600">يسعدنا استقبال ملاحظاتكم ومساعدتكم في تحسين السجل.</p>
            <a href="{{ route('front.contact') }}" class="inline-flex items-center gap-2 mt-5 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800 transition">
                <i class="fa-solid fa-envelope"></i>
                اتصل بنا
            </a>
        </section>
    </main>
@endsection
