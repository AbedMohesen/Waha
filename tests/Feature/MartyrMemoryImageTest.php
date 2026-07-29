<?php

use App\Models\Martyr;
use App\Models\User;
use Flasher\Prime\Test\FlasherAssert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('martyr_images');
});

test('memory image routes require dashboard authentication', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للاختبار',
    ]);
    $path = "martyrs/{$martyr->id}/memories/protected.jpg";

    Storage::disk('martyr_images')->put($path, 'memory image');
    $memory = $martyr->momeriesImg()->create([
        'img_path' => $path,
        'caption' => 'ذكرى محمية',
    ]);

    $this
        ->post(route('dashboard.martyr.memories.store', $martyr), [
            'img_path' => UploadedFile::fake()->image('memory.jpg'),
        ])
        ->assertRedirect(route('login'));

    $this
        ->delete(route('dashboard.martyr.memories.destroy', [$martyr, $memory]))
        ->assertRedirect(route('login'));

    $this->assertDatabaseHas('momeries_imgs', [
        'id' => $memory->id,
    ]);
    Storage::disk('martyr_images')->assertExists($path);
});

test('show page displays the empty memory state and upload form', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بلا صور ذكريات',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr));

    $response
        ->assertOk()
        ->assertViewHas('martyr', function (Martyr $viewMartyr) use ($martyr): bool {
            return $viewMartyr->is($martyr)
                && $viewMartyr->relationLoaded('story')
                && $viewMartyr->relationLoaded('momeriesImg')
                && $viewMartyr->momeriesImg->isEmpty();
        })
        ->assertSeeText('صور الذكريات')
        ->assertSeeText('لا توجد صور ذكريات مضافة.')
        ->assertSeeText('إضافة صورة ذكرى')
        ->assertSee(route('dashboard.martyr.memories.store', $martyr), false)
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('name="img_path"', false)
        ->assertSee('name="caption"', false)
        ->assertSee('name="_token"', false)
        ->assertSee("\$dispatch('open-modal', 'add-martyr-memory-image')", false);
});

test('missing memory image validation preserves caption and opens its modal', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للتحقق',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.memories.store', $martyr), [
            'caption' => 'وصف محفوظ مؤقتًا',
        ])
        ->assertRedirect($showUrl)
        ->assertSessionHasErrorsIn('memoryImage', ['img_path']);

    $this->assertDatabaseCount('momeries_imgs', 0);

    $this
        ->get($showUrl)
        ->assertOk()
        ->assertSee('value="وصف محفوظ مؤقتًا"', false)
        ->assertSeeText('يرجى اختيار صورة ذكرى.')
        ->assertSee('add-martyr-memory-image', false)
        ->assertSee('style="display: block;"', false);
});

test('memory upload rejects executable and invalid files', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بملف غير صالح',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.memories.store', $martyr), [
            'img_path' => UploadedFile::fake()->image('payload.pht'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr))
        ->assertSessionHasErrorsIn('memoryImage', ['img_path']);

    $this->assertDatabaseCount('momeries_imgs', 0);
    expect(Storage::disk('martyr_images')->allFiles())->toBeEmpty();
});

test('an authenticated user can upload a memory image with a caption', function () {
    $martyr = Martyr::create([
        'name_ar' => 'الشهيد صاحب الذكرى',
    ]);
    $otherMartyr = Martyr::create([
        'name_ar' => 'شهيد آخر',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);
    $caption = '<script>alert("xss")</script> ذكرى موثقة';

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.memories.store', $martyr), [
            'img_path' => UploadedFile::fake()->image('memory.webp', 800, 600)->size(512),
            'caption' => $caption,
            'martyr_id' => $otherMartyr->id,
        ])
        ->assertRedirect($showUrl);

    $memory = $martyr->momeriesImg()->firstOrFail();

    expect($memory->martyr_id)->toBe($martyr->id)
        ->and($memory->img_path)->toStartWith("martyrs/{$martyr->id}/memories/")
        ->and($memory->caption)->toBe($caption);

    Storage::disk('martyr_images')->assertExists($memory->img_path);
    $this->assertDatabaseMissing('momeries_imgs', [
        'martyr_id' => $otherMartyr->id,
    ]);

    $page = $this->get($showUrl);

    FlasherAssert::notification('success', 'تمت إضافة صورة الذكرى بنجاح.');

    $page
        ->assertOk()
        ->assertSee(asset('assets/img/'.$memory->img_path), false)
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt; ذكرى موثقة', false)
        ->assertDontSee('<script>alert("xss")</script>', false)
        ->assertSee(route('dashboard.martyr.memories.destroy', [$martyr, $memory]), false)
        ->assertSee('هل أنت متأكد من حذف صورة الذكرى؟ لا يمكن التراجع عن هذا الإجراء.', false);
});

test('memory image caption can be left empty', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بذكرى دون وصف',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.martyr.memories.store', $martyr), [
            'img_path' => UploadedFile::fake()->image('memory.png'),
            'caption' => '',
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $memory = $martyr->momeriesImg()->firstOrFail();

    expect($memory->caption)->toBeNull();
    Storage::disk('martyr_images')->assertExists($memory->img_path);
});

test('a missing memory file displays the placeholder instead of a broken URL', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بصورة ذكرى مفقودة',
    ]);
    $missingPath = "martyrs/{$martyr->id}/memories/missing.jpg";

    $martyr->momeriesImg()->create([
        'img_path' => $missingPath,
        'caption' => 'صورة مفقودة',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSee(asset('assets/img/No-photo-m.png'), false)
        ->assertDontSee(asset('assets/img/'.$missingPath), false)
        ->assertSeeText('صورة مفقودة');
});

test('deleting a memory image removes its record and stored file', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد لحذف الذكرى',
    ]);
    $path = "martyrs/{$martyr->id}/memories/to-delete.jpg";

    Storage::disk('martyr_images')->put($path, 'memory image');
    $memory = $martyr->momeriesImg()->create([
        'img_path' => $path,
        'caption' => 'ذكرى ستحذف',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $this
        ->actingAs(User::factory()->create())
        ->delete(route('dashboard.martyr.memories.destroy', [$martyr, $memory]))
        ->assertRedirect($showUrl);

    $this->assertDatabaseMissing('momeries_imgs', [
        'id' => $memory->id,
    ]);
    Storage::disk('martyr_images')->assertMissing($path);

    $page = $this->get($showUrl);

    FlasherAssert::notification('success', 'تم حذف صورة الذكرى بنجاح.');

    $page
        ->assertOk()
        ->assertSeeText('لا توجد صور ذكريات مضافة.')
        ->assertSeeText('إضافة صورة ذكرى')
        ->assertDontSee('action="'.route('dashboard.martyr.memories.destroy', [$martyr, $memory]).'"', false);
});

test('a memory image belonging to another martyr cannot be deleted', function () {
    $selectedMartyr = Martyr::create([
        'name_ar' => 'الشهيد المحدد',
    ]);
    $memoryOwner = Martyr::create([
        'name_ar' => 'صاحب الذكرى',
    ]);
    $path = "martyrs/{$memoryOwner->id}/memories/protected.jpg";

    Storage::disk('martyr_images')->put($path, 'protected memory');
    $foreignMemory = $memoryOwner->momeriesImg()->create([
        'img_path' => $path,
        'caption' => 'ذكرى محمية من IDOR',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->delete(route('dashboard.martyr.memories.destroy', [$selectedMartyr, $foreignMemory]))
        ->assertNotFound();

    $this->assertDatabaseHas('momeries_imgs', [
        'id' => $foreignMemory->id,
        'martyr_id' => $memoryOwner->id,
    ]);
    Storage::disk('martyr_images')->assertExists($path);
});

test('memory image operations leave story display and search payload unchanged', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للقصة والبحث',
        'national_id' => '556677889',
    ]);
    $martyr->story()->create([
        'title' => 'قصة باقية',
        'content' => 'محتوى القصة الباقي',
    ]);
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->post(route('dashboard.martyr.memories.store', $martyr), [
            'img_path' => UploadedFile::fake()->image('memory.jpg'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $this
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSeeText('قصة باقية')
        ->assertSeeText('محتوى القصة الباقي');

    $this
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('search', ['q' => '556677889']))
        ->assertOk()
        ->assertJsonPath('data.0.id', $martyr->id)
        ->assertJsonMissingPath('data.0.momeries_img')
        ->assertJsonMissingPath('data.0.story');
});
