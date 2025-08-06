<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Role Name</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse($roles as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->role_name }}</td>
            <td>{{ $role->status == '1' ? 'Active' : 'Inactive' }}</td>
            <td>{{ $role->created_at->format('d-m-Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">No roles found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
