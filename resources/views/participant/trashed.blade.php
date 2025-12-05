<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Видалені учні</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/participants'" class="btn btn-outline-secondary btn-sm">Повернутися до активних</button>
</div>

<div class="container mt-5">

    <h1>Видалені учні</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>ПІБ</th>
                <th>Звання</th>
                <th>Стать</th>
                <th>Номер нагрудного</th>
                <th>Категорія</th>
                <th>Вікова група</th>
                <th>Підрозділ</th>
                <th style="width: 120px;">Дія</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $p)
                <tr>
                    <td>{{ $p->fullname }}</td>
                    <td>{{ $p->milRank->name ?? '' }}</td>
                    <td>{{ $p->gender }}</td>
                    <td>{{ $p->badge_number }}</td>
                    <td>{{ $p->category->category_number ?? '' }}</td>
                    <td>{{ $p->ageGroup->age_group_number ?? '' }} - {{ $p->ageGroup->gender ?? '' }}</td>
                    <td>{{ $p->unit->unit_name ?? '' }}</td>
                    <td>
                        <form action="{{ route('participants.restore', $p) }}" method="POST" onsubmit="return confirm('Відновити цього учня?');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Відновити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Немає видалених учнів</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
</body>
</html>
