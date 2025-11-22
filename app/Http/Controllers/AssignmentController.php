<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Item;
use App\Models\Personnel;
use App\Http\Requests\AssignItemRequest;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * اکشن: تحویل کالا به پرسنل
     */
    public function assign(AssignItemRequest $request, Item $item)
    {
        try {
            $personnel = Personnel::findOrFail($request->personnel_id);

            $item->assignTo(
                $personnel,
                auth()->user(),
                $request->notes
            );

            return back()->with('success', "کالا به {$personnel->full_name} تحویل داده شد.");

        }
        catch (\Exception $e)
        {

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * اکشن: بازگشت کالا به انبار
     */
    public function retake(Request $request, Item $item)
    {
        try
        {
            $item->returnToStorage($request->notes);

            return back()->with('success', 'کالا به انبار بازگشت.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * نمایش تاریخچه کلی نقل و انتقالات
     */
    public function history()
    {
        $assignments = Assignment::with(['item', 'personnel', 'registrar'])
            ->latest()
            ->paginate(50);

        return view('assignments.index', compact('assignments'));
    }
}