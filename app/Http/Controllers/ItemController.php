<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

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

        return view('items.index', compact('items'));
    }


    public function create()
    {
        return view('items.create');
    }

    public function store(StoreItemRequest $request)
    {
        Item::create($request->validated());
        return redirect()->route('items.index')->with('success', 'کالا با موفقیت ثبت شد.');
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
