<x-app-layout>
    <x-slot name="header">
        <div dir="rtl">
            <p class="oasis-kicker">إدارة التعزيات</p>
            <h1 class="oasis-heading mt-1">مراجعة التعزيات</h1>
            <p class="oasis-copy mt-1">مراجعة رسائل التعزية المعلقة قبل اعتمادها ونشرها في صفحات الشهداء.</p>
        </div>
    </x-slot>

    <div class="oasis-container py-8 sm:py-12" dir="rtl">
        <!-- Filter Tabs -->
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach ([
                'pending' => 'المعلقة',
                'approved' => 'المعتمدة',
                'all' => 'الكل',
            ] as $status => $label)
                <a
                    href="{{ route('dashboard.condolences.index', ['status' => $status]) }}"
                    class="oasis-button !min-h-[40px] !px-5 !py-2 !text-xs {{ $filter === $status
                        ? 'oasis-button-primary'
                        : 'border border-oasis-ceramic bg-white text-oasis-black/70 hover:border-oasis-mint hover:text-oasis-accent' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Condolences List -->
        <section class="oasis-card overflow-hidden">
            <div class="border-b border-black/[0.06] bg-oasis-mint/20 px-6 py-4">
                <h2 class="font-serif text-base font-bold text-oasis-house">
                    {{ $filter === 'pending' ? 'التعزيات بانتظار المراجعة' : ($filter === 'approved' ? 'التعزيات المعتمدة' : 'جميع التعزيات') }}
                </h2>
            </div>

            <div class="divide-y divide-black/[0.06]">
                @forelse ($condolences as $condolence)
                    <article class="p-6 transition hover:bg-oasis-cream/20">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-serif text-base font-bold text-oasis-house">
                                        {{ $condolence->martyr->name_ar ?: 'شهيد غير متاح' }}
                                    </h3>
                                    @if ($condolence->status === 'pending')
                                        <span class="oasis-pill oasis-pill-gold">معلقة</span>
                                    @else
                                        <span class="oasis-pill oasis-pill-mint">معتمدة</span>
                                    @endif
                                </div>

                                <p class="mt-1.5 text-xs text-oasis-black/60">
                                    أرسلها <strong class="text-oasis-black/80">{{ $condolence->author_name ?: 'أحد الزوار' }}</strong>
                                    <span class="mx-1.5 text-oasis-black/30">•</span>
                                    <time datetime="{{ $condolence->created_at->toIso8601String() }}">{{ $condolence->created_at->format('Y-m-d H:i') }}</time>
                                </p>

                                <div class="mt-4 rounded-xl border border-black/[0.04] bg-oasis-cream/40 p-4 text-sm leading-7 text-oasis-black/85">
                                    <p class="whitespace-pre-line break-words">{{ $condolence->content }}</p>
                                </div>

                                @if ($condolence->martyr->exists)
                                    <a
                                        href="{{ route('martyr', $condolence->martyr) }}"
                                        class="mt-3.5 inline-flex items-center gap-1.5 text-xs font-bold text-oasis-accent hover:text-oasis-green hover:underline"
                                    >
                                        <span>عرض صفحة الشهيد</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @endif
                            </div>

                            @if ($condolence->status === 'pending')
                                <div class="flex shrink-0 flex-wrap items-center gap-2 pt-1 lg:pt-0">
                                    <form method="POST" action="{{ route('dashboard.condolences.approve', $condolence) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="oasis-button oasis-button-primary !min-h-[38px] !px-4 !py-2 !text-xs"
                                        >
                                            <i class="fa-solid fa-check ml-1.5"></i>
                                            موافقة
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('dashboard.condolences.reject', $condolence) }}"
                                        onsubmit="return confirm('هل أنت متأكد من رفض هذه التعزية وحذفها نهائيًا؟')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="oasis-button oasis-button-outline !min-h-[38px] !border-red-200 !px-4 !py-2 !text-xs !text-red-700 hover:!bg-red-50"
                                        >
                                            <i class="fa-solid fa-trash ml-1.5"></i>
                                            رفض وحذف
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="oasis-empty py-16 text-center text-sm text-oasis-black/60">
                        <i class="fa-regular fa-comments mb-3 text-3xl text-oasis-black/30"></i>
                        <p>لا توجد تعزيات ضمن هذا التصنيف.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if ($condolences->hasPages())
            <div class="mt-8">
                {{ $condolences->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
