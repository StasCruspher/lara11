<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати звання</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="container mt-5">

    <h1>Редагування військового звання</h1>

    <a href="{{ route('mil-ranks.index') }}" class="btn btn-secondary mb-3">⬅️ Назад</a>

    <form method="POST" action="{{ route('mil-ranks.update', $mil_rank) }}" style="max-width: 400px;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Поточна назва:</label>
            <input type="text" class="form-control" value="{{ $mil_rank->name }}" disabled>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Нова назва:</label>
            <input type="text" name="name" id="name" class="form-control" required maxlength="250" value="{{ old('name', $mil_rank->name) }}">
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            💾 Зберегти зміни
        </button>
    </form>
</div>

</body>
</html>
