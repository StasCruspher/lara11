<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Видалені категорії</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/participants'" class="btn btn-outline-secondary btn-sm">Повернутися до активних</button>
</div>

<div class="container mt-5">

    <h1>Видалені категорії</h1>

    {{-- Повідомлення --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Таблиця видалених категорій --}}
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-primary">
            <tr>
                <th style="width: 120px;">Номер</th>
                <th style="width: 150px;">Дія</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->category_number }}</td>
                    <td>
                        <form action="{{ route('categories.restore', $category->id) }}" method="POST" onsubmit="return confirm('Відновити цю категорію?');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Відновити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">Немає видалених категорій</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
</body>
</html>
