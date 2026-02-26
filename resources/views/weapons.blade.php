<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Оружие</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="mb-3 d-flex gap-2">
    <button onclick="location.href='{{ route('home.index') }}'"
            class="btn btn-outline-secondary btn-sm">
        Головна
    </button>
    <button onclick="location.href='{{ route('weapons.index') }}'"
            class="btn btn-outline-secondary btn-sm">
        Стрільби
    </button>
    <button onclick="location.href='{{ route('weapons.index') }}'"
            class="btn btn-primary btn-sm">
        Зброя
    </button>
    <button onclick="location.href='{{ route('shooting_exercises.index') }}'"
            class="btn btn-outline-secondary btn-sm">
        Вправи
    </button>
    <button onclick="location.href='{{ route('shooting_grade_requirements.index') }}'"
            class="btn btn-outline-secondary btn-sm">
        Норми оцінювання
    </button>
</div>

<div class="container mt-5">
    <h1>Оружие</h1>

    {{-- Форма добавления --}}
    <form method="POST" action="{{ route('weapons.store') }}" style="max-width: 500px;">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Назва зброї:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">➕ Додати</button>
    </form>

    {{-- Таблица --}}
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-primary">
        <tr>
            <th>Назва</th>
            <th style="width: 100px;">Дія</th>
        </tr>
        </thead>
        <tbody>
        @forelse($weapons as $weapon)
        <tr>
            <td>{{ $weapon->name }}</td>
            <td>
                <form action="{{ route('weapons.destroy', $weapon) }}" method="POST" onsubmit="return confirm('Видалити цю зброю?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Видалити</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="2" class="text-center text-muted">Немає зброї</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
