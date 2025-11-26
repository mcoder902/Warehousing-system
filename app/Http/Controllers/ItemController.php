<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\Item;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('currentPersonnel');

        if ($search = $request->input('search'))
        {
            $query->where(function($q) use ($search)
            {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('inventory_code', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status'))
        {
            $query->where('status', $status);
        }

        $items = $query->latest()->paginate(20);
        $personals = Personnel::all();
        return view('items.index', compact('items', 'personals'));
    }


    public function create()
    {
        return view('items.create');
    }

    public function store(StoreItemRequest $request)
    {

        DB::transaction(function () use ($request) {


            $data = $request->except(['serial_number_auto', 'inventory_code_auto', '_token']);


            if ($request->boolean('serial_number_auto'))
            {
                $data['serial_number'] = $this->generateNextCode('serial_number', 'SN-', 10000);
            }

            if ($request->boolean('inventory_code_auto'))
            {
                $data['inventory_code'] = $this->generateNextCode('inventory_code', 'INV-', 10000);
            }

            Item::create($data);
        });

        return redirect()->route('items.index')
            ->with('success', 'کالا با موفقیت ثبت شد.');
    }

    /**
     * متد هوشمند برای تولید کد بعدی
     * @param string $column نام ستون (serial_number یا inventory_code)
     * @param string $prefix پیشوند ثابت (مثلاً INV-)
     * @param int $startFrom عدد شروع کننده (مثلاً 10000 برای اینکه همه کدها هم‌اندازه باشند)
     */
    private function generateNextCode($column, $prefix, $startFrom = 10000)
    {

        $lastRecord = Item::where($column, 'LIKE', "{$prefix}%")
            ->orderByRaw("LENGTH({$column}) DESC")
            ->orderBy($column, 'desc')
            ->lockForUpdate()
            ->first();

        if (! $lastRecord)
        {
            $nextNumber = $startFrom;
        }
        else
        {

            $currentNumber = (int) str_replace($prefix, '', $lastRecord->$column);
            $nextNumber = $currentNumber + 1;
        }

        return $prefix . $nextNumber;
    }
    public function show(Item $item)
    {
        $item->load(['assignments.personnel', 'assignments.registrar']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(StoreItemRequest $request, Item $item)
    {
        $item->update($request->validated());
        return redirect()->route('items.index')->with('success', 'مشخصات کالا ویرایش شد.');
    }

    public function destroy(Item $item)
    {
        if ($item->current_personnel_id)
        {
            return back()->with('error', 'کالا دست پرسنل است و قابل حذف نیست. ابتدا عودت بزنید.');
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'کالا حذف (بایگانی) شد.');
    }

}
