<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Martyr;
use App\Models\MomeriesImg;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MomeriesImgController extends Controller
{
    private const DISK = 'martyr_images';

    public function store(Request $request, Martyr $martyr): RedirectResponse
    {
        $validator = Validator::make(
            [
                'img_path' => $request->file('img_path'),
                'caption' => $request->input('caption'),
            ],
            [
                'img_path' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'extensions:jpg,jpeg,png,webp',
                    'max:5120',
                ],
                'caption' => ['nullable', 'string', 'max:255'],
            ],
            [
                'img_path.required' => 'يرجى اختيار صورة ذكرى.',
                'img_path.file' => 'يجب رفع ملف صالح.',
                'img_path.image' => 'يجب أن يكون الملف صورة صالحة.',
                'img_path.mimes' => 'يجب أن تكون الصورة من نوع JPG أو JPEG أو PNG أو WEBP.',
                'img_path.extensions' => 'امتداد الصورة غير مسموح به.',
                'img_path.max' => 'يجب ألا يتجاوز حجم الصورة 5 ميغابايت.',
                'caption.string' => 'يجب أن يكون وصف الصورة نصًا.',
                'caption.max' => 'يجب ألا يتجاوز وصف الصورة 255 حرفًا.',
            ],
        );

        if ($validator->fails()) {
            return $this->redirectWithErrors($request, $martyr, $validator);
        }

        try {
            $storedPath = $request->file('img_path')->store(
                "martyrs/{$martyr->getKey()}/memories",
                self::DISK,
            );
        } catch (Throwable $exception) {
            report($exception);

            return $this->redirectWithStorageError($request, $martyr);
        }

        if (! is_string($storedPath) || $storedPath === '') {
            return $this->redirectWithStorageError($request, $martyr);
        }

        $validated = $validator->validated();

        try {
            $martyr->momeriesImg()->create([
                'img_path' => $storedPath,
                'caption' => filled($validated['caption'] ?? null)
                    ? $validated['caption']
                    : null,
            ]);
        } catch (Throwable $exception) {
            $this->deleteStoredFile($storedPath);
            report($exception);

            return $this->redirectWithStorageError($request, $martyr);
        }

        flash()->success('تمت إضافة صورة الذكرى بنجاح.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    public function destroy(Martyr $martyr, MomeriesImg $memory): RedirectResponse
    {
        $ownedMemory = $martyr->momeriesImg()
            ->whereKey($memory->getKey())
            ->firstOrFail();
        $storedPath = $ownedMemory->img_path;

        $ownedMemory->delete();
        $this->deleteStoredFile($storedPath);

        flash()->success('تم حذف صورة الذكرى بنجاح.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    private function redirectWithErrors(
        Request $request,
        Martyr $martyr,
        ValidatorContract $validator,
    ): RedirectResponse {
        return redirect()
            ->route('dashboard.martyr.show', $martyr)
            ->withErrors($validator, 'memoryImage')
            ->withInput([
                'caption' => is_string($request->input('caption'))
                    ? $request->input('caption')
                    : '',
            ]);
    }

    private function redirectWithStorageError(
        Request $request,
        Martyr $martyr,
    ): RedirectResponse {
        return redirect()
            ->route('dashboard.martyr.show', $martyr)
            ->withErrors(
                ['img_path' => 'تعذر حفظ الصورة حاليًا، يرجى المحاولة مرة أخرى.'],
                'memoryImage',
            )
            ->withInput([
                'caption' => is_string($request->input('caption'))
                    ? $request->input('caption')
                    : '',
            ]);
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! is_string($path) || trim($path) === '') {
            return;
        }

        $protectedFiles = [
            'No-photo-m.png',
            'noImg.png',
            'no-img.jpg',
            'icon.png',
            'icon1.png',
        ];

        if (in_array(basename(str_replace('\\', '/', $path)), $protectedFiles, true)) {
            return;
        }

        try {
            Storage::disk(self::DISK)->delete($path);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
