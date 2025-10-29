@php
    $accountRouteMap = $typeRouteMap ?? [
        '1' => 'vendors',
        '2' => 'contractors',
        '3' => 'clients',
        '4' => 'buyers',
    ];
@endphp

@php
    $showBusinessColumns = in_array($currentType ?? '', ['vendors', 'contractors'], true);
    $emptyColspan = $showBusinessColumns ? 10 : 8;
@endphp

<table class="table align-middle text-nowrap table-hover table-centered mb-0">
    <thead class="bg-light-subtle">
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            @if($showBusinessColumns)
                <th>GST</th>
                <th>Product/Services</th>
            @endif
            <th>Created Date</th>
            <th>Status</th>
            <th>Join User</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Illuminate\Support\Str::title($user->name) }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                @if($showBusinessColumns)
                    <td>{{ $user->gst ?: '—' }}</td>
                    <td>
                    @php
                        $proSer = $user->pro_ser ?? '';
                        $shortText = Str::limit($proSer, 20, '...');
                    @endphp

                    @if($proSer)
                        {{ $shortText }}
                        <i class="bi bi-info-circle ms-1 text-primary"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="{{ $proSer }}"></i>
                    @else
                        —
                    @endif
                </td>

                @endif
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                <td>
                    @if($user->status == '1')
                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                    @endif
                </td>
                <td>
                    @if(!empty($user->ref_code))
                        <a href="{{ url('admin/joinmember/'.$user->ref_code) }}" class="btn btn-soft-primary btn-sm">View Members</a>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @php $routeType = $accountRouteMap[$user->acc_type] ?? ''; @endphp
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.accounts.edit', [$routeType, $user->id]) }}" class="btn btn-soft-primary btn-sm" title="Edit">
                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                        </a>
                        <a href="{{ route('super-admin.accounts.show', [$routeType, $user->id]) }}" class="btn btn-soft-info btn-sm" title="View">
                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $emptyColspan }}" class="text-center">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
