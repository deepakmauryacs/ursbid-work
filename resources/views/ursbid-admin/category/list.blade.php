@extends('ursbid-admin.layouts.app')
@section('title', 'Categories')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 text-end">
                        <a href="{{ route('super-admin.categories.create') }}" class="btn btn-primary">Add Category</a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Post Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr id="row-{{ $category->id }}">
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->title }}</td>
                                <td>{{ $category->post_date }}</td>
                                <td>{{ $category->status == '1' ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('super-admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <button type="button" data-id="{{ $category->id }}" class="btn btn-sm btn-danger deleteBtn">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No categories found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
        var id = $(this).data('id');
        $.ajax({
            url: '/super-admin/categories/'+id,
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
});
</script>
@endpush
