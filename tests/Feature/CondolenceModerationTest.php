<?php

use App\Http\Controllers\PublicCondolenceController;
use App\Models\Condolence;
use App\Models\Martyr;
use App\Models\User;
use Flasher\Prime\Test\FlasherAssert;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('the public martyr page displays the condolence form without authentication', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد التعزيات',
    ]);

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSeeText('إرسال تعزية')
        ->assertSeeText('يمكنك كتابة رسالة تعزية، وستظهر بعد مراجعتها.')
        ->assertSee(route('martyr.condolences.store', $martyr), false)
        ->assertSee('name="author_name"', false)
        ->assertSee('name="content"', false)
        ->assertSee('name="_token"', false)
        ->assertDontSee('name="status"', false)
        ->assertDontSee('name="martyr_id"', false);

    expect($martyr->condolences())->toBeInstanceOf(HasMany::class);
});

test('empty whitespace and oversized condolence messages are rejected with old input', function (string $content) {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد التحقق',
    ]);
    $cookieName = PublicCondolenceController::cookieName($martyr);

    $response = $this->post(route('martyr.condolences.store', $martyr), [
        'author_name' => '  زائر كريم  ',
        'content' => $content,
    ]);

    $response
        ->assertRedirect(route('martyr', $martyr))
        ->assertSessionHasErrorsIn('condolence', ['content'])
        ->assertSessionHasInput('author_name', 'زائر كريم')
        ->assertCookieMissing($cookieName);

    $this->assertDatabaseCount('condolences', 0);
})->with([
    'empty' => '',
    'whitespace only' => " \t \n ",
    'longer than one thousand characters' => str_repeat('ا', 1001),
]);

test('condolence messages containing links are rejected', function (string $content) {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد منع الروابط',
    ]);

    $this
        ->post(route('martyr.condolences.store', $martyr), [
            'content' => $content,
        ])
        ->assertRedirect(route('martyr', $martyr))
        ->assertSessionHasErrorsIn('condolence', ['content'])
        ->assertCookieMissing(PublicCondolenceController::cookieName($martyr));

    $this->assertDatabaseCount('condolences', 0);
})->with([
    'http' => 'تعزية على http://example.test',
    'https' => 'تعزية على https://example.test',
    'www' => 'زيارة www.example.test',
    'html anchor' => '<a href="/somewhere">رابط</a>',
    'domain-like link' => 'يمكنكم زيارة example.com للمزيد',
]);

test('a valid condolence is trimmed and forced to pending for the route-bound martyr', function () {
    $martyr = Martyr::create([
        'name_ar' => 'الشهيد المقصود',
    ]);
    $otherMartyr = Martyr::create([
        'name_ar' => 'شهيد آخر',
    ]);
    $cookieName = PublicCondolenceController::cookieName($martyr);

    $response = $this->post(route('martyr.condolences.store', $martyr), [
        'author_name' => '  صاحب التعزية  ',
        'content' => '  رحم الله الشهيد وأسكنه فسيح جناته.  ',
        'status' => Condolence::STATUS_APPROVED,
        'martyr_id' => $otherMartyr->id,
    ]);

    $response
        ->assertRedirect(route('martyr', $martyr))
        ->assertCookie($cookieName);

    $condolence = Condolence::query()->sole();

    expect($condolence->author_name)->toBe('صاحب التعزية')
        ->and($condolence->content)->toBe('رحم الله الشهيد وأسكنه فسيح جناته.')
        ->and($condolence->status)->toBe(Condolence::STATUS_PENDING)
        ->and($condolence->martyr_id)->toBe($martyr->id);

    $cookie = collect($response->headers->getCookies())
        ->first(fn ($cookie) => $cookie->getName() === $cookieName);

    expect($cookie)->not->toBeNull()
        ->and($cookie->getExpiresTime())->toBeGreaterThan(now()->addMonths(11)->timestamp);

    $this->get(route('martyr', $martyr))->assertOk();

    FlasherAssert::notification(
        'success',
        'تم إرسال تعزيتك بنجاح، وستظهر بعد مراجعتها.',
    );
});

test('an omitted visitor name uses the neutral visitor label', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد التعزية المجهولة',
    ]);

    $this
        ->post(route('martyr.condolences.store', $martyr), [
            'content' => 'خالص العزاء والمواساة.',
        ])
        ->assertRedirect(route('martyr', $martyr));

    expect(Condolence::query()->sole()->author_name)->toBe('أحد الزوار');
});

test('the martyr cookie prevents duplicate submission but does not block another martyr', function () {
    $firstMartyr = Martyr::create([
        'name_ar' => 'الشهيد الأول',
    ]);
    $secondMartyr = Martyr::create([
        'name_ar' => 'الشهيد الثاني',
    ]);
    $firstCookieName = PublicCondolenceController::cookieName($firstMartyr);
    $existingCondolence = $firstMartyr->condolences()->create([
        'author_name' => 'الزائر الأول',
        'content' => 'التعزية الأصلية',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->withCookie($firstCookieName, (string) $existingCondolence->id)
        ->post(route('martyr.condolences.store', $firstMartyr), [
            'content' => 'تعزية مكررة',
        ])
        ->assertRedirect(route('martyr', $firstMartyr))
        ->assertSessionHasErrorsIn('condolence', ['content']);

    $this->assertDatabaseCount('condolences', 1);

    $this
        ->withCookie($firstCookieName, (string) $existingCondolence->id)
        ->post(route('martyr.condolences.store', $secondMartyr), [
            'content' => 'تعزية لشهيد مختلف',
        ])
        ->assertRedirect(route('martyr', $secondMartyr))
        ->assertCookie(PublicCondolenceController::cookieName($secondMartyr));

    $this->assertDatabaseHas('condolences', [
        'martyr_id' => $secondMartyr->id,
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->withCookie($firstCookieName, (string) $existingCondolence->id)
        ->get(route('martyr', $firstMartyr))
        ->assertOk()
        ->assertSeeText('لقد أرسلت تعزية لهذا الشهيد مسبقًا.')
        ->assertDontSee(route('martyr.condolences.store', $firstMartyr), false);
});

test('rate limiting blocks the fourth attempt for the same ip and martyr without a cookie', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد تحديد المعدل',
    ]);
    $route = route('martyr.condolences.store', $martyr);

    foreach (range(1, 3) as $attempt) {
        $this
            ->withServerVariables(['REMOTE_ADDR' => '198.51.100.25'])
            ->post($route, ['content' => ''])
            ->assertSessionHasErrorsIn('condolence', ['content']);
    }

    $response = $this
        ->withServerVariables(['REMOTE_ADDR' => '198.51.100.25'])
        ->post($route, [
            'content' => 'هذه المحاولة يجب أن يمنعها محدد المعدل.',
        ]);

    $response
        ->assertRedirect(route('martyr', $martyr))
        ->assertSessionHasErrorsIn('condolence', ['content'])
        ->assertCookieMissing(PublicCondolenceController::cookieName($martyr));

    expect(session('errors')->getBag('condolence')->first('content'))
        ->toContain('تم تجاوز عدد المحاولات');
    $this->assertDatabaseCount('condolences', 0);
});

test('only approved condolences are shown publicly and visitor content is escaped', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد العرض العام',
    ]);
    $approved = $martyr->condolences()->create([
        'author_name' => '<b>زائر</b>',
        'content' => '<script>alert("xss")</script> رحم الله الشهيد',
        'status' => Condolence::STATUS_APPROVED,
    ]);
    $martyr->condolences()->create([
        'author_name' => 'زائر معلق',
        'content' => 'هذه رسالة معلقة سرية',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSeeText($approved->content)
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertSee('&lt;b&gt;زائر&lt;/b&gt;', false)
        ->assertDontSee('<script>alert("xss")</script>', false)
        ->assertDontSeeText('هذه رسالة معلقة سرية');
});

test('approved condolences use their own ten-item pagination query string', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد صفحات التعزية',
    ]);
    $baseTime = now()->subDays(20);

    foreach (range(1, 11) as $number) {
        $martyr->condolences()->create([
            'author_name' => 'زائر '.$number,
            'content' => 'تعزية منشورة رقم '.$number,
            'status' => Condolence::STATUS_APPROVED,
            'created_at' => $baseTime->copy()->addMinutes($number),
            'updated_at' => $baseTime->copy()->addMinutes($number),
        ]);
    }

    $firstPage = $this->get(route('martyr', $martyr));

    $firstPage
        ->assertOk()
        ->assertSeeText('تعزية منشورة رقم 1')
        ->assertSeeText('تعزية منشورة رقم 10')
        ->assertDontSeeText('تعزية منشورة رقم 11')
        ->assertSee('condolences_page=2', false);

    $this
        ->get(route('martyr', [
            'martyr' => $martyr,
            'condolences_page' => 2,
        ]))
        ->assertOk()
        ->assertSeeText('تعزية منشورة رقم 11')
        ->assertDontSee('>تعزية منشورة رقم 1<', false);
});

test('the public page displays the approved condolences empty state', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بلا تعزيات منشورة',
    ]);
    $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'رسالة لم تعتمد بعد',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSeeText('لا توجد تعزيات منشورة حتى الآن.')
        ->assertDontSeeText('رسالة لم تعتمد بعد');
});

test('the existing public story and memory images remain visible', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد القصة والذكرى',
    ]);
    $martyr->story()->create([
        'title' => 'قصة باقية',
        'content' => 'تفاصيل قصة الشهيد',
    ]);
    $martyr->momeriesImg()->create([
        'img_path' => 'martyrs/memory-stays.jpg',
        'caption' => 'ذكرى باقية',
    ]);

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSeeText('قصة باقية')
        ->assertSeeText('تفاصيل قصة الشهيد')
        ->assertSee(asset('assets/img/martyrs/memory-stays.jpg'), false);
});

test('moderation routes require dashboard authentication', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد حماية الإدارة',
    ]);
    $condolence = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية محمية',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->get(route('dashboard.condolences.index'))
        ->assertRedirect(route('login'));
    $this
        ->patch(route('dashboard.condolences.approve', $condolence))
        ->assertRedirect(route('login'));
    $this
        ->delete(route('dashboard.condolences.reject', $condolence))
        ->assertRedirect(route('login'));

    expect($condolence->fresh()->status)->toBe(Condolence::STATUS_PENDING);
});

test('the moderation queue eager loads martyrs paginates and allow-lists filters', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد قائمة المراجعة',
    ]);

    foreach (range(1, 16) as $number) {
        $martyr->condolences()->create([
            'author_name' => 'زائر '.$number,
            'content' => 'تعزية معلقة رقم '.$number,
            'status' => Condolence::STATUS_PENDING,
        ]);
    }

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.condolences.index', ['status' => 'unsafe-column']));

    $response
        ->assertOk()
        ->assertViewHas('filter', Condolence::STATUS_PENDING)
        ->assertViewHas('condolences', function ($condolences): bool {
            return $condolences->perPage() === 15
                && $condolences->total() === 16
                && collect($condolences->items())
                    ->every(fn (Condolence $condolence) => $condolence->relationLoaded('martyr'));
        })
        ->assertSeeText('شهيد قائمة المراجعة')
        ->assertSeeText('تعزية معلقة رقم 1')
        ->assertDontSeeText('تعزية معلقة رقم 16')
        ->assertSee('page=2', false);
});

test('an admin can approve a pending condolence and it then appears publicly', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد اعتماد التعزية',
    ]);
    $condolence = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية ستعتمد',
        'status' => Condolence::STATUS_PENDING,
    ]);
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(route('dashboard.condolences.index'))
        ->assertOk()
        ->assertSeeText('مراجعة التعزيات')
        ->assertSeeText('تعزية ستعتمد')
        ->assertSee(route('dashboard.condolences.approve', $condolence), false)
        ->assertSee('name="_method" value="PATCH"', false)
        ->assertSee('data-pending-condolences-count="1"', false);

    $this
        ->patch(route('dashboard.condolences.approve', $condolence))
        ->assertRedirect(route('dashboard.condolences.index'));

    expect($condolence->fresh()->status)->toBe(Condolence::STATUS_APPROVED);

    $this->get(route('dashboard.condolences.index'))->assertOk();

    FlasherAssert::notification('success', 'تمت الموافقة على التعزية بنجاح.');

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSeeText('تعزية ستعتمد');

    $this
        ->get(route('dashboard.condolences.index'))
        ->assertOk()
        ->assertDontSeeText('تعزية ستعتمد')
        ->assertDontSee('data-pending-condolences-count=', false);
});

test('an admin can permanently reject a pending condolence but cannot reject an approved one', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد رفض التعزية',
    ]);
    $pending = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية سترفض',
        'status' => Condolence::STATUS_PENDING,
    ]);
    $approved = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية معتمدة محمية',
        'status' => Condolence::STATUS_APPROVED,
    ]);

    $page = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.condolences.index'));

    $page
        ->assertOk()
        ->assertSee(route('dashboard.condolences.reject', $pending), false)
        ->assertSee('name="_method" value="DELETE"', false)
        ->assertSee('هل أنت متأكد من رفض هذه التعزية وحذفها نهائيًا؟', false);

    $this
        ->delete(route('dashboard.condolences.reject', $pending))
        ->assertRedirect(route('dashboard.condolences.index'));

    $this->assertDatabaseMissing('condolences', [
        'id' => $pending->id,
    ]);

    $this->get(route('dashboard.condolences.index'))->assertOk();

    FlasherAssert::notification('success', 'تم رفض التعزية وحذفها بنجاح.');

    $this
        ->delete(route('dashboard.condolences.reject', $approved))
        ->assertRedirect(route('dashboard.condolences.index'));

    $this->assertDatabaseHas('condolences', [
        'id' => $approved->id,
        'status' => Condolence::STATUS_APPROVED,
    ]);

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertDontSeeText('تعزية سترفض')
        ->assertSeeText('تعزية معتمدة محمية');
});
