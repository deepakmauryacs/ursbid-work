@extends('ursbid-admin.layouts.app')
@section('title', 'Products')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Products List</h4>
                    </div>
                    <a href="{{ route('super-admin.products.create') }}" class="btn btn-sm btn-primary">Add Product</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Post Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr id="row-{{ $product->id }}">
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->title }}</td>
                                        <td>{{ $product->category_title }}</td>
                                        <td>{{ $product->sub_title }}</td>
                                        <td>{{ $product->post_date ? \Carbon\Carbon::parse($product->post_date)->format('d-m-Y') : '' }}</td>
                                        <td>{{ $product->status == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <a href="{{ route('super-admin.products.edit', $product->id) }}" class="btn btn-sm btn-info">Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-url="{{ route('super-admin.products.destroy', $product->id) }}">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex flex-wrap justify-content-between align-items-center">
                    <form method="get" class="d-flex align-items-center mb-2 mb-md-0">
                        <label class="me-2 mb-0">Show</label>
                        <select name="per_page" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ms-2">entries</span>
                        <span class="ms-3 text-muted">
                            Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
                        </span>
                    </form>
                    <div>
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
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
