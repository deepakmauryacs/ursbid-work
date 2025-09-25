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
                    <form method="post" action="{{ url('/update_details/' . $blog->id) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="seller-name" class="form-label text-capitalize">Name</label>
                                <input type="text" class="form-control" id="seller-name" placeholder="Enter your name" name="name" value="{{ $blog->name }}">
                                @if ($errors->has('name'))
                                    <div class="text-danger small mt-1">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="seller-phone" class="form-label text-capitalize">Phone</label>
                                <input type="text" class="form-control" id="seller-phone" placeholder="Enter your phone number" name="phone" value="{{ $blog->phone }}">
                                @if ($errors->has('phone'))
                                    <div class="text-danger small mt-1">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="seller-gst" class="form-label text-uppercase">GST</label>
                                <input type="text" class="form-control" id="seller-gst" placeholder="Enter GST number" name="gst" value="{{ $blog->gst }}">
                                @if ($errors->has('gst'))
                                    <div class="text-danger small mt-1">{{ $errors->first('gst') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="seller-email" class="form-label text-capitalize">Email</label>
                                <input type="email" class="form-control" id="seller-email" placeholder="Email" value="{{ $blog->email }}" readonly>
                                @if ($errors->has('email'))
                                    <div class="text-danger small mt-1">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            @php
                                $selectedAccTypes = explode(',', $blog->acc_type);
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
                                @if ($errors->has('acc_type'))
                                    <div class="text-danger small mt-1">{{ $errors->first('acc_type') }}</div>
                                @endif
                            </div>

                            <div class="col-12">
                                <div id="gstField" class="mt-2" style="display: none;">
                                    <label for="pro_ser" class="form-label fw-semibold">Product / Services</label>
                                    <div id="checkboxContainer" class="row g-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updateDropdown() {
        var selectedCategories = [];
        if ($('#acc_type_seller').is(':checked')) {
            selectedCategories.push(9);
        }
        if ($('#acc_type_contractor').is(':checked')) {
            selectedCategories.push(10);
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
                }
            });
        } else {
            $('#gstField').hide();
            $('#checkboxContainer').html('');
        }
    }

    $('input[name="acc_type[]"]').change(function() {
        updateDropdown();
    });

    updateDropdown();
});
</script>
@endsection
