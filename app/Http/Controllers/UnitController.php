<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('name')) {
            $query->where('unit_name', 'like', '%' . $request->name . '%');
        }

        $units = $query->orderBy('unit_name', 'asc')->get();

        return view('unit', compact('units'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => [
                'required',
                'string',
                'max:200',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Unit::where('unit_name', $value)
                        ->whereNull('deleted_at')
                        ->exists();

                    if ($exists) {
                        $fail('Такий підрозділ вже існує.');
                    }
                },
            ],
        ], [
            'unit_name.required' => 'Назва підрозділу є обов’язковою.',
            'unit_name.max' => 'Максимальна довжина — 200 символів.',
        ]);


        Unit::create($request->only('unit_name'));

        return redirect()->route('units.index')->with('success', 'Підрозділ додано!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Підрозділ видалено!');
    }

    public function trashed()
    {
        $units = Unit::onlyTrashed()->get();

        return view('unit_trashed', compact('units'));
    }

    public function restore($id)
    {
        $unit = Unit::onlyTrashed()->findOrFail($id);

        $exists = Unit::where('unit_name', $unit->unit_name)->exists();
        if ($exists) {
            return redirect()->route('units.trashed')
                             ->with('error', 'Неможливо відновити: вже існує активний підрозділ з такою назвою.');
        }

        $unit->restore();

        return redirect()->route('units.index')->with('success', 'Підрозділ успішно відновлено.');
    }

    public function edit(Unit $unit)
    {
        return view('unit_edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_name' => 'required|string|max:250|unique:unit,unit_name,' . $unit->id,
        ]);

        $unit->update([
            'unit_name' => $request->unit_name,
        ]);

        return redirect()->route('units.index')->with('success', 'Підрозділ успішно перейменовано.');
    }
}
