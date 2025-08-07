<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Role Name</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($roles as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->role_name }}</td>
            <td>{{ $role->status == '1' ? 'Active' : 'Inactive' }}</td>
            <td>{{ $role->created_at->format('d-m-Y') }}</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('super-admin.roles.permissions.edit', $role->id) }}" class="btn btn-soft-secondary btn-sm" title="Permissions"><i class="ri-lock-line"></i></a>
                    <a href="{{ route('super-admin.roles.edit', $role->id) }}" class="btn btn-soft-primary btn-sm">Edit</a>
                    <button type="button" class="btn btn-soft-danger btn-sm deleteBtn" data-id="{{ $role->id }}">Delete</button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No roles found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
