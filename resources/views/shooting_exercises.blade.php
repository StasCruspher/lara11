<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Вправи для стрільб</title>
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
            class="btn btn-outline-secondary btn-sm">
        Зброя
    </button>
    <button onclick="location.href='{{ route('shooting_exercises.index') }}'"
            class="btn btn-primary btn-sm">
        Вправи
    </button>
    <button onclick="location.href='{{ route('shooting_grade_requirements.index') }}'"
            class="btn btn-outline-secondary btn-sm">
        Норми оцінювання
    </button>
</div>

<div class="container mt-5">
    <h1>Вправи для стрільб</h1>

    {{-- Форма добавления --}}
    <form method="POST" action="{{ route('shooting_exercises.store') }}" style="max-width: 600px;">
        @csrf
        <div class="mb-3">
            <label for="code">Код вправи:</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name">Назва вправи:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="weapon_id">Зброя:</label>
            <select name="weapon_id" id="weapon_id" class="form-select" required>
                <option value="">-- Оберіть зброю --</option>
                @foreach($weapons as $weapon)
                <option value="{{ $weapon->id }}">{{ $weapon->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="target_description">Ціль:</label>
            <textarea name="target_description" id="target_description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label>Дальність (м):</label>
            <input type="number" name="distance_m" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Кількість боєприпасів:</label>
            <input type="number" name="ammo_count" class="form-control" required>
        </div>
        <div class="mb-3 d-flex gap-2">
            <label>Час:</label>
            <input type="number" name="time_minutes" placeholder="Хвилини" class="form-control">
            <input type="number" name="time_seconds" placeholder="Секунди" class="form-control">
            <div class="form-check align-self-center">
                <input type="checkbox" name="time_unlimited" class="form-check-input" id="time_unlimited">
                <label for="time_unlimited" class="form-check-label">Без обмежень</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="shooting_position">Положення стрільби:</label>
            <textarea name="shooting_position" id="shooting_position" class="form-control" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label for="execution_order">Порядок виконання:</label>
            <textarea name="execution_order" id="execution_order" class="form-control" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">➕ Додати</button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Таблица --}}
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-primary">
        <tr>
            <th>Код</th>
            <th>Назва</th>
            <th>Зброя</th>
            <th>Дальність</th>
            <th>Бойові</th>
            <th>Дія</th>
        </tr>
        </thead>
        <tbody>
        @forelse($exercises as $ex)
        <tr>
            <td>{{ $ex->code }}</td>
            <td>{{ $ex->name }}</td>
            <td>{{ $ex->weapon->name }}</td>
            <td>{{ $ex->distance_m }}</td>
            <td>{{ $ex->ammo_count }}</td>
            <td>
                <form action="{{ route('shooting_exercises.destroy', $ex) }}" method="POST" onsubmit="return confirm('Видалити вправу?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Видалити</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Немає вправ</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
