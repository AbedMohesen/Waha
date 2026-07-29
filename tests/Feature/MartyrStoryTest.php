<?php

use App\Models\Martyr;
use App\Models\User;
use Flasher\Prime\Test\FlasherAssert;

test('story pages and store route require dashboard authentication', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للاختبار',
    ]);

    $this
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertRedirect(route('login'));

    $this
        ->post(route('dashboard.martyr.story.store', $martyr), [
            'title' => 'عنوان القصة',
            'content' => 'نص القصة',
        ])
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('stories', 0);
});

test('show page displays the empty story state and modal controls', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بلا قصة',
        'national_id' => '123456789',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr));

    $response
        ->assertOk()
        ->assertViewHas('martyr', function (Martyr $viewMartyr) use ($martyr): bool {
            return $viewMartyr->is($martyr)
                && $viewMartyr->relationLoaded('story')
                && ! $viewMartyr->story->exists;
        })
        ->assertSeeText('قصة الشهيد')
        ->assertSeeText('لم تتم إضافة قصة لهذا الشهيد بعد.')
        ->assertSeeText('إضافة قصة')
        ->assertSee(route('dashboard.martyr.story.store', $martyr), false)
        ->assertSee('name="_token"', false)
        ->assertSee('name="title"', false)
        ->assertSee('name="content"', false)
        ->assertSee("\$dispatch('open-modal', 'add-martyr-story')", false)
        ->assertSee("\$dispatch('close')", false)
        ->assertSee('style="display: none;"', false);
});

test('story validation returns to show with old input and an open modal', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للتحقق',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $response = $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.story.store', $martyr), [
            'title' => 'عنوان محفوظ مؤقتًا',
            'content' => '',
        ]);

    $response
        ->assertRedirect($showUrl)
        ->assertSessionHasErrorsIn('storyCreation', ['content']);

    $this->assertDatabaseCount('stories', 0);

    $this
        ->get($showUrl)
        ->assertOk()
        ->assertSee('value="عنوان محفوظ مؤقتًا"', false)
        ->assertSeeText('نص القصة مطلوب.')
        ->assertSee('style="display: block;"', false);
});

test('story title is required by the existing database schema', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بلا عنوان',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.story.store', $martyr), [
            'title' => '',
            'content' => 'يوجد نص للقصة.',
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr))
        ->assertSessionHasErrorsIn('storyCreation', ['title']);

    $this->assertDatabaseCount('stories', 0);
});

test('non string story input is rejected without breaking the show page', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بمدخلات غير صالحة',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.story.store', $martyr), [
            'title' => ['عنوان غير صالح'],
            'content' => ['محتوى غير صالح'],
        ])
        ->assertRedirect($showUrl)
        ->assertSessionHasErrorsIn('storyCreation', ['title', 'content']);

    $this
        ->get($showUrl)
        ->assertOk()
        ->assertSee('style="display: block;"', false);

    $this->assertDatabaseCount('stories', 0);
});

test('story store uses route model binding', function () {
    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.story.store', 999999), [
            'title' => 'عنوان',
            'content' => 'محتوى',
        ])
        ->assertNotFound();

    $this->assertDatabaseCount('stories', 0);
});

test('an authenticated user can add a story to the bound martyr', function () {
    $martyr = Martyr::create([
        'name_ar' => 'الشهيد صاحب القصة',
        'national_id' => '987654321',
    ]);
    $otherMartyr = Martyr::create([
        'name_ar' => 'شهيد آخر',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $response = $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.story.store', $martyr), [
            'title' => 'عنوان موثق',
            'content' => "السطر الأول\nالسطر الثاني",
            'martyr_id' => $otherMartyr->id,
        ]);

    $response->assertRedirect($showUrl);

    $this->assertDatabaseHas('stories', [
        'martyr_id' => $martyr->id,
        'title' => 'عنوان موثق',
        'content' => "السطر الأول\nالسطر الثاني",
    ]);
    $this->assertDatabaseMissing('stories', [
        'martyr_id' => $otherMartyr->id,
    ]);

    $page = $this->get($showUrl);

    FlasherAssert::notification('success', 'تمت إضافة قصة الشهيد بنجاح.');

    $page
        ->assertOk()
        ->assertSeeText('الشهيد صاحب القصة')
        ->assertSeeText('987654321')
        ->assertSeeText('عنوان موثق')
        ->assertSee("السطر الأول<br />\nالسطر الثاني", false)
        ->assertDontSeeText('إضافة قصة')
        ->assertDontSee('name="content"', false)
        ->assertDontSee("\$dispatch('open-modal', 'add-martyr-story')", false);
});

test('story output is escaped while preserving line breaks', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بقصة آمنة',
    ]);
    $martyr->story()->create([
        'title' => '<b>عنوان آمن</b>',
        'content' => "سطر أول\n<script>alert(\"xss\")</script>",
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSee('&lt;b&gt;عنوان آمن&lt;/b&gt;', false)
        ->assertSee('سطر أول<br />', false)
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false)
        ->assertDontSee('<b>عنوان آمن</b>', false)
        ->assertDontSee('<script>alert("xss")</script>', false);
});

test('a second story request is rejected without replacing the existing story', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بقصة واحدة',
    ]);
    $martyr->story()->create([
        'title' => 'القصة الأصلية',
        'content' => 'المحتوى الأصلي',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $response = $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.story.store', $martyr), [
            'title' => 'قصة ثانية',
            'content' => 'يجب ألا تحفظ',
        ]);

    $response->assertRedirect($showUrl);

    $this->assertDatabaseCount('stories', 1);
    $this->assertDatabaseHas('stories', [
        'martyr_id' => $martyr->id,
        'title' => 'القصة الأصلية',
        'content' => 'المحتوى الأصلي',
    ]);
    $this->assertDatabaseMissing('stories', [
        'title' => 'قصة ثانية',
    ]);

    $this->get($showUrl)->assertOk();

    FlasherAssert::notification('error', 'توجد قصة مضافة لهذا الشهيد بالفعل.');
});

test('adding a story does not change public search results', function () {
    $martyr = Martyr::create([
        'name_ar' => 'اسم بحث مميز',
        'national_id' => '112233445',
    ]);
    $martyr->story()->create([
        'title' => 'قصة',
        'content' => 'محتوى',
    ]);
    $user = User::factory()->create();

    $this
        ->get(route('front.search'))
        ->assertOk();

    $this
        ->actingAs($user)
        ->get(route('dashboard.martyr.index'))
        ->assertOk();

    $this
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('search', ['q' => '112233445']))
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.id', $martyr->id)
        ->assertJsonMissingPath('data.0.story');
});
