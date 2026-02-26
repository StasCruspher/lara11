<?php

namespace App\Http\Controllers;

use App\Models\ShootingGradeRequirement;
use App\Models\ShootingExercise;
use Illuminate\Http\Request;

class ShootingGradeController extends Controller
{
    public function index()
    {
        $exercises = ShootingExercise::all();
        $grades = ShootingGradeRequirement::with('exercise')->orderBy('grade_name')->get();
        return view('shooting.grades', compact('grades', 'exercises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shooting_exercise_id' => 'required|exists:shooting_exercises,id',
            'grade_name' => 'required|string|max:50',
            'condition_description' => 'required|string',
        ]);

        ShootingGradeRequirement::create($request->all());

        return redirect()->back()->with('success', 'Оцінка додана!');
    }

    public function destroy(ShootingGradeRequirement $shootingGradeRequirement)
    {
        $shootingGradeRequirement->delete();
        return redirect()->back()->with('success', 'Оцінка видалена!');
    }
}
