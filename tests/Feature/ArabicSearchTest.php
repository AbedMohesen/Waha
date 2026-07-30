<?php

use App\Imports\MartyrsImport;
use App\Models\Martyr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;

function searchForMartyr(string $query): TestResponse
{
    return test()
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('search', ['q' => $query]));
}

test('Arabic search matches normalized Alef variants', function (string $query, string $storedName) {
    $martyr = Martyr::create(['name_ar' => $storedName]);

    searchForMartyr($query)
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.id', $martyr->id);
})->with([
    'Alef with Hamza above' => ['احمد', 'أحمد محمود'],
    'Alef with Hamza below' => ['ايمان', 'إيمان خالد'],
    'Alef with Madda' => ['ادم', 'آدم يوسف'],
]);

test('Arabic search ignores diacritics and tatweel', function () {
    $martyr = Martyr::create(['name_ar' => 'مُـحَـمَّـد علي']);

    expect($martyr->name_ar_normalized)->toBe('محمد علي');

    searchForMartyr('محمد')
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.id', $martyr->id);
});

test('Arabic search keeps national ID lookup unchanged', function () {
    $martyr = Martyr::create([
        'name_ar' => 'اسم للاختبار',
        'national_id' => '123456789',
    ]);

    searchForMartyr('123456789')
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.id', $martyr->id);
});

test('Arabic search returns an empty result for an unknown value', function () {
    Martyr::create(['name_ar' => 'أحمد محمود']);

    searchForMartyr('اسم غير موجود')
        ->assertOk()
        ->assertJsonPath('total', 0)
        ->assertJsonCount(0, 'data');
});

test('Arabic search treats LIKE wildcards as literal characters on SQLite', function () {
    expect(DB::connection()->getDriverName())->toBe('sqlite');

    Martyr::create(['name_ar' => 'اسم عادي']);

    searchForMartyr('%')
        ->assertOk()
        ->assertJsonPath('total', 0);
});

test('creating updating and importing martyrs use the same Arabic normalizer', function () {
    $martyr = Martyr::create(['name_ar' => 'إِيمَان']);
    expect($martyr->name_ar_normalized)->toBe('ايمان');

    $martyr->update(['name_ar' => 'آدَم']);
    expect($martyr->fresh()->name_ar_normalized)->toBe('ادم');

    $imported = (new MartyrsImport)->model([
        null,
        'Ahmed',
        'أَحْمَد',
        '20',
        null,
        'm',
        '987654321',
    ]);
    $imported->save();

    expect($imported->name_ar_normalized)->toBe('احمد');
});

test('rebuild command fills existing normalized names in chunks', function () {
    $first = Martyr::create(['name_ar' => 'أحمد']);
    $second = Martyr::create(['name_ar' => 'إيمان']);

    DB::table('martyrs')->where('id', $first->id)->update(['name_ar_normalized' => null]);
    DB::table('martyrs')->where('id', $second->id)->update(['name_ar_normalized' => 'قيمة قديمة']);

    expect(Artisan::call('martyrs:rebuild-search-names', ['--chunk' => 1]))->toBe(0)
        ->and($first->fresh()->name_ar_normalized)->toBe('احمد')
        ->and($second->fresh()->name_ar_normalized)->toBe('ايمان');
});
