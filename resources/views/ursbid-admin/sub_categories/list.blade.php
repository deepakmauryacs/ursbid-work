@extends('ursbid-admin.layouts.app')
@section('title', 'Sub Categories')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Sub Categories List</h4>
                    </div>
                    <a href="{{ route('super-admin.sub-categories.create') }}" class="btn btn-sm btn-primary">Add Sub Category</a>
                </div>
                <div class="card-body">
                    <form method="get" class="row mb-3">
                        <div class="col-md-2">
                            <select name="per_page" class="form-select" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Post Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subs as $sub)
                                    <tr id="row-{{ $sub->id }}">
                                        <td>{{ $sub->id }}</td>
                                        <td>{{ $sub->title }}</td>
                                        <td>{{ $sub->category_title }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('d-m-Y', $sub->post_date)->format('d-m-Y') }}</td>
                                        <td>{{ $sub->status == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <a href="{{ route('super-admin.sub-categories.edit', $sub->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-url="{{ route('super-admin.sub-categories.destroy', $sub->id) }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $subs->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(function(){
    $('.delete-btn').on('click', function(){
        if(!confirm('Are you sure?')) return;
        let url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'POST',
            data: {_method:'DELETE', _token:'{{ csrf_token() }}'},
            success: function(res){
                toastr.success(res.message);
                $('#row-'+url.split('/').pop()).remove();
            },
            error: function(){
                toastr.error('Delete failed', 'Error');
            }
        });
    });
});
</script>
@endpush
@endsection
