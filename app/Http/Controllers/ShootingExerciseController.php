<?php

namespace App\Http\Controllers;

use App\Models\ShootingExercise;
use App\Models\Weapon;
use Illuminate\Http\Request;

class ShootingExerciseController extends Controller
{
    public function index()
    {
        $weapons = Weapon::all();
        $exercises = ShootingExercise::with('weapon')->orderBy('code')->get();
        return view('shooting_exercises', compact('exercises', 'weapons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'weapon_id' => 'required|exists:weapon,id',
            'target_description' => 'required|string',
            'distance_m' => 'required|integer|min:1',
            'ammo_count' => 'required|integer|min:1',
            'time_minutes' => 'nullable|integer|min:0',
            'time_seconds' => 'nullable|integer|min:0|max:59',
            'time_unlimited' => 'nullable', // ← УБРАЛ boolean
            'shooting_position' => 'nullable|string',
            'execution_order' => 'nullable|string',
        ]);

        $unlimited = $request->has('time_unlimited'); // ← ключевой момент

        $minutes = $request->time_minutes;
        $seconds = $request->time_seconds;

        if ($unlimited) {
            if (!is_null($minutes) || !is_null($seconds)) {
                return back()->withErrors([
                    'time_unlimited' => 'Або встановіть час, або оберіть "Без обмеження".'
                ])->withInput();
            }
        } else {
            if (is_null($minutes) && is_null($seconds)) {
                return back()->withErrors([
                    'time_minutes' => 'Потрібно вказати час або обрати "Без обмеження".'
                ])->withInput();
            }
        }

        ShootingExercise::create([
            'code' => $request->code,
            'name' => $request->name,
            'weapon_id' => $request->weapon_id,
            'target_description' => $request->target_description,
            'distance_m' => $request->distance_m,
            'ammo_count' => $request->ammo_count,
            'time_minutes' => $unlimited ? null : $minutes,
            'time_seconds' => $unlimited ? null : $seconds,
            'time_unlimited' => $unlimited ? 1 : 0,
            'shooting_position' => $request->shooting_position,
            'execution_order' => $request->execution_order,
        ]);

        return back()->with('success', 'Вправа додана!');
    }

    public function destroy(ShootingExercise $shootingExercise)
    {
        $shootingExercise->delete();
        return redirect()->back()->with('success', 'Вправа видалена!');
    }
}
