@extends('ursdashboard.layouts.app')

@section('title', 'Update Account')

@section('content')
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="page-title mb-0">Update Account</h4>
                        <p class="text-muted mb-0">Keep your profile details and business preferences current.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-xl-10">
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body p-4">
                        <form id="updateAccountForm" method="post" action="{{ url('/update_details/' . $account->id) }}">
                            @csrf

                            <div class="row g-4">
                                @php
                                    $selectedAccTypes = array_filter(explode(',', $account->acc_type));
                                    $selectedProServices = array_filter(array_map('trim', explode(',', (string) $account->pro_ser)));
                                @endphp

                                <div class="col-md-6">
                                    <label for="seller-name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="seller-name" name="name" value="{{ $account->name }}">
                                    <div class="text-danger small mt-1 error-text" data-error="name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="seller-phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="seller-phone" name="phone" value="{{ $account->phone }}">
                                    <div class="text-danger small mt-1 error-text" data-error="phone"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="seller-gst" class="form-label">GST</label>
                                    <input type="text" class="form-control" id="seller-gst" name="gst" value="{{ $account->gst }}">
                                    <div class="text-danger small mt-1 error-text" data-error="gst"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="seller-email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="seller-email" name="email" value="{{ $account->email }}" readonly>
                                    <div class="text-danger small mt-1 error-text" data-error="email"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Register As <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <label class="form-check-label me-3">
                                            <input type="checkbox" class="form-check-input me-1" name="acc_type[]" value="1" {{ in_array(1, $selectedAccTypes) ? 'checked' : '' }}>
                                            Seller
                                        </label>
                                        <label class="form-check-label me-3">
                                            <input type="checkbox" class="form-check-input me-1" name="acc_type[]" value="2" {{ in_array(2, $selectedAccTypes) ? 'checked' : '' }}>
                                            Contractor
                                        </label>
                                        <label class="form-check-label me-3">
                                            <input type="checkbox" class="form-check-input me-1" name="acc_type[]" value="3" {{ in_array(3, $selectedAccTypes) ? 'checked' : '' }}>
                                            Client
                                        </label>
                                        <label class="form-check-label me-3">
                                            <input type="checkbox" class="form-check-input me-1" name="acc_type[]" value="4" {{ in_array(4, $selectedAccTypes) ? 'checked' : '' }}>
                                            Buyer
                                        </label>
                                    </div>
                                    <div class="text-danger small mt-1 error-text" data-error="acc_type"></div>
                                </div>

                                <div class="col-12">
                                    <div id="gstField" class="mt-2" style="display:none;">
                                        <label class="form-label">Product / Services</label>
                                        <div id="checkboxContainer" class="row g-2"></div>
                                    </div>
                                    <div class="text-danger small mt-1 error-text" data-error="pro_ser"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="updateAccountSubmit" class="btn btn-primary">
                                    Save Changes
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
            const existingServices = @json($selectedProServices);

            function markExistingServices() {
                if (!existingServices || !existingServices.length) return;

                $('#checkboxContainer').find('input[type="checkbox"]').each(function () {
                    const value = $(this).val();
                    if (existingServices.includes(value)) {
                        $(this).prop('checked', true);
                    }
                });
            }

            function updateDropdown() {
                let selectedCategories = [];
                if ($('input[name="acc_type[]"][value="1"]').is(':checked')) {
                    selectedCategories.push(1);
                }
                if ($('input[name="acc_type[]"][value="2"]').is(':checked')) {
                    selectedCategories.push(2);
                }

                if (selectedCategories.length > 0) {
                    $('#gstField').show();
                    $.ajax({
                        url: '{{ route("fetch.optionsback") }}',
                        type: 'POST',
                        data: {
                            categories: selectedCategories,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#checkboxContainer').html(response);
                            markExistingServices();
                        }
                    });
                } else {
                    $('#gstField').hide();
                    $('#checkboxContainer').html('');
                }
            }

            $('input[name="acc_type[]"]').on('change', function () {
                updateDropdown();
                $('input[name="acc_type[]"]').valid();
            });

            updateDropdown();

            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 4000,
                positionClass: 'toast-top-right'
            };

            $.validator.addMethod("indianGST", function (value, element) {
                const val = $.trim(value);
                if (val === "") return true;
                const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i;
                return gstRegex.test(val);
            }, "Please enter a valid Indian GSTIN (15 characters).");

            function clearInlineErrors() {
                $('#updateAccountForm').find('.error-text').text('').hide();
                $('#updateAccountForm').find('.is-invalid').removeClass('is-invalid');
            }

            var form = $('#updateAccountForm');

            form.validate({
                ignore: [],
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    gst: {
                        indianGST: true,
                    },
                    'acc_type[]': {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: 'Name is required.',
                        minlength: 'Name must be at least 2 characters.',
                    },
                    phone: {
                        required: 'Phone is required.',
                        digits: 'Please enter only numbers.',
                        minlength: 'Phone must be at least 10 digits.',
                        maxlength: 'Phone cannot exceed 15 digits.',
                    },
                    email: {
                        required: 'Email is required.',
                        email: 'Please provide a valid email address.',
                    },
                    gst: {
                        indianGST: 'Please enter a valid GST number (15 alphanumeric characters).',
                    },
                    'acc_type[]': {
                        required: 'Please select at least one registration type.',
                    },
                },
                errorPlacement: function (error, element) {
                    const name = element.attr('name');
                    if (name === 'acc_type[]') {
                        $('[data-error="acc_type"]').text(error.text()).show();
                    } else if (name === 'pro_ser[]') {
                        $('[data-error="pro_ser"]').text(error.text()).show();
                    } else {
                        $('[data-error="' + name + '"]').text(error.text()).show();
                        element.addClass('is-invalid');
                    }
                },
                success: function (label, element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function (formEl, event) {
                    event.preventDefault();
                    clearInlineErrors();

                    const formData = new FormData(formEl);
                    $('#updateAccountSubmit').prop('disabled', true).text('Saving...');

                    $.ajax({
                        url: formEl.action,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#updateAccountSubmit').prop('disabled', false).text('Save Changes');

                            if (response.status === 'success') {
                                toastr.success(response.message);
                            } else {
                                toastr.info(response.message || 'No changes were necessary.');
                            }
                        },
                        error: function (xhr) {
                            $('#updateAccountSubmit').prop('disabled', false).text('Save Changes');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors) {
                                    Object.keys(errors).forEach(function (key) {
                                        const normalizedKey = key.replace(/\.$/, '');
                                        $('[data-error="' + normalizedKey + '"]').text(errors[key][0]).show();
                                    });
                                }
                                toastr.error(xhr.responseJSON.message || 'Please correct the errors and try again.');
                            } else {
                                toastr.error('An unexpected error occurred. Please try again.');
                            }
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endpush
