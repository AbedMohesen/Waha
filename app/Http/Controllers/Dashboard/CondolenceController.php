<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Condolence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CondolenceController extends Controller
{
    public function index(Request $request): View
    {
        $filter = in_array($request->query('status'), ['pending', 'approved', 'all'], true)
            ? $request->query('status')
            : Condolence::STATUS_PENDING;

        $condolences = Condolence::query()
            ->with('martyr')
            ->when(
                $filter !== 'all',
                fn ($query) => $query->where('status', $filter),
            )
            ->oldest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.condolences.index', compact('condolences', 'filter'));
    }

    public function approve(Condolence $condolence): RedirectResponse
    {
        if ($condolence->status === Condolence::STATUS_APPROVED) {
            flash()->info('هذه التعزية معتمدة مسبقًا.');

            return redirect()->route('dashboard.condolences.index');
        }

        if ($condolence->status !== Condolence::STATUS_PENDING) {
            flash()->error('لا يمكن اعتماد هذه التعزية بحالتها الحالية.');

            return redirect()->route('dashboard.condolences.index');
        }

        $condolence->update([
            'status' => Condolence::STATUS_APPROVED,
        ]);

        flash()->success('تمت الموافقة على التعزية بنجاح.');

        return redirect()->route('dashboard.condolences.index');
    }

    public function reject(Condolence $condolence): RedirectResponse
    {
        if ($condolence->status !== Condolence::STATUS_PENDING) {
            flash()->error('لا يمكن رفض تعزية غير معلقة.');

            return redirect()->route('dashboard.condolences.index');
        }

        $condolence->delete();

        flash()->success('تم رفض التعزية وحذفها بنجاح.');

        return redirect()->route('dashboard.condolences.index');
    }
}
