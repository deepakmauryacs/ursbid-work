@extends('ursbid-admin.layouts.app')
@section('title', 'Categories')

@section('content')
<div class="container-fluid">

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Categories</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
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
                        <h4 class="card-title mb-0">All Categories List</h4>
                    </div>
                     @if(auth()->user()->hasModulePermission('categories','can_add'))
                     <a href="{{ route('super-admin.categories.create') }}" class="btn  btn-sm btn-primary">Add Category</a>
                     @endif
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Category Name</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr id="row-{{ $category->id }}">
                                <td>{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                           <img src="{{ $category->image ? asset('public/'.$category->image) : asset('assets/images/default-category.png') }}" alt="{{ $category->name }}"  class="avatar-md rounded border border-light border-3" style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <a href="#!" class="text-dark fw-medium fs-15">{{ $category->name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $category->created_at ? \Carbon\Carbon::parse($category->created_at)->format('d-m-Y') : '' }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" data-id="{{ $category->id }}" {{ $category->status == '1' ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if(auth()->user()->hasModulePermission('categories','can_edit'))
                                        <a href="{{ route('super-admin.categories.edit', $category->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        @endif
                                        @if(auth()->user()->hasModulePermission('categories','can_delete'))
                                        <button type="button" data-id="{{ $category->id }}" data-url="{{ route('super-admin.categories.destroy', $category->id) }}" class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No categories found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $categories->links() }}
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
<script>
let deleteId = null;
let deleteUrl = null;
$(function(){
    $('.deleteBtn').on('click', function(){
        deleteId = $(this).data('id');
        deleteUrl = $(this).data('url');
        $('#deleteConfirmModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function(){
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res){
                $('#deleteConfirmModal').modal('hide');
                toastr.success(res.message);
                $('#row-'+deleteId).remove();
            },
            error: function(){
                $('#deleteConfirmModal').modal('hide');
                toastr.error('Unable to delete record');
            }
        });
    });

    $(document).on('change', '.status-toggle', function(){
        const checkbox = $(this);
        const id = checkbox.data('id');
        const status = checkbox.is(':checked') ? 1 : 2;
        const url = '{{ route('super-admin.categories.toggle-status', ':id') }}'.replace(':id', id);

        $.ajax({
            url: url,
            type: 'PATCH',
            data: { status: status, _token: '{{ csrf_token() }}' },
            success: function(res){
                toastr.success(res.message);
            },
            error: function(){
                toastr.error('Unable to update status');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });
});
</script>
@endpush
