<?php

namespace App\Http\Controllers;

use App\Models\Condolence;
use App\Models\Martyr;
use App\Rules\NoUrls;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PublicCondolenceController extends Controller
{
    private const COOKIE_LIFETIME_MINUTES = 525600;

    public function store(Request $request, Martyr $martyr): RedirectResponse
    {
        $cookieName = self::cookieName($martyr);

        if (self::hasExistingSubmission($request, $martyr)) {
            return redirect()
                ->route('martyr', $martyr)
                ->withErrors(
                    ['content' => 'لقد أرسلت تعزية لهذا الشهيد مسبقًا.'],
                    'condolence',
                );
        }

        $input = [
            'author_name' => is_string($request->input('author_name'))
                ? trim($request->input('author_name'))
                : $request->input('author_name'),
            'content' => is_string($request->input('content'))
                ? trim($request->input('content'))
                : $request->input('content'),
        ];

        $validator = Validator::make(
            $input,
            [
                'author_name' => ['nullable', 'string', 'max:255'],
                'content' => ['required', 'string', 'max:1000', new NoUrls],
            ],
            [
                'author_name.string' => 'يجب أن يكون اسم الزائر نصًا.',
                'author_name.max' => 'يجب ألا يتجاوز اسم الزائر 255 حرفًا.',
                'content.required' => 'يرجى كتابة رسالة التعزية.',
                'content.string' => 'يجب أن تكون رسالة التعزية نصًا.',
                'content.max' => 'يجب ألا تتجاوز رسالة التعزية 1000 حرف.',
            ],
        );

        if ($validator->fails()) {
            return redirect()
                ->route('martyr', $martyr)
                ->withErrors($validator, 'condolence')
                ->withInput($input);
        }

        $validated = $validator->validated();

        try {
            $condolence = $martyr->condolences()->create([
                'author_name' => filled($validated['author_name'] ?? null)
                    ? $validated['author_name']
                    : 'أحد الزوار',
                'content' => $validated['content'],
                'status' => Condolence::STATUS_PENDING,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('martyr', $martyr)
                ->withErrors(
                    ['content' => 'تعذر إرسال التعزية حاليًا، يرجى المحاولة مرة أخرى.'],
                    'condolence',
                )
                ->withInput($input);
        }

        flash()->success('تم إرسال تعزيتك بنجاح، وستظهر بعد مراجعتها.');

        return redirect()
            ->route('martyr', $martyr)
            ->withCookie(Cookie::make(
                $cookieName,
                (string) $condolence->getKey(),
                self::COOKIE_LIFETIME_MINUTES,
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                'lax',
            ));
    }

    public static function cookieName(Martyr $martyr): string
    {
        return 'martyr_condolence_submitted_'.$martyr->getKey();
    }

    public static function hasExistingSubmission(Request $request, Martyr $martyr): bool
    {
        $condolenceId = $request->cookie(self::cookieName($martyr));

        if (! is_scalar($condolenceId) || ! ctype_digit((string) $condolenceId)) {
            return false;
        }

        return $martyr->condolences()
            ->whereKey((int) $condolenceId)
            ->exists();
    }
}
