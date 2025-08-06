@extends('ursbid-admin.layouts.app')
@section('title', 'Products')

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

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Products</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->

    <!-- Filter Section Start -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sub Category</label>
                                <select name="subcategory" id="subcategory" class="form-select">
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Product Name">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="button" id="resetBtn" class="btn btn-secondary ms-2">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter Section End -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Products List</h4>
                    </div>
                    <a href="{{ route('super-admin.products.create') }}" class="btn btn-sm btn-primary">Add Product</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Product Title</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            @include('ursbid-admin.products.partials.table')
                        </tbody>
                    </table>
                </div>
                <div id="paginationContainer">
                    @include('ursbid-admin.products.partials.pagination')
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    function loadSubCategories(catId){
        $('#subcategory').html('<option value="">Select Sub Category</option>');
        if(catId){
            $.get('{{ route('super-admin.products.get-subcategories') }}', {cat_id: catId}, function(res){
                res.forEach(function(item){
                    $('#subcategory').append(`<option value="${item.id}">${item.title}</option>`);
                });
            });
        }
    }

    $('#category').on('change', function(){
        loadSubCategories($(this).val());
    });

    $('#filterForm').on('submit', function(e){
        e.preventDefault();
        const name = $('#name').val();
        if(name.length > 255){
            toastr.error('Product name may not be greater than 255 characters.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.products.index') }}',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
                $('#productTableBody').html(res.html);
                $('#paginationContainer').html(res.pagination);
            },
            error: function(xhr){
                if(xhr.status === 422){
                    toastr.error('Please provide valid filter inputs.');
                } else {
                    toastr.error('Unable to fetch products.');
                }
            }
        });
    });

    $('#resetBtn').on('click', function(){
        $('#filterForm')[0].reset();
        $('#subcategory').html('<option value="">Select Sub Category</option>');
        $('#filterForm').trigger('submit');
    });

    $(document).on('click', '.deleteBtn', function(){
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
});
</script>
@endpush
