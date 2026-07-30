<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1" dir="rtl">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">مراجعة التعزيات</h2>
            <p class="text-sm text-gray-500">مراجعة الرسائل المعلقة قبل نشرها في صفحات الشهداء.</p>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap gap-2">
                @foreach ([
                    'pending' => 'المعلقة',
                    'approved' => 'المعتمدة',
                    'all' => 'الكل',
                ] as $status => $label)
                    <a
                        href="{{ route('dashboard.condolences.index', ['status' => $status]) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ $filter === $status
                            ? 'bg-emerald-700 text-white shadow-sm'
                            : 'border border-gray-200 bg-white text-gray-600 hover:border-emerald-200 hover:text-emerald-700' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-emerald-50 px-5 py-4 sm:px-6">
                    <h3 class="font-bold text-emerald-950">
                        {{ $filter === 'pending' ? 'التعزيات بانتظار المراجعة' : ($filter === 'approved' ? 'التعزيات المعتمدة' : 'جميع التعزيات') }}
                    </h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($condolences as $condolence)
                        <article class="p-5 sm:p-6">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="font-bold text-slate-800">{{ $condolence->martyr->name_ar ?: 'شهيد غير متاح' }}</h4>
                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $condolence->status === 'pending'
                                            ? 'bg-amber-100 text-amber-800'
                                            : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $condolence->status === 'pending' ? 'معلقة' : 'معتمدة' }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-xs text-gray-500">
                                        أرسلها {{ $condolence->author_name ?: 'أحد الزوار' }}
                                        <span class="mx-1">•</span>
                                        {{ $condolence->created_at->format('Y-m-d H:i') }}
                                    </p>

                                    <p class="mt-4 whitespace-pre-line break-words rounded-xl bg-gray-50 p-4 text-sm leading-7 text-gray-700">{{ $condolence->content }}</p>

                                    @if ($condolence->martyr->exists)
                                        <a
                                            href="{{ route('martyr', $condolence->martyr) }}"
                                            class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-900"
                                        >
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            عرض صفحة الشهيد
                                        </a>
                                    @endif
                                </div>

                                @if ($condolence->status === 'pending')
                                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                                        <form method="POST" action="{{ route('dashboard.condolences.approve', $condolence) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-emerald-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-800"
                                            >
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
                                                class="rounded-lg border border-rose-200 bg-white px-4 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-50"
                                            >
                                                رفض وحذف
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-gray-500">
                            لا توجد تعزيات ضمن هذا التصنيف.
                        </div>
                    @endforelse
                </div>
            </section>

            @if ($condolences->hasPages())
                <div class="mt-6">
                    {{ $condolences->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
