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
            expect($statistics['total'])->toBe(8)
                ->and($statistics['gender']['male'])->toBe(['count' => 3, 'percentage' => 42.9])
                ->and($statistics['gender']['female'])->toBe(['count' => 4, 'percentage' => 57.1])
                ->and($statistics['gender']['unclassified'])->toBe(1)
                ->and($statistics['age_groups']['children'])->toBe(['count' => 2, 'percentage' => 33.3])
                ->and($statistics['age_groups']['youth'])->toBe(['count' => 3, 'percentage' => 50.0])
                ->and($statistics['age_groups']['elders'])->toBe(['count' => 1, 'percentage' => 16.7])
                ->and($statistics['age_groups']['unclassified'])->toBe(2);

            return true;
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
