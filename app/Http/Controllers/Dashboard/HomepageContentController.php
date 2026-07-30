<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FeaturedContent;
use App\Models\Martyr;
use App\Models\MomeriesImg;
use App\Models\Story;
use App\Support\ArabicText;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HomepageContentController extends Controller
{
    public function index(Request $request): View
    {
        $selectedMartyrs = $this->selected(
            FeaturedContent::SECTION_MARTYRS,
            ['martyr.profileImg'],
        );
        $selectedStories = $this->selected(
            FeaturedContent::SECTION_STORIES,
            ['story.martyr.profileImg'],
        );
        $selectedMemoryImages = $this->selected(
            FeaturedContent::SECTION_MEMORY_IMAGES,
            ['memoryImage.martyr'],
        );

        $martyrSearch = trim((string) $request->query('martyrs_q', ''));
        $storySearch = trim((string) $request->query('stories_q', ''));
        $memorySearch = trim((string) $request->query('memories_q', ''));

        $availableMartyrs = Martyr::query()
            ->with('profileImg')
            ->whereNotIn('id', $selectedMartyrs->pluck('martyr_id'))
            ->when(
                $martyrSearch !== '',
                fn (Builder $query) => $query->searchArabicName($martyrSearch),
            )
            ->orderBy('name_ar')
            ->limit(20)
            ->get();

        $availableStories = Story::query()
            ->with('martyr.profileImg')
            ->whereHas('martyr')
            ->whereNotIn('id', $selectedStories->pluck('story_id'))
            ->when(
                $storySearch !== '',
                fn (Builder $query) => $this->searchStories($query, $storySearch),
            )
            ->latest()
            ->limit(20)
            ->get();

        $availableMemoryImages = MomeriesImg::query()
            ->with('martyr')
            ->whereHas('martyr')
            ->whereNotIn('id', $selectedMemoryImages->pluck('momeries_img_id'))
            ->when(
                $memorySearch !== '',
                fn (Builder $query) => $this->searchMemoryImages($query, $memorySearch),
            )
            ->latest()
            ->limit(20)
            ->get();

        return view('dashboard.homepage-content.index', compact(
            'selectedMartyrs',
            'selectedStories',
            'selectedMemoryImages',
            'availableMartyrs',
            'availableStories',
            'availableMemoryImages',
            'martyrSearch',
            'storySearch',
            'memorySearch',
        ));
    }

    public function store(Request $request, string $section): RedirectResponse
    {
        $limit = FeaturedContent::limitFor($section);
        $foreignKey = FeaturedContent::foreignKeyFor($section);

        abort_unless($limit !== null && $foreignKey !== null, 404);

        $validated = $request->validateWithBag($section, [
            'record_id' => ['required', 'integer', 'min:1'],
        ], [
            'record_id.required' => 'يرجى اختيار سجل لإضافته.',
            'record_id.integer' => 'السجل المحدد غير صالح.',
            'record_id.min' => 'السجل المحدد غير صالح.',
        ]);

        try {
            $result = Cache::lock('featured-content-section:'.$section, 10)
                ->block(5, function () use ($section, $foreignKey, $limit, $validated): string {
                    return DB::transaction(function () use ($section, $foreignKey, $limit, $validated): string {
                        $lockedAssignments = FeaturedContent::query()
                            ->forSection($section)
                            ->lockForUpdate()
                            ->get(['id', $foreignKey]);
                        $recordId = (int) $validated['record_id'];

                        if ($lockedAssignments->contains($foreignKey, $recordId)) {
                            return 'duplicate';
                        }

                        if ($lockedAssignments->count() >= $limit) {
                            return 'limit';
                        }

                        if (! $this->sourceExists($section, $recordId)) {
                            return 'missing';
                        }

                        FeaturedContent::query()->create([
                            'section' => $section,
                            $foreignKey => $recordId,
                        ]);

                        return 'created';
                    }, 3);
                });
        } catch (LockTimeoutException) {
            $result = 'busy';
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() !== '23000') {
                throw $exception;
            }

            $result = 'duplicate';
        }

        if ($result !== 'created') {
            return $this->redirectWithError($section, $result);
        }

        flash()->success($this->successMessage($section));

        return redirect()->route('dashboard.homepage-content.index');
    }

    public function destroy(FeaturedContent $featuredContent): RedirectResponse
    {
        abort_unless(in_array($featuredContent->section, FeaturedContent::SECTIONS, true), 404);

        $section = $featuredContent->section;
        $featuredContent->delete();

        flash()->success($this->removalMessage($section));

        return redirect()->route('dashboard.homepage-content.index');
    }

    private function selected(string $section, array $relations)
    {
        return FeaturedContent::query()
            ->forSection($section)
            ->with($relations)
            ->latest()
            ->get();
    }

    private function sourceExists(string $section, int $recordId): bool
    {
        return match ($section) {
            FeaturedContent::SECTION_MARTYRS => Martyr::query()
                ->whereKey($recordId)
                ->lockForUpdate()
                ->first(['id']) !== null,
            FeaturedContent::SECTION_STORIES => Story::query()
                ->whereKey($recordId)
                ->whereHas('martyr')
                ->lockForUpdate()
                ->first(['id']) !== null,
            FeaturedContent::SECTION_MEMORY_IMAGES => MomeriesImg::query()
                ->whereKey($recordId)
                ->whereHas('martyr')
                ->lockForUpdate()
                ->first(['id']) !== null,
            default => false,
        };
    }

    private function searchStories(Builder $query, string $search): Builder
    {
        $like = '%'.ArabicText::escapeLike($search).'%';

        return $query->where(function (Builder $query) use ($like, $search): void {
            $query
                ->whereRaw("title LIKE ? ESCAPE '!'", [$like])
                ->orWhereRaw("content LIKE ? ESCAPE '!'", [$like])
                ->orWhereHas(
                    'martyr',
                    fn (Builder $martyrQuery) => $martyrQuery->searchArabicName($search),
                );
        });
    }

    private function searchMemoryImages(Builder $query, string $search): Builder
    {
        $like = '%'.ArabicText::escapeLike($search).'%';

        return $query->where(function (Builder $query) use ($like, $search): void {
            $query
                ->whereRaw("caption LIKE ? ESCAPE '!'", [$like])
                ->orWhereHas(
                    'martyr',
                    fn (Builder $martyrQuery) => $martyrQuery->searchArabicName($search),
                );
        });
    }

    private function redirectWithError(string $section, string $error): RedirectResponse
    {
        $message = match ($error) {
            'duplicate' => 'هذا السجل موجود بالفعل ضمن القسم المحدد.',
            'limit' => $this->limitMessage($section),
            'busy' => 'تعذر إتمام الطلب بسبب وجود عملية أخرى. يرجى المحاولة مرة أخرى.',
            default => 'السجل المحدد غير موجود أو لم يعد متاحًا.',
        };

        $validation = ValidationException::withMessages([
            'record_id' => $message,
        ]);
        $validation->errorBag = $section;

        throw $validation;
    }

    private function limitMessage(string $section): string
    {
        return match ($section) {
            FeaturedContent::SECTION_MARTYRS => 'تم الوصول إلى الحد الأقصى لأبرز الشهداء.',
            FeaturedContent::SECTION_STORIES => 'تم الوصول إلى الحد الأقصى لأبرز القصص.',
            FeaturedContent::SECTION_MEMORY_IMAGES => 'تم الوصول إلى الحد الأقصى لأبرز صور الذكريات.',
            default => 'تم الوصول إلى الحد الأقصى لهذا القسم.',
        };
    }

    private function successMessage(string $section): string
    {
        return match ($section) {
            FeaturedContent::SECTION_MARTYRS => 'تمت إضافة الشهيد إلى قائمة أبرز الشهداء.',
            FeaturedContent::SECTION_STORIES => 'تمت إضافة القصة إلى قائمة أبرز القصص.',
            FeaturedContent::SECTION_MEMORY_IMAGES => 'تمت إضافة الصورة إلى قائمة أبرز صور الذكريات.',
            default => 'تمت إضافة المحتوى إلى الصفحة الرئيسية.',
        };
    }

    private function removalMessage(string $section): string
    {
        return match ($section) {
            FeaturedContent::SECTION_MARTYRS => 'تمت إزالة الشهيد من قائمة أبرز الشهداء.',
            FeaturedContent::SECTION_STORIES => 'تمت إزالة القصة من قائمة أبرز القصص.',
            FeaturedContent::SECTION_MEMORY_IMAGES => 'تمت إزالة الصورة من قائمة أبرز صور الذكريات.',
            default => 'تمت إزالة المحتوى من الصفحة الرئيسية.',
        };
    }
}
