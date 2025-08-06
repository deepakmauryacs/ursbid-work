<table class="table align-middle text-nowrap table-hover table-centered mb-0">
    <thead class="bg-light-subtle">
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>User Type</th>
            <th>Created Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->user_type == 1 ? 'Super Admin' : 'Admin' }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.user-management.edit', $user->id) }}" class="btn btn-soft-primary btn-sm">Edit</a>
                        <button type="button" class="btn btn-soft-danger btn-sm deleteBtn" data-id="{{ $user->id }}">Delete</button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
