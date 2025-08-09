@extends('ursbid-admin.layouts.app')
@section('title', 'Sub Categories')

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

  <!-- Filters -->
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <form id="filterForm" action="{{ route('super-admin.sub-categories.index') }}" method="GET">
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" id="category" class="form-select">
                  <option value="">Select Category</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Sub Category Name</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="Sub Category Name">
              </div>
              <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="text" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="dd-mm-yyyy">
              </div>
              <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="text" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="dd-mm-yyyy">
              </div>
              <div class="col-md-1">
                <label class="form-label">Per Page</label>
                <select id="perPage" class="form-select">
                  @php $pp = request('per_page', $perPage ?? 10); @endphp
                  @foreach([10,25,50,100] as $n)
                    <option value="{{ $n }}" {{ (int)$pp === $n ? 'selected' : '' }}>{{ $n }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-1 d-flex align-items-end justify-content-end gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
              </div>
              <div class="col-12 text-end">
                <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                @if(auth()->user()->hasModulePermission('sub-categories','can_add'))
                <a href="{{ route('super-admin.sub-categories.create') }}" class="btn btn-success ms-2">Add Sub Category</a>
                @endif
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
<script>
(function(){
  $(document).on('click', '.deleteBtn', function(){
    if(!confirm('Are you sure want to delete?')) return;
    let url = $(this).data('url');
    $.ajax({
      url: url, type: 'DELETE',
      data: { _token: '{{ csrf_token() }}' },
      success: function(res){ toastr.success(res.message); $('#filterForm').trigger('submit'); },
      error: function(){ toastr.error('Unable to delete record'); }
    });
  });

  $(document).on('submit', '#filterForm', function(e){
    e.preventDefault();
    const name = $('#name').val();
    const from = $('#from_date').val();
    const to = $('#to_date').val();
    const datePattern = /^\d{2}-\d{2}-\d{4}$/;

    if(name.length > 255){
      toastr.error('Sub category name may not be greater than 255 characters.');
      return;
    }
    if(from && !datePattern.test(from)){
      toastr.error('From date must be in dd-mm-yyyy format.');
      return;
    }
    if(to && !datePattern.test(to)){
      toastr.error('To date must be in dd-mm-yyyy format.');
      return;
    }

    const url = $(this).attr('action') + '?' + $(this).serialize() + '&per_page=' + $('#perPage').val();
    loadTable(url);
  });

  $(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    loadTable($(this).attr('href'));
  });

  $(document).on('change', '#perPage', function(){
    const form = $('#filterForm');
    const url = form.attr('action') + '?' + form.serialize() + '&per_page=' + $(this).val();
    loadTable(url);
  });

  $('#resetBtn').on('click', function(){
    $('#filterForm')[0].reset();
    const url = $('#filterForm').attr('action') + '?per_page=' + $('#perPage').val();
    loadTable(url);
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
