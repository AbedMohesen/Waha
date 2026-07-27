<?php

use App\Support\ArabicText;

test('it normalizes Arabic letter variants and removes diacritics', function () {
    expect(ArabicText::normalize('إِبْرَاهِيم آلاء فاطمة مُصْطَفَى'))
        ->toBe('ابراهيم الاء فاطمه مصطفي');
});

test('it normalizes whitespace and safely escapes like wildcards', function () {
    expect(ArabicText::terms("  أحمد\tبن   علي  "))
        ->toBe(['احمد', 'بن', 'علي'])
        ->and(ArabicText::escapeLike('100%_مؤكد!'))
        ->toBe('100!%!_مؤكد!!');
});
