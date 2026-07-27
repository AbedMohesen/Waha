<?php

use App\Models\Martyr;
use App\Models\User;

test('dashboard statistics aggregate gender and age groups correctly', function () {
    Martyr::query()->insert([
        martyrData('m', '10'),
        martyrData('male', '18'),
        martyrData('ذكر', '60'),
        martyrData('f', '17'),
        martyrData('female', '49'),
        martyrData('أنثى', '50'),
        martyrData('انثى', null),
        martyrData('unknown', 'not-recorded'),
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.index'));

    $response
        ->assertOk()
        ->assertViewHas('statistics', function (array $statistics): bool {
            return $statistics['total'] === 8
                && $statistics['gender']['male'] === ['count' => 3, 'percentage' => 42.9]
                && $statistics['gender']['female'] === ['count' => 4, 'percentage' => 57.1]
                && $statistics['gender']['unclassified'] === 1
                && $statistics['age_groups']['children'] === ['count' => 2, 'percentage' => 33.3]
                && $statistics['age_groups']['youth'] === ['count' => 2, 'percentage' => 33.3]
                && $statistics['age_groups']['elders'] === ['count' => 2, 'percentage' => 33.3]
                && $statistics['age_groups']['unclassified'] === 2;
        });
});

test('text ages below one year are classified as children', function () {
    Martyr::query()->insert([
        martyrData('m', 'Less than a day'),
        martyrData('f', 'One month'),
        martyrData('m', '6 months'),
        martyrData('f', 'أقل من عام'),
        martyrData('m', 'شهر واحد'),
        martyrData('f', 'not-recorded'),
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard.index'));

    $response
        ->assertOk()
        ->assertViewHas('statistics', function (array $statistics): bool {
            return $statistics['age_groups']['children'] === ['count' => 5, 'percentage' => 100.0]
                && $statistics['age_groups']['classified'] === 5
                && $statistics['age_groups']['unclassified'] === 1;
        });
});

function martyrData(string $sex, ?string $age): array
{
    return [
        'name_ar' => 'سجل اختباري',
        'sex' => $sex,
        'age' => $age,
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
