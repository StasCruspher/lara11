<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Редагувати підрозділ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">

    <h1>Редагувати підрозділ</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('units.update', $unit) }}" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="unit_name" class="form-label">Назва підрозділу:</label>
            <input type="text" name="unit_name" id="unit_name" class="form-control" required
                   value="{{ old('unit_name', $unit->unit_name) }}">
            @error('unit_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">💾 Зберегти</button>
        <a href="{{ route('units.index') }}" class="btn btn-secondary">Назад</a>
    </form>

</div>
</body>
</html>
