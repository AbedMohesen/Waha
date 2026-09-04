@extends('front.layout')

@section('title', 'تواصل معنا | واحة الشهداء')

@section('content')
    <div class="oasis-container oasis-section">
        <div class="grid overflow-hidden rounded-[12px] bg-white shadow-card lg:grid-cols-[.95fr_1.05fr]">
            <!-- Aside Feature Band: House Green (#1E3932) -->
            <aside class="oasis-band p-8 sm:p-12 lg:p-14 flex flex-col justify-between">
                <div>
                    <span class="oasis-pill-gold">
                        <i class="fa-solid fa-headset text-[10px]"></i>
                        تواصل
                    </span>
                    <h1 class="mt-4 text-3xl sm:text-4xl font-semibold leading-tight text-white">
                        نستمع إلى ملاحظاتكم بكل اهتمام.
                    </h1>
                    <p class="mt-4 text-sm sm:text-base leading-8 text-white/75">
                        للاستفسارات المتعلقة بالسجل التوثيقي، أو لتحديث وتصحيح معلومات موثقة، يسعدنا التواصل المباشر مع فريق إدارة الواحة عبر البريد الإلكتروني.
                    </p>
                </div>

                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-xs font-semibold text-white/60 mb-3">البريد الإلكتروني المعتمد:</p>
                    <a href="mailto:support@martyrs-oasis.com" class="inline-flex items-center gap-3.5 rounded-[12px] border border-white/15 bg-white/5 p-4 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-oasis-green shadow-sm">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <span dir="ltr">support@martyrs-oasis.com</span>
                    </a>
                </div>
            </aside>

            <!-- Main Form Info: Pure White Card Canvas -->
            <section class="p-8 sm:p-12 lg:p-14 bg-white flex flex-col justify-between">
                <div>
                    <p class="oasis-kicker">إرسال استفسار</p>
                    <h2 class="oasis-heading mt-1">كيف يمكننا مساعدتك؟</h2>
                    <p class="oasis-copy mt-2">
                        سيفتح زر المراسلة تطبيق البريد الإلكتروني المفضل لديك مع تعبئة العنوان المناسب مسبقًا.
                    </p>

                    <div class="mt-8 rounded-[12px] bg-oasis-cream p-5 border border-black/5">
                        <p class="text-sm font-bold text-oasis-house flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb text-oasis-accent"></i>
                            نقترح أن تتضمن رسالتك:
                        </p>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-black/65">
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-xs text-oasis-accent mt-1"></i>
                                <span>موضوع الاستفسار أو الملاحظة التوثيقية بدقة</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-xs text-oasis-accent mt-1"></i>
                                <span>اسم الشهيد أو الرقم الوطني المراد الإشارة إليه إن وجد</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fa-solid fa-circle-check text-xs text-oasis-accent mt-1"></i>
                                <span>وسيلة الاتصال المفضلة للرد عليك</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-black/5 flex flex-col sm:flex-row gap-3">
                    <a href="mailto:support@martyrs-oasis.com?subject=رسالة%20من%20واحة%20الشهداء" class="oasis-button oasis-button-primary">
                        <span>إرسال بريد إلكتروني</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </a>
                    <a href="{{ route('front.index') }}" class="oasis-button oasis-button-outline">
                        العودة للرئيسية
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection
