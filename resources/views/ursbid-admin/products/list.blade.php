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
.page-item { margin: 0; }
.page-link {
    display: inline-block;
    padding: 5px 10px;
    border: 1px solid #ddd; border-radius: 4px;
    background-color: #fff;
}
.page-item.active .page-link {
    background-color: #614ce1; color: #fff; border-color: #614ce1;
}
.page-item.disabled .page-link {
    color: #6c757d; pointer-events: none; background-color: #fff; border-color: #ddd;
}
.page-link:hover { background-color: #f8f9fa; color: #0056b3; }
</style>

<div class="container-fluid">
    <!-- Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Products</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form id="filterForm" action="{{ route('super-admin.products.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected':'' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sub Category</label>
                                <select name="subcategory" id="subcategory" class="form-select">
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="Product Name">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Per Page</label>
                                <select id="perPage" class="form-select">
                                    @php $pp = request('per_page', $perPage ?? 10); @endphp
                                    @foreach([10,25,50,100] as $n)
                                        <option value="{{ $n }}" {{ (int)$pp === $n ? 'selected':'' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                                <a href="{{ route('super-admin.products.create') }}" class="btn btn-success ms-2">Add Product</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div id="table-container">
                        @include('ursbid-admin.products.partials.table', ['products' => $products])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    function loadSubCategories(catId, preselect = "{{ request('subcategory') }}"){
        const $sub = $('#subcategory');
        $sub.html('<option value="">Loading...</option>');
        if(!catId){
            $sub.html('<option value="">Select Sub Category</option>');
            return;
        }
        $.get('{{ route('super-admin.products.get-subcategories') }}', { cat_id: catId }, function(res){
            $sub.html('<option value="">Select Sub Category</option>');
            res.forEach(function(item){
                const sel = (String(preselect) === String(item.id)) ? 'selected' : '';
                $sub.append(`<option value="${item.id}" ${sel}>${item.name}</option>`);
            });
        });
    }

    // initialize subcategory on load if category is selected
    const initCat = $('#category').val();
    if(initCat){ loadSubCategories(initCat); }

    // Change subcategory on category change
    $('#category').on('change', function(){ loadSubCategories($(this).val(), ''); });

    // AJAX: filter submit
    $(document).on('submit', '#filterForm', function(e){
        e.preventDefault();
        const url = $(this).attr('action') + '?' + $(this).serialize() + '&per_page=' + $('#perPage').val();
        loadTable(url);
    });

    // AJAX: pagination click
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        loadTable($(this).attr('href'));
    });

    // AJAX: per page change
    $(document).on('change', '#perPage', function(){
        const form = $('#filterForm');
        const url = form.attr('action') + '?' + form.serialize() + '&per_page=' + $(this).val();
        loadTable(url);
    });

    // Reset
    $('#resetBtn').on('click', function(){
        $('#filterForm')[0].reset();
        $('#subcategory').html('<option value="">Select Sub Category</option>');
        const url = $('#filterForm').attr('action') + '?per_page=' + $('#perPage').val();
        loadTable(url);
    });

    // Delete (delegated)
    $(document).on('click', '.deleteBtn', function(){
        if(!confirm('Are you sure want to delete?')) return;
        let id  = $(this).data('id');
        let url = $(this).data('url');
        $.ajax({
            url: url, type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res){
                toastr.success(res.message || 'Deleted');
                $('#filterForm').trigger('submit'); // reload list
            },
            error: function(){ toastr.error('Unable to delete record'); }
        });
    });

    function loadTable(url){
        $.ajax({
            url: url, type: 'GET',
            beforeSend: function(){
                $('#table-container').html('<div class="text-center py-4">Loading...</div>');
            },
            success: function(html){
                $('#table-container').html(html);
                if(history.pushState){ history.pushState(null, null, url); }
            },
            error: function(){ toastr.error('Unable to load data.'); }
        });
    }
})();
</script>
@endpush
