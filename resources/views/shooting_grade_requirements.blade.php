<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Оцінки для стрільб</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='{{ route('home.index') }}'" class="btn btn-outline-secondary btn-sm">Головна</button>
    <button onclick="location.href='{{ route('sport.index') }}'" class="btn btn-outline-secondary btn-sm">Спорт</button>
    <button onclick="location.href='{{ route('shooting.index') }}'" class="btn btn-primary btn-sm">Стрільби</button>
</div>

<div class="container mt-5">
    <h1>Оцінки для стрільб</h1>

    {{-- Форма добавления --}}
    <form method="POST" action="{{ route('shooting_grades.store') }}" style="max-width: 600px;">
        @csrf
        <div class="mb-3">
            <label for="shooting_exercise_id">Вправа:</label>
            <select name="shooting_exercise_id" id="shooting_exercise_id" class="form-select" required>
                <option value="">-- Оберіть вправу --</option>
                @foreach($exercises as $ex)
                <option value="{{ $ex->id }}">{{ $ex->code }} — {{ $ex->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="grade_name">Назва оцінки:</label>
            <input type="text" name="grade_name" id="grade_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="condition_description">Умова отримання:</label>
            <textarea name="condition_description" id="condition_description" class="form-control" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">➕ Додати</button>
    </form>

    {{-- Таблица --}}
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-primary">
        <tr>
            <th>Вправа</th>
            <th>Оцінка</th>
            <th>Умова</th>
            <th style="width: 100px;">Дія</th>
        </tr>
        </thead>
        <tbody>
        @forelse($grades as $grade)
        <tr>
            <td>{{ $grade->exercise->code }} — {{ $grade->exercise->name }}</td>
            <td>{{ $grade->grade_name }}</td>
            <td>{{ $grade->condition_description }}</td>
            <td>
                <form action="{{ route('shooting_grades.destroy', $grade) }}" method="POST" onsubmit="return confirm('Видалити оцінку?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Видалити</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center text-muted">Немає оцінок</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
