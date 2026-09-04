<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="oasis-kicker">تفاصيل السجل</p>
                <h1 class="oasis-heading mt-1">{{ $martyr->name_ar }}</h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.martyr.edit', $martyr->id) }}"
                    class="oasis-button oasis-button-primary text-xs">
                    <i class="fa-solid fa-pen text-xs"></i>
                    <span>تعديل السجل</span>
                </a>
                <a href="{{ route('dashboard.martyr.index') }}"
                    class="oasis-button oasis-button-outline text-xs">
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                    <span>العودة للسجلات</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="oasis-container py-8 sm:py-10 space-y-8">
        <!-- Basic Information Card -->
        <section class="oasis-card overflow-hidden">
            <div class="border-b border-black/5 bg-oasis-cream px-6 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-oasis-house" dir="rtl">{{ $martyr->name_ar }}</h2>
                    @if ($martyr->name_en)
                        <p class="mt-0.5 text-xs text-black/50" dir="ltr">{{ $martyr->name_en }}</p>
                    @endif
                </div>
                <a href="{{ route('martyr', $martyr) }}" target="_blank" class="oasis-button oasis-button-outline text-xs !min-h-9 !px-3">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>عرض بالواجهة العامة</span>
                </a>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x sm:divide-x-reverse divide-black/5">
                <div class="px-6 py-4">
                    <dt class="text-xs font-semibold text-black/50">الرقم الوطني</dt>
                    <dd class="mt-1 text-sm font-bold text-oasis-house">{{ $martyr->national_id ?: '-' }}</dd>
                </div>
                <div class="px-6 py-4">
                    <dt class="text-xs font-semibold text-black/50">الجنس</dt>
                    <dd class="mt-1 text-sm font-bold text-oasis-house">{{ $martyr->sex === 'm' ? 'ذكر' : ($martyr->sex === 'f' ? 'أنثى' : ($martyr->sex ?: '-')) }}</dd>
                </div>
                <div class="px-6 py-4">
                    <dt class="text-xs font-semibold text-black/50">العمر</dt>
                    <dd class="mt-1 text-sm font-bold text-oasis-house">{{ $martyr->age ? $martyr->age . ' سنة' : '-' }}</dd>
                </div>
                <div class="px-6 py-4 border-t border-black/5">
                    <dt class="text-xs font-semibold text-black/50">تاريخ الميلاد</dt>
                    <dd class="mt-1 text-sm font-bold text-oasis-house">{{ $martyr->born ?: '-' }}</dd>
                </div>
                <div class="px-6 py-4 border-t border-black/5">
                    <dt class="text-xs font-semibold text-black/50">تاريخ الإضافة</dt>
                    <dd class="mt-1 text-sm font-bold text-oasis-house">{{ $martyr->created_at?->format('Y-m-d H:i') ?: '-' }}</dd>
                </div>
                <div class="px-6 py-4 border-t border-black/5">
                    <dt class="text-xs font-semibold text-black/50">آخر تحديث</dt>
                    <dd class="mt-1 text-sm font-bold text-oasis-house">{{ $martyr->updated_at?->format('Y-m-d H:i') ?: '-' }}</dd>
                </div>
            </dl>
        </section>

        @php
            $hasProfileImage = $martyr->profileImg->exists;
            $profileImagePath = $hasProfileImage ? $martyr->profileImg->img_path : null;
            $profileImageFileExists = false;

            if (filled($profileImagePath)) {
                try {
                    $profileImageFileExists = \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($profileImagePath);
                } catch (\Throwable) {
                    $profileImageFileExists = false;
                }
            }

            $profileImageUrl = $profileImageFileExists
                ? asset('assets/img/' . ltrim(str_replace('\\', '/', $profileImagePath), '/'))
                : asset('assets/img/No-photo-m.png');
        @endphp

        <!-- Profile Image Section -->
        <section class="oasis-card overflow-hidden" dir="rtl">
            <div class="flex flex-col gap-4 border-b border-black/5 bg-oasis-cream px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                        <i class="fa-solid fa-image"></i>
                    </span>
                    <h2 class="text-base font-bold text-oasis-house">الصورة الشخصية</h2>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'manage-martyr-profile-image')"
                        class="oasis-button oasis-button-primary text-xs !min-h-9 !px-3.5"
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
                                class="oasis-button oasis-button-danger text-xs !min-h-9 !px-3"
                            >
                                حذف الصورة
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <img
                        src="{{ $profileImageUrl }}"
                        alt="الصورة الشخصية لـ {{ $martyr->name_ar }}"
                        class="h-28 w-28 rounded-full border-4 border-white object-cover shadow-card"
                    >
                    <div>
                        @if ($hasProfileImage)
                            @if (! $profileImageFileExists)
                                <span class="oasis-pill-gold text-xs">تعذر العثور على ملف الصورة المحفوظ</span>
                                <p class="mt-2 text-xs text-black/50">الملف غير موجود على وسيط التخزين، يرجى استبدال الصورة.</p>
                            @else
                                <span class="oasis-pill-mint text-xs">صورة شخصية معتمدة</span>
                                <p class="mt-2 text-xs text-black/50">تم حفظ الصورة بنجاح وتظهر في صفحة السجل العامة.</p>
                            @endif
                        @else
                            <p class="text-sm font-semibold text-black/70">لم تتم إضافة صورة شخصية لهذا الشهيد بعد.</p>
                            <p class="mt-1 text-xs text-black/45">يمكنك رفع صورة شخصية واضحة لإبرازها في السجل.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Image Modal -->
            <x-modal
                name="manage-martyr-profile-image"
                :show="$errors->profileImage->isNotEmpty()"
                focusable
            >
                <form
                    method="POST"
                    action="{{ route('dashboard.martyr.profile-image.update', $martyr) }}"
                    enctype="multipart/form-data"
                    dir="rtl"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <h3 class="oasis-heading text-lg">
                        {{ $hasProfileImage ? 'استبدال الصورة الشخصية' : 'إضافة صورة شخصية' }}
                    </h3>

                    <p class="oasis-copy text-xs">
                        اختر ملف صورة (JPG, PNG, WebP) بحجم لا يتجاوز 5 ميغابايت.
                    </p>

                    <div class="mt-4">
                        <x-input-label for="profile_image_file" value="ملف الصورة" />
                        <input
                            id="profile_image_file"
                            name="img_path"
                            type="file"
                            accept="image/*"
                            required
                            class="oasis-input mt-1 !p-2"
                        >
                        <x-input-error :messages="$errors->profileImage->get('img_path')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex flex-row-reverse items-center gap-2 border-t border-black/5 pt-4">
                        <button type="submit" class="oasis-button oasis-button-primary text-xs">
                            حفظ الصورة
                        </button>
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            إلغاء
                        </x-secondary-button>
                    </div>
                </form>
            </x-modal>
        </section>

        <!-- Story Section -->
        @php
            $hasStory = $martyr->story && $martyr->story->exists;
        @endphp
        <section class="oasis-card overflow-hidden" dir="rtl">
            <div class="flex flex-col gap-4 border-b border-black/5 bg-oasis-cream px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                        <i class="fa-solid fa-book-open"></i>
                    </span>
                    <h2 class="text-base font-bold text-oasis-house">قصة الشهيد</h2>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if ($hasStory)
                        <button
                            type="button"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'edit-martyr-story')"
                            class="oasis-button oasis-button-primary text-xs !min-h-9 !px-3.5"
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
                                class="oasis-button oasis-button-danger text-xs !min-h-9 !px-3"
                            >
                                حذف القصة
                            </button>
                        </form>
                    @else
                        <button
                            type="button"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'add-martyr-story')"
                            class="oasis-button oasis-button-primary text-xs !min-h-9 !px-3.5"
                        >
                            إضافة قصة
                        </button>
                    @endif
                </div>
            </div>

            <div class="p-6">
                @if ($hasStory)
                    <h3 class="text-lg font-bold text-oasis-house">{{ $martyr->story->title }}</h3>
                    <div class="oasis-copy mt-3 whitespace-pre-line text-sm leading-8 text-black/75">
                        {!! nl2br(e($martyr->story->content)) !!}
                    </div>
                @else
                    <p class="text-sm font-semibold text-black/70">لم تتم إضافة قصة لهذا الشهيد بعد.</p>
                    <p class="mt-1 text-xs text-black/45">يمكنك كتابة سيرة أو قصة توثيقية ترتبط بحياة وسيرة الشهيد.</p>
                @endif
            </div>

            <!-- Add Story Modal -->
            @if (! $hasStory)
                <x-modal
                    name="add-martyr-story"
                    :show="$errors->storyCreation->isNotEmpty()"
                    focusable
                >
                    <form
                        method="POST"
                        action="{{ route('dashboard.martyr.story.store', $martyr) }}"
                        dir="rtl"
                        class="space-y-4"
                    >
                        @csrf
                        <h3 class="oasis-heading text-lg">إضافة قصة جديدة</h3>
                        <div>
                            <x-input-label for="add_story_title" value="عنوان القصة" />
                            <x-text-input
                                id="add_story_title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('title')"
                                required
                                maxlength="255"
                            />
                            <x-input-error :messages="$errors->storyCreation->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="add_story_content" value="نص القصة" />
                            <textarea
                                id="add_story_content"
                                name="content"
                                rows="6"
                                required
                                class="oasis-input mt-1 block w-full resize-y"
                            >{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->storyCreation->get('content')" class="mt-2" />
                        </div>
                        <div class="mt-6 flex flex-row-reverse items-center gap-2 border-t border-black/5 pt-4">
                            <button type="submit" class="oasis-button oasis-button-primary text-xs">
                                حفظ القصة
                            </button>
                            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                إلغاء
                            </x-secondary-button>
                        </div>
                    </form>
                </x-modal>
            @endif

            <!-- Edit Story Modal -->
            @if ($hasStory)
                <x-modal
                    name="edit-martyr-story"
                    :show="$errors->storyUpdate->isNotEmpty()"
                    focusable
                >
                    <form
                        method="POST"
                        action="{{ route('dashboard.martyr.story.update', [$martyr, $martyr->story]) }}"
                        dir="rtl"
                        class="space-y-4"
                    >
                        @csrf
                        @method('PUT')
                        <h3 class="oasis-heading text-lg">تعديل القصة</h3>
                        <div>
                            <x-input-label for="edit_story_title" value="عنوان القصة" />
                            <x-text-input
                                id="edit_story_title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('title', $martyr->story->title)"
                                required
                                maxlength="255"
                            />
                            <x-input-error :messages="$errors->storyUpdate->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="edit_story_content" value="نص القصة" />
                            <textarea
                                id="edit_story_content"
                                name="content"
                                rows="6"
                                required
                                class="oasis-input mt-1 block w-full resize-y"
                            >{{ old('content', $martyr->story->content) }}</textarea>
                            <x-input-error :messages="$errors->storyUpdate->get('content')" class="mt-2" />
                        </div>
                        <div class="mt-6 flex flex-row-reverse items-center gap-2 border-t border-black/5 pt-4">
                            <button type="submit" class="oasis-button oasis-button-primary text-xs">
                                حفظ التعديلات
                            </button>
                            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                إلغاء
                            </x-secondary-button>
                        </div>
                    </form>
                </x-modal>
            @endif
        </section>

        <!-- Memories Images Section -->
        <section class="oasis-card overflow-hidden" dir="rtl">
            <div class="flex flex-col gap-4 border-b border-black/5 bg-oasis-cream px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                        <i class="fa-solid fa-images"></i>
                    </span>
                    <h2 class="text-base font-bold text-oasis-house">صور الذكريات</h2>
                </div>

                <button
                    type="button"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'add-martyr-memory-image')"
                    class="oasis-button oasis-button-primary text-xs !min-h-9 !px-3.5"
                >
                    إضافة صورة ذكرى
                </button>
            </div>

            <div class="p-6">
                @if ($martyr->momeriesImg->isEmpty())
                    <p class="text-sm font-semibold text-black/70">لا توجد صور ذكريات مضافة.</p>
                    <p class="mt-1 text-xs text-black/45">أضف صورًا ووثائق تذكارية ترتبط بالشهيد.</p>
                @else
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($martyr->momeriesImg as $memory)
                            @php
                                $memoryFileExists = false;
                                if (filled($memory->img_path)) {
                                    try {
                                        $memoryFileExists = \Illuminate\Support\Facades\Storage::disk('martyr_images')->exists($memory->img_path);
                                    } catch (\Throwable) {
                                        $memoryFileExists = false;
                                    }
                                }
                                $memoryUrl = $memoryFileExists
                                    ? asset('assets/img/' . ltrim(str_replace('\\', '/', $memory->img_path), '/'))
                                    : asset('assets/img/No-photo-m.png');
                            @endphp
                            <div class="rounded-[12px] border border-black/5 bg-oasis-cream overflow-hidden flex flex-col justify-between">
                                <img src="{{ $memoryUrl }}" alt="{{ $memory->caption ?: 'صورة ذكرى' }}" class="h-32 w-full object-cover">
                                <div class="p-3 bg-white flex-1 flex flex-col justify-between">
                                    @if ($memory->caption)
                                        <p class="text-xs text-black/70 line-clamp-2">{{ $memory->caption }}</p>
                                    @else
                                        <p class="text-xs text-black/40 italic">بدون وصف</p>
                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('dashboard.martyr.memories.destroy', [$martyr, $memory]) }}"
                                        onsubmit="return confirm('هل أنت متأكد من حذف صورة الذكرى؟ لا يمكن التراجع عن هذا الإجراء.')"
                                        class="mt-3 pt-2 border-t border-black/5"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="oasis-button oasis-button-danger text-[11px] !min-h-7 !px-2.5 w-full">
                                            حذف الصورة
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Add Memory Image Modal -->
            <x-modal
                name="add-martyr-memory-image"
                :show="$errors->memoryImage->isNotEmpty()"
                focusable
            >
                <form
                    method="POST"
                    action="{{ route('dashboard.martyr.memories.store', $martyr) }}"
                    enctype="multipart/form-data"
                    dir="rtl"
                    class="space-y-4"
                >
                    @csrf
                    <h3 class="oasis-heading text-lg">إضافة صورة ذكرى</h3>
                    <div>
                        <x-input-label for="memory_image_file" value="ملف الصورة" />
                        <input
                            id="memory_image_file"
                            name="img_path"
                            type="file"
                            accept="image/*"
                            required
                            class="oasis-input mt-1 !p-2"
                        >
                        <x-input-error :messages="$errors->memoryImage->get('img_path')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="memory_caption" value="وصف الصورة (اختياري)" />
                        <x-text-input
                            id="memory_caption"
                            name="caption"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('caption')"
                            maxlength="255"
                        />
                        <x-input-error :messages="$errors->memoryImage->get('caption')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex flex-row-reverse items-center gap-2 border-t border-black/5 pt-4">
                        <button type="submit" class="oasis-button oasis-button-primary text-xs">
                            حفظ الصورة
                        </button>
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            إلغاء
                        </x-secondary-button>
                    </div>
                </form>
            </x-modal>
        </section>

        <!-- Condolences Management Section -->
        <section class="oasis-card overflow-hidden" dir="rtl">
            <div class="border-b border-black/5 bg-oasis-cream px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-oasis-mint text-oasis-green text-xs">
                        <i class="fa-solid fa-comments"></i>
                    </span>
                    <h2 class="text-base font-bold text-oasis-house">إدارة التعزيات</h2>
                </div>
                <span class="oasis-pill-mint text-xs">{{ $condolences->total() }} تعزية</span>
            </div>

            <div class="space-y-4 p-6">
                @forelse ($condolences as $condolence)
                    <article class="rounded-[12px] bg-oasis-cream p-5 border border-black/5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if (filled($condolence->author_name))
                                        <h3 class="font-bold text-sm text-oasis-house">{{ $condolence->author_name }}</h3>
                                    @endif

                                    @if ($condolence->status === 'pending')
                                        <span class="oasis-pill-gold text-xs">بانتظار المراجعة</span>
                                    @else
                                        <span class="oasis-pill-mint text-xs">تمت الموافقة</span>
                                    @endif
                                </div>

                                <time
                                    datetime="{{ $condolence->created_at->toIso8601String() }}"
                                    class="mt-1.5 block text-xs text-black/45"
                                >
                                    {{ $condolence->created_at->format('Y-m-d H:i') }}
                                </time>

                                <p class="mt-3 whitespace-pre-line break-words text-sm leading-7 text-black/75">{{ $condolence->content }}</p>
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
                                            class="oasis-button oasis-button-primary text-xs !min-h-9 !px-3.5"
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
                                        class="oasis-button oasis-button-danger text-xs !min-h-9 !px-3"
                                    >
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="py-8 text-center text-sm text-black/55">
                        <i class="fa-regular fa-comment-dots text-3xl text-black/20 mb-2 block"></i>
                        لا توجد أي تعزيات لهذا الشهيد.
                    </div>
                @endforelse
            </div>

            @if ($condolences->hasPages())
                <div class="border-t border-black/5 px-6 py-4">
                    {{ $condolences->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
