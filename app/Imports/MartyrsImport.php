<?php

namespace App\Imports;

use App\Models\Martyr;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MartyrsImport implements ToModel, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $bornDate = null;
        if (!empty($row[4])) {
            $bornDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]))->format('Y-m-d');
        }
        static $i = 0;
        $i++;
        dump($i, $row);
        return new Martyr([
            'name_en' => $row[1],
            'name_ar' => $row[2],
            'age' => $row[3],
            'born' => $bornDate,
            'sex' => $row[5],
            'national_id' => $row[6],
        ]);
    }
    /**
     * تحديد عدد الأسطر التي يتم قراءتها في كل دفعة لتوفير الذاكرة والوقت
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 500; // يمكنك زيادة أو تقليل هذا الرقم حسب حجم الملف (500 إلى 1000 يعتبر ممتاز)
    }
}
