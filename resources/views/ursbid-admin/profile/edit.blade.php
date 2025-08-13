@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    <!-- Left: Profile summary -->
                    <div class="col-md-4 p-4 d-flex flex-column justify-content-between border-end" style="background: var(--bs-tertiary-bg);">
                        <div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle overflow-hidden" style="border:1px solid var(--bs-border-color);">
                                    <img src="{{ $user->avatar_url ?? 'https://placehold.co/144x144?text=U' }}"
                                         alt="Avatar" class="w-100 h-100" style="object-fit:cover;">
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="small text-body-secondary user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="mt-4 small text-body-secondary">
                                Keep your details accurate. This info can be visible to other users.
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="col-md-8" style="background: var(--bs-body-bg);">
                        <form id="profileForm" class="p-4">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-2">Basic Information</label>
                                    <div class="card border-0 rounded-3 p-3" style="background: var(--bs-tertiary-bg);">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-floating has-icon">
                                                    <input type="text" class="form-control" id="name"
                                                           name="name" value="{{ $user->name }}" placeholder="Name" required autocomplete="name">
                                                    <label for="name">Name</label>
                                                    <i class="bi bi-person float-icon"></i>
                                                </div>
                                                <div class="invalid-feedback d-block small mt-1" data-field="name"></div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-floating has-icon">
                                                    <input type="email" class="form-control" id="email"
                                                           name="email" value="{{ $user->email }}" placeholder="Email" required autocomplete="email">
                                                    <label for="email">Email</label>
                                                    <i class="bi bi-envelope-at float-icon"></i>
                                                </div>
                                                <div class="invalid-feedback d-block small mt-1" data-field="email"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-2">Contact</label>
                                    <div class="card border-0 rounded-3 p-3" style="background: var(--bs-tertiary-bg);">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-floating has-icon">
                                                    <input type="text" class="form-control" id="address"
                                                           name="address" value="{{ $user->address }}" placeholder="Address">
                                                    <label for="address">Address (optional)</label>
                                                    <i class="bi bi-geo-alt float-icon"></i>
                                                </div>
                                                <div class="invalid-feedback d-block small mt-1" data-field="address"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end pt-2">
                                    <button type="submit" id="updateBtn" class="btn btn-primary px-4 rounded-pill">
                                        <span class="btn-label"><i class="bi bi-save me-1"></i> Save Changes</span>
                                        <span class="btn-spinner d-none ms-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <!-- row -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Floating-label with trailing icon */
.has-icon { position: relative; }
.float-icon {
    position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: var(--bs-secondary-color, #9aa0a6);
}
/* Inputs use theme tokens so they adapt */
.form-control {
    border-color: var(--bs-border-color);
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
}
.form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .15);
}
/* Fix floating labels when icons present */
.has-icon .form-control { padding-right: 2.25rem; }

/* Make muted text adapt in both themes */
.text-muted { color: var(--bs-secondary-color) !important; }

/* Email: keep on one line, ellipsis if too long */
.user-email {
    white-space: nowrap;
    word-break: keep-all;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Outline button contrast in dark */
[data-bs-theme="dark"] .btn-outline-secondary {
    color: var(--bs-body-color);
    border-color: var(--bs-border-color);
}
[data-bs-theme="dark"] .btn-outline-secondary:hover {
    background-color: var(--bs-secondary-bg);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#profileForm').validate({
        rules:{
            name:{ required:true, minlength:2, maxlength:120 },
            email:{ required:true, email:true, maxlength:190 },
            address:{ maxlength:255 },
        },
        messages:{
            name:{ required:'Please enter your name.' },
            email:{ required:'Please enter your email.' }
        },
        errorPlacement:function(error,element){
            element.closest('.col-12').find('.invalid-feedback').html(error.text());
        },
        highlight:function(el){ $(el).addClass('is-invalid'); },
        unhighlight:function(el){
            $(el).removeClass('is-invalid');
            $(el).closest('.col-12').find('.invalid-feedback').empty();
        },
        submitHandler:function(form){
            const $btn = $('#updateBtn');
            $btn.prop('disabled',true);
            $btn.find('.btn-label').text('Saving…');
            $btn.find('.btn-spinner').removeClass('d-none');

            const formData = new FormData(form);
            $.ajax({
                url: '{{ route('super-admin.profile.update') }}',
                type: 'POST',
                data: formData,
                processData:false,
                contentType:false,
                success:function(res){
                    if(res.status === 'success'){
                        toastr.success(res.message || 'Profile updated');
                    } else {
                        toastr.error(res.message || 'Update failed');
                    }
                },
                error:function(xhr){
                    if(xhr.status === 422 && xhr.responseJSON?.errors){
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            $('[data-field="'+field+'"]').html(errors[field][0]);
                        }
                        toastr.error('Please fix the highlighted errors.');
                    } else {
                        toastr.error('Update failed');
                    }
                },
                complete:function(){
                    $btn.prop('disabled',false);
                    $btn.find('.btn-label').html('<i class="bi bi-save me-1"></i> Save Changes');
                    $btn.find('.btn-spinner').addClass('d-none');
                }
            });
            return false;
        }
    });
});
</script>
@endpush
