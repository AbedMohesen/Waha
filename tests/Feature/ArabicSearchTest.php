<?php

use App\Models\Martyr;

test('Arabic search matches normalized letter variants and diacritics', function () {
    $martyr = Martyr::create([
        'name_ar' => 'إِبْرَاهِيم فاطمة مُصْطَفَى',
        'age' => '25',
        'sex' => 'm',
    ]);

    expect($martyr->name_ar_normalized)
        ->toBe('ابراهيم فاطمه مصطفي');

    $this
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('search', ['q' => 'ابراهيم فاطمه مصطفي']))
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.id', $martyr->id);
});

test('Arabic search treats like wildcards as literal characters', function () {
    Martyr::create(['name_ar' => 'اسم عادي']);

    $this
        ->withHeader('X-Requested-With', 'XMLHttpRequest')
        ->getJson(route('search', ['q' => '%']))
        ->assertOk()
        ->assertJsonPath('total', 0);
});
