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

/* ===== Filter bar ===== */
.filter-wrap{
    background:#fff;
    border:1px solid #e9edf3;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(28,39,60,.06);
}
.filter-grid{row-gap:12px}
@media(min-width:992px){
  .filter-grid>[class*="col-"]{display:flex;align-items:flex-end}
} 
.filter-label{
  font-size:12px;
  color:#6b7280;
  margin-bottom:6px;
}

/* unified bold border around icon + field */
.input-group.pill-wrap{
  border: 1px solid #000;
  border-radius: 12px;
  overflow: hidden;
  background:#fff;
}
.input-group.pill-wrap .input-group-text{
  background:#fff;
  border:0;
}
.input-group.pill-wrap .form-control,
.input-group.pill-wrap .form-select{
  border:0 !important;
  height:42px;
  box-shadow:none;
}
.input-group.pill-wrap:focus-within{
  border-color:#000;
  box-shadow:0 0 0 3px rgba(0,0,0,.06);
}

/* action buttons area */
.btn-icon i{font-size:16px;margin-right:6px}
.actions-bar{gap:8px}
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
      <div class="card filter-wrap">
        <div class="card-body py-3">
          <form id="filterForm" action="{{ route('super-admin.sub-categories.index') }}" method="GET">
            <div class="row g-3 filter-grid align-items-end">

              <!-- Category -->
              <div class="col-xxl-3 col-lg-4 col-md-6">
                <div class="w-100">
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
                </div>
              </div>

              <!-- Search -->
              <div class="col-xxl-4 col-lg-5 col-md-6">
                <div class="w-100">
                  <label class="filter-label">Search (optional):</label>
                  <div class="input-group pill-wrap">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="name" id="name" value="{{ request('name') }}"
                           class="form-control" placeholder="Enter sub category">
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="col-xxl-5 col-lg-3 col-md-12">
                <div class="d-flex justify-content-xxl-end justify-content-lg-end justify-content-start actions-bar flex-wrap w-100">
                  <button type="submit" class="btn btn-primary btn-icon">
                    <i class="bi bi-funnel"></i>Apply
                  </button>
                  <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-icon">
                    <i class="bi bi-arrow-counterclockwise"></i>Reset
                  </button>
                  <a href="{{ route('super-admin.categories.create') }}" class="btn btn-success btn-icon">
                    <i class="bi bi-plus-circle"></i>Add Sub Category
                  </a>
                </div>
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
            @include('ursbid-admin.sub_categories.partials.table', ['subs' => $subs])
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
(function(){
  // Delete
  $(document).on('click', '.deleteBtn', function(){
    if(!confirm('Are you sure want to delete?')) return;
    let url = $(this).data('url');
    $.ajax({
      url, type:'DELETE',
      data:{ _token:'{{ csrf_token() }}' },
      success(res){ toastr.success(res.message); loadTable(window.location.href.split('#')[0]); },
      error(){ toastr.error('Unable to delete record'); }
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
