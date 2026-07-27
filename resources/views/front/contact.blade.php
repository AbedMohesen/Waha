@extends('front.layout')

@section('title', 'اتصل بنا | واحة الشهداء')

@section('content')
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <section class="grid lg:grid-cols-2 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="bg-emerald-950 px-6 py-12 sm:px-10 text-white">
                <span class="inline-flex items-center gap-2 text-sm font-bold text-amber-300">
                    <i class="fa-solid fa-envelope-open-text"></i>
                    واحة الشهداء
                </span>
                <h1 class="mt-5 text-3xl font-bold">اتصل بنا</h1>
                <p class="mt-4 text-sm leading-8 text-emerald-50">
                    نرحّب باستفساراتكم وملاحظاتكم المتعلقة بالسجل أو بتحديث المعلومات الموثقة.
                </p>

                <a href="mailto:support@martyrs-oasis.com" class="mt-8 flex items-center gap-3 rounded-2xl border border-emerald-800 bg-emerald-900/60 p-4 hover:bg-emerald-900 transition">
                    <i class="fa-solid fa-envelope text-amber-300"></i>
                    <span class="text-sm" dir="ltr">support@martyrs-oasis.com</span>
                </a>
            </div>

            <div class="px-6 py-12 sm:px-10">
                <h2 class="text-xl font-bold text-slate-800">أرسل رسالة</h2>
                <p class="mt-2 text-sm text-gray-500">سيتم فتح برنامج البريد الإلكتروني لإرسال رسالتك مباشرة.</p>

                <a href="mailto:support@martyrs-oasis.com?subject=رسالة%20من%20موقع%20واحة%20الشهداء"
                    class="inline-flex items-center gap-2 mt-6 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800 transition">
                    <i class="fa-solid fa-paper-plane"></i>
                    فتح البريد الإلكتروني
                </a>

                <div class="mt-8 border-t border-gray-100 pt-6 text-sm leading-7 text-gray-600">
                    <p class="font-semibold text-slate-700">لأسرع متابعة، يرجى تضمين:</p>
                    <ul class="mt-2 space-y-1 list-disc list-inside">
                        <li>اسم الشهيد أو رقم السجل عند توفره.</li>
                        <li>وصفاً واضحاً للمعلومة أو الملاحظة.</li>
                        <li>وسيلة مناسبة للرد عليكم.</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
@endsection
