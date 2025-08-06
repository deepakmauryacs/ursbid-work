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
                <h4 class="mb-0 fw-semibold">Sub Categories</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sub Categories</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Sub Categories List</h4>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#subCategoryModal">Add Sub Category</button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Sub Category Name</th>
                                <th>Category</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subs as $sub)
                            <tr id="row-{{ $sub->id }}">
                                <td>{{ $subs->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <img src="{{ $sub->image ? asset('public/'.$sub->image) : asset('public/uploads/no-image.jpg') }}" alt="{{ $sub->name }}" class="avatar-md rounded border border-light border-3" style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <a href="#!" class="text-dark fw-medium fs-15">{{ $sub->name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $sub->category_name }}</td>
                                <td>{{ $sub->created_at ? \Carbon\Carbon::parse($sub->created_at)->format('d-m-Y') : '' }}</td>
                                <td>
                                    @if($sub->status == '1')
                                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.sub-categories.edit', $sub->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button" data-id="{{ $sub->id }}" data-url="{{ route('super-admin.sub-categories.destroy', $sub->id) }}" class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No sub categories found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-paginationwithlength :paginator="$subs" />
                
            </div>
        </div>
    </div>
</div>

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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('.deleteBtn').on('click', function(){
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
                    location.reload();
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
});
</script>
@endpush
