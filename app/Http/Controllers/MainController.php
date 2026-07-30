<?php

namespace App\Http\Controllers;

use App\Models\FeaturedContent;
use App\Models\Martyr;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $featuredMartyrs = FeaturedContent::query()
            ->forSection(FeaturedContent::SECTION_MARTYRS)
            ->with('martyr.profileImg')
            ->latest()
            ->limit(4)
            ->get()
            ->pluck('martyr')
            ->filter();
        $featuredStories = FeaturedContent::query()
            ->forSection(FeaturedContent::SECTION_STORIES)
            ->with('story.martyr.profileImg')
            ->latest()
            ->limit(3)
            ->get()
            ->pluck('story')
            ->filter();
        $featuredMemoryImages = FeaturedContent::query()
            ->forSection(FeaturedContent::SECTION_MEMORY_IMAGES)
            ->with('memoryImage.martyr')
            ->latest()
            ->limit(4)
            ->get()
            ->pluck('memoryImage')
            ->filter();

        return view('front.index', compact(
            'featuredMartyrs',
            'featuredStories',
            'featuredMemoryImages',
        ));
    }

    public function martyr(Request $request, ?Martyr $martyr = null)
    {
        abort_unless($martyr?->exists, 404);

        $martyr->loadMissing(['profileImg', 'story', 'momeriesImg']);

        $approvedCondolences = $martyr->condolences()
            ->approved()
            ->oldest()
            ->paginate(10, ['*'], 'condolences_page')
            ->withQueryString();
        $hasSubmittedCondolence = PublicCondolenceController::hasExistingSubmission(
            $request,
            $martyr,
        );
        $d = $martyr;

        return view(
            'front.martyr',
            compact('d', 'approvedCondolences', 'hasSubmittedCondolence'),
        );
    }

    public function search(Request $req)
    {
        // 1. التحقق من الطلب أو المساعدة في حمايته دون إرجاع Redirect يفسد الـ Fetch
        if (! $req->wantsJson() && ! $req->ajax()) {
            return response()->json(['message' => 'Invalid Request'], 400);
        }

        $queryText = trim($req->q);

        // 2. إرجاع نتيجة فارغة بهيكل صحيح وموحد إذا كان النص فارغاً
        if (! filled($queryText)) {
            return Martyr::query()->whereRaw('1 = 0')->paginate(27);
        }

        // 3. توحيد بناء النتيجة بالـ paginate لكلا الحالتين (بالهوية أو بالاسم)
        if (is_numeric($queryText)) {
            return Martyr::query()
                ->where('national_id', $queryText)
                ->paginate(27);
        }

        return Martyr::query()
            ->searchArabicName($queryText)
            ->paginate(27);
    }

    public function martyr_search()
    {
        return view('front.search');
    }

    public function about()
    {
        return view('front.about');
    }

    public function contact()
    {
        return view('front.contact');
    }
}
