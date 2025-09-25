@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Product</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.products.index') }}">Product List</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="productForm" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $product->title }}" required>
                        </div>

                        {{-- Category --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="cat_id" id="cat_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option
                                        value="{{ $cat->id }}"
                                        data-slug="{{ $cat->slug ?? '' }}"
                                        {{ $product->cat_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sub Category --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sub Category <span class="text-danger">*</span></label>
                            <select name="sub_id" id="sub_id" class="form-control" required>
                                <option value="">Select Sub Category</option>
                                @foreach($subCategories as $sub)
                                    <option
                                        value="{{ $sub->id }}"
                                        data-slug="{{ $sub->slug ?? '' }}"
                                        {{ $product->sub_id == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- URL Preview (base_url / category_slug / sub_category_slug / slug) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="baseUrl">{{ url('/') }}/</span>
                                <input type="text" class="form-control" id="categorySlug" value="" disabled>
                                <span class="input-group-text">/</span>
                                <input type="text" class="form-control" id="subCategorySlug" value="" disabled>
                                <span class="input-group-text">/</span>
                                <input type="text" name="slug" class="form-control" id="productSlug"
                                       value="{{ $product->slug ?? '' }}" placeholder="product-slug" required>
                            </div>
                            <small class="form-text text-muted">
                                Full URL:
                                <a href="#" id="fullUrlLink" target="_blank" class="text-primary text-decoration-none">
                                    <span id="fullUrlPreview"></span>
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                                <span class="text-muted ms-1">(Click to open in new tab)</span>
                            </small>
                        </div>

                        {{-- Order --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order By</label>
                            <input type="number" name="order_by" class="form-control" value="{{ $product->order_by }}">
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Image --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if($product->image)
                                <img src="{{ asset('public/' . ltrim($product->image, '/')) }}" alt="Image" class="img-thumbnail mt-2" style="max-width:150px;">
                            @endif
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="6">{{ $product->description }}</textarea>
                        </div>

                        {{-- Meta --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ $product->meta_title }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ $product->meta_keywords }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ $product->meta_description }}</textarea>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
  #fullUrlPreview{color:#614ce1;font-weight:500}
  #fullUrlLink:hover{text-decoration:underline!important}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

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
(function(){
  // ---- Helpers ----
  function sluggify(str) {
      return (str || '')
        .toString()
        .normalize('NFKD') // normalize accented characters
        .replace(/[\u0300-\u036f]/g, '') // remove diacritics
        .toLowerCase()
    } 


  function updateUrlPreview(){
    var baseUrl = $('#baseUrl').text() || ('{{ url('/') }}/');
    var c = $('#categorySlug').val() || '';
    var s = $('#subCategorySlug').val() || '';
    var p = $('#productSlug').val() || '';
    var full = baseUrl + [c, s, p].filter(Boolean).join('/');
    $('#fullUrlPreview').text(full);
    $('#fullUrlLink').attr('href', full);
  }

  function setSelectedSlugsFromDropdowns(){
    var catSlug = $('#cat_id').find('option:selected').data('slug') || '';
    var subSlug = $('#sub_id').find('option:selected').data('slug') || '';
    $('#categorySlug').val(catSlug || '');
    $('#subCategorySlug').val(subSlug || '');
    updateUrlPreview();
  }

  $(document).ready(function(){
    setSelectedSlugsFromDropdowns();

    // If productSlug empty, derive from title when typing
    $('#title').on('input', function(){
      var slugInput = $('#productSlug');
      if(!slugInput.val()){
        slugInput.val(sluggify($(this).val()));
        updateUrlPreview();
      }
    });

    // Manual slug typing – keep it clean
    $('#productSlug').on('input', function(){
      var cleaned = sluggify($(this).val());
      if($(this).val() !== cleaned) $(this).val(cleaned);
      updateUrlPreview();
    });

    // Category change -> load subs & reset sub slug
    $('#cat_id').on('change', function(){
      var $sub = $('#sub_id');
      $sub.html('<option value="">Select Sub Category</option>');
      $('#subCategorySlug').val('');
      var catSlug = $(this).find('option:selected').data('slug') || '';
      $('#categorySlug').val(catSlug);
      updateUrlPreview();

      var cat_id = $(this).val();
      if(cat_id){
        $.get('{{ route('super-admin.products.get-subcategories') }}', {cat_id: cat_id}, function(res){
          // Expecting res = [{id, name, slug?}, ...]
          $.each(res, function(i, item){
            var s = item.slug ? item.slug : sluggify(item.name);
            $sub.append('<option value="'+item.id+'" data-slug="'+s+'">'+item.name+'</option>');
          });
        });
      }
    });

    // Subcategory select -> set sub slug
    $('#sub_id').on('change', function(){
      var subSlug = $(this).find('option:selected').data('slug') || '';
      $('#subCategorySlug').val(subSlug);
      updateUrlPreview();
    });

    updateUrlPreview();
  });
})();
</script>

<script>
$(function(){
    // jQuery Validate: slug rule (allow hyphen & underscore)
    $.validator.addMethod('slug', function (value, element) {
        return this.optional(element) || /^[a-z0-9]+(?:[-_][a-z0-9]+)*$/.test(value);
    }, 'Slug can only contain lowercase letters, numbers, hyphens or underscores.');

    $('#productForm').validate({
        ignore: [],
        rules:{
            title:{ required:true },
            cat_id:{ required:true },
            sub_id:{ required:true },
            slug:{ required:true, slug:true }
        },
        submitHandler:function(form){
            // Sync CKEditor before submit
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#saveBtn').attr('disabled', true).text('Updating...');
            $.ajax({
                url: '{{ route('super-admin.products.update', $product->id) }}',
                type: 'POST', // keep POST-only if your route expects POST
                data: new FormData(form),
                processData:false,
                contentType:false,
                success: function(res){
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message || 'Updated successfully');
                    }
                    $('#saveBtn').attr('disabled', false).text('Update');
                },
                error: function(xhr){
                    let err = 'Error updating data';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        err = xhr.responseJSON.message;
                    }
                    if (typeof toastr !== 'undefined') toastr.error(err, 'Error'); else alert(err);
                    $('#saveBtn').attr('disabled', false).text('Update');
                }
            });
        }
    });
});
</script>
@endpush
