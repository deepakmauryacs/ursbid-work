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
                    <form id="updateAccountForm" method="post" action="{{ url('/update_details/' . $blog->id) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="seller-name" class="form-label text-capitalize">Name</label>
                                <input type="text" class="form-control" id="seller-name" placeholder="Enter your name" name="name" value="{{ $blog->name }}">
                                <div class="text-danger small mt-1 error-text" data-error="name" style="{{ $errors->has('name') ? '' : 'display:none;' }}">{{ $errors->first('name') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="seller-phone" class="form-label text-capitalize">Phone</label>
                                <input type="text" class="form-control" id="seller-phone" placeholder="Enter your phone number" name="phone" value="{{ $blog->phone }}">
                                <div class="text-danger small mt-1 error-text" data-error="phone" style="{{ $errors->has('phone') ? '' : 'display:none;' }}">{{ $errors->first('phone') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="seller-gst" class="form-label text-uppercase">GST</label>
                                <input type="text" class="form-control" id="seller-gst" placeholder="Enter GST number" name="gst" value="{{ $blog->gst }}">
                                <div class="text-danger small mt-1 error-text" data-error="gst" style="{{ $errors->has('gst') ? '' : 'display:none;' }}">{{ $errors->first('gst') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="seller-email" class="form-label text-capitalize">Email</label>
                                <input type="email" class="form-control" id="seller-email" placeholder="Email" value="{{ $blog->email }}" readonly>
                                @if ($errors->has('email'))
                                    <div class="text-danger small mt-1">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            @php
                                $selectedAccTypes = array_filter(explode(',', $blog->acc_type));
                                $selectedProServices = array_filter(array_map('trim', explode(',', (string) $blog->pro_ser)));
                            @endphp

                            <div class="col-12">
                                <span class="form-label d-block mb-2">Register As</span>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="acc_type_seller" name="acc_type[]" value="1" {{ in_array(1, $selectedAccTypes) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="acc_type_seller">Seller</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="acc_type_contractor" name="acc_type[]" value="2" {{ in_array(2, $selectedAccTypes) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="acc_type_contractor">Contractor</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="acc_type_client" name="acc_type[]" value="3" {{ in_array(3, $selectedAccTypes) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="acc_type_client">Client</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="acc_type_buyer" name="acc_type[]" value="4" {{ in_array(4, $selectedAccTypes) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="acc_type_buyer">Buyer</label>
                                    </div>
                                </div>
                                <div class="text-danger small mt-1 error-text" data-error="acc_type" style="{{ $errors->has('acc_type') ? '' : 'display:none;' }}">{{ $errors->first('acc_type') }}</div>
                            </div>

                            <div class="col-12">
                                <div id="gstField" class="mt-2" style="display: none;">
                                    <label for="pro_ser" class="form-label fw-semibold">Product / Services</label>
                                    <div id="checkboxContainer" class="row g-2"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="text-danger small mt-1 error-text" data-error="pro_ser" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" id="updateAccountSubmit">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKM265AxtUtKeAoD0UDiP0wQmgFDBDsG/T7InhxiZx4nU+uGHVxgAhWYoS4JT3KjIP9ezefK1z6LXm4O0hZrXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-HHV3Pk2VYK9ER5iq90aqw2GUlycvIbwkWNAv8hBlC9yxy0OTUUkLR53Lcs7Vp1IU7DqDZr1P0N6xJbVQfE3d3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            const existingServices = @json($selectedProServices);

            function markExistingServices() {
                if (!existingServices || !existingServices.length) {
                    return;
                }

                $('#checkboxContainer input[type="checkbox"]').each(function() {
                    const value = $(this).val();
                    if (existingServices.includes(value)) {
                        $(this).prop('checked', true);
                    }
                });
            }

            function updateDropdown() {
                var selectedCategories = [];
                if ($('#acc_type_seller').is(':checked')) {
                    selectedCategories.push(1);
                }
                if ($('#acc_type_contractor').is(':checked')) {
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
                        success: function(response) {
                            $('#checkboxContainer').html(response);
                            markExistingServices();
                        }
                    });
                } else {
                    $('#gstField').hide();
                    $('#checkboxContainer').html('');
                }
            }

            function clearErrors() {
                $('#updateAccountForm').find('.error-text').each(function() {
                    $(this).text('').hide();
                });
            }

            function showFieldError(field, message) {
                var $target = $('#updateAccountForm').find('.error-text[data-error="' + field + '"]');
                if ($target.length) {
                    $target.text(message).show();
                } else {
                    toastr.error(message);
                }
            }

            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 4000,
                positionClass: 'toast-top-right'
            };

            $('#updateAccountForm').on('submit', function(event) {
                event.preventDefault();

                var $form = $(this);
                var $submitButton = $('#updateAccountSubmit');

                clearErrors();

                $submitButton.prop('disabled', true).addClass('disabled');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        if (response && response.status === 'success') {
                            toastr.success(response.message || 'Account details updated successfully.');
                        } else {
                            toastr.success('Account details updated successfully.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON ? xhr.responseJSON.errors : {};
                            $.each(errors, function(field, messages) {
                                if ($.isArray(messages) && messages.length) {
                                    showFieldError(field, messages[0]);
                                }
                            });
                            var errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Please correct the highlighted errors and try again.';
                            toastr.error(errorMessage);
                        } else {
                            var generalMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Something went wrong. Please try again.';
                            toastr.error(generalMessage);
                        }
                    },
                    complete: function() {
                        $submitButton.prop('disabled', false).removeClass('disabled');
                    }
                });
            });

            $('input[name="acc_type[]"]').change(function() {
                updateDropdown();
            });

            updateDropdown();
        });
    </script>
@endpush
@endsection
