<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="check-all">
            </th>
            <th>ID</th>
            <th>Nombre</th>
            <th>Empresa</th>
            <th>Email</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr>
            <td>
                <input type="checkbox"
                       name="users[]"
                       value="{{ $user->id }}">
            </td>
            <td>{{ $user->id }}</td>
            <td>{{ $user->nombre }}</td>
            <td>{{ $user->profile->empresa ?? '-' }}</td>
            <td>{{ $user->email }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
