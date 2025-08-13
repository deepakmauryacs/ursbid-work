@extends('ursbid-admin.layouts.app')
@section('title', 'Blogs')

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

/* Modal Styling */
.modal-content {
    border-radius: 12px;
}
.modal-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}
.btn-light, .btn-danger {
    border-radius: 6px;
}
</style>

<div class="container-fluid">
    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Blogs</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blogs</li>
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
                        <h4 class="card-title mb-0">All Blogs List</h4>
                    </div>
                    <a href="{{ route('super-admin.blogs.create') }}" class="btn btn-sm btn-primary">Add Blog</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Blog Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                            <tr id="row-{{ $blog->id }}">
                                <td>{{ ($blogs->currentPage() - 1) * $blogs->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <img src="{{ $blog->image ? asset('public/'.$blog->image) : asset('public/uploads/no-image.jpg') }}" alt="{{ $blog->title }}" class="avatar-md rounded border border-light border-3" style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <a href="#!" class="text-dark fw-medium fs-15">{{ $blog->title }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" data-id="{{ $blog->id }}" {{ $blog->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.blogs.edit', $blog->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button"
                                                data-id="{{ $blog->id }}"
                                                data-url="{{ route('super-admin.blogs.destroy', $blog->id) }}"
                                                class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No blogs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-paginationwithlength :paginator="$blogs" />
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

    $('.status-toggle').on('change', function(){
        const checkbox = $(this);
        const id = checkbox.data('id');
        const status = checkbox.is(':checked') ? 1 : 0;
        const url = '{{ route('super-admin.blogs.toggle-status', ':id') }}'.replace(':id', id);

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
