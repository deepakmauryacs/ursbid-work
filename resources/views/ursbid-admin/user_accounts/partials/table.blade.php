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
    $emptyColspan = $showBusinessColumns ? 9 : 7;
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
            <th>Join Users</th>
            <th>Created Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Illuminate\Support\Str::title($user->name) }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                @php $routeType = $accountRouteMap[$user->acc_type] ?? ($currentType ?? ''); @endphp
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
                <td>
                    @if(!empty($user->ref_code))
                        <a href="{{ route('super-admin.accounts.join-users', [$routeType, $user->ref_code]) }}" class="btn btn-soft-primary btn-sm">
                            View
                        </a>
                    @else
                        —
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                <td>
                    @if($user->status == '1')
                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $emptyColspan }}" class="text-center">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
