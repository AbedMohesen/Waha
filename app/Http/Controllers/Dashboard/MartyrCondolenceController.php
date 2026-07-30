<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Condolence;
use App\Models\Martyr;
use Illuminate\Http\RedirectResponse;

class MartyrCondolenceController extends Controller
{
    public function approve(Martyr $martyr, Condolence $condolence): RedirectResponse
    {
        $ownedCondolence = $this->ownedCondolence($martyr, $condolence);

        if ($ownedCondolence->status === Condolence::STATUS_APPROVED) {
            flash()->info('هذه التعزية معتمدة مسبقًا.');

            return redirect()->route('dashboard.martyr.show', $martyr);
        }

        if ($ownedCondolence->status !== Condolence::STATUS_PENDING) {
            flash()->error('لا يمكن اعتماد هذه التعزية بحالتها الحالية.');

            return redirect()->route('dashboard.martyr.show', $martyr);
        }

        $ownedCondolence->update([
            'status' => Condolence::STATUS_APPROVED,
        ]);

        flash()->success('تمت الموافقة على التعزية بنجاح.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    public function destroy(Martyr $martyr, Condolence $condolence): RedirectResponse
    {
        $this->ownedCondolence($martyr, $condolence)->delete();

        flash()->success('تم حذف التعزية نهائيًا.');

        return redirect()->route('dashboard.martyr.show', $martyr);
    }

    private function ownedCondolence(Martyr $martyr, Condolence $condolence): Condolence
    {
        return $martyr->condolences()
            ->whereKey($condolence->getKey())
            ->firstOrFail();
    }
}
