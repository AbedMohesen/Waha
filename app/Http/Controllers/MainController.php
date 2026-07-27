<?php

namespace App\Http\Controllers;

use App\Models\Martyr;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $featuredMartyrs = Martyr::inRandomOrder()->limit(4)->get();

        return view('front.index', compact('featuredMartyrs'));
    }

    public function martyr($id)
    {
        $d = Martyr::where('id', $id)->first();

        return view('front.martyr', compact('d'));
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
