<?php

use App\Models\Martyr;
use App\Models\User;
use Flasher\Prime\Test\FlasherAssert;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('martyr_images');
});

test('profile image routes require dashboard authentication', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للاختبار',
    ]);

    $this
        ->put(route('dashboard.martyr.profile-image.update', $martyr), [
            'img_path' => UploadedFile::fake()->image('profile.jpg'),
        ])
        ->assertRedirect(route('login'));

    $this
        ->delete(route('dashboard.martyr.profile-image.destroy', $martyr))
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('profile_imgs', 0);
    expect(Storage::disk('martyr_images')->allFiles())->toBeEmpty();
});

test('show page displays a placeholder and upload form when no profile image exists', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بلا صورة',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr));

    $response
        ->assertOk()
        ->assertViewHas('martyr', function (Martyr $viewMartyr) use ($martyr): bool {
            return $viewMartyr->is($martyr)
                && $viewMartyr->relationLoaded('story')
                && $viewMartyr->relationLoaded('profileImg')
                && ! $viewMartyr->profileImg->exists;
        })
        ->assertSeeText('الصورة الشخصية')
        ->assertSeeText('لم تتم إضافة صورة شخصية لهذا الشهيد بعد.')
        ->assertSeeText('إضافة صورة شخصية')
        ->assertSee(asset('assets/img/No-photo-m.png'), false)
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('name="img_path"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('name="_method" value="PUT"', false)
        ->assertSee("\$dispatch('open-modal', 'manage-martyr-profile-image')", false);
});

test('profile image validation returns to show and opens only its modal', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للتحقق',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $this
        ->actingAs(User::factory()->create())
        ->put(route('dashboard.martyr.profile-image.update', $martyr))
        ->assertRedirect($showUrl)
        ->assertSessionHasErrorsIn('profileImage', ['img_path']);

    $this->assertDatabaseCount('profile_imgs', 0);

    $this
        ->get($showUrl)
        ->assertOk()
        ->assertSeeText('يرجى اختيار صورة شخصية.')
        ->assertSee('manage-martyr-profile-image', false)
        ->assertSee('style="display: block;"', false)
        ->assertSeeText('قصة الشهيد');
});

test('profile upload rejects executable file extensions', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بملف غير صالح',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->put(route('dashboard.martyr.profile-image.update', $martyr), [
            'img_path' => UploadedFile::fake()->image('payload.pht'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr))
        ->assertSessionHasErrorsIn('profileImage', ['img_path']);

    $this->assertDatabaseCount('profile_imgs', 0);
    expect(Storage::disk('martyr_images')->allFiles())->toBeEmpty();
});

test('an authenticated user can upload a profile image for the bound martyr', function () {
    $martyr = Martyr::create([
        'name_ar' => 'الشهيد صاحب الصورة',
    ]);
    $otherMartyr = Martyr::create([
        'name_ar' => 'شهيد آخر',
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $response = $this
        ->actingAs(User::factory()->create())
        ->put(route('dashboard.martyr.profile-image.update', $martyr), [
            'img_path' => UploadedFile::fake()->image('portrait.jpg', 600, 600)->size(512),
            'martyr_id' => $otherMartyr->id,
        ]);

    $response->assertRedirect($showUrl);

    $profileImage = $martyr->profileImg()->firstOrFail();

    expect($profileImage->martyr_id)->toBe($martyr->id)
        ->and($profileImage->img_path)->toStartWith("martyrs/{$martyr->id}/profile/")
        ->and($martyr->profileImg()->count())->toBe(1);

    Storage::disk('martyr_images')->assertExists($profileImage->img_path);
    $this->assertDatabaseMissing('profile_imgs', [
        'martyr_id' => $otherMartyr->id,
    ]);

    $page = $this->get($showUrl);

    FlasherAssert::notification('success', 'تمت إضافة الصورة الشخصية بنجاح.');

    $page
        ->assertOk()
        ->assertSee(asset('assets/img/'.$profileImage->img_path), false)
        ->assertSeeText('استبدال الصورة')
        ->assertSeeText('حذف الصورة');
});

test('replacing a profile image updates the same record and deletes the old file', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد لاستبدال الصورة',
    ]);
    $user = User::factory()->create();
    $route = route('dashboard.martyr.profile-image.update', $martyr);

    $this
        ->actingAs($user)
        ->put($route, [
            'img_path' => UploadedFile::fake()->image('old-profile.png'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $profileImage = $martyr->profileImg()->firstOrFail();
    $recordId = $profileImage->id;
    $oldPath = $profileImage->img_path;

    Storage::disk('martyr_images')->assertExists($oldPath);

    $this
        ->put($route, [
            'img_path' => UploadedFile::fake()->image('new-profile.webp'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $profileImage->refresh();

    expect($profileImage->id)->toBe($recordId)
        ->and($profileImage->img_path)->not->toBe($oldPath)
        ->and($martyr->profileImg()->count())->toBe(1);

    Storage::disk('martyr_images')->assertMissing($oldPath);
    Storage::disk('martyr_images')->assertExists($profileImage->img_path);

    $this->get(route('dashboard.martyr.show', $martyr))->assertOk();

    FlasherAssert::notification('success', 'تم استبدال الصورة الشخصية بنجاح.');
});

test('failed replacement validation preserves the current record and file', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بصورة محفوظة',
    ]);
    $oldPath = "martyrs/{$martyr->id}/profile/current.jpg";

    Storage::disk('martyr_images')->put($oldPath, 'current image');
    $profileImage = $martyr->profileImg()->create([
        'img_path' => $oldPath,
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->put(route('dashboard.martyr.profile-image.update', $martyr), [
            'img_path' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr))
        ->assertSessionHasErrorsIn('profileImage', ['img_path']);

    expect($profileImage->fresh()->img_path)->toBe($oldPath)
        ->and($martyr->profileImg()->count())->toBe(1);

    Storage::disk('martyr_images')->assertExists($oldPath);
});

test('deleting a profile image removes its record and stored file', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد لحذف الصورة',
    ]);
    $path = "martyrs/{$martyr->id}/profile/to-delete.jpg";

    Storage::disk('martyr_images')->put($path, 'profile image');
    $profileImage = $martyr->profileImg()->create([
        'img_path' => $path,
    ]);
    $showUrl = route('dashboard.martyr.show', $martyr);

    $this
        ->actingAs(User::factory()->create())
        ->delete(route('dashboard.martyr.profile-image.destroy', $martyr))
        ->assertRedirect($showUrl);

    $this->assertDatabaseMissing('profile_imgs', [
        'id' => $profileImage->id,
    ]);
    Storage::disk('martyr_images')->assertMissing($path);

    $page = $this->get($showUrl);

    FlasherAssert::notification('success', 'تم حذف الصورة الشخصية بنجاح.');

    $page
        ->assertOk()
        ->assertSeeText('إضافة صورة شخصية')
        ->assertSee(asset('assets/img/No-photo-m.png'), false);
});

test('a missing stored file displays the placeholder instead of a broken URL', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد بملف مفقود',
    ]);
    $missingPath = "martyrs/{$martyr->id}/profile/missing.jpg";

    $martyr->profileImg()->create([
        'img_path' => $missingPath,
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSee(asset('assets/img/No-photo-m.png'), false)
        ->assertDontSee(asset('assets/img/'.$missingPath), false)
        ->assertSeeText('تعذر العثور على ملف الصورة المحفوظ')
        ->assertSeeText('استبدال الصورة');
});

test('profile image operations leave story display and search payload unchanged', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد للقصة والبحث',
        'national_id' => '445566778',
    ]);
    $martyr->story()->create([
        'title' => 'قصة باقية',
        'content' => 'محتوى القصة الباقي',
    ]);
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->put(route('dashboard.martyr.profile-image.update', $martyr), [
            'img_path' => UploadedFile::fake()->image('profile.jpg'),
        ])
        ->assertRedirect(route('dashboard.martyr.show', $martyr));

    $this
        ->get(route('dashboard.martyr.show', $martyr))
        ->assertOk()
        ->assertSeeText('قصة باقية')
        ->assertSeeText('محتوى القصة الباقي');

    $this
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('search', ['q' => '445566778']))
        ->assertOk()
        ->assertJsonPath('data.0.id', $martyr->id)
        ->assertJsonMissingPath('data.0.profile_img')
        ->assertJsonMissingPath('data.0.story');
});
