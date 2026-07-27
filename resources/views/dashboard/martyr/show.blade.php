<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Martyr details') }}
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.martyr.edit', $martyr->id) }}"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('dashboard.martyr.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="overflow-hidden bg-white shadow-sm sm:rounded-2xl">
                <div class="border-b border-gray-200 bg-emerald-50 px-6 py-5">
                    <h1 class="text-2xl font-bold text-emerald-900" dir="rtl">{{ $martyr->name_ar }}</h1>
                    @if ($martyr->name_en)
                        <p class="mt-1 text-sm text-emerald-700">{{ $martyr->name_en }}</p>
                    @endif
                </div>

                <dl class="grid grid-cols-1 divide-y divide-gray-200 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
                    <div class="px-6 py-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('National ID') }}</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $martyr->national_id ?: '-' }}</dd>
                    </div>
                    <div class="px-6 py-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Sex') }}</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $martyr->sex ?: '-' }}</dd>
                    </div>
                    <div class="px-6 py-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Age') }}</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $martyr->age ?: '-' }}</dd>
                    </div>
                    <div class="px-6 py-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Date of birth') }}</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $martyr->born ?: '-' }}</dd>
                    </div>
                    <div class="px-6 py-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Created at') }}</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $martyr->created_at?->format('Y-m-d H:i') ?: '-' }}</dd>
                    </div>
                    <div class="px-6 py-5">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Last updated') }}</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $martyr->updated_at?->format('Y-m-d H:i') ?: '-' }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
</x-app-layout>
