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

            @php
                $hasProfileImage = $martyr->profileImg->exists;
                $profileImagePath = $hasProfileImage ? $martyr->profileImg->img_path : null;
                $profileImageFileExists = false;

                if (filled($profileImagePath)) {
                    try {
                        $profileImageFileExists = \Illuminate\Support\Facades\Storage::disk('martyr_images')
                            ->exists($profileImagePath);
                    } catch (\Throwable) {
                        $profileImageFileExists = false;
                    }
                }

                $profileImageUrl = $profileImageFileExists
                    ? asset('assets/img/' . ltrim(str_replace('\\', '/', $profileImagePath), '/'))
                    : asset('assets/img/No-photo-m.png');
            @endphp

            <section class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-2xl" dir="rtl">
                <div class="flex flex-col gap-4 border-b border-gray-200 bg-emerald-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-xl font-bold text-emerald-900">الصورة الشخصية</h2>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'manage-martyr-profile-image')"
                            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            {{ $hasProfileImage ? 'استبدال الصورة' : 'إضافة صورة شخصية' }}
                        </button>

                        @if ($hasProfileImage)
                            <form
                                method="POST"
                                action="{{ route('dashboard.martyr.profile-image.destroy', $martyr) }}"
                                onsubmit="return confirm('هل أنت متأكد من حذف الصورة الشخصية؟')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                >
                                    حذف الصورة
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4 px-6 py-6 text-center">
                    <img
                        src="{{ $profileImageUrl }}"
                        alt="الصورة الشخصية للشهيد {{ $martyr->name_ar }}"
                        class="h-48 w-48 rounded-2xl border border-gray-200 bg-gray-50 object-cover shadow-sm"
                        onerror="this.onerror=null; this.src='{{ asset('assets/img/No-photo-m.png') }}';"
                    >

                    @unless ($hasProfileImage)
                        <p class="text-sm text-gray-600">لم تتم إضافة صورة شخصية لهذا الشهيد بعد.</p>
                    @endunless

                    @if ($hasProfileImage && ! $profileImageFileExists)
                        <p class="text-sm text-amber-700">
                            تعذر العثور على ملف الصورة المحفوظ؛ يمكنك استبداله بصورة جديدة.
                        </p>
                    @endif
                </div>
            </section>

            <x-modal
                name="manage-martyr-profile-image"
                :show="$errors->profileImage->isNotEmpty()"
                maxWidth="xl"
                focusable
            >
                <form
                    method="POST"
                    action="{{ route('dashboard.martyr.profile-image.update', $martyr) }}"
                    enctype="multipart/form-data"
                    class="p-6"
                    dir="rtl"
                    novalidate
                >
                    @csrf
                    @method('PUT')

                    <h2 class="text-xl font-bold text-emerald-900">
                        {{ $hasProfileImage ? 'استبدال الصورة الشخصية' : 'إضافة صورة شخصية' }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        اختر صورة بصيغة JPG أو JPEG أو PNG أو WEBP، وبحجم لا يتجاوز 5 ميغابايت.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="profile_img_path" value="ملف الصورة" />
                        <input
                            id="profile_img_path"
                            name="img_path"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            aria-required="true"
                            aria-invalid="{{ $errors->profileImage->has('img_path') ? 'true' : 'false' }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm text-gray-700 shadow-sm file:ml-4 file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:ring-emerald-500"
                        >
                        <x-input-error
                            :messages="$errors->profileImage->get('img_path')"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6 flex flex-row-reverse items-center gap-3">
                        <button
                            type="submit"
                            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            حفظ الصورة
                        </button>
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            إلغاء
                        </x-secondary-button>
                    </div>
                </form>
            </x-modal>

            @php
                $hasStory = $martyr->story->exists;
            @endphp

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

                        <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5">
                            <button
                                type="button"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'edit-martyr-story')"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                تعديل القصة
                            </button>

                            <form
                                method="POST"
                                action="{{ route('dashboard.martyr.story.destroy', [$martyr, $martyr->story]) }}"
                                onsubmit="return confirm('هل أنت متأكد من حذف قصة الشهيد؟ لا يمكن التراجع عن هذا الإجراء.')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                >
                                    حذف القصة
                                </button>
                            </form>
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

            @if ($hasStory)
                <x-modal
                    name="edit-martyr-story"
                    :show="$errors->storyUpdate->isNotEmpty()"
                    maxWidth="xl"
                    focusable
                >
                    <form
                        method="POST"
                        action="{{ route('dashboard.martyr.story.update', [$martyr, $martyr->story]) }}"
                        class="p-6"
                        dir="rtl"
                        novalidate
                    >
                        @csrf
                        @method('PUT')

                        <h2 class="text-xl font-bold text-emerald-900">
                            تعديل قصة الشهيد
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            عدّل عنوان القصة ونصها، ثم احفظ التغييرات.
                        </p>

                        <div class="mt-6">
                            <x-input-label for="story_edit_title" value="عنوان القصة" />
                            <x-text-input
                                id="story_edit_title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('title', $martyr->story->title)"
                                maxlength="255"
                                aria-required="true"
                                :aria-invalid="$errors->storyUpdate->has('title') ? 'true' : 'false'"
                                autofocus
                            />
                            <x-input-error
                                :messages="$errors->storyUpdate->get('title')"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="story_edit_content" value="نص القصة" />
                            <textarea
                                id="story_edit_content"
                                name="content"
                                rows="8"
                                aria-required="true"
                                aria-invalid="{{ $errors->storyUpdate->has('content') ? 'true' : 'false' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            >{{ old('content', $martyr->story->content) }}</textarea>
                            <x-input-error
                                :messages="$errors->storyUpdate->get('content')"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6 flex flex-row-reverse items-center gap-3">
                            <button
                                type="submit"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                حفظ التعديلات
                            </button>
                            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                إلغاء
                            </x-secondary-button>
                        </div>
                    </form>
                </x-modal>
            @endif

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

            <section class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-2xl" dir="rtl">
                <div class="flex flex-col gap-4 border-b border-gray-200 bg-emerald-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-xl font-bold text-emerald-900">صور الذكريات</h2>

                    <button
                        type="button"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'add-martyr-memory-image')"
                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        إضافة صورة ذكرى
                    </button>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($martyr->momeriesImg as $memory)
                            @php
                                $memoryImagePath = $memory->img_path;
                                $memoryImageFileExists = filled($memoryImagePath)
                                    && \Illuminate\Support\Facades\Storage::disk('martyr_images')
                                        ->exists($memoryImagePath);

                                $memoryImageUrl = $memoryImageFileExists
                                    ? asset('assets/img/' . ltrim(str_replace('\\', '/', $memoryImagePath), '/'))
                                    : asset('assets/img/No-photo-m.png');
                            @endphp

                            <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                                <img
                                    src="{{ $memoryImageUrl }}"
                                    alt="{{ filled($memory->caption) ? $memory->caption : 'صورة ذكرى للشهيد ' . $martyr->name_ar }}"
                                    class="aspect-[4/3] w-full bg-gray-50 object-cover"
                                    onerror="this.onerror=null; this.src='{{ asset('assets/img/No-photo-m.png') }}';"
                                >

                                <div class="space-y-4 p-4">
                                    @if (filled($memory->caption))
                                        <p class="break-words text-sm leading-7 text-gray-700">
                                            {{ $memory->caption }}
                                        </p>
                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('dashboard.martyr.memories.destroy', [$martyr, $memory]) }}"
                                        onsubmit="return confirm('هل أنت متأكد من حذف صورة الذكرى؟ لا يمكن التراجع عن هذا الإجراء.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                        >
                                            حذف الصورة
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <p class="text-sm leading-7 text-gray-600 sm:col-span-2 lg:col-span-3">
                                لا توجد صور ذكريات مضافة.
                            </p>
                        @endforelse
                    </div>
                </div>
            </section>

            <x-modal
                name="add-martyr-memory-image"
                :show="$errors->memoryImage->isNotEmpty()"
                maxWidth="xl"
                focusable
            >
                <form
                    method="POST"
                    action="{{ route('dashboard.martyr.memories.store', $martyr) }}"
                    enctype="multipart/form-data"
                    class="p-6"
                    dir="rtl"
                    novalidate
                >
                    @csrf

                    <h2 class="text-xl font-bold text-emerald-900">
                        إضافة صورة ذكرى
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        اختر صورة بصيغة JPG أو JPEG أو PNG أو WEBP، ويمكنك إضافة وصف اختياري.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="memory_img_path" value="ملف الصورة" />
                        <input
                            id="memory_img_path"
                            name="img_path"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            aria-required="true"
                            aria-invalid="{{ $errors->memoryImage->has('img_path') ? 'true' : 'false' }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-sm text-gray-700 shadow-sm file:ml-4 file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:ring-emerald-500"
                        >
                        <x-input-error
                            :messages="$errors->memoryImage->get('img_path')"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6">
                        <x-input-label for="memory_caption" value="وصف الصورة (اختياري)" />
                        <x-text-input
                            id="memory_caption"
                            name="caption"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('caption')"
                            maxlength="255"
                            :aria-invalid="$errors->memoryImage->has('caption') ? 'true' : 'false'"
                        />
                        <x-input-error
                            :messages="$errors->memoryImage->get('caption')"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6 flex flex-row-reverse items-center gap-3">
                        <button
                            type="submit"
                            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            حفظ الصورة
                        </button>
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            إلغاء
                        </x-secondary-button>
                    </div>
                </form>
            </x-modal>

            <section class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-2xl" dir="rtl">
                <div class="border-b border-gray-200 bg-emerald-50 px-6 py-5">
                    <h2 class="text-xl font-bold text-emerald-900">إدارة التعزيات</h2>
                </div>

                <div class="space-y-5 px-6 py-6">
                    @forelse ($condolences as $condolence)
                        <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if (filled($condolence->author_name))
                                            <h3 class="font-semibold text-gray-900">{{ $condolence->author_name }}</h3>
                                        @endif

                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $condolence->status === 'pending'
                                            ? 'bg-amber-100 text-amber-800'
                                            : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $condolence->status === 'pending' ? 'بانتظار المراجعة' : 'تمت الموافقة' }}
                                        </span>
                                    </div>

                                    <time
                                        datetime="{{ $condolence->created_at->toIso8601String() }}"
                                        class="mt-2 block text-xs text-gray-400"
                                    >
                                        {{ $condolence->created_at->format('Y-m-d H:i') }}
                                    </time>

                                    <p class="mt-4 whitespace-pre-line break-words text-sm leading-7 text-gray-700">{{ $condolence->content }}</p>
                                </div>

                                <div class="flex shrink-0 flex-wrap items-center gap-2">
                                    @if ($condolence->status === 'pending')
                                        <form
                                            method="POST"
                                            action="{{ route('dashboard.martyr.condolences.approve', [$martyr, $condolence]) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                            >
                                                موافقة
                                            </button>
                                        </form>
                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('dashboard.martyr.condolences.destroy', [$martyr, $condolence]) }}"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذه التعزية نهائيًا؟')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 bg-white px-4 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                        >
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm leading-7 text-gray-600">
                            لا توجد أي تعزيات لهذا الشهيد.
                        </p>
                    @endforelse
                </div>

                @if ($condolences->hasPages())
                    <div class="border-t border-gray-100 px-6 py-5">
                        {{ $condolences->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
