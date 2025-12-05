<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Видалені вікові групи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="mb-3" style="padding: 10px;">
    <button onclick="location.href='/participants'" class="btn btn-outline-secondary btn-sm">Повернутися до активних</button>
</div>

<div class="container mt-5">

    <h1>Видалені вікові групи</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Номер</th>
                <th>Опис</th>
                <th>Стать</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ageGroups as $group)
                <tr>
                    <td>{{ $group->age_group_number }}</td>
                    <td>{{ $group->description }}</td>
                    <td>{{ ucfirst($group->gender) }}</td>
                    <td>
                        <form action="{{ route('age_groups.restore', $group) }}" method="POST" onsubmit="return confirm('Відновити цю вікову групу?');">
                            @csrf
                            <button class="btn btn-success btn-sm" type="submit">Відновити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Немає видалених вікових груп</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
</body>
</html>
