<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Головна</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/'" class="btn btn-primary btn-sm">Головна</button>
    <button onclick="location.href='{{ route('scores.index') }}'" class="btn btn-outline-secondary btn-sm">Спорт</button>
    <button onclick="location.href='{{ route('weapons.index') }}'" class="btn btn-outline-secondary btn-sm">Стрільби</button>

    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm">
            🚪 Вийти
        </button>
    </form>
</div>

<div class="container mt-5 text-center">
    <h1 class="mb-4">Система обліку нормативів</h1>

    <div class="d-flex justify-content-center gap-4">
        <button onclick="location.href='{{ route('scores.index') }}'" class="btn btn-outline-secondary btn-sm">
            Спорт
        </button>

        <button onclick="location.href='{{ route('weapons.index') }}'" class="btn btn-outline-secondary btn-sm">
            Стрільби
        </button>
    </div>
</div>

</body>
</html>
