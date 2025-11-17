@extends('seller.layouts.app')
@section('title', 'Update Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="mb-4">
                <h1 class="h3 mb-1">Update Account Details</h1>
                <p class="text-muted mb-0">Keep your contact and business preferences current so we can serve you better.</p>
            </div>

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

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form id="updateAccountForm" method="post" action="{{ url('/update_details/' . $blog->id) }}">
                        @csrf

                        <div class="row g-4">

                            {{-- NAME --}}
                            <div class="col-md-6">
                                <label for="seller-name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="seller-name" name="name" value="{{ $blog->name }}">
                                <div class="text-danger small mt-1 error-text" data-error="name"></div>
                            </div>

                            {{-- PHONE --}}
                            <div class="col-md-6">
                                <label for="seller-phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="seller-phone" name="phone" value="{{ $blog->phone }}">
                                <div class="text-danger small mt-1 error-text" data-error="phone"></div>
                            </div>

                            {{-- GST --}}
                            <div class="col-md-6">
                                <label for="seller-gst" class="form-label">GST</label>
                                <input type="text" class="form-control" id="seller-gst" name="gst" value="{{ $blog->gst }}">
                                <div class="text-danger small mt-1 error-text" data-error="gst"></div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-md-6">
                                <label for="seller-email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="seller-email" name="email" value="{{ $blog->email }}" readonly>
                                <div class="text-danger small mt-1 error-text" data-error="email"></div>
                            </div>

                            @php
                                $selectedAccTypes = array_filter(explode(',', $blog->acc_type));
                                $selectedProServices = array_filter(array_map('trim', explode(',', (string) $blog->pro_ser)));
                            @endphp

                            {{-- REGISTER AS --}}
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

                            {{-- PRODUCT / SERVICES --}}
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
    {{-- jQuery Validate --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
            const existingServices = @json($selectedProServices);

            // ---------- Product/Service checkboxes ----------
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
                // Seller = 1, Contractor = 2 (agar aapka backend aise expect kar raha hai)
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

            // change on acc_type -> dropdown + re-validate group
            $('input[name="acc_type[]"]').on('change', function () {
                updateDropdown();
                $('input[name="acc_type[]"]').valid();   // validate checkbox group again
            });

            updateDropdown();

            // ---------- Toastr options ----------
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 4000,
                positionClass: 'toast-top-right'
            };

            // ---------- Custom GST rule (Indian GSTIN) ----------
            $.validator.addMethod("indianGST", function (value, element) {
                const val = $.trim(value);
                if (val === "") return true; // optional field
                const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i;
                return gstRegex.test(val);
            }, "Please enter a valid Indian GSTIN (15 characters).");

            function clearInlineErrors() {
                $('#updateAccountForm').find('.error-text').text('').hide();
                $('#updateAccountForm').find('.is-invalid').removeClass('is-invalid');
            }

            // ---------- jQuery Validate setup ----------
            var form = $('#updateAccountForm');

            form.validate({
                ignore: [], // dynamic fields bhi validate hon
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    gst: {
                        indianGST: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    "acc_type[]": {
                        required: true
                    },
                    "pro_ser[]": {
                        required: function () {
                            return $('#gstField').is(':visible');
                        }
                    }
                },
                messages: {
                    name: {
                        required: "Name is required."
                    },
                    phone: {
                        required: "Phone is required.",
                        digits: "Phone must contain digits only.",
                        minlength: "Phone must be 10 digits.",
                        maxlength: "Phone must be 10 digits."
                    },
                    gst: {
                        indianGST: "Please enter a valid Indian GSTIN (15 characters)."
                    },
                    email: {
                        required: "Email is required.",
                        email: "Please provide a valid email address."
                    },
                    "acc_type[]": {
                        required: "Please select at least one account type."
                    },
                    "pro_ser[]": {
                        required: "Please select at least one product / service."
                    }
                },
                errorPlacement: function (error, element) {
                    var name = element.attr('name') || '';
                    name = name.replace('[]', ''); // acc_type[] -> acc_type

                    var $span = form.find('.error-text[data-error="' + name + '"]');
                    if ($span.length) {
                        $span.text(error.text()).show();
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                    var name = $(element).attr('name') || '';
                    name = name.replace('[]', '');
                    form.find('.error-text[data-error="' + name + '"]').text('').hide();
                },
                submitHandler: function (formEl) {
                    clearInlineErrors();

                    var $form = $(formEl);
                    var $submitButton = $('#updateAccountSubmit');
                    $submitButton.prop('disabled', true).addClass('disabled');

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(),
                        success: function (response) {
                            clearInlineErrors();
                            if (response && response.status === 'success') {
                                toastr.success(response.message || 'Account details updated successfully.');
                            } else {
                                toastr.success('Account details updated successfully.');
                            }
                        },
                        error: function (xhr) {
                            clearInlineErrors();

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                var firstField = null;
                                $.each(xhr.responseJSON.errors, function (field, messages) {
                                    var name = field.replace(/\.\d+$/, '');
                                    var $span = form.find('.error-text[data-error="' + name + '"]');
                                    if ($span.length && messages.length) {
                                        $span.text(messages[0]).show();
                                        var $input = form.find('[name="' + name + '"], [name="' + name + '[]"]');
                                        $input.addClass('is-invalid');
                                        if (!firstField) firstField = name;
                                    }
                                });

                                if (firstField) {
                                    var $first = form.find('.error-text[data-error="' + firstField + '"]');
                                    if ($first.length) {
                                        $('html, body').animate({
                                            scrollTop: $first.offset().top - 120
                                        }, 400);
                                    }
                                }
                            } else {
                                var msg = (xhr.responseJSON && xhr.responseJSON.message)
                                    ? xhr.responseJSON.message
                                    : 'Something went wrong. Please try again.';
                                toastr.error(msg);
                            }
                        },
                        complete: function () {
                            $submitButton.prop('disabled', false).removeClass('disabled');
                        }
                    });

                    return false; // prevent default submit
                }
            });
        });
    </script>
@endpush
