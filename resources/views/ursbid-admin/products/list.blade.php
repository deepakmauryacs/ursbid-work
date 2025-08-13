@extends('ursbid-admin.layouts.app')
@section('title', 'Products')

@section('content')

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
/* ===== Pagination ===== */
.pagination-container {display:flex;justify-content:center;align-items:center;margin-top:20px}
.pagination {display:flex;list-style:none;padding:0;gap:5px}
.page-item {margin:0}
.page-link {display:inline-block;padding:5px 10px;color:inherit;text-decoration:none;border:1px solid #ddd;border-radius:4px;background-color:#fff}
.page-item.active .page-link {background-color:#614ce1;color:#fff;border-color:#614ce1}
.page-item.disabled .page-link {color:#6c757d;pointer-events:none;background-color:#fff;border-color:#ddd}
.page-link:hover {background-color:#f8f9fa;color:#0056b3}

/* ===== Theme Vars ===== */
:root {
  --filter-bg:#ffffff;
  --filter-border:#d1d5db;
  --filter-label:#6b7280;
  --filter-field-bg:#ffffff;
  --filter-placeholder:#6b7280;
}

/* Dark mode overrides */
[data-bs-theme="dark"], .dark, [data-theme="dark"], body.dark {
  --filter-bg:#0f172a;
  --filter-border:#334155;
  --filter-label:#cbd5e1;
  --filter-field-bg:#0b1320;
  --filter-placeholder:#94a3b8;
}

/* Ensure the card uses dark variables */
.card.filter-wrap,
.card.filter-wrap .card-body {
  background:var(--filter-bg) !important;
  border-color:var(--filter-border) !important;
}

/* Inputs & icons in dark */
.input-group.pill-wrap {
  background:var(--filter-field-bg) !important;
  border-color:var(--filter-border) !important;
}
.input-group.pill-wrap .input-group-text {
  background:var(--filter-field-bg) !important;
  color:inherit;
}
.input-group.pill-wrap .form-control,
.input-group.pill-wrap .form-select {
  background:var(--filter-field-bg) !important;
  color:inherit;
}
.input-group.pill-wrap .form-control::placeholder {
  color:var(--filter-placeholder) !important;
}
.input-group.pill-wrap:focus-within{
  box-shadow:0 0 0 3px rgba(148,163,184,.25);
}

/* ===== Filter UL Layout ===== */
.filter-wrap {
  background:var(--filter-bg);
  border:1px solid var(--filter-border);
  border-radius:14px;
  padding:10px;
  box-shadow:0 2px 10px rgba(28,39,60,.06);
}
.filter-ul {
  list-style:none;
  margin:0;
  padding:0;
  display:flex;
  flex-wrap:wrap;
  gap:10px;
}
.filter-ul li {
  flex:1 1 auto;
  min-width:200px;
}
.filter-label {
  font-size:12px;
  font-weight:500;
  color:var(--filter-label);
  margin-bottom:4px;
  display:block;
}

/* Pill-style input group */
.input-group.pill-wrap {
  border:1px solid var(--filter-border);
  border-radius:12px;
  overflow:hidden;
}
.input-group.pill-wrap .input-group-text {
  border:none;
}
.input-group.pill-wrap .form-control,
.input-group.pill-wrap .form-select {
  border:none!important;
  height:44px;
  box-shadow:none;
}
.input-group.pill-wrap .form-control::placeholder {color:var(--filter-placeholder)}

/* Buttons inline */
.filter-actions {
  display:flex;
  flex-wrap:wrap;
  gap:8px;
  align-items:flex-end;
}
.filter-actions .btn {
  border-radius:8px;
  padding:10px 16px;
  font-weight:500;
  display:flex;
  align-items:center;
}
.filter-actions .btn i {margin-right:6px}
.btn-apply {background:#614ce1;border-color:#614ce1;color:#fff}
.btn-apply:hover {filter:brightness(0.95)}
.btn-reset {background:transparent;border:1px solid #614ce1;color:#614ce1}
.btn-reset:hover {background:rgba(97,76,225,.08)}
.btn-add {background:#4fb98f;border-color:#4fb98f;color:#fff}
.btn-add:hover {filter:brightness(0.95)}

@media(max-width:768px){
  .filter-ul li {min-width:100%;}
  .filter-actions {width:100%;}
}
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
    <div class="col-12">
      <div class="card filter-wrap border-0">
        <div class="card-body py-3">
          <form id="filterForm" action="{{ route('super-admin.products.index') }}" method="GET">
            <ul class="filter-ul">
              
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

              <li>
                <label class="filter-label">Sub Category:</label>
                <div class="input-group pill-wrap">
                  <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                  <select name="subcategory" id="subcategory" class="form-select">
                    <option value="">All</option>
                  </select>
                </div>
              </li>

              <li>
                <label class="filter-label">Search:</label>
                <div class="input-group pill-wrap">
                  <span class="input-group-text"><i class="bi bi-search"></i></span>
                  <input type="text" name="name" id="name" value="{{ request('name') }}"
                         class="form-control" placeholder="Enter product name">
                </div>
              </li>

              <li class="filter-actions">
                <button type="submit" class="btn btn-apply"><i class="bi bi-funnel"></i>Apply</button>
                <button type="button" id="resetBtn" class="btn btn-reset"><i class="bi bi-arrow-counterclockwise"></i>Reset</button>
                <a href="{{ route('super-admin.products.create') }}" class="btn btn-add"><i class="bi bi-plus-circle"></i>Add Product</a>
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

  $(document).on('change', '.status-toggle', function(){
    const checkbox = $(this);
    const id = checkbox.data('id');
    const status = checkbox.is(':checked') ? 1 : 0;
    const url = '{{ route('super-admin.products.toggle-status', ':id') }}'.replace(':id', id);
    $.ajax({
      url: url,
      type: 'PATCH',
      data: { status: status, _token: '{{ csrf_token() }}' },
      success(res){
        toastr.success(res.message);
      },
      error(){
        toastr.error('Unable to update status');
        checkbox.prop('checked', !checkbox.prop('checked'));
      }
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
