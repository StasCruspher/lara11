<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Заліки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/age-groups'" class="btn btn-outline-secondary btn-sm">Вікові групи</button>
    <button onclick="location.href='/categories'" class="btn btn-outline-secondary btn-sm">Категорії</button>
<button onclick="location.href='/phys-fitness-requirements'" class="btn btn-outline-secondary btn-sm">Вимоги</button>

    <button onclick="location.href='/mil-ranks'" class="btn btn-outline-secondary btn-sm">Військові звання</button>
    <button onclick="location.href='/participants'" class="btn btn-outline-secondary btn-sm">Учні</button>
    <button onclick="location.href='/units'" class="btn btn-outline-secondary btn-sm">Підрозділи</button>
    <button onclick="location.href='/exercises'" class="btn btn-outline-secondary btn-sm">Вправи</button>
    <button onclick="location.href='/scores'" class="btn btn-primary btn-sm">Залік</button>
<form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-outline-danger btn-sm">
        🚪 Вийти
    </button>
</form>

<div class="container mt-5">
    <h1>Створення заліку</h1>
    <p>Оберіть підрозділ</p>

    <form action="{{ route('scores.create') }}" method="GET" style="max-width: 400px;">
        @csrf
        <div class="mb-3">
            <select name="unit_id" id="unit_id" class="form-control" required>
                <option value="">Підрозділ...</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Далі →</button>
    </form>
</div>
</body>
</html>
