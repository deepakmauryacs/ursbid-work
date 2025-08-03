@extends('ursbid-admin.layouts.app')
@section('title', 'Sub Categories')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 text-end">
                        <a href="{{ route('super-admin.sub-categories.create') }}" class="btn btn-primary">Add Sub Category</a>
                    </div>
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
                                        <td>{{ $sub->post_date }}</td>
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
