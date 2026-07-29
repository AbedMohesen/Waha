<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Martyr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProfileImgController extends Controller
{
    private const DISK = 'martyr_images';

    public function update(Request $request, Martyr $martyr): RedirectResponse
    {
        $validator = Validator::make(
            ['img_path' => $request->file('img_path')],
            [
                'img_path' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'extensions:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'img_path.required' => 'يرجى اختيار صورة شخصية.',
                'img_path.file' => 'يجب رفع ملف صالح.',
                'img_path.image' => 'يجب أن يكون الملف صورة صالحة.',
                'img_path.mimes' => 'يجب أن تكون الصورة من نوع JPG أو JPEG أو PNG أو WEBP.',
                'img_path.extensions' => 'امتداد الصورة غير مسموح به.',
                'img_path.max' => 'يجب ألا يتجاوز حجم الصورة 5 ميغابايت.',
            ],
        );

        if ($validator->fails()) {
            return $this->redirectWithErrors($martyr, $validator);
        }

        try {
            $newPath = $request->file('img_path')->store(
                "martyrs/{$martyr->getKey()}/profile",
                self::DISK,
            );
        } catch (Throwable $exception) {
            report($exception);

            return $this->redirectWithStorageError($martyr);
        }

        if (! is_string($newPath) || $newPath === '') {
            return $this->redirectWithStorageError($martyr);
        }

        $oldPath = null;
        $replacedExistingImage = false;

        try {
            DB::transaction(function () use (
                $martyr,
                $newPath,
                &$oldPath,
                &$replacedExistingImage,
            ): void {
                $lockedMartyr = Martyr::query()
                    ->whereKey($martyr->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $profileImage = $lockedMartyr->profileImg()->first();

                if ($profileImage) {
                    $oldPath = $profileImage->img_path;
                    $replacedExistingImage = true;
                    $profileImage->update(['img_path' => $newPath]);

                    return;
                }

                $lockedMartyr->profileImg()->create(['img_path' => $newPath]);
            });
        } catch (Throwable $exception) {
            $this->deleteStoredFile($newPath);
            report($exception);

            return $this->redirectWithStorageError($martyr);
        }

        if ($replacedExistingImage && $oldPath !== $newPath) {
            $this->deleteStoredFile($oldPath);
        }

        flash()->success(
            $replacedExistingImage
                ? 'تم استبدال الصورة الشخصية بنجاح.'
                : 'تمت إضافة الصورة الشخصية بنجاح.',
        );

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    public function destroy(Martyr $martyr): RedirectResponse
    {
        $deletedImagePath = DB::transaction(function () use ($martyr): ?string {
            $lockedMartyr = Martyr::query()
                ->whereKey($martyr->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $profileImage = $lockedMartyr->profileImg()->first();

            if (! $profileImage) {
                return null;
            }

            $path = $profileImage->img_path;
            $profileImage->delete();

            return $path;
        });

        if ($deletedImagePath === null) {
            flash()->error('لا توجد صورة شخصية محفوظة لهذا الشهيد.');

            return redirect()->route('dashboard.martyr.show', $martyr);
        }

        $this->deleteStoredFile($deletedImagePath);
        flash()->success('تم حذف الصورة الشخصية بنجاح.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    private function redirectWithErrors(
        Martyr $martyr,
        \Illuminate\Contracts\Validation\Validator $validator,
    ): RedirectResponse {
        return redirect()
            ->route('dashboard.martyr.show', $martyr)
            ->withErrors($validator, 'profileImage');
    }

    private function redirectWithStorageError(Martyr $martyr): RedirectResponse
    {
        return redirect()
            ->route('dashboard.martyr.show', $martyr)
            ->withErrors(
                ['img_path' => 'تعذر حفظ الصورة حاليًا، يرجى المحاولة مرة أخرى.'],
                'profileImage',
            );
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
