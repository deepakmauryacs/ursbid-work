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
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input status-toggle" type="checkbox" data-id="{{ $user->id }}" {{ $user->status == '1' ? 'checked' : '' }}>
                        </div>
                        <span class="status-text fw-medium {{ $user->status == '1' ? 'text-success' : 'text-danger' }}">
                            {{ $user->status == '1' ? 'Active' : 'Inactive' }}
                        </span>
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
