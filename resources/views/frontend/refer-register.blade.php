<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Register</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <style>
    .hidden {
        display: none;
    }
    </style> -->
    @include('frontend.inc.header-links')

</head>

<body>


    <!-- Body main wrapper start -->
    <div class="body-wrapper">

        @include('frontend.inc.header')

        <div class="ltn__utilize-overlay"></div>

        <div class="ltn__breadcrumb-area text-left">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__breadcrumb-inner">
                            <h1 class="page-title"> Register Here</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('/')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li> Register Here</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="ltn__login-area pb-65">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center">
                            <h1 class="section-title">Register Here</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 regis">
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if($errors->any())
                        @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @endif
                        @if ($errors->has('ref_error'))
                        <span class="text-danger">{{ $errors->first('ref_error') }}</span>
                        @endif
                        <div class="account-login-inner">
                            <form action="{{ url('/seller-register') }}" method="POST"
                                class="ltn__form-box contact-form-box">
                                @csrf
                                <div class="block_in">
                                    <label class="label">Name <span class="text-danger"> *</span> </label>
                                    <input type="text" placeholder="Name*" name="name" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="block_in">
                                    <label class="label">Email <span class="text-danger"> *</span></label>
                                    <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="block_in">
                                    <label class="label">Phone <span class="text-danger"> *</span></label>
                                    <input type="text" name="phone" placeholder="Phone*" value="{{ old('phone') }}">
                                    @if ($errors->has('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <!-- <div class="col-md-7 radio mrb_t">


                                    <label>
                                        <input type="radio" name="gender" checked value="Male"><span
                                            class="outside"><span class="inside"></span></span>Male
                                    </label>

                                    <label>
                                        <input type="radio" name="gender" value="Female"><span class="outside"><span
                                                class="inside"></span></span>Female
                                    </label>


                                </div> -->

                                <label for="" class="label">Register As </label>
                                <div class="col-md-12">
                                    <label>
                                        <input type="checkbox" id="acc_type_seller" name="acc_type[]" value="1"><span
                                            class="outside"></span> Seller
                                    </label>
                                    <label>
                                        <input type="checkbox" id="acc_type_contractor" name="acc_type[]"
                                            value="2"><span class="outside"></span> Contractor
                                    </label>
                                    <label>
                                        <input type="checkbox" id="acc_type_client" checked name="acc_type[]"
                                            value="3"><span class="outside"></span> Client
                                    </label>
                                    <label>
                                        <input type="checkbox" id="acc_type_buyer" name="acc_type[]" value="4"><span
                                            class="outside"></span> Buyer
                                    </label>
                                </div>

                                <!-- <div id="gstField" style="display:none;">
                                    <label for="label" class="label">Product/Services</label>
                                    <select name="pro_ser" id="pro_ser" placeholder="Enter GSTIN">
                                        <option value="">Select</option>
                                    </select>
                                </div> -->
                                <div id="gstField" style="display:none;">
                                    <label for="pro_ser" class="label">Product/Services</label>
                                    <!-- This is where the checkboxes will appear -->
                                    <div id="checkboxContainer"></div>
                                </div>


                                <div id="gstField">
                                    <label for="label" class="label">GST No:</label>
                                    <input type="text" id="gstNo" placeholder="Enter GSTIN" name="gst">
                                </div>




                                <div class="block_in">
                                    <label class="label">Password <span class="text-danger"> *</span></label>
                                    <input type="password" name="password" placeholder="Password*"
                                        value="{{ old('password') }}">
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="block_in">

                                    <input type="hidden" name="referral_code" placeholder="referral_code"
                                        value="{{ $refer_id }}">

                                </div>

                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        @error('g-recaptcha-response') <span style="color:red">{{ $message }}</span><br> @enderror



                                <div class="btn-wrapper">
                                    <button class="my_bnty" type="submit">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <img src="{{url('assets/front/img/inner/login.jpg')}}">
                            <div class="btn-wrapper">
                                <!-- <a href="{{url('seller-login')}}" class="theme-btn-1 btn black-btn">Login</a> -->
                                <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                    class="theme-btn-1 btn black-btn">Login</a>
                            </div>
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

                // Check if certain checkboxes are checked and push corresponding category values
                if ($('#acc_type_seller').is(':checked')) {
                    selectedCategories.push(9);
                }
                if ($('#acc_type_contractor').is(':checked')) {
                    selectedCategories.push(10);
                }

                // Show or hide GST field based on the selection
                if (selectedCategories.length > 0) {
                    $('#gstField').show();

                    // Make AJAX request to fetch subcategories
                    $.ajax({
                        url: '{{ route("fetch.options") }}',
                        type: 'POST',
                        data: {
                            categories: selectedCategories,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Update the checkbox container with the response (checkboxes)
                            $('#checkboxContainer').html(response);
                        }
                    });
                } else {
                    $('#gstField').hide();
                    $('#checkboxContainer').html(''); // Clear any existing checkboxes
                }
            }

            // Bind the change event for the account type checkboxes
            $('input[name="acc_type[]"]').change(function() {
                updateDropdown();
            });

            // Initialize dropdown on page load
            updateDropdown();
        });
        </script>


        <!-- <script>
        function toggleFields() {
            const gstField = document.getElementById('gstField');
            const panField = document.getElementById('panField');
            const sellerRadio = document.querySelector('input[name="user_type"][value="seller"]');

            if (sellerRadio.checked) {
                gstField.classList.remove('hidden');
                panField.classList.add('hidden');
            } else {
                gstField.classList.add('hidden');
                panField.classList.remove('hidden');
            }
        }

        // Initial call to set the correct fields on page load
        toggleFields();
        </script> -->






        @include('frontend.inc.footer')


    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>