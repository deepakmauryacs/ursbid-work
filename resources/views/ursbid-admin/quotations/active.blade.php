@extends('ursbid-admin.layouts.app')
@section('title', 'Active Quotations')

@section('content')

<div class="container-fluid">
    <!-- Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Active Quotations</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Active Quotations</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Filter Active Quotations</h5>
                    <div class="d-flex align-items-center gap-2">
                        <!--<a href="{{ route('super-admin.products.create') }}" class="btn btn-success btn-sm">-->
                        <!--    <i class="bi bi-plus-circle me-1"></i>Add Product-->
                        <!--</a>-->
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#productFilters" aria-expanded="true" aria-controls="productFilters">
                            Toggle Filters
                        </button>
                    </div>
                </div>
    
                <div class="collapse show" id="productFilters">
                    <div class="card-body">
                        <form id="filterForm" action="{{ route('super-admin.quotations.active') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Sub Category</label>
                                <select name="subcategory" id="subcategory" class="form-select">
                                    <option value="">All</option>
                                </select>
                            </div>
                
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" id="product_name" value="{{ request('product_name') }}" class="form-control" placeholder="Enter product name">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Quotation ID</label>
                                <input type="text" name="qutation_id" id="qutation_id" value="{{ request('qutation_id') }}" class="form-control" placeholder="Enter quotation ID">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control" placeholder="Enter date">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" id="city" value="{{ request('city') }}" class="form-control" placeholder="Enter city">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Quantity</label>
                                <input type="text" name="quantity" id="quantity" value="{{ request('quantity') }}" class="form-control" placeholder="Enter quantity">
                            </div>
                
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label d-none d-lg-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-2"></i>Apply Filters</button>
                            </div>
                
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label d-none d-lg-block">&nbsp;</label>
                                <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters</button>
                            </div>
                        </form>
                    </div>
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
                        @include('ursbid-admin.quotations.partials.table', ['quotations' => $quotations])
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
      $sub.html('<option value="">All</option>');
      return;
    }
    $.get('{{ route('super-admin.products.get-subcategories') }}', { cat_id: catId }, function(res){
      $sub.html('<option value="">All</option>');
      (res || []).forEach(function(item){
        const sel = (String(preselect) === String(item.id)) ? 'selected' : '';
        $sub.append(`<option value="${item.id}" ${sel}>${item.name}</option>`);
      });
    });
  }

  const initCat = $('#category').val();
  if(initCat){ loadSubCategories(initCat); }
  $('#category').on('change', function(){ loadSubCategories($(this).val(), ''); });

  $(document).on('submit', '#filterForm', function(e){
    e.preventDefault();
    loadTable($(this).attr('action') + '?' + $(this).serialize());
  });

  $(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    loadTable($(this).attr('href'));
  });

  $('#resetBtn').on('click', function(){
    $('#filterForm')[0].reset();
    $('#subcategory').html('<option value="">All</option>');
    loadTable($('#filterForm').attr('action'));
  });

  $(document).on('click', '.deleteBtn', function(){
    if(!confirm('Are you sure want to delete?')) return;
    $.ajax({
      url: $(this).data('url'),
      type:'DELETE',
      data:{ _token:'{{ csrf_token() }}' },
      success(res){
        toastr.success(res.message || 'Deleted');
        loadTable(window.location.href.split('#')[0]);
      },
      error(){ toastr.error('Unable to delete record'); }
    });
  });

  function loadTable(url){
    $.ajax({
      url, type:'GET',
      beforeSend(){ $('#table-container').html('<div class="text-center py-4">Loading...</div>'); },
      success(html){
        $('#table-container').html(html);
        if(history.pushState){ history.pushState(null, null, url); }
      },
      error(){ toastr.error('Unable to load data.'); }
    });
  }
})();
</script>
@endpush
