<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Видалені підрозділи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/units'" class="btn btn-outline-secondary btn-sm">Повернутися до активних</button>
</div>

<div class="container mt-5">

    <h1>Видалені підрозділи</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Назва підрозділу</th>
                <th style="width: 120px;">Дія</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $unit)
                <tr>
                    <td>{{ $unit->unit_name }}</td>
                    <td>
                        <form action="{{ route('units.restore', $unit) }}" method="POST" onsubmit="return confirm('Відновити цей підрозділ?');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Відновити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center text-muted">Немає видалених підрозділів</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
</body>
</html>
