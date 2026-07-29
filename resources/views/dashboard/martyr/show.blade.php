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

            @php($hasStory = $martyr->story->exists)

            <section class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-2xl" dir="rtl">
                <div class="border-b border-gray-200 bg-emerald-50 px-6 py-5">
                    <h2 class="text-xl font-bold text-emerald-900">قصة الشهيد</h2>
                </div>

                @if ($hasStory)
                    <article class="px-6 py-6">
                        @if (filled($martyr->story->title))
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $martyr->story->title }}
                            </h3>
                        @endif

                        <div class="{{ filled($martyr->story->title) ? 'mt-4' : '' }} break-words text-base leading-8 text-gray-700">
                            {!! nl2br(e($martyr->story->content)) !!}
                        </div>
                    </article>
                @else
                    <div class="px-6 py-6">
                        <p class="text-sm leading-7 text-gray-600">
                            لم تتم إضافة قصة لهذا الشهيد بعد.
                        </p>

                        <button
                            type="button"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'add-martyr-story')"
                            class="mt-5 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            إضافة قصة
                        </button>
                    </div>
                @endif
            </section>

            @unless ($hasStory)
                <x-modal
                    name="add-martyr-story"
                    :show="$errors->storyCreation->isNotEmpty()"
                    maxWidth="xl"
                    focusable
                >
                    <form
                        method="POST"
                        action="{{ route('dashboard.martyr.story.store', $martyr) }}"
                        class="p-6"
                        dir="rtl"
                        novalidate
                    >
                        @csrf

                        <h2 class="text-xl font-bold text-emerald-900">
                            إضافة قصة الشهيد
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            أدخل عنوان القصة ونصها، ثم احفظها لعرضها في صفحة الشهيد.
                        </p>

                        <div class="mt-6">
                            <x-input-label for="story_title" value="عنوان القصة" />
                            <x-text-input
                                id="story_title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('title')"
                                maxlength="255"
                                aria-required="true"
                                :aria-invalid="$errors->storyCreation->has('title') ? 'true' : 'false'"
                                autofocus
                            />
                            <x-input-error
                                :messages="$errors->storyCreation->get('title')"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="story_content" value="نص القصة" />
                            <textarea
                                id="story_content"
                                name="content"
                                rows="8"
                                aria-required="true"
                                aria-invalid="{{ $errors->storyCreation->has('content') ? 'true' : 'false' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >{{ old('content') }}</textarea>
                            <x-input-error
                                :messages="$errors->storyCreation->get('content')"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6 flex flex-row-reverse items-center gap-3">
                            <button
                                type="submit"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                حفظ القصة
                            </button>
                            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                إلغاء
                            </x-secondary-button>
                        </div>
                    </form>
                </x-modal>
            @endunless
        </div>
    </div>
</x-app-layout>
