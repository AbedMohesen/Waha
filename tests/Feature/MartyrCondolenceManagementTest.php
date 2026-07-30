<?php

use App\Http\Controllers\PublicCondolenceController;
use App\Models\Condolence;
use App\Models\Martyr;
use App\Models\User;

test('martyr condolence management routes require dashboard authentication', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد حماية التعزيات',
    ]);
    $condolence = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية محمية',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->patch(route('dashboard.martyr.condolences.approve', [$martyr, $condolence]))
        ->assertRedirect(route('login'));
    $this
        ->delete(route('dashboard.martyr.condolences.destroy', [$martyr, $condolence]))
        ->assertRedirect(route('login'));

    $this->assertDatabaseHas('condolences', [
        'id' => $condolence->id,
        'status' => Condolence::STATUS_PENDING,
    ]);
});

test('martyr show displays all of its condolences with status-specific actions', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد إدارة التعزيات',
    ]);
    $pending = $martyr->condolences()->create([
        'author_name' => 'زائر منتظر',
        'content' => 'تعزية معلقة داخل الصفحة',
        'status' => Condolence::STATUS_PENDING,
    ]);
    $approved = $martyr->condolences()->create([
        'author_name' => 'زائر معتمد',
        'content' => 'تعزية معتمدة داخل الصفحة',
        'status' => Condolence::STATUS_APPROVED,
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr));

    $response
        ->assertOk()
        ->assertSeeText('إدارة التعزيات')
        ->assertSeeText('زائر منتظر')
        ->assertSeeText('تعزية معلقة داخل الصفحة')
        ->assertSeeText('بانتظار المراجعة')
        ->assertSeeText('زائر معتمد')
        ->assertSeeText('تعزية معتمدة داخل الصفحة')
        ->assertSeeText('تمت الموافقة')
        ->assertSee(route('dashboard.martyr.condolences.approve', [$martyr, $pending]), false)
        ->assertDontSee(route('dashboard.martyr.condolences.approve', [$martyr, $approved]), false)
        ->assertSee(route('dashboard.martyr.condolences.destroy', [$martyr, $pending]), false)
        ->assertSee(route('dashboard.martyr.condolences.destroy', [$martyr, $approved]), false)
        ->assertSee('name="_method" value="PATCH"', false)
        ->assertSee('name="_method" value="DELETE"', false)
        ->assertSee('هل أنت متأكد من حذف هذه التعزية نهائيًا؟', false);
});

test('martyr show displays the condolence empty state and paginates ten per page', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد صفحات الإدارة',
    ]);
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSeeText('لا توجد أي تعزيات لهذا الشهيد.');

    $baseTime = now()->subDays(15);

    foreach (range(1, 11) as $number) {
        $martyr->condolences()->create([
            'author_name' => 'زائر '.$number,
            'content' => 'تعزية إدارية رقم '.$number,
            'status' => $number % 2 === 0
                ? Condolence::STATUS_APPROVED
                : Condolence::STATUS_PENDING,
            'created_at' => $baseTime->copy()->addMinutes($number),
            'updated_at' => $baseTime->copy()->addMinutes($number),
        ]);
    }

    $this
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSeeText('تعزية إدارية رقم 1')
        ->assertSeeText('تعزية إدارية رقم 10')
        ->assertDontSee('>تعزية إدارية رقم 11<', false)
        ->assertSee('martyr_condolences_page=2', false);

    $this
        ->get(route('dashboard.martyr.show', [
            'martyr' => $martyr,
            'martyr_condolences_page' => 2,
        ]))
        ->assertOk()
        ->assertSeeText('تعزية إدارية رقم 11')
        ->assertDontSee('>تعزية إدارية رقم 1<', false);
});

test('approving from martyr show synchronizes the global queue and public page', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد مزامنة الاعتماد',
    ]);
    $condolence = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية ستنتقل إلى النشر',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->patch(route('dashboard.martyr.condolences.approve', [$martyr, $condolence]))
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    expect($condolence->fresh()->status)->toBe(Condolence::STATUS_APPROVED);

    $this
        ->get(route('dashboard.condolences.index'))
        ->assertOk()
        ->assertDontSeeText('تعزية ستنتقل إلى النشر');

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSeeText('تعزية ستنتقل إلى النشر');
});

test('deleting pending or approved condolences removes them everywhere', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد مزامنة الحذف',
    ]);
    $pending = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية معلقة ستحذف',
        'status' => Condolence::STATUS_PENDING,
    ]);
    $approved = $martyr->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية منشورة ستحذف',
        'status' => Condolence::STATUS_APPROVED,
    ]);
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->delete(route('dashboard.martyr.condolences.destroy', [$martyr, $pending]))
        ->assertRedirect(route('dashboard.martyr.show', $martyr));
    $this
        ->delete(route('dashboard.martyr.condolences.destroy', [$martyr, $approved]))
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $this->assertDatabaseMissing('condolences', ['id' => $pending->id]);
    $this->assertDatabaseMissing('condolences', ['id' => $approved->id]);

    $this
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSeeText('لا توجد أي تعزيات لهذا الشهيد.')
        ->assertDontSeeText('تعزية معلقة ستحذف')
        ->assertDontSeeText('تعزية منشورة ستحذف');

    $this
        ->get(route('dashboard.condolences.index', ['status' => 'all']))
        ->assertOk()
        ->assertDontSeeText('تعزية معلقة ستحذف')
        ->assertDontSeeText('تعزية منشورة ستحذف');

    $this
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertDontSeeText('تعزية منشورة ستحذف');
});

test('a stale submission cookie allows resubmission after its condolence is deleted', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد الكوكي القديم',
    ]);
    $deletedCondolence = $martyr->condolences()->create([
        'author_name' => 'الزائر نفسه',
        'content' => 'تعزية حذفتها الإدارة',
        'status' => Condolence::STATUS_APPROVED,
    ]);
    $cookieName = PublicCondolenceController::cookieName($martyr);

    $this
        ->actingAs(User::factory()->create())
        ->delete(route('dashboard.martyr.condolences.destroy', [$martyr, $deletedCondolence]))
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $this
        ->withCookie($cookieName, (string) $deletedCondolence->id)
        ->get(route('martyr', $martyr))
        ->assertOk()
        ->assertSee(route('martyr.condolences.store', $martyr), false)
        ->assertDontSeeText('لقد أرسلت تعزية لهذا الشهيد مسبقًا.');

    $this
        ->withCookie($cookieName, (string) $deletedCondolence->id)
        ->post(route('martyr.condolences.store', $martyr), [
            'author_name' => 'الزائر نفسه',
            'content' => 'تعزية جديدة بعد حذف السابقة',
        ])
        ->assertRedirect(route('martyr', $martyr))
        ->assertCookie($cookieName);

    $this->assertDatabaseHas('condolences', [
        'martyr_id' => $martyr->id,
        'content' => 'تعزية جديدة بعد حذف السابقة',
        'status' => Condolence::STATUS_PENDING,
    ]);
});

test('a condolence belonging to another martyr cannot be approved or deleted', function () {
    $selectedMartyr = Martyr::create([
        'name_ar' => 'الشهيد المحدد',
    ]);
    $owner = Martyr::create([
        'name_ar' => 'صاحب التعزية',
    ]);
    $foreignCondolence = $owner->condolences()->create([
        'author_name' => 'زائر',
        'content' => 'تعزية محمية من تغيير الشهيد',
        'status' => Condolence::STATUS_PENDING,
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->patch(route('dashboard.martyr.condolences.approve', [$selectedMartyr, $foreignCondolence]))
        ->assertNotFound();
    $this
        ->delete(route('dashboard.martyr.condolences.destroy', [$selectedMartyr, $foreignCondolence]))
        ->assertNotFound();

    $this->assertDatabaseHas('condolences', [
        'id' => $foreignCondolence->id,
        'martyr_id' => $owner->id,
        'status' => Condolence::STATUS_PENDING,
    ]);
});
