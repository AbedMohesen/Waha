<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Martyr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    public function store(Request $request, Martyr $martyr): RedirectResponse
    {
        if ($martyr->story()->exists()) {
            return $this->redirectWithExistingStory($martyr);
        }

        $validator = Validator::make(
            $request->only(['title', 'content']),
            [
                'title' => ['required', 'string'],
                'content' => ['required', 'string'],
            ],
            [
                'title.required' => 'عنوان القصة مطلوب.',
                'title.string' => 'يجب أن يكون عنوان القصة نصًا.',
                'content.required' => 'نص القصة مطلوب.',
                'content.string' => 'يجب أن يكون محتوى القصة نصًا.',
            ],
        );

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.martyr.show', $martyr)
                ->withErrors($validator, 'storyCreation')
                ->withInput([
                    'title' => is_string($request->input('title')) ? $request->input('title') : '',
                    'content' => is_string($request->input('content')) ? $request->input('content') : '',
                ]);
        }

        $validated = $validator->validated();

        $storyCreated = DB::transaction(function () use ($martyr, $validated): bool {
            $lockedMartyr = Martyr::query()
                ->whereKey($martyr->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedMartyr->story()->exists()) {
                return false;
            }

            $lockedMartyr->story()->create($validated);

            return true;
        });

        if (!$storyCreated) {
            return $this->redirectWithExistingStory($martyr);
        }

        flash()->success('تمت إضافة قصة الشهيد بنجاح.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    private function redirectWithExistingStory(Martyr $martyr): RedirectResponse
    {
        flash()->error('توجد قصة مضافة لهذا الشهيد بالفعل.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }
}
