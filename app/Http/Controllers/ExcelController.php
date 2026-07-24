<?php

namespace App\Http\Controllers;

use App\Imports\MartyrsImport;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;


class ExcelController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);
        // رفع القيود عن وقت التنفيذ (0 تعني بدون حد أقصى للوقت)
        ini_set('max_execution_time', 0);

        // يمكنك أيضاً زيادة حد الذاكرة المسموح بها إذا كان الملف ضخماً جداً
        ini_set('memory_limit', '512M');
        Excel::import(new MartyrsImport(), $request->file('file'));

        return redirect()->route('import')->with('success', 'تم الاستيراد بنجاح');
    }
}

