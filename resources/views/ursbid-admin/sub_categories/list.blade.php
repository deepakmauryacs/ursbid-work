@extends('ursbid-admin.layouts.app')
@section('title', 'Sub Categories')

@section('content')

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
/* ===== pagination (unchanged) ===== */
.pagination-container{display:flex;justify-content:center;align-items:center;margin-top:20px}
.pagination{display:flex;list-style:none;padding:0;gap:5px}
.page-item{margin:0}
.page-link{display:inline-block;padding:5px 10px;color:#333;text-decoration:none;border:1px solid #ddd;border-radius:4px;background-color:#fff}
.page-item.active .page-link{background-color:#614ce1;color:#fff;border-color:#614ce1}
.page-item.disabled .page-link{color:#6c757d;pointer-events:none;background-color:#fff;border-color:#ddd}
.page-link:hover{background-color:#f8f9fa;color:#0056b3}

/* ===== Theme Variables ===== */
:root{
  --filter-bg:#ffffff;
  --filter-border:#000000;         
  --filter-label:#6b7280;
  --filter-field-bg:#ffffff;
  --filter-shadow:0 2px 10px rgba(28,39,60,.06);
  --filter-placeholder:#6b7280;
}

/* Dark theme override */
[data-bs-theme="dark"], .dark, [data-theme="dark"], body.dark {
  --filter-bg:#0f172a;             
  --filter-border:#9aa4b2;         
  --filter-label:#cbd5e1;          
  --filter-field-bg:#0b1320;       
  --filter-shadow:0 2px 10px rgba(0,0,0,.45);
  --filter-placeholder:#94a3b8;    
}

/* System dark preference */
@media (prefers-color-scheme: dark) {
  :root:not([data-bs-theme]):not(.dark):not([data-theme="dark"]):not(body.dark){
    --filter-bg:#0f172a;
    --filter-border:#9aa4b2;
    --filter-label:#cbd5e1;
    --filter-field-bg:#0b1320;
    --filter-shadow:0 2px 10px rgba(0,0,0,.45);
    --filter-placeholder:#94a3b8;
  }
}

/* ===== Filter (UL/LI) ===== */
.filter-card{
  background:var(--filter-bg);
  border:1px solid var(--filter-border);
  border-radius:14px;
  box-shadow:var(--filter-shadow);
}
.filter-ul{
  list-style:none;
  margin:0;
  padding:0;
  display:flex;
  flex-wrap:wrap;
  gap:10px;
}
.filter-ul li{
  flex:1 1 auto;
  min-width:220px;
}
.filter-ul li.actions-li{
  flex:0 0 auto;
  display:flex;
  align-items:flex-end;
  gap:8px;
}
.filter-label{
  font-size:12px;
  color:var(--filter-label);
  margin-bottom:6px;
  display:block;
}

/* Unified bold border around icon + field */
.input-group.pill-wrap{
  border:1px solid var(--filter-border);
  border-radius:12px;
  overflow:hidden;
  background:var(--filter-field-bg);
}
.input-group.pill-wrap .input-group-text{
  background:var(--filter-field-bg);
  border:0;
}
.input-group.pill-wrap .form-control,
.input-group.pill-wrap .form-select{
  border:0 !important;
  height:42px;
  box-shadow:none;
  background:var(--filter-field-bg);
  color:inherit;
}
.input-group.pill-wrap .form-control::placeholder{color:var(--filter-placeholder)}
.input-group.pill-wrap:focus-within{
  border-color:var(--filter-border);
  box-shadow:0 0 0 3px rgba(148,163,184,.25);
}

/* Buttons */
.btn-icon i{font-size:16px;margin-right:6px}

@media(max-width:768px){
  .filter-ul li{min-width:100%}
  .filter-ul li.actions-li{flex-wrap:wrap}
}
</style>

<div class="container-fluid">
  <!-- Page Title -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <h4 class="mb-0 fw-semibold">Sub Categories</h4>
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Sub Categories</li>
        </ol>
      </div>
    </div>
  </div>

  <!-- FILTERS -->
  <div class="row mb-3">
    <div class="col-12">
      <div class="card filter-card border-0">
        <div class="card-body py-3">
          <form id="filterForm" action="{{ route('super-admin.sub-categories.index') }}" method="GET">
            <ul class="filter-ul">

              <li>
                <label class="filter-label">Category (optional):</label>
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
                <label class="filter-label">Search (optional):</label>
                <div class="input-group pill-wrap">
                  <span class="input-group-text"><i class="bi bi-search"></i></span>
                  <input type="text" name="name" id="name" value="{{ request('name') }}"
                         class="form-control" placeholder="Enter sub category">
                </div>
              </li>

              <li class="actions-li">
                <button type="submit" class="btn btn-primary btn-icon">
                  <i class="bi bi-funnel"></i>Apply
                </button>
                <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-icon">
                  <i class="bi bi-arrow-counterclockwise"></i>Reset
                </button>
                <a href="{{ route('super-admin.sub-categories.create') }}" class="btn btn-success btn-icon">
                  <i class="bi bi-plus-circle"></i>Add Sub Category
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
            @include('ursbid-admin.sub_categories.partials.table', ['subs' => $subs])
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white rounded-top">
        <h5 class="modal-title" id="deleteConfirmLabel">
          <i class="ri-error-warning-line me-2"></i> Confirm Deletion
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="mb-3">
          <i class="ri-alert-line text-danger" style="font-size: 40px;"></i>
        </div>
        <p class="mb-1 fs-5 fw-semibold">Are you sure you want to delete this item?</p>
        <p class="text-muted">This action cannot be undone.</p>
      </div>
      <div class="modal-footer justify-content-center border-0">
        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
          <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
          <i class="ri-delete-bin-line me-1"></i> Delete
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
(function(){
  let deleteId = null;
  let deleteUrl = null;

  // Delete
  $(document).on('click', '.deleteBtn', function(){
    deleteId = $(this).data('id');
    deleteUrl = $(this).data('url');
    $('#deleteConfirmModal').modal('show');
  });

  $('#confirmDeleteBtn').on('click', function(){
    $.ajax({
      url: deleteUrl,
      type: 'DELETE',
      data: { _token: '{{ csrf_token() }}' },
      success(res){
        $('#deleteConfirmModal').modal('hide');
        toastr.success(res.message);
        $('#row-'+deleteId).remove();
      },
      error(){
        $('#deleteConfirmModal').modal('hide');
        toastr.error('Unable to delete record');
      }
    });
  });

  // Filter submit (AJAX)
  $(document).on('submit', '#filterForm', function(e){
    e.preventDefault();
    const url = $(this).attr('action') + '?' + $(this).serialize();
    loadTable(url);
  });

  // Pagination click
  $(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    loadTable($(this).attr('href'));
  });

  // Reset
  $('#resetBtn').on('click', function(){
    $('#filterForm')[0].reset();
    const url = $('#filterForm').attr('action');
    loadTable(url);
  });

  @if(auth()->user()->hasModulePermission('sub-categories','can_add'))
  // Add modal save
  $('#subCategoryForm').validate({
    rules:{ name:{required:true}, category_id:{required:true}, description:{required:true}, status:{required:true} },
    submitHandler:function(form){
      $('#saveSubBtn').prop('disabled',true).text('Saving...');
      $.ajax({
        url:"{{ route('super-admin.sub-categories.store') }}",
        type:'POST',
        data:new FormData(form), processData:false, contentType:false,
        success(res){
          toastr.success(res.message);
          $('#saveSubBtn').prop('disabled',false).text('Save');
          $('#subCategoryModal').modal('hide');
          loadTable(window.location.href.split('#')[0]);
        },
        error(xhr){
          let err='Error saving data';
          if(xhr.responseJSON && xhr.responseJSON.errors){
            err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
          }
          toastr.error(err);
          $('#saveSubBtn').prop('disabled',false).text('Save');
        }
      });
      return false;
    }
  });
  @endif

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
