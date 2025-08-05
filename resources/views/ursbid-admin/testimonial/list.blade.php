@extends('ursbid-admin.layouts.app')
@section('title', 'Testimonials')
@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Testimonials</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Testimonials</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h4 class="card-title mb-0">All Testimonials</h4>
                    </div>
                    <a href="{{ route('super-admin.testimonials.create') }}" class="btn btn-sm btn-primary">Add Testimonial</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $item)
                            <tr id="row-{{ $item->id }}">
                                <td>{{ $testimonials->firstItem() + $loop->index }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->position }}</td>
                                <td>
                                    @if($item->status == 1)
                                        <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('super-admin.testimonials.edit', $item->id) }}" class="btn btn-soft-primary btn-sm">
                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </a>
                                        <button type="button" data-id="{{ $item->id }}" data-url="{{ route('super-admin.testimonials.destroy', $item->id) }}" class="btn btn-soft-danger btn-sm deleteBtn">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No testimonials found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-paginationwithlength :paginator="$testimonials" />
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
});
</script>
@endpush
