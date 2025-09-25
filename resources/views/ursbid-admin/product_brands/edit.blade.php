@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Product Brand')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Product Brand</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.product-brands.index') }}">Product Brand</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- IMPORTANT: novalidate so our JS can show custom errors --}}
                    <form id="brandForm" enctype="multipart/form-data" autocomplete="off" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $brand->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block" id="err_category_id"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sub Category <span class="text-danger">*</span></label>
                            <select name="sub_category_id" class="form-control" required>
                                <option value="">Select Sub Category</option>
                                {{-- will be filled via AJAX --}}
                            </select>
                            <div class="invalid-feedback d-block" id="err_sub_category_id"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-control" required>
                                <option value="">Select Product</option>
                                {{-- will be filled via AJAX --}}
                            </select>
                            <div class="invalid-feedback d-block" id="err_product_id"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" name="brand_name" class="form-control" value="{{ $brand->brand_name }}" placeholder="Enter Brand Name" required>
                            <div class="invalid-feedback d-block" id="err_brand_name"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Brand Image/Logo</label>
                            <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                            <small class="text-muted d-block mt-1">Allowed: jpeg, png, jpg, gif, webp. Max 5 MB.</small>

                            {{-- Current image (if any) --}}
                            @php
                                $currentImg = $brand->image ?? null;
                                $currentSrc = $currentImg
                                    ? (\Illuminate\Support\Str::startsWith($currentImg, ['http://','https://']) ? $currentImg : asset('public/'.$currentImg))
                                    : '';
                            @endphp

                            @if($currentSrc)
                                <div class="mt-2">
                                    <span class="text-muted small d-block">Current:</span>
                                    <img src="{{ $currentSrc }}" alt="Current Image" style="max-height:80px;border:1px solid #eee;padding:4px;border-radius:6px;">
                                </div>
                            @endif

                            {{-- Live preview for newly selected file --}}
                            <img id="imagePreview" src="" alt="" style="display:none;max-height:80px;margin-top:10px;border:1px solid #eee;padding:4px;border-radius:6px;">
                            <div class="invalid-feedback d-block" id="err_image"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter Description">{{ $brand->description }}</textarea>
                            <div class="invalid-feedback d-block" id="err_description"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ $brand->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $brand->status == '2' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="invalid-feedback d-block" id="err_status"></div>
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

<script>
$(function(){
    // ===== Image Live Preview =====
    $('#image').on('change', function(e){
        const file = e.target.files && e.target.files[0];
        const $prev = $('#imagePreview');
        if(!file){ $prev.hide(); return; }
        $prev.attr('src', URL.createObjectURL(file)).show();
        clearFieldError('image');
    });

    // ===== Dependent dropdowns (Category -> SubCategory -> Product) =====
    const $cat  = $('[name="category_id"]');
    const $sub  = $('[name="sub_category_id"]');
    const $prod = $('[name="product_id"]');

    const preSelected = {
        category_id: '{{ $brand->category_id }}',
        sub_category_id: '{{ $brand->sub_category_id }}',
        product_id: '{{ $brand->product_id }}'
    };

    function resetSubcategory() {
        $sub.html('<option value="">Select Sub Category</option>').prop('disabled', true);
    }
    function resetProduct() {
        $prod.html('<option value="">Select Product</option>').prop('disabled', true);
    }

    // load subcategories for selected category (and select current)
    function loadSubcategories(categoryId, selectedId = null){
        if(!categoryId){ resetSubcategory(); return $.Deferred().resolve().promise(); }
        $sub.prop('disabled', true).html('<option value="">Loading...</option>');
        return $.getJSON('{{ route("ajax.subcategories") }}', { category_id: categoryId })
            .done(function(res){
                if(res.status === 'success'){
                    let opts = '<option value="">Select Sub Category</option>';
                    res.data.forEach(function(row){
                        const sel = (String(row.id) === String(selectedId)) ? 'selected' : '';
                        opts += `<option value="${row.id}" ${sel}>${row.name}</option>`;
                    });
                    $sub.html(opts).prop('disabled', false);
                } else {
                    toastr?.error(res.message || 'Failed to load sub-categories');
                    resetSubcategory();
                }
            })
            .fail(function(){ toastr?.error('Unable to load sub-categories'); resetSubcategory(); });
    }

    // load products for selected sub-category (and select current)
    function loadProducts(subId, selectedId = null){
        if(!subId){ resetProduct(); return; }
        $prod.prop('disabled', true).html('<option value="">Loading...</option>');
        $.getJSON('{{ route("ajax.products") }}', { sub_category_id: subId })
            .done(function(res){
                if(res.status === 'success'){
                    let opts = '<option value="">Select Product</option>';
                    res.data.forEach(function(row){
                        const sel = (String(row.id) === String(selectedId)) ? 'selected' : '';
                        opts += `<option value="${row.id}" ${sel}>${row.title}</option>`;
                    });
                    $prod.html(opts).prop('disabled', false);
                } else {
                    toastr?.error(res.message || 'Failed to load products');
                    resetProduct();
                }
            })
            .fail(function(){ toastr?.error('Unable to load products'); resetProduct(); });
    }

    // On Category change -> reload subs (and clear products)
    $cat.on('change', function(){
        const categoryId = $(this).val();
        resetSubcategory();
        resetProduct();
        clearFieldError('category_id');

        if(!categoryId) return;

        loadSubcategories(categoryId, null);
    });

    // On Sub-category change -> reload products
    $sub.on('change', function(){
        const subId = $(this).val();
        resetProduct();
        clearFieldError('sub_category_id');
        if(!subId) return;
        loadProducts(subId, null);
    });

    // ===== Pre-populate on first load with existing brand values =====
    (function initDependentSelects(){
        // If category already selected, load subs and pre-select
        const catId = preSelected.category_id || $cat.val();
        if(catId){
            loadSubcategories(catId, preSelected.sub_category_id).done(function(){
                // After subs loaded, load products for the selected sub
                if(preSelected.sub_category_id){
                    loadProducts(preSelected.sub_category_id, preSelected.product_id);
                }
            });
        } else {
            resetSubcategory();
            resetProduct();
        }
    })();

    // ===== Helpers (errors) =====
    function clearErrors(){
        $('[id^="err_"]').html('');
        $('.is-invalid').removeClass('is-invalid');
    }
    function clearFieldError(field){
        $('[name="'+field+'"]').removeClass('is-invalid');
        $('#err_'+field).html('');
    }
    function setErr(field, message){
        var fieldSelector = '[name="'+field+'"]';
        if ($(fieldSelector).length === 0 && field.indexOf('.') !== -1) {
            fieldSelector = '[name="'+field.split('.')[0]+'"]';
        }
        $(fieldSelector).addClass('is-invalid');

        var errId = '#err_' + field.replace(/\./g, '_');
        if ($(errId).length === 0) {
            errId = '#err_' + field.split('.')[0];
        }
        $(errId).html(message);
    }
    function hasAnyError(){
        return $('[id^="err_"]').filter(function(){ return $(this).text().trim().length > 0; }).length > 0;
    }
    function scrollToFirstError(){
        var $first = $('[id^="err_"]').filter(function(){ return $(this).text().trim().length > 0; }).first();
        if ($first.length){
            $('html, body').animate({scrollTop: $first.offset().top - 120}, 250);
        }
    }

    // Clear error when user edits a field
    $(document).on('input change', 'input, select, textarea', function(){
        clearFieldError($(this).attr('name'));
    });

    // ===== Submit (PUT with FormData) =====
    $('#brandForm').on('submit', function(e){
        e.preventDefault();

        if(window.CKEDITOR && CKEDITOR.instances){
            Object.values(CKEDITOR.instances).forEach(function(inst){ inst.updateElement(); });
        }

        clearErrors();

        // Client-side validation
        if(!$('[name="category_id"]').val()) setErr('category_id', 'Category is required.');
        if(!$('[name="sub_category_id"]').val()) setErr('sub_category_id', 'Sub category is required.');
        if(!$('[name="product_id"]').val()) setErr('product_id', 'Product is required.');

        var brandName = $('[name="brand_name"]').val() || '';
        if(brandName.trim().length === 0) setErr('brand_name', 'Brand name is required.');

        var descVal = $('#description').val() || '';
        if(descVal.trim().length === 0) setErr('description', 'Description is required.');

        if(!$('[name="status"]').val()) setErr('status', 'Status is required.');

        // New image checks (if user selected)
        var imgFile = ($('#image')[0].files || [])[0];
        if (imgFile) {
            var allowed = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
            var extOk = /\.(jpe?g|png|gif|webp)$/i.test(imgFile.name || '');
            if(!allowed.includes(imgFile.type) && !extOk) setErr('image', 'Only JPEG, PNG, GIF, WEBP are allowed.');
            if(imgFile.size > 5*1024*1024) setErr('image', 'Image size must be under 5 MB.');
        }

        if(hasAnyError()){
            scrollToFirstError();
            window.toastr ? toastr.error('Please fix the highlighted errors.') : alert('Please fix the highlighted errors.');
            return false;
        }

        var formData = new FormData(document.getElementById('brandForm')); // includes _method=PUT

        $('#saveBtn').prop('disabled', true).text('Updating...');
        $.ajax({
            url: '{{ route('super-admin.product-brands.update', $brand->id) }}',
            type: 'POST', // Laravel treats via _method
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                if (res && res.status === 'success') {
                    window.toastr ? toastr.success(res.message || 'Brand updated') : alert('Brand updated');
                    setTimeout(function() {
                        window.location.href = '{{ route('super-admin.product-brands.index') }}';
                    }, 1000); // 1000 ms = 1 second
                    return;
                }

                if (res && res.errors && typeof res.errors === 'object') {
                    Object.entries(res.errors).forEach(function([field, messages]){
                        setErr(field, Array.isArray(messages) ? messages.join('<br>') : String(messages));
                    });
                    scrollToFirstError();
                    window.toastr ? toastr.error(res.message || 'Please fix the highlighted errors.') : alert(res.message || 'Please fix the highlighted errors.');
                } else {
                    window.toastr ? toastr.error((res && res.message) || 'An error occurred') : alert('An error occurred');
                }
                $('#saveBtn').prop('disabled', false).text('Update');
            },
            error: function(xhr){
                if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    Object.entries(errors).forEach(function([field, messages]){
                        setErr(field, Array.isArray(messages) ? messages.join('<br>') : String(messages));
                    });
                    scrollToFirstError();
                    window.toastr ? toastr.error('Please fix the highlighted errors.') : alert('Please fix the highlighted errors.');
                } else {
                    var err = 'An error occurred';
                    if (xhr.status === 419) err = 'Session expired (419). Please refresh and try again.';
                    else if (xhr.status === 413) err = 'File too large (413). Please upload an image under 5 MB.';
                    else if (xhr.responseJSON && xhr.responseJSON.message) err = xhr.responseJSON.message;
                    window.toastr ? toastr.error(err) : alert(err);
                }
                $('#saveBtn').prop('disabled', false).text('Update');
            }
        });

        return false;
    });
});
</script>
@endpush
