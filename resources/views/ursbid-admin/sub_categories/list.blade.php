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
    color: #333;
    text-decoration: none;
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
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sub Category Name</label>
                                <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="Sub Category Name">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Per Page</label>
                                <select id="perPage" class="form-select">
                                    @php $pp = request('per_page', $perPage ?? 10); @endphp
                                    @foreach([10,25,50,100] as $n)
                                        <option value="{{ $n }}" {{ (int)$pp === $n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                                @if(auth()->user()->hasModulePermission('sub-categories','can_add'))
                                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#subCategoryModal">Add Sub Category</button>
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

@if(auth()->user()->hasModulePermission('sub-categories','can_add'))
<!-- Modal -->
<div class="modal fade" id="subCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Sub Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="subCategoryForm" enctype="multipart/form-data">
          @csrf
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Name<span class="text-danger">*</span></label></div>
            <div class="col-md-8"><input type="text" name="name" class="form-control" required></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Category<span class="text-danger">*</span></label></div>
            <div class="col-md-8">
              <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Description<span class="text-danger">*</span></label></div>
            <div class="col-md-8"><textarea name="description" class="form-control" rows="3" required></textarea></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Tags</label></div>
            <div class="col-md-8"><input type="text" name="tags" class="form-control" placeholder="tag1, tag2"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Order By</label></div>
            <div class="col-md-8"><input type="number" name="order_by" class="form-control"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Status</label></div>
            <div class="col-md-8">
              <select name="status" class="form-control">
                <option value="1">Active</option>
                <option value="2">Inactive</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Image</label></div>
            <div class="col-md-8"><input type="file" name="image" class="form-control"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Meta Title</label></div>
            <div class="col-md-8"><input type="text" name="meta_title" class="form-control"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Meta Keywords</label></div>
            <div class="col-md-8"><input type="text" name="meta_keywords" class="form-control"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Meta Description</label></div>
            <div class="col-md-8"><textarea name="meta_description" class="form-control" rows="3"></textarea></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="subCategoryForm" id="saveSubBtn" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
(function(){
    // Delete
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
                $('#filterForm').trigger('submit');
            },
            error: function(){
                toastr.error('Unable to delete record');
            }
        });
    });

    // Filter submit
    $(document).on('submit', '#filterForm', function(e){
        e.preventDefault();
        const url = $(this).attr('action') + '?' + $(this).serialize() + '&per_page=' + $('#perPage').val();
        loadTable(url);
    });

    // Pagination click
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        loadTable($(this).attr('href'));
    });

    // per page change
    $(document).on('change', '#perPage', function(){
        const form = $('#filterForm');
        const url = form.attr('action') + '?' + form.serialize() + '&per_page=' + $(this).val();
        loadTable(url);
    });

    // Reset
    $('#resetBtn').on('click', function(){
        $('#filterForm')[0].reset();
        const url = $('#filterForm').attr('action') + '?per_page=' + $('#perPage').val();
        loadTable(url);
    });

    @if(auth()->user()->hasModulePermission('sub-categories','can_add'))
    $('#subCategoryForm').validate({
        rules:{
            name:{ required:true },
            category_id:{ required:true },
            description:{ required:true },
            status:{ required:true }
        },
        submitHandler:function(form){
            $('#saveSubBtn').prop('disabled',true).text('Saving...');
            $.ajax({
                url: "{{ route('super-admin.sub-categories.store') }}",
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(res){
                    toastr.success(res.message);
                    $('#saveSubBtn').prop('disabled',false).text('Save');
                    $('#subCategoryModal').modal('hide');
                    $('#filterForm').trigger('submit');
                },
                error: function(xhr){
                    let err = 'Error saving data';
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
            url: url,
            type: 'GET',
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
