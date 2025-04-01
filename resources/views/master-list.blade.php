<table class="table">
    <thead>
        <tr>
            <th>Player</th>
            <th>Castle</th>
            <th>Range</th>
            <th>Dukes Needed</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->gameData->castle_level }}</td>
                <td>{{ $user->gameData->range_level }}</td>
                <td>{{ $user->dukes_needed }}</td>
            </tr>
        @endforeach
    </tbody>
</table>