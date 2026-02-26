<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use Illuminate\Http\Request;

class WeaponController extends Controller
{
    public function index()
    {
        $weapons = Weapon::orderBy('name')->get();
        return view('weapons', compact('weapons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Weapon::create($request->only('name'));

        return redirect()->back()->with('success', 'Зброя додана!');
    }

    public function destroy(Weapon $weapon)
    {
        $weapon->delete();
        return redirect()->back()->with('success', 'Зброя видалена!');
    }
}
