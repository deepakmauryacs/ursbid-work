@extends('ursbid-admin.layouts.app')
@section('title', 'Products')

@section('content')


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
    <div class="col-12">
      <div class="card filter-wrap border-0">
        <div class="card-body py-3">
          <form id="filterForm" action="{{ route('super-admin.products.index') }}" method="GET">
            <ul class="filter-ul">
        
                <!-- Category -->
                <li>
                    <label class="filter-label">Category:</label>
                    <div class="input-group pill-wrap">
                        <span class="input-group-text"><i class="bi bi-collection"></i></span>
                        <select name="category" id="category" class="form-select">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </li>
        
                <!-- Sub Category -->
                <li>
                    <label class="filter-label">Sub Category:</label>
                    <div class="input-group pill-wrap">
                        <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                        <select name="subcategory" id="subcategory" class="form-select">
                            <option value="">All</option>
                        </select>
                    </div>
                </li>
        
                <!-- Search -->
                <li>
                    <label class="filter-label">Search:</label>
                    <div class="input-group pill-wrap">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="name" id="name" value="{{ request('name') }}"
                               class="form-control" placeholder="Enter product name">
                    </div>
                </li>
        
                <!-- Actions -->
                <li class="actions-li">
                    <button type="submit" class="btn btn-primary btn-icon">
                        <i class="bi bi-funnel"></i>Apply
                    </button>
                    <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-icon">
                        <i class="bi bi-arrow-counterclockwise"></i>Reset
                    </button>
                    <a href="{{ route('super-admin.products.create') }}" class="btn btn-success btn-icon">
                        <i class="bi bi-plus-circle"></i>Add Product
                    </a>
                </li>
        
            </ul>
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
