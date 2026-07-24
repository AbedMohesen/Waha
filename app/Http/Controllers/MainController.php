<?php

namespace App\Http\Controllers;

use App\Models\Martyr;
use Illuminate\Http\Request;

class MainController extends Controller
{
    function index()
    {
        return view('front.index');
    }
    function martyr($id)
    {
        $d = Martyr::where('id', $id)->first();
        return view('front.martyr', compact('d'));
    }
    function search(Request $req)
    {
        if (!$req->ajax()) {
            return redirect()->route('front.index');
        }
        if (!filled($req->q)) {
            return [];
        }
        if (is_numeric($req->q)) {
            return response()->json([
                'data' => Martyr::where('national_id', $req->q)->get(),
            ]);
        } else {
            $names = explode(' ', trim($req->q));
            $query = Martyr::query();
            foreach ($names as $name) {
                $query->where('name_ar', 'LIKE', "%{$name}%");
            }
            return $query->paginate(27);
        }
    }
    function martyr_search()
    {
        return view('front.search');
    }
}
