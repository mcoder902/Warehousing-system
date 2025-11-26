<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $personnel = Personnel::when($search, function($q) use ($search){
            $q->where('last_name', 'like', "%$search%")
              ->orWhere('personnel_code', 'like', "%$search%");
        })->latest()->paginate(20);

        return view('personnel.index', compact('personnel'));
    }

    public function create()
    {
        return view('personnel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'personnel_code' => 'nullable|string|unique:personnels,personnel_code',
            'national_code' => 'nullable|string|digits:10|unique:personnels,national_code',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        Personnel::create($validated);

        return redirect()->route('personnel.index')
            ->with('success', 'پرسنل جدید با موفقیت تعریف شد.');
    }

    public function edit(Personnel $personnel)
    {
        return view('personnel.edit', compact('personnel'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'personnel_code' => 'nullable|string|unique:personnels,personnel_code,' . $personnel->id,
            'national_code' => 'nullable|string|digits:10|unique:personnels ,national_code,' . $personnel->id,
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $personnel->update($validated);

        return redirect()->route('personnel.index')
            ->with('success', 'اطلاعات پرسنل ویرایش شد.');
    }

    public function destroy(Personnel $personnel)
    {
        if ($personnel->currentItems()->exists()) {
            return back()->with('error', 'این پرسنل دارای اقلام تحویل نشده است.');
        }

        $personnel->delete();
        return back()->with('success', 'پرسنل حذف شد.');
    }
}