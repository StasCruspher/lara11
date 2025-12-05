<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Військові звання - Видалені</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/mil-ranks'" class="btn btn-outline-secondary btn-sm">Повернутися до активних</button>
</div>

<div class="container mt-5">
    <h1>Видалені військові звання</h1>

    {{-- Повідомлення --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-primary">
            <tr>
                <th>Назва звання</th>
                <th style="width: 150px;">Дія</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ranks as $rank)
                <tr>
                    <td>{{ $rank->name }}</td>
                    <td>
                        <form action="{{ route('mil-ranks.restore', $rank) }}" method="POST" onsubmit="return confirm('Відновити це звання?');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Відновити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">Немає видалених звань</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
