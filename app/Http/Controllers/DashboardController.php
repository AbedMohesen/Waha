<?php

namespace App\Http\Controllers;

use App\Models\Martyr;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $query = Martyr::query();

        $numericAgeCondition = match ($query->getModel()->getConnection()->getDriverName()) {
            'sqlite' => "TRIM(age) <> '' AND TRIM(age) NOT GLOB '*[^0-9]*'",
            'pgsql' => "TRIM(age) ~ '^[0-9]+$'",
            'sqlsrv' => "TRIM(age) <> '' AND TRIM(age) NOT LIKE '%[^0-9]%'",
            default => "TRIM(age) REGEXP '^[0-9]+$'",
        };

        $infantAgeCondition = <<<'SQL'
            (
                LOWER(TRIM(age)) LIKE '%hour%'
                OR LOWER(TRIM(age)) LIKE '%day%'
                OR LOWER(TRIM(age)) LIKE '%week%'
                OR LOWER(TRIM(age)) LIKE '%month%'
                OR LOWER(TRIM(age)) LIKE '%newborn%'
                OR LOWER(TRIM(age)) LIKE '%infant%'
                OR LOWER(TRIM(age)) LIKE '%less than%year%'
                OR LOWER(TRIM(age)) LIKE '%under%year%'
                OR TRIM(age) LIKE '%ساعة%'
                OR TRIM(age) LIKE '%ساعات%'
                OR TRIM(age) LIKE '%يوم%'
                OR TRIM(age) LIKE '%أيام%'
                OR TRIM(age) LIKE '%ايام%'
                OR TRIM(age) LIKE '%أسبوع%'
                OR TRIM(age) LIKE '%اسبوع%'
                OR TRIM(age) LIKE '%أسابيع%'
                OR TRIM(age) LIKE '%اسابيع%'
                OR TRIM(age) LIKE '%شهر%'
                OR TRIM(age) LIKE '%أشهر%'
                OR TRIM(age) LIKE '%اشهر%'
                OR TRIM(age) LIKE '%أقل%سنة%'
                OR TRIM(age) LIKE '%اقل%سنة%'
                OR TRIM(age) LIKE '%دون%سنة%'
                OR TRIM(age) LIKE '%أقل%عام%'
                OR TRIM(age) LIKE '%اقل%عام%'
                OR TRIM(age) LIKE '%دون%عام%'
            )
        SQL;

        $totals = $query
            ->selectRaw(
                <<<SQL
                    COUNT(*) AS total_count,
                    SUM(CASE WHEN LOWER(TRIM(sex)) IN ('m', 'male', 'ذكر') THEN 1 ELSE 0 END) AS male_count,
                    SUM(CASE WHEN LOWER(TRIM(sex)) IN ('f', 'female', 'أنثى', 'انثى') THEN 1 ELSE 0 END) AS female_count,
                    SUM(CASE WHEN {$numericAgeCondition} OR {$infantAgeCondition} THEN 1 ELSE 0 END) AS valid_age_count,
                    SUM(CASE WHEN ({$numericAgeCondition} AND (age * 1) < 18) OR {$infantAgeCondition} THEN 1 ELSE 0 END) AS children_count,
                    SUM(CASE WHEN {$numericAgeCondition} AND (age * 1) BETWEEN 18 AND 59 THEN 1 ELSE 0 END) AS youth_count,
                    SUM(CASE WHEN {$numericAgeCondition} AND (age * 1) >= 60 THEN 1 ELSE 0 END) AS elders_count
                SQL
            )
            ->first();

        $totalCount = (int) $totals->total_count;
        $maleCount = (int) $totals->male_count;
        $femaleCount = (int) $totals->female_count;
        $genderTotal = $maleCount + $femaleCount;
        $validAgeCount = (int) $totals->valid_age_count;

        $percentage = static fn(int $count, int $total): float => $total > 0
            ? round(($count / $total) * 100, 1)
            : 0;

        $statistics = [
            'total' => $totalCount,
            'gender' => [
                'male' => [
                    'count' => $maleCount,
                    'percentage' => $percentage($maleCount, $genderTotal),
                ],
                'female' => [
                    'count' => $femaleCount,
                    'percentage' => $percentage($femaleCount, $genderTotal),
                ],
                'classified' => $genderTotal,
                'unclassified' => max(0, $totalCount - $genderTotal),
            ],
            'age_groups' => [
                'children' => [
                    'count' => (int) $totals->children_count,
                    'percentage' => $percentage((int) $totals->children_count, $validAgeCount),
                ],
                'youth' => [
                    'count' => (int) $totals->youth_count,
                    'percentage' => $percentage((int) $totals->youth_count, $validAgeCount),
                ],
                'elders' => [
                    'count' => (int) $totals->elders_count,
                    'percentage' => $percentage((int) $totals->elders_count, $validAgeCount),
                ],
                'classified' => $validAgeCount,
                'unclassified' => max(0, $totalCount - $validAgeCount),
            ],
        ];

        return view('dashboard', compact('statistics'));
    }
}
