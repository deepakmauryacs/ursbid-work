<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Qutation Form</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('frontend.inc.header-links')
    <style>
    .nysc {
        min-height: 68px;
    }
    </style>
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
                            <h1 class="page-title">Qutation Form</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{ url('/') }}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Qutation </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section>
            <div class="container">

                <div class="col-md-12">
                    @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ url('qutation_form') }}" method='POST' enctype="multipart/form-data">
                        @csrf
                        <div class="ltn__apartments-tab-content-inner my_for">
                            <h6 class="frm"> Qutation Form <span class="text-danger">( All Fileds are required )</span>
                            </h6>
                            <div class="row">
                                <input type="hidden" name="product_name" placeholder="*Name"
                                    value="{{ $products->title }}">
                                <input type="hidden" name="product_id" placeholder="*Name" value="{{ $products->id }}">
                                <input type="hidden" name="product_img" placeholder="*Name"
                                    value="{{ $products->image }}">

                                <div class="col-md-6">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">Name</label>
                                        <!-- <input type="text" name="name" value="{{ old('name') }}" placeholder="*Name" -->
                                        @php

                                        $name = session('seller')->name ?? '';
                                        @endphp
                                        <input type="text" name="name" value="{{ $name ?: old('name') }}"
                                            placeholder="*Name" required {{ $name ? 'readonly' : '' }}>

                                    </div>
                                    @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">Email</label>
                                        @php

                                        $email = session('seller')->email ?? '';
                                        @endphp

                                        <input type="email" name="email" value="{{ $email ?: old('email') }}"
                                            placeholder="*Email" required {{ $email ? 'readonly' : '' }}>

                                        <!-- <input type="email" name="email" value="{{ old('email') }}" placeholder="*Email"
                                            required> -->
                                    </div>
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>


                                <div class="col-md-12">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <textarea name="message" required value="{{ old('message') }}"
                                            placeholder="Description of product ">{{ old('message') }}</textarea>
                                    </div>
                                    @if ($errors->has('message'))
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                                    @endif
                                </div>


                                <div class="col-md-6">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <!-- <label class="q_lab"></label> -->
                                        <button type="button" class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                            onclick="autofillAddress()">Use my location</button>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">City</label>
                                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                                            placeholder="*city" required>
                                    </div>
                                    @if ($errors->has('city'))
                                    <span class="text-danger">{{ $errors->first('city') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">State</label>
                                        <input type="text" id="state" name="state" value="{{ old('state') }}"
                                            placeholder="*state" required>
                                    </div>
                                    @if ($errors->has('state'))
                                    <span class="text-danger">{{ $errors->first('state') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">Zipcode</label>
                                        <input type="text" id="postal-code" name="zipcode" value="{{ old('zipcode') }}"
                                            placeholder="*zipcode" required>
                                    </div>
                                    @if ($errors->has('zipcode'))
                                    <span class="text-danger">{{ $errors->first('zipcode') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label class="q_lab">Address</label>
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <textarea name="address" class="nysc" required value="{{ old('address') }}"
                                            placeholder="Address">{{ old('address') }}</textarea>
                                    </div>
                                    @if ($errors->has('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <h6>Quotation Type</h6>
                            <div class="row">
                                <div class="col-md-7 radio">


                                    <label>
                                        <input type="radio" checked name="material" value="Only Product"><span
                                            class="outside"><span class="inside"></span></span>Only Product
                                    </label>

                                    <label>
                                        <input type="radio" name="material" value="Including Shipping"><span
                                            class="outside"><span class="inside"></span></span>Including Shipping
                                    </label>


                                </div>
                                <!-- <div class="col-md-5">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">Location</label>
                                        <input type="text" value="{{ old('location') }}" required name="location" placeholder="*Enter Location">
                                    </div>
                                    @if ($errors->has('location'))
                                    <span class="text-danger">{{ $errors->first('location') }}</span>
                                    @endif
                                </div> -->
                                <div class="col-md-2">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <h6>Area</h6>
                                        <div class="input-item">
                                            <select class="nice-select" name='bid_area' required>
                                                <option value='2'>Around 2 KM</option>
                                                <option value='5'>Around 5 KM</option>
                                                <option value='10'>Around 10 KM</option>



                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" id="latitude" name="latitude" value=""><br>
                                <input type="text" id="longitude" name="longitude" value="">

                                <div class="col-md-2">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <h6>Time</h6>  
                                        <div class="input-item">
                                            <select class="nice-select" name='bid_time' required>
                                                <option value='1'>1 Day</option>
                                                <option value='3'>3 Days</option>
                                                <option value='7'>7 Days</option>
                                                <option value='28'>28 Days</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <label class="q_lab">Quantity</label>
                                        <input type="text" value="{{ old('quantity') }}" required name="quantity"
                                            placeholder="*Enter quantity">
                                    </div>
                                    @if ($errors->has('quantity'))
                                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <h6>Unit</h6>
                                        <div class="input-item">
                                            <select class="nice-select" name='unit' required>

                                                @foreach ($units as $unit)

                                                <option value=" {{ $unit->title }} "> {{ $unit->title }} </option>

                                                @endforeach




                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-item input-item-textarea ltn__custom-icon">
                                        <h6>Upload Quation File / Image</h6>
                                        <input type="file" id="myFile" name="images[]" multiple>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-item input-item-textarea ltn__custom-icon">

                                        <input type="checkbox" name="term_and_condition" required
                                            value="term_and_cond_acc" checked> I have read and accept the terms and
                                        conditions
                                    </div>
                                    @if ($errors->has('term_and_condition'))
                                    <span class="text-danger">{{ $errors->first('term_and_condition') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="btn-wrapper text-center--- mt-0">
                                @if (session()->has('seller'))
                                <button type='submit' class="btn theme-btn-1 btn-effect-1 text-uppercase">Get
                                    Quotation</button>
                                @else
                                <a data-bs-toggle="modal" data-bs-target="#staticBackdrop2"
                                    class="btn theme-btn-1 btn-effect-1 text-uppercase">
                                    Get Quotation </a>
                                @endif


                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </section>
        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Login Here</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                        <form action="{{ url('/seller-login-form') }}" method="POST" class="">
                            @csrf
                            <div class="block_in">
                                <label class="label">Email</label>
                                <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
                            </div>


                            <div class="block_in">
                                <label class="label">Password</label>
                                <input type="password" name="password" value="{{ old('password') }}"
                                    placeholder="Password*">
                            </div>
                            @if($errors->any())

                            <div class='text-danger'> Please Provide Correct Details.</div>
                            @endif
                            <div class="btn-wrapper">
                                <button class="my_bnty" type="submit">Login</button>
                            </div>

                            <div class="go-to-btn mt-20">
                                <a href="{{ url('/forgot-password') }}"><small>Forgot your password?</small></a>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        Don't have an account yet?
                        <a href="{{url('seller-register')}}">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
    function autofillAddress() {
        fetch('https://ipinfo.io/json?token=8d6fa5c7bf454c')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const ip = data.ip;  // Get the IP address
                    const [latitude, longitude] = data.loc.split(',');
                    const city = data.city;
                    const state = data.region;
                    const postalCode = data.postal;

                    // Populate the city, state, postal code, latitude, and longitude fields
                    document.getElementById('city').value = city;
                    document.getElementById('state').value = state;
                    document.getElementById('postal-code').value = postalCode;
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;

                    // Optionally populate a field with the IP address
                    if (document.getElementById('ip')) {
                        document.getElementById('ip').value = ip;
                    }

                    // Fetch the address using the Google Maps Geocoding API
                    const apiKey = 'AIzaSyAILGVlt-SOiL381JT3TQ9dxxoNIUuxrV8'; // Replace with your Google Maps API key
                    fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`)
                        .then(response => response.json())
                        .then(geoData => {
                            if (geoData.status === 'OK' && geoData.results.length > 0) {
                                const address = geoData.results[0].formatted_address;
                                document.getElementById('address').value = address; // Populate the address field
                            } else {
                                console.log('Geocoding API error:', geoData.status);
                            }
                        })
                        .catch(error => console.log('Error fetching address:', error));

                } else {
                    alert('Unable to fetch location data');
                }
            })
            .catch(error => console.log('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', autofillAddress);
</script>





        <script>
        // document.addEventListener('DOMContentLoaded', () => {
        //     const latitudeField = document.getElementById('latitude');
        //     const longitudeField = document.getElementById('longitude');

        //     const autoFillLocation = () => {
        //         if ('geolocation' in navigator) {
        //             navigator.geolocation.getCurrentPosition(
        //                 position => {
        //                     latitudeField.value = position.coords.latitude;
        //                     longitudeField.value = position.coords.longitude;
        //                 },
        //                 error => {
        //                     handleGeolocationError(error);
        //                 }
        //             );
        //         } else {
        //             alert("Geolocation is not supported by your browser.");
        //         }
        //     };

        //     const handleGeolocationError = error => {
        //         let errorMessage = '';
        //         switch (error.code) {
        //             case error.PERMISSION_DENIED:
        //                 errorMessage = "User denied the request for Geolocation.";
        //                 break;
        //             case error.POSITION_UNAVAILABLE:
        //                 errorMessage = "Location information is unavailable.";
        //                 break;
        //             case error.TIMEOUT:
        //                 errorMessage = "The request to get user location timed out.";
        //                 break;
        //             case error.UNKNOWN_ERROR:
        //             default:
        //                 errorMessage = "An unknown error occurred.";
        //                 break;
        //         }

        //     };

        //     autoFillLocation();
        // });
        </script>

        <script>
        var radioGroup = $('input[name=radios]');

        radioGroup.click(function() {
            enableDisableInput($(this));
        });

        function enableDisableInput(currentRadio) {
            var inputPath = currentRadio.siblings('input[type=\'text\']');
            var siblingInputPath = currentRadio.parent().siblings('.has-text-input').find('input[type=\'text\']');

            if (currentRadio.parent().hasClass('has-text-input')) {
                inputPath.prop('disabled', false);
                siblingInputPath.prop('disabled', true);
            } else {
                siblingInputPath.prop('disabled', true);
            }
        }
        </script>

        @include('frontend.inc.footer')


    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>