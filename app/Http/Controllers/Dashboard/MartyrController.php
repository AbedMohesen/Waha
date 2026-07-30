<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Martyr;
use Illuminate\Http\Request;

class MartyrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.martyr.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.martyr.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'national_id' => 'required|max:9',
            'name_ar' => 'required|string|min:2|max:250',
            'sex' => 'required|max:1',
            'age' => 'required|max:200',
            'date_barth' => 'required|date',
        ]);
        Martyr::create([
            'national_id' => $request->national_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'sex' => $request->sex,
            'age' => $request->age,
            'born' => $request->date_barth,
        ]);
        flash()->success('تم الاضافة بنجاح ');

        return redirect()->route('dashboard.martyr.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Martyr $martyr)
    {
        $martyr->loadMissing(['story', 'profileImg', 'momeriesImg']);

        $condolences = $martyr->condolences()
            ->oldest()
            ->paginate(10, ['*'], 'martyr_condolences_page')
            ->withQueryString();

        return view('dashboard.martyr.show', compact('martyr', 'condolences'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $martyr = Martyr::find($id);

        return view('dashboard.martyr.edit', compact('martyr'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Martyr $martyr)
    {
        $request->validate([
            'national_id' => 'required|max:9',
            'name_ar' => 'required|string|min:2|max:250',
            'sex' => 'required|max:1',
            'age' => 'required|max:200',
            'date_barth' => 'required|date',
        ]);
        $martyr->update([
            'national_id' => $request->national_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'sex' => $request->sex,
            'age' => $request->age,
            'born' => $request->date_barth,
        ]);

        flash()->success('تم الاضافة بنجاح ');

        return redirect()->route('dashboard.martyr.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $martyr = Martyr::find($id);
        $martyr->delete();

        return redirect()->route('dashboard.martyr.index');
    }
}
