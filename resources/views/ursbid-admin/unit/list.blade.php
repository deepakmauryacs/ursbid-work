@extends('ursbid-admin.layouts.app')
@section('title', 'Units')

@section('content')
<style>
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.page-item {
    margin: 0;
}

.page-link {
    display: inline-block;
    padding: 5px 10px;
    color: #333;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
}

.page-item.active .page-link {
    background-color: #614ce1;
    color: #fff;
    border-color: #614ce1;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #ddd;
}

.page-link:hover {
    background-color: #f8f9fa;
    color: #0056b3;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Units</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Units</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Units List</h4>
                    </div>
                    <a href="{{ route('super-admin.units.create') }}" class="btn btn-sm btn-primary">Add Unit</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Unit Title</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                            <tr id="row-{{ $unit->id }}">
                                <td>{{ $units->firstItem() + $loop->index }}</td>
                                <td>{{ $unit->category_name }}</td>
                                <td>{{ $unit->sub_name }}</td>
                                <td>{{ $unit->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($unit->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" data-id="{{ $unit->id }}" {{ $unit->status == '1' ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.units.edit', $unit->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button" data-id="{{ $unit->id }}" data-url="{{ route('super-admin.units.destroy', $unit->id) }}" class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No units found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-paginationwithlength :paginator="$units" />

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('.deleteBtn').on('click', function(){
        if(!confirm('Are you sure want to delete?')) return;
        let id = $(this).data('id');
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res){
                toastr.success(res.message);
                $('#row-'+id).remove();
            },
            error: function(){
                toastr.error('Unable to delete record');
            }
        });
    });

    $(document).on('change', '.status-toggle', function(){
        const checkbox = $(this);
        const id = checkbox.data('id');
        const status = checkbox.is(':checked') ? 1 : 2;
        const url = '{{ route('super-admin.units.toggle-status', ':id') }}'.replace(':id', id);
        $.ajax({
            url: url,
            type: 'PATCH',
            data: { status: status, _token: '{{ csrf_token() }}' },
            success: function(res){
                toastr.success(res.message);
            },
            error: function(){
                toastr.error('Unable to update status');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });
});
</script>
@endpush
