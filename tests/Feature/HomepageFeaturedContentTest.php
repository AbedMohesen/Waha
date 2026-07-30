<?php

use App\Models\FeaturedContent;
use App\Models\Martyr;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('martyr_images');
});

test('homepage content management routes require dashboard authentication', function () {
    $martyr = Martyr::create(['name_ar' => 'شهيد محمي']);
    $assignment = FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MARTYRS,
        'martyr_id' => $martyr->id,
    ]);

    $this
        ->get(route('dashboard.homepage-content.index'))
        ->assertRedirect(route('login'));
    $this
        ->post(route('dashboard.homepage-content.store', FeaturedContent::SECTION_MARTYRS), [
            'record_id' => $martyr->id,
        ])
        ->assertRedirect(route('login'));
    $this
        ->delete(route('dashboard.homepage-content.destroy', $assignment))
        ->assertRedirect(route('login'));

    $this->assertDatabaseHas('featured_contents', ['id' => $assignment->id]);
});

test('management page displays navigation counters searches and only real story options', function () {
    $withStory = Martyr::create(['name_ar' => 'شهيد صاحب قصة']);
    $story = $withStory->story()->create([
        'title' => 'قصة حقيقية',
        'content' => 'محتوى قصة حقيقية',
    ]);
    $withoutStory = Martyr::create(['name_ar' => 'شهيد بلا قصة']);
    $firstMemory = $withStory->momeriesImg()->create([
        'img_path' => 'martyrs/with-story/first.jpg',
        'caption' => 'الصورة الأولى',
    ]);
    $secondMemory = $withStory->momeriesImg()->create([
        'img_path' => 'martyrs/with-story/second.jpg',
        'caption' => 'الصورة الثانية',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.homepage-content.index'));

    $response
        ->assertOk()
        ->assertSeeText('إدارة محتوى الصفحة الرئيسية')
        ->assertSeeText('أبرز الشهداء')
        ->assertSeeText('أبرز القصص')
        ->assertSeeText('أبرز صور الذكريات')
        ->assertSee('name="martyrs_q"', false)
        ->assertSee('name="stories_q"', false)
        ->assertSee('name="memories_q"', false)
        ->assertSee(route('dashboard.homepage-content.index'), false)
        ->assertViewHas('availableStories', function ($stories) use ($story, $withoutStory): bool {
            return $stories->contains($story)
                && $stories->doesntContain('martyr_id', $withoutStory->id);
        })
        ->assertViewHas('availableMemoryImages', function ($images) use ($firstMemory, $secondMemory): bool {
            return $images->contains($firstMemory) && $images->contains($secondMemory);
        });
});

test('admin can select up to four unique featured martyrs and the fifth is rejected', function () {
    $martyrs = collect(range(1, 5))->map(
        fn (int $number) => Martyr::create(['name_ar' => 'شهيد مميز '.$number]),
    );
    $user = User::factory()->create();
    $route = route('dashboard.homepage-content.store', FeaturedContent::SECTION_MARTYRS);

    $this->actingAs($user)->post($route, [
        'record_id' => $martyrs[0]->id,
    ])->assertRedirect(route('dashboard.homepage-content.index'));

    $this->post($route, [
        'record_id' => $martyrs[0]->id,
    ])->assertSessionHasErrorsIn(FeaturedContent::SECTION_MARTYRS, ['record_id']);

    foreach ($martyrs->slice(1, 3) as $martyr) {
        $this->post($route, ['record_id' => $martyr->id])
            ->assertRedirect(route('dashboard.homepage-content.index'));
    }

    $response = $this->post($route, [
        'record_id' => $martyrs[4]->id,
    ]);

    $response->assertSessionHasErrorsIn(FeaturedContent::SECTION_MARTYRS, ['record_id']);
    expect(session('errors')->getBag(FeaturedContent::SECTION_MARTYRS)->first('record_id'))
        ->toBe('تم الوصول إلى الحد الأقصى لأبرز الشهداء.');
    $this->assertDatabaseCount('featured_contents', 4);
    $this->assertDatabaseMissing('featured_contents', [
        'section' => FeaturedContent::SECTION_MARTYRS,
        'martyr_id' => $martyrs[4]->id,
    ]);
});

test('featured stories enforce real unique stories and the three-item limit', function () {
    $martyrs = collect(range(1, 5))->map(
        fn (int $number) => Martyr::create(['name_ar' => 'شهيد القصة '.$number]),
    );
    $stories = $martyrs->take(4)->map(
        fn (Martyr $martyr, int $index) => $martyr->story()->create([
            'title' => 'قصة مميزة '.($index + 1),
            'content' => 'محتوى القصة '.($index + 1),
        ]),
    );
    $user = User::factory()->create();

    $this->actingAs($user)->post(
        route('dashboard.homepage-content.store', FeaturedContent::SECTION_MARTYRS),
        ['record_id' => $martyrs[0]->id],
    )->assertRedirect(route('dashboard.homepage-content.index'));

    $route = route('dashboard.homepage-content.store', FeaturedContent::SECTION_STORIES);

    foreach ($stories->take(3) as $story) {
        $this->post($route, ['record_id' => $story->id])
            ->assertRedirect(route('dashboard.homepage-content.index'));
    }

    $this->post($route, ['record_id' => $stories[0]->id])
        ->assertSessionHasErrorsIn(FeaturedContent::SECTION_STORIES, ['record_id']);
    $this->post($route, ['record_id' => $stories[3]->id])
        ->assertSessionHasErrorsIn(FeaturedContent::SECTION_STORIES, ['record_id']);

    $this->assertDatabaseHas('featured_contents', [
        'section' => FeaturedContent::SECTION_MARTYRS,
        'martyr_id' => $martyrs[0]->id,
    ]);
    $this->assertDatabaseHas('featured_contents', [
        'section' => FeaturedContent::SECTION_STORIES,
        'story_id' => $stories[0]->id,
    ]);
    expect(FeaturedContent::forSection(FeaturedContent::SECTION_STORIES)->count())->toBe(3);
});

test('a missing story or an arbitrary section cannot be featured', function () {
    $martyrWithoutStory = Martyr::create([
        'name_ar' => 'شهيد بلا قصة حقيقية',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->post(route('dashboard.homepage-content.store', FeaturedContent::SECTION_STORIES), [
            'record_id' => $martyrWithoutStory->id + 1000,
        ])
        ->assertSessionHasErrorsIn(FeaturedContent::SECTION_STORIES, ['record_id']);

    $this
        ->post(route('dashboard.homepage-content.store', 'App-Models-Martyr'), [
            'record_id' => $martyrWithoutStory->id,
        ])
        ->assertNotFound();

    $this->assertDatabaseCount('featured_contents', 0);
});

test('admin can feature four memory images from the same martyr but not a duplicate or fifth image', function () {
    $martyr = Martyr::create([
        'name_ar' => 'شهيد متعدد الصور',
    ]);
    $images = collect(range(1, 5))->map(
        fn (int $number) => $martyr->momeriesImg()->create([
            'img_path' => 'martyrs/same/memory-'.$number.'.jpg',
            'caption' => 'ذكرى رقم '.$number,
        ]),
    );
    $route = route('dashboard.homepage-content.store', FeaturedContent::SECTION_MEMORY_IMAGES);

    $this->actingAs(User::factory()->create());

    foreach ($images->take(4) as $image) {
        $this->post($route, ['record_id' => $image->id])
            ->assertRedirect(route('dashboard.homepage-content.index'));
    }

    $this->post($route, ['record_id' => $images[0]->id])
        ->assertSessionHasErrorsIn(FeaturedContent::SECTION_MEMORY_IMAGES, ['record_id']);
    $this->post($route, ['record_id' => $images[4]->id])
        ->assertSessionHasErrorsIn(FeaturedContent::SECTION_MEMORY_IMAGES, ['record_id']);

    expect(FeaturedContent::forSection(FeaturedContent::SECTION_MEMORY_IMAGES)->count())->toBe(4)
        ->and(
            FeaturedContent::forSection(FeaturedContent::SECTION_MEMORY_IMAGES)
                ->distinct()
                ->count('momeries_img_id'),
        )->toBe(4);
});

test('removing an assignment keeps its source record and file intact', function () {
    $martyr = Martyr::create(['name_ar' => 'شهيد إزالة الاختيار']);
    $memory = $martyr->momeriesImg()->create([
        'img_path' => 'martyrs/removal/kept.jpg',
        'caption' => 'صورة ستبقى',
    ]);
    Storage::disk('martyr_images')->put($memory->img_path, 'image contents');
    $assignment = FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MEMORY_IMAGES,
        'momeries_img_id' => $memory->id,
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->delete(route('dashboard.homepage-content.destroy', $assignment))
        ->assertRedirect(route('dashboard.homepage-content.index'));

    $this->assertDatabaseMissing('featured_contents', ['id' => $assignment->id]);
    $this->assertDatabaseHas('momeries_imgs', ['id' => $memory->id]);
    Storage::disk('martyr_images')->assertExists($memory->img_path);
});

test('homepage uses only manually selected records with newest assignments first', function () {
    $oldMartyr = Martyr::create(['name_ar' => 'الشهيد المختار أولًا']);
    $newMartyr = Martyr::create(['name_ar' => 'الشهيد المختار حديثًا']);
    $unselectedMartyr = Martyr::create(['name_ar' => 'شهيد غير مختار']);
    $oldStory = $oldMartyr->story()->create([
        'title' => 'القصة المختارة أولًا',
        'content' => '<b>محتوى آمن أول</b>',
    ]);
    $newStory = $newMartyr->story()->create([
        'title' => 'القصة المختارة حديثًا',
        'content' => '<script>alert("xss")</script> محتوى آمن حديث',
    ]);
    $oldMemory = $oldMartyr->momeriesImg()->create([
        'img_path' => 'martyrs/home/old.jpg',
        'caption' => 'الصورة المختارة أولًا',
    ]);
    $newMemory = $newMartyr->momeriesImg()->create([
        'img_path' => 'martyrs/home/new.jpg',
        'caption' => 'الصورة المختارة حديثًا',
    ]);
    $oldTime = now()->subDay();
    $newTime = now();

    FeaturedContent::insert([
        [
            'section' => FeaturedContent::SECTION_MARTYRS,
            'martyr_id' => $oldMartyr->id,
            'story_id' => null,
            'momeries_img_id' => null,
            'created_at' => $oldTime,
            'updated_at' => $oldTime,
        ],
        [
            'section' => FeaturedContent::SECTION_MARTYRS,
            'martyr_id' => $newMartyr->id,
            'story_id' => null,
            'momeries_img_id' => null,
            'created_at' => $newTime,
            'updated_at' => $newTime,
        ],
        [
            'section' => FeaturedContent::SECTION_STORIES,
            'martyr_id' => null,
            'story_id' => $oldStory->id,
            'momeries_img_id' => null,
            'created_at' => $oldTime,
            'updated_at' => $oldTime,
        ],
        [
            'section' => FeaturedContent::SECTION_STORIES,
            'martyr_id' => null,
            'story_id' => $newStory->id,
            'momeries_img_id' => null,
            'created_at' => $newTime,
            'updated_at' => $newTime,
        ],
        [
            'section' => FeaturedContent::SECTION_MEMORY_IMAGES,
            'martyr_id' => null,
            'story_id' => null,
            'momeries_img_id' => $oldMemory->id,
            'created_at' => $oldTime,
            'updated_at' => $oldTime,
        ],
        [
            'section' => FeaturedContent::SECTION_MEMORY_IMAGES,
            'martyr_id' => null,
            'story_id' => null,
            'momeries_img_id' => $newMemory->id,
            'created_at' => $newTime,
            'updated_at' => $newTime,
        ],
    ]);

    $response = $this->get(route('front.index'));

    $response
        ->assertOk()
        ->assertSeeInOrder([$newMartyr->name_ar, $oldMartyr->name_ar])
        ->assertSeeInOrder([$newStory->title, $oldStory->title])
        ->assertSeeInOrder([$newMemory->caption, $oldMemory->caption])
        ->assertDontSeeText($unselectedMartyr->name_ar)
        ->assertSeeText('alert("xss") محتوى آمن حديث')
        ->assertDontSee('<script>alert("xss")</script>', false);
});

test('homepage displays selected source relationships without n plus one loading', function () {
    $martyr = Martyr::create(['name_ar' => 'شهيد التحميل المسبق']);
    $martyr->profileImg()->create(['img_path' => 'martyrs/eager/profile.jpg']);
    $story = $martyr->story()->create([
        'title' => 'قصة التحميل المسبق',
        'content' => 'محتوى القصة',
    ]);
    $memory = $martyr->momeriesImg()->create([
        'img_path' => 'martyrs/eager/memory.jpg',
        'caption' => 'ذكرى التحميل المسبق',
    ]);

    FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MARTYRS,
        'martyr_id' => $martyr->id,
    ]);
    FeaturedContent::create([
        'section' => FeaturedContent::SECTION_STORIES,
        'story_id' => $story->id,
    ]);
    FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MEMORY_IMAGES,
        'momeries_img_id' => $memory->id,
    ]);

    $this
        ->get(route('front.index'))
        ->assertOk()
        ->assertViewHas('featuredMartyrs', function ($martyrs): bool {
            return $martyrs->every(
                fn (Martyr $martyr) => $martyr->relationLoaded('profileImg'),
            );
        })
        ->assertViewHas('featuredStories', function ($stories): bool {
            return $stories->every(
                fn ($story) => $story->relationLoaded('martyr')
                    && $story->martyr->relationLoaded('profileImg'),
            );
        })
        ->assertViewHas('featuredMemoryImages', function ($images): bool {
            return $images->every(
                fn ($image) => $image->relationLoaded('martyr'),
            );
        });
});

test('deleting source records cascades only their featured assignments', function () {
    $storyMartyr = Martyr::create(['name_ar' => 'شهيد حذف القصة']);
    $story = $storyMartyr->story()->create([
        'title' => 'قصة ستحذف',
        'content' => 'محتوى',
    ]);
    $storyAssignment = FeaturedContent::create([
        'section' => FeaturedContent::SECTION_STORIES,
        'story_id' => $story->id,
    ]);

    $memoryMartyr = Martyr::create(['name_ar' => 'شهيد حذف الصورة']);
    $memory = $memoryMartyr->momeriesImg()->create([
        'img_path' => 'martyrs/cascade/memory.jpg',
        'caption' => 'صورة ستحذف',
    ]);
    $memoryAssignment = FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MEMORY_IMAGES,
        'momeries_img_id' => $memory->id,
    ]);

    $wholeMartyr = Martyr::create(['name_ar' => 'شهيد سيحذف كاملًا']);
    $wholeStory = $wholeMartyr->story()->create([
        'title' => 'قصة الشهيد المحذوف',
        'content' => 'محتوى',
    ]);
    $wholeMemory = $wholeMartyr->momeriesImg()->create([
        'img_path' => 'martyrs/cascade/whole.jpg',
        'caption' => 'صورة الشهيد المحذوف',
    ]);
    FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MARTYRS,
        'martyr_id' => $wholeMartyr->id,
    ]);
    FeaturedContent::create([
        'section' => FeaturedContent::SECTION_STORIES,
        'story_id' => $wholeStory->id,
    ]);
    FeaturedContent::create([
        'section' => FeaturedContent::SECTION_MEMORY_IMAGES,
        'momeries_img_id' => $wholeMemory->id,
    ]);

    $story->delete();
    $memory->delete();
    $wholeMartyr->delete();

    $this->assertDatabaseMissing('featured_contents', ['id' => $storyAssignment->id]);
    $this->assertDatabaseMissing('featured_contents', ['id' => $memoryAssignment->id]);
    $this->assertDatabaseCount('featured_contents', 0);
    $this->get(route('front.index'))->assertOk();
});
