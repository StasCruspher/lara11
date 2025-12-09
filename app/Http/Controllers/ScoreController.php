<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Unit;
use App\Models\Exercise;
use App\Models\ScoreExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Result;
use App\Models\Participant;
use App\Models\ResultExercise;
use App\Models\Requirement;
use App\Models\PhysFitnessRequirement;


class ScoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Score::with([
            'unit' => fn($q) => $q->withTrashed(),
            'results.participant'
        ]);

        if ($request->filled('participant_id')) {
            $query->whereIn('id', function($q) use ($request) {
                $q->select('score_id')
                  ->from('result')
                  ->where('participant_id', $request->participant_id);
            });
        }

        if ($request->filled('unit')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('unit_name', 'like', '%' . $request->unit . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('exercise_count')) {
            $query->where('exercise_count', $request->exercise_count);
        }

        if ($request->filled('incomplete') && $request->incomplete) {
            $query->whereHas('results', function($q){
                $q->where('phys_fitness_point', 0);
            });
        }

        $sortDate = $request->filled('sort_date') && in_array($request->sort_date, ['asc', 'desc'])
            ? $request->sort_date
            : 'desc';

        $query->orderBy('date', $sortDate)
              ->orderBy(Unit::select('unit_name')
                     ->whereColumn('unit.id', 'score.unit_id'), 'asc');

        $scores = $query->paginate(10);

        // Для фільтру "кількість вправ"
        $exerciseCounts = Score::select('exercise_count')
            ->distinct()
            ->orderBy('exercise_count')
            ->pluck('exercise_count');

        return view('scores.index', compact('scores', 'exerciseCounts'));
    }

    public function selectUnit()
    {
        $units = Unit::all();
        return view('scores.select-unit', compact('units'));
    }


    public function create(Request $request)
    {
        $unitId = $request->query('unit_id');

        $units = Unit::all();
        $exercises = Exercise::all();
        
        $participants = Participant::where('unit_id', $unitId)->get();

        return view('scores.create', compact('units', 'exercises', 'participants', 'unitId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:unit,id',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:participant,id',
            'exercises' => 'required|array|min:3|max:5',
            'exercises.*' => 'exists:exercise,id'
        ], [
            'unit_id.required' => 'Підрозділ не вибрано.',
            'participants.required' => 'Виберіть учнів.',
            'participants.*.exists' => 'Один із вибраних учнів не існує.',
            'exercises.required' => 'Виберіть вправи.',
            'exercises.min' => 'Мінімум 3 вправи.',
            'exercises.max' => 'Максимум 5 вправ.'
        ]);

        // 1️⃣ Створюємо сам залік
        $score = Score::create([
            'unit_id' => $request->unit_id,
            'date' => $request->date,
            'exercise_count' => count($request->exercises),
        ]);

        // 2️⃣ Додаємо вправи до заліку
        foreach ($request->exercises as $exerciseId) {
            ScoreExercise::create([
                'score_id' => $score->id,
                'exercise_id' => $exerciseId,
            ]);
        }

        // 3️⃣ Створюємо результати ТІЛЬКИ для вибраних учнів
        $participants = Participant::whereIn('id', $request->participants)->get();

        foreach ($participants as $participant) {
            $result = Result::create([
                'score_id' => $score->id,
                'participant_id' => $participant->id,
            ]);

            // Додаємо вправи для кожного результату
            foreach ($request->exercises as $exerciseId) {
                ResultExercise::create([
                    'result_id' => $result->id,
                    'exercise_id' => $exerciseId,
                ]);
            }
        }

        return redirect()->route('scores.index')->with('success', 'Залік створено успішно!');
    }

    public function storeParticipant(Request $request, Score $score)
    {
        $request->validate([
            'participant_id' => 'required|exists:participant,id',
        ], [
            'participant_id.required' => 'Виберіть учня.',
        ]);

        if(Result::where('score_id', $score->id)->where('participant_id', $request->participant_id)->exists()){
            return redirect()->back()->with('error', 'Учень вже доданий до заліку!');
        }

        $result = Result::create([
            'score_id' => $score->id,
            'participant_id' => $request->participant_id,
        ]);

        foreach ($score->exercises as $exercise) {
            ResultExercise::create([
                'result_id' => $result->id,
                'exercise_id' => $exercise->id,
            ]);
        }

        return redirect()->back()->with('success', 'Учня додано до заліку!');
    }

public function show($id)
{
    $score = Score::withTrashed()->findOrFail($id);

    $score->load(['unit' => fn($q) => $q->withTrashed()]);

    $results = Result::with([
        'participant' => fn($q) => $q->withTrashed()->with([
            'milRank'   => fn($q2) => $q2->withTrashed(),
            'ageGroup'  => fn($q2) => $q2->withTrashed(),
            'category'  => fn($q2) => $q2->withTrashed(),
        ]),
        'exercises.exercise' => fn($q) => $q->withTrashed(),
    ])
    ->where('score_id', $id)
    ->get();

    $exercises = $score->exercises()->withTrashed()->get();

    $participants = Participant::with([
        'milRank'  => fn($q) => $q->withTrashed(),
        'ageGroup' => fn($q) => $q->withTrashed(),
        'category' => fn($q) => $q->withTrashed(),
    ])->get();
    
    $allParticipants = Participant::with([
        'milRank' => function ($q) {
            $q->withTrashed();
        }
    ])->get();
    
    $archived = false;

    if ($score->trashed() || ($score->unit && $score->unit->trashed())) {
        $archived = true;
    }

    if ($exercises->contains(fn($e) => $e->trashed())) {
        $archived = true;
    }

    foreach ($results as $result) {
        $p = $result->participant;
        if (!$p) continue;

        if ($p->trashed()
            || ($p->milRank && $p->milRank->trashed())
            || ($p->ageGroup && $p->ageGroup->trashed())
            || ($p->category && $p->category->trashed())) {
            $archived = true;
            break;
        }

        foreach ($result->exercises as $re) {
            if ($re->exercise && $re->exercise->trashed()) {
                $archived = true;
                break 2;
            }
        }
    }

    return view('scores.show', compact('participants', 'score', 'exercises', 'results', 'archived', 'allParticipants'));
}

public function updateResult(Request $request)
{
    try {
        $participantId = $request->participant;
        $exerciseId = $request->exercise;
        $scoreId = $request->score;
        $inputResult = $request->result;

        $participant = Participant::with(['ageGroup', 'category'])->findOrFail($participantId);
        $score = Score::with('scoreExercises')->findOrFail($scoreId);
        $exercise = Exercise::findOrFail($exerciseId);

        $req50 = Requirement::where('exercise_id', $exerciseId)
            ->where('gender', $participant->gender)
            ->where('point', 50)
            ->first();

        $isLowerBetter = false;
        if ($req50) {
            $anyOther = Requirement::where('exercise_id', $exerciseId)
                ->where('gender', $participant->gender)
                ->where('point', '<>', 50)
                ->orderBy('point', 'asc')
                ->first();

            if ($anyOther && bccomp($req50->result, $anyOther->result, 2) < 0) {
                $isLowerBetter = true;
            }
        }

        $requirements = Requirement::where('exercise_id', $exerciseId)
            ->where('gender', $participant->gender)
            ->get();

        $isTimeBased = $this->isTimeBasedExercise($requirements);

        if ($isLowerBetter) {
            $req = Requirement::where('exercise_id', $exerciseId)
                ->where('gender', $participant->gender)
                ->where('result', '>=', $inputResult)
                ->orderBy('result', 'asc')
                ->first();
        } else {
            $req = Requirement::where('exercise_id', $exerciseId)
                ->where('gender', $participant->gender)
                ->where('result', '<=', $inputResult)
                ->orderBy('result', 'desc')
                ->first();
        }

        $assignedPoint = $req ? $req->point : 0;
        $bonus = 0;

        if ($req50) {
            if ($isTimeBased) {
                $inputParts = explode('.', strval($inputResult));
                $inputMin = intval($inputParts[0]);
                $inputSec = isset($inputParts[1]) ? intval($inputParts[1]) : 0;

                $maxParts = explode('.', strval($req50->result));
                $maxMin = intval($maxParts[0]);
                $maxSec = isset($maxParts[1]) ? intval($maxParts[1]) : 0;

                $inputTotalSec = $inputMin * 60 + $inputSec;
                $maxTotalSec = $maxMin * 60 + $maxSec;

                if ($isLowerBetter && $inputTotalSec < $maxTotalSec) {
                    $bonus = $maxTotalSec - $inputTotalSec;
                } elseif (!$isLowerBetter && $inputTotalSec > $maxTotalSec) {
                    $bonus = $inputTotalSec - $maxTotalSec;
                }
            } else {
                if ($isLowerBetter) {
                    $diff = bcsub($req50->result, $inputResult, 2);
                } else {
                    $diff = bcsub($inputResult, $req50->result, 2);
                }

                $bonus = floor(bcmul($diff, '10', 2));
            }
        }

        $assignedPointWithBonus = ($assignedPoint >= 50) ? $assignedPoint + $bonus : $assignedPoint;

        $result = Result::firstOrCreate([
            'participant_id' => $participantId,
            'score_id' => $scoreId
        ]);

        ResultExercise::updateOrCreate(
            [
                'result_id' => $result->id,
                'exercise_id' => $exerciseId,
            ],
            [
                'result' => $inputResult,
                'point' => $assignedPointWithBonus
            ]
        );

        $pointSum = ResultExercise::where('result_id', $result->id)->sum('point');
        $result->point_sum = $pointSum;

        $completedExercises = ResultExercise::where('result_id', $result->id)
            ->whereNotNull('result')
            ->count();

        $physFitnessPoint = null;

        $minExercisesRequired = 3;

        if ($completedExercises >= $minExercisesRequired) {
            $threshold = PhysFitnessRequirement::where('age_group_id', $participant->age_group_id)
                ->where('category_id', $participant->category_id)
                ->where('gender', $participant->gender)
                ->first();

            if ($threshold) {
                $passedCount = ResultExercise::where('result_id', $result->id)
                    ->whereNotNull('result')
                    ->where('point', '>=', $threshold->exercise_threshold)
                    ->count();

                $minPassedRequired = 3;

                if ($passedCount >= $minPassedRequired) {
                    $grade = PhysFitnessRequirement::where('age_group_id', $participant->age_group_id)
                        ->where('category_id', $participant->category_id)
                        ->where('gender', $participant->gender)
                        ->where('exercise_count', $completedExercises)
                        ->where('total_points', '<=', $pointSum)
                        ->orderByDesc('total_points')
                        ->first();

                    $physFitnessPoint = $grade ? $grade->result : 0;
                } else {
                    $physFitnessPoint = 0;
                }
            }
        } else {
            $physFitnessPoint = 0;
        }

        $result->phys_fitness_point = $physFitnessPoint;
        $result->save();

        return response()->json([
            'assigned_point' => $assignedPointWithBonus,
            'point_sum' => $pointSum,
            'phys_fitness_point' => $physFitnessPoint ?? 0,
        ]);

    } catch (\Exception $e) {
        \Log::error('Помилка у updateResult: ' . $e->getMessage());
        return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
    }
}

private function isTimeBasedExercise($requirements)
{
    foreach ($requirements as $req) {
        $parts = explode('.', strval($req->result));
        if (count($parts) == 2 && intval($parts[1]) > 59) {
            return false;
        }
    }
    return true;
}

private function determineStepByRequirementsCollection($requirements)
{
    $hasTwoDigits = false;
    $hasOneDigit = false;

    foreach ($requirements as $r) {
        $parts = explode('.', strval($r->result));
        if (count($parts) == 2) {
            $decimals = $parts[1];
            if (strlen($decimals) >= 2) {
                $hasTwoDigits = true;
                break;
            } elseif (strlen($decimals) == 1) {
                $hasOneDigit = true;
            }
        }
    }

    if ($hasTwoDigits) return 0.01;
    if ($hasOneDigit) return 0.1;
    return 1;
}

}
