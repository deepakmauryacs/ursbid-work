@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Sub Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Sub Category</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.sub-categories.index') }}">Sub Category</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- POST-only (no method spoofing) --}}
                    <form id="subCategoryForm" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        {{-- @method('PUT')  // REMOVED because route doesn't accept PUT --}}

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $sub->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required id="categorySelect">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        data-slug="{{ $cat->slug ?? '' }}"
                                        {{ $sub->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name ?? $cat->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- URL Field -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="baseUrl">{{ url('/') }}/</span>
                                <input type="text" class="form-control" id="categorySlug" value="{{ $categorySlug }}" disabled>
                                <span class="input-group-text">/</span>
                                <input type="text" name="slug" class="form-control" id="subcategorySlug"
                                       value="{{ $sub->slug }}" placeholder="subcategory-slug" required>
                            </div>
                            <small class="form-text text-muted">
                                Full URL:
                                <a href="#" id="fullUrlLink" target="_blank" class="text-primary text-decoration-none">
                                    <span id="fullUrlPreview">{{ url('/') }}/{{ $categorySlug }}/{{ $sub->slug }}</span>
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                                <span class="text-muted ms-1">(Click to open in new tab)</span>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="3" required>{{ $sub->description }}</textarea>
                        </div>

                        <!-- Tags Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tags"
                                   class="form-control"
                                   data-role="tagsinput"
                                   value="{{ implode(',', $sub->tags ? json_decode($sub->tags) : []) }}"
                                   placeholder="Add tags" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order By</label>
                            <input type="number" name="order_by" class="form-control" value="{{ $sub->order_by }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $sub->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $sub->status == '2' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            @if (!empty($sub->image))
                                <div class="mb-2">
                                    <img src="{{ asset('public/' . $sub->image) }}" alt="Image" height="80" style="border:1px solid #ccc;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ $sub->meta_title }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ $sub->meta_keywords }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ $sub->meta_description }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="saveBtn" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
.bootstrap-tagsinput{width:100%;padding:6px 12px;line-height:22px;background:#fff;border:1px solid #ccc;border-radius:6px}
.bootstrap-tagsinput .tag{background:#614ce1;padding:5px 8px;border-radius:4px;margin-right:4px;color:#fff;font-size:13px}
#fullUrlPreview{color:#614ce1;font-weight:500}
#fullUrlLink:hover{text-decoration:underline!important}
.dark-theme .bootstrap-tagsinput{background-color:#1e1e1e;border:1px solid #444;color:#ddd}
.dark-theme .bootstrap-tagsinput input{color:#ddd}
.dark-theme .bootstrap-tagsinput .tag{background:#8a73ff;color:#fff}
@media (prefers-color-scheme: dark){
  .bootstrap-tagsinput{background-color:#1e1e1e;border:1px solid #444;color:#ddd}
  .bootstrap-tagsinput input{color:#ddd}
  .bootstrap-tagsinput .tag{background:#8a73ff;color:#fff}
}
</style>
@endpush

@push('scripts')
{{-- CKEditor --}}
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>
<script>
CKEDITOR.replace('description', {
    height: 300,
    contentsCss: [
        'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap',
        CKEDITOR.basePath + 'contents.css'
    ],
    bodyClass: 'dm-sans-body',
    font_names: 'DM Sans/DM Sans;' + CKEDITOR.config.font_names,
    font_defaultLabel: 'DM Sans',
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
// ---- Debug helper: Log FormData cleanly ----
function logFormData(fd){
    const obj = {};
    fd.forEach((v,k)=>{
        if (v instanceof File) {
            obj[k] = { _file: true, name: v.name, size: v.size, type: v.type };
        } else {
            obj[k] = v;
        }
    });
    console.log('[DEBUG] FormData payload:', obj);
}

$(function () {
    // ===== URL Preview =====
    function updateUrlPreview() {
        const baseUrl = $('#baseUrl').text();
        const categorySlug = $('#categorySlug').val() || '';
        const subcategorySlug = $('#subcategorySlug').val() || '';
        const fullUrl = `${baseUrl}${categorySlug}/${subcategorySlug}`;
        $('#fullUrlPreview').text(fullUrl);
        $('#fullUrlLink').attr('href', fullUrl);
    }
    updateUrlPreview();

    $('#categorySelect').on('change', function () {
        const categorySlug = $(this).find('option:selected').data('slug') || '';
        $('#categorySlug').val(categorySlug);
        updateUrlPreview();
    });

    $('#subcategorySlug').on('input', updateUrlPreview);

    $('input[name="name"]').on('input', function () {
        if (!$('#subcategorySlug').val()) {
            const generatedSlug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
            $('#subcategorySlug').val(generatedSlug);
            updateUrlPreview();
        }
    });

    // ===== jQuery Validate: custom slug rule =====
    $.validator.addMethod('slug', function (value, element) {
        return this.optional(element) || /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(value);
    }, 'Slug can only contain lowercase letters, numbers, and hyphens. No spaces or special characters allowed.');

    let submitting = false;

    // Global AJAX header for CSRF (needs <meta name="csrf-token"> in layout head)
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#subCategoryForm').validate({
        ignore: [], // include hidden fields (CKEditor sync)
        rules: {
            name: { required: true },
            category_id: { required: true },
            slug: { required: true, slug: true },
            description: { required: true },
            status: { required: true }
        },
        submitHandler: function (form) {
            if (submitting) return false;
            submitting = true;

            // Sync CKEditor back to textarea
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#saveBtn').prop('disabled', true).text('Updating...');

            const fd = new FormData(form);

            // Ensure CSRF present (redundant but harmless)
            if (!fd.has('_token')) {
                const metaToken = $('meta[name="csrf-token"]').attr('content');
                if (metaToken) fd.append('_token', metaToken);
            }

            // DO NOT append _method=PUT (route is POST-only)

            // DEBUG: print payload BEFORE sending
            logFormData(fd);

            $.ajax({
                url: "{{ route('super-admin.sub-categories.update', $sub->id) }}",
                type: 'POST',             // POST-only route
                data: fd,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function(xhr){
                    console.log('[DEBUG] Sending AJAX (POST) to:', "{{ route('super-admin.sub-categories.update', $sub->id) }}");
                    console.log('[DEBUG] X-CSRF-TOKEN:', $('meta[name="csrf-token"]').attr('content'));
                }
            })
            .done(function (res) {
                console.log('[DEBUG] Success response:', res);
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.message || 'Updated successfully');
                }
                window.location.href = "{{ route('super-admin.sub-categories.index') }}";
            })
            .fail(function (xhr) {
                console.error('[DEBUG] Fail status:', xhr.status);
                console.error('[DEBUG] Fail responseText:', xhr.responseText);
                let err = 'Error updating data';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    err = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    err = 'Session expired or CSRF token mismatch. Please refresh the page and try again.';
                } else if (xhr.status === 405) {
                    err = 'Method not allowed for this URL. (This page is using POST; ensure your route expects POST.)';
                }
                if (typeof toastr !== 'undefined') toastr.error(err, 'Error'); else alert(err);
            })
            .always(function () {
                submitting = false;
                $('#saveBtn').prop('disabled', false).text('Update');
            });

            return false; // prevent normal submit
        }
    });
});
</script>
@endpush
