<table class="table align-middle text-nowrap table-hover table-centered mb-0">
    <thead class="bg-light-subtle">
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Roles</th>
            <th>Created Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>
                    @foreach($user->roles as $r)
                        <span class="badge bg-info-subtle text-info me-1">{{ $r->role_name }}</span>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                <td>
                    @if($user->status == '1')
                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.user-management.edit', $user->id) }}" class="btn btn-soft-primary btn-sm">Edit</a>
                        <button type="button" class="btn btn-soft-danger btn-sm deleteBtn" data-id="{{ $user->id }}">Delete</button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
