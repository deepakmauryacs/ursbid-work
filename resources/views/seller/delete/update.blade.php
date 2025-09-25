@extends('seller.layouts.app')
@section('title', 'Update Details')

@section('content')

<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Edit</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <!-- <div class="action-btn">

                                        <div class="form-group mb-0">
                                            <div class="input-container icon-left position-relative">
                                                <span class="input-icon icon-left">
                                                    <span data-feather="calendar"></span>
                                                </span>
                                                <input type="text" class="form-control form-control-default date-ranger" name="date-ranger" placeholder="Oct 30, 2019 - Nov 30, 2019">
                                                <span class="input-icon icon-right">
                                                    <span data-feather="chevron-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div> -->


                        <!-- <div class="action-btn">
                                        <a href="#" class="btn btn-sm btn-primary btn-add">
                                            <i class="la la-plus"></i> </a>
                                    </div> -->
                    </div>
                </div>

            </div>

        </div>
        <div class="form-element">
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default card-md mb-4">

                        <div class="card-body py-md-25">
                            <form method="post" action="{{ url('/update_details/'.$blog->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                name</label>
                                            <input type="text"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="name" name="name" value="{{ $blog->name }}">
                                        </div>
                                        @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                phone</label>
                                            <input type="text"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="phone" name="phone" value="{{ $blog->phone }}">
                                        </div>
                                        @if ($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                gst</label>
                                            <input type="text"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="gst" name="gst" value="{{ $blog->gst }}">
                                        </div>
                                        @if ($errors->has('gst'))
                                        <span class="text-danger">{{ $errors->first('gst') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                email</label>
                                            <input type="email"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="email" readonly value="{{ $blog->email }}">
                                        </div>
                                        @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <label for="" class="label">Register As </label>
                                    @php
                                    $selectedAccTypes = explode(',', $blog->acc_type); 
                                    @endphp

                                    <div class="col-md-12">
                                        <label>
                                            <input type="checkbox" id="acc_type_seller" name="acc_type[]" value="1"
                                                {{ in_array(1, $selectedAccTypes) ? 'checked' : '' }}>
                                            <span class="outside"></span> Seller
                                        </label>

                                        <label>
                                            <input type="checkbox" id="acc_type_contractor" name="acc_type[]" value="2"
                                                {{ in_array(2, $selectedAccTypes) ? 'checked' : '' }}>
                                            <span class="outside"></span> Contractor
                                        </label>

                                        <label>
                                            <input type="checkbox" id="acc_type_client" name="acc_type[]" value="3"
                                                {{ in_array(3, $selectedAccTypes) ? 'checked' : '' }}>
                                            <span class="outside"></span> Client
                                        </label>

                                        <label>
                                            <input type="checkbox" id="acc_type_buyer" name="acc_type[]" value="4"
                                                {{ in_array(4, $selectedAccTypes) ? 'checked' : '' }}>
                                            <span class="outside"></span> Buyer
                                        </label>
                                        <br>
                                        @if ($errors->has('acc_type'))
                                        <span class="text-danger">{{ $errors->first('acc_type') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <div id="gstField" style="display:none;">
                                            <label for="pro_ser" class="label"><b>Product/Services</b></label>
                                            <!-- This is where the checkboxes will appear -->
                                            <div id="checkboxContainer"></div>
                                        </div>
                                    </div>




                                </div>

                              

                                <div class="col-md-8">

                                    <button type="submit" class="px-4 btn-sm btn-primary">Submit</button>
                                </div>


                            </form>
                        </div>

                    </div>
                </div>
                <!-- ends: .card -->
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

    // Initialize dropdown on page load
    updateDropdown();
});
</script>

@endsection