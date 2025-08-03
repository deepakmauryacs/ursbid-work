@extends('ursbid-admin.layouts.app')
@section('title', 'Categories')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Categories List</h4>
                    </div>
                     <a href="{{ route('super-admin.categories.create') }}" class="btn  btn-sm btn-primary">Add Category</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>Category Title</th>
                                <th>Post Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr id="row-{{ $category->id }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <img src="{{ asset('assets/images/properties/p-1.jpg') }}" alt="" class="avatar-md rounded border border-light border-3">
                                        </div>
                                        <div>
                                            <a href="#!" class="text-dark fw-medium fs-15">{{ $category->title }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $category->post_date }}</td>
                                <td>
                                    @if($category->status == '1')
                                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.categories.edit', $category->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button" data-id="{{ $category->id }}" class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No categories found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $categories->links() }}
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
