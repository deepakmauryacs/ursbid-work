@extends('ursbid-admin.layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-body border-0 rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock"></i> Change Password
                    </h4>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 toggle-all" title="Show/Hide all">
                        <i class="bi bi-eye"></i><span class="d-none d-sm-inline">Toggle all</span>
                    </button>
                </div>

                <form id="passwordForm" class="px-4 pb-4">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group modern-input">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="current_password" class="form-control pw-field" required autocomplete="current-password" />
                            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1" aria-label="Show/Hide">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback d-block small mt-1" data-field="current_password"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group modern-input">
                            <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                            <input type="password" name="password" class="form-control pw-field" required autocomplete="new-password" id="newPassword" />
                            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1" aria-label="Show/Hide">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <!-- Strength meter -->
                        <div class="mt-2">
                            <div class="progress strength-progress" style="height:8px;">
                                <div class="progress-bar" id="pwStrengthBar" role="progressbar"></div>
                            </div>
                            <div class="d-flex gap-3 mt-2 small text-body-secondary" id="pwHints">
                                <span class="req req-len"><i class="bi bi-dot"></i> 8+ chars</span>
                                <span class="req req-case"><i class="bi bi-dot"></i> upper & lower</span>
                                <span class="req req-num"><i class="bi bi-dot"></i> number</span>
                                <span class="req req-spc"><i class="bi bi-dot"></i> special</span>
                            </div>
                        </div>

                        <div class="invalid-feedback d-block small mt-1" data-field="password"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group modern-input">
                            <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                            <input type="password" name="password_confirmation" class="form-control pw-field" required autocomplete="new-password" id="confirmPassword" />
                            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1" aria-label="Show/Hide">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text small" id="matchHint" style="display:none;"></div>
                        <div class="invalid-feedback d-block small mt-1" data-field="password_confirmation"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-arrow-repeat me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-center mt-3 text-body-secondary small">
                Use a strong, unique password you don’t use elsewhere.
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Use Bootstrap theme tokens so it works in light & dark */
.modern-input .input-group-text {
    border-right: 0;
    background: var(--bs-tertiary-bg);
    color: var(--bs-body-color);
}
.modern-input .form-control {
    border-left: 0;
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
}
.modern-input .form-control:focus,
.modern-input .btn:focus {
    box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .15);
}
.modern-input .form-control,
.modern-input .input-group-text,
.modern-input .btn {
    border-color: var(--bs-border-color);
}
.modern-input:focus-within .form-control,
.modern-input:focus-within .input-group-text,
.modern-input:focus-within .btn {
    border-color: var(--bs-primary);
}

.card { overflow: hidden; }

/* Progress background adapts; bar colors come from classes */
.strength-progress {
    background-color: var(--bs-secondary-bg);
    border-radius: var(--bs-border-radius);
}
.strength-progress .progress-bar {
    transition: width .25s ease;
    /* Let .bg-* classes control color so they adapt to theme */
}

/* Hint color in success state */
.req.ok { color: var(--bs-success); }

/* Optional: nicer button outline contrast in dark */
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
<script>
$(function(){
    // Show/Hide for individual field
    $(document).on('click', '.toggle-password', function(){
        const input = $(this).closest('.input-group').find('input');
        const icon = $(this).find('i');
        const isPw = input.attr('type') === 'password';
        input.attr('type', isPw ? 'text' : 'password');
        icon.toggleClass('bi-eye bi-eye-slash');
    });

    // Master toggle (top-right button)
    $('.toggle-all').on('click', function(){
        const anyHidden = $('.pw-field[type="password"]').length > 0;
        $('.pw-field').each(function(){
            const input = $(this);
            const groupBtnIcon = input.closest('.input-group').find('.toggle-password i');
            input.attr('type', anyHidden ? 'text' : 'password');
            groupBtnIcon.toggleClass('bi-eye', !anyHidden).toggleClass('bi-eye-slash', anyHidden);
        });
        $(this).find('i').toggleClass('bi-eye bi-eye-slash');
    });

    // Password strength + hints
    const $pw = $('#newPassword');
    const $bar = $('#pwStrengthBar');
    const $hints = {
        len: $('.req-len'),
        case: $('.req-case'),
        num: $('.req-num'),
        spc: $('.req-spc')
    };

    function assess(pw){
        const rules = {
            len: pw.length >= 8,
            case: /[a-z]/.test(pw) && /[A-Z]/.test(pw),
            num: /\d/.test(pw),
            spc: /[^A-Za-z0-9]/.test(pw)
        };
        for (const k in rules) $hints[k].toggleClass('ok', rules[k]);

        let score = Object.values(rules).filter(Boolean).length;
        const widths = {0:0,1:25,2:50,3:75,4:100};
        const classes = ['bg-danger','bg-warning','bg-info','bg-primary','bg-success'];
        $bar.removeClass('bg-danger bg-warning bg-info bg-primary bg-success');
        $bar.addClass(classes[score]).css('width', widths[score] + '%');
        $bar.attr('aria-valuenow', widths[score]);
    }

    $pw.on('input', function(){ assess(this.value); });

    // Match hint
    function checkMatch(){
        const p1 = $pw.val();
        const p2 = $('#confirmPassword').val();
        const $hint = $('#matchHint');
        if(!p2){ $hint.hide(); return; }
        if(p1 === p2){
            $hint.show().removeClass('text-danger').addClass('text-success')
                .html('<i class="bi bi-check-circle"></i> Passwords match');
        } else {
            $hint.show().removeClass('text-success').addClass('text-danger')
                .html('<i class="bi bi-x-circle"></i> Passwords do not match');
        }
    }
    $('#confirmPassword, #newPassword').on('input', checkMatch);

    // Submit
    $('#passwordForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        $.ajax({
            url: '{{ route('super-admin.password.update') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message || 'Password updated');
                $('#passwordForm')[0].reset();
                $('#matchHint').hide();
                $('#pwStrengthBar').css('width','0%').removeClass('bg-danger bg-warning bg-info bg-primary bg-success');
                // reset icons
                $('.pw-field').attr('type','password');
                $('.toggle-password i').removeClass('bi-eye-slash').addClass('bi-eye');
                $('.toggle-all i').removeClass('bi-eye-slash').addClass('bi-eye');
            },
            error: function(xhr){
                if(xhr.status === 422 && xhr.responseJSON?.errors){
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $('[data-field="'+field+'"]').text(errors[field][0]);
                    }
                    toastr.error('Please fix the highlighted errors.');
                } else {
                    toastr.error('Update failed');
                }
            }
        });
    });
});
</script>
@endpush
