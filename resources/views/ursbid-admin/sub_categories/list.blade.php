@extends('ursbid-admin.layouts.app')
@section('title', 'Sub Categories')
@section('content')

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
      <div class="card mb-4 shadow-sm">
        <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
          <h5 class="mb-0">Filter Sub Categories</h5>
          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('super-admin.sub-categories.create') }}" class="btn btn-success btn-sm">
              <i class="bi bi-plus-circle me-1"></i>Add Sub Category
            </a>
            <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#subCategoryFilters" aria-expanded="true" aria-controls="subCategoryFilters">
              Toggle Filters
            </button>
          </div>
        </div>

        <div class="collapse show" id="subCategoryFilters">
          <div class="card-body">
            <form id="filterForm" action="{{ route('super-admin.sub-categories.index') }}" method="GET" class="row g-3 align-items-end">

              <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <label class="form-label">Category (optional)</label>
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
                <label class="form-label">Search (optional)</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="Enter sub category">
              </div>

              <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <label class="form-label d-none d-lg-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                  <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                </button>
              </div>

              <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <label class="form-label d-none d-lg-block">&nbsp;</label>
                <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100">
                  <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                </button>
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

  $(document).on('change', '.status-toggle', function(){
    const checkbox = $(this);
    const id = checkbox.data('id');
    const status = checkbox.is(':checked') ? 1 : 2;
    const url = '{{ route('super-admin.sub-categories.toggle-status', ':id') }}'.replace(':id', id);

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
