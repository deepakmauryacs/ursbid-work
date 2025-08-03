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

    .modal1 {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content1 {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        height: 290px;
        position: relative;
    }

    .close1 {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        width: 45px;
        position: absolute;
        right: 0;
        top: 0;
    }

    .close1:hover,
    .close1:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
                                <input type="hidden" name="product_brand" placeholder="brand"
                                    value="{{ isset($superproducts->title) ? $superproducts->title : 'N/A' }}">
                                <input type="hidden" name="product_name" placeholder="*Name"
                                    value="{{ $products->title }}">
                                <input type="hidden" name="subcategory_check" placeholder="*Name" value="{{ $sid }}">
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
                                            onclick="getLocation()">Use my location</button>
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
                                        <textarea name="address" id="address" class="nysc" required
                                            value="{{ old('address') }}"
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

                                    @if($category->id == 9)
                                    <label>
                                        <input type="radio" checked name="material" value="Only Product"><span
                                            class="outside"><span class="inside"></span></span>Only Product
                                    </label>

                                    <label>
                                        <input type="radio" name="material" value="Including Shipping"><span
                                            class="outside"><span class="inside"></span></span>Including Shipping
                                    </label>
                                    @else
                                    <label>
                                        <input type="radio" checked name="material" value="With Material"><span
                                            class="outside"><span class="inside"></span></span>With Material
                                    </label>

                                    <label>
                                        <input type="radio" name="material" value="Without Material"><span
                                            class="outside"><span class="inside"></span></span>Without Material
                                    </label>
                                    @endif

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
                                                <option value='50'>Around 50 KM</option>
                                                <option value='100'>Around 100 KM</option>
                                                <!-- <option value='India'>In India</option>-->
                                                <option value='5000'>No limit</option> 
                                              
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude" name="latitude" value=""><br>
                                <input type="hidden" id="longitude" name="longitude" value="">
                                <input type="hidden" id="longitude" name="cat_id" value="{{ $category->id }}">

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
                                        <h6>Upload Quotation File / Image</h6>
                                        <div id="file-upload-area">
                                            <div class="file-upload-wrapper">
                                                <input type="file" id="myFile" name="images[]" class="custom-file-input"
                                                    multiple>
                                                <button type="button" class="remove-btn  mb-2  btn-primary"
                                                    onclick="removeFile(this)">Remove</button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-primary" onclick="addMoreFiles()">Add
                                            More</button>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-item input-item-textarea ltn__custom-icon">

                                        <input type="checkbox" id="termAndCondition1" name="term_and_condition" required
                                            value="term_and_cond_acc"> I have read and accept the terms and
                                        conditions
                                    </div>
                                    @if ($errors->has('term_and_condition'))
                                    <span class="text-danger">{{ $errors->first('term_and_condition') }}</span>
                                    @endif
                                </div>

                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        @error('g-recaptcha-response') <span style="color:red">{{ $message }}</span><br> @enderror

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
        <script>
    function removeFile(button) {
        const wrapper = button.closest('.file-upload-wrapper');
        const allWrappers = document.querySelectorAll('#file-upload-area .file-upload-wrapper');

        if (allWrappers.length > 1) {
            wrapper.remove(); // Remove the whole block
        } else {
            const fileInput = wrapper.querySelector('input[type="file"]');
            fileInput.value = ''; // Just reset the file input
        }
    }

    function addMoreFiles() {
        const fileUploadArea = document.getElementById('file-upload-area');
        const newWrapper = document.createElement('div');
        newWrapper.className = 'file-upload-wrapper';
        newWrapper.innerHTML = `
            <input type="file" id="myFile" name="images[]" class="custom-file-input" multiple>
            <button type="button" class="remove-btn mb-2 btn-primary" onclick="removeFile(this)">Remove</button>
        `;
        fileUploadArea.appendChild(newWrapper);
    }
</script>

        <!-- <script>
        function addMoreFiles() {
            const fileUploadArea = document.getElementById('file-upload-area');

            // Create a new file input field with a remove button
            const newFileWrapper = document.createElement('div');
            newFileWrapper.classList.add('file-upload-wrapper');

            const newFileInput = document.createElement('input');
            newFileInput.setAttribute('type', 'file');
            newFileInput.setAttribute('name', 'images[]');
            newFileInput.setAttribute('multiple', true);

            // Add the same class as the main file input
            newFileInput.classList.add('custom-file-input');

            const removeBtn = document.createElement('button');
            removeBtn.setAttribute('type', 'button');
            removeBtn.classList.add('remove-btn');
            removeBtn.textContent = 'Remove';
            removeBtn.onclick = function() {
                removeFile(removeBtn);
            };

            newFileWrapper.appendChild(newFileInput);
            newFileWrapper.appendChild(removeBtn);

            fileUploadArea.appendChild(newFileWrapper);
        }

        function removeFile(button) {
            const fileUploadArea = document.getElementById('file-upload-area');
            const totalFiles = fileUploadArea.getElementsByClassName('file-upload-wrapper');

            // Only allow removal if more than one file input exists
            if (totalFiles.length > 1) {
                button.parentNode.remove();
            }
        }
        </script> -->
        <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-text="true">
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
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const city = data.address.city || data.address.town || data.address.village;
                        const state = data.address.state;
                        const postalCode = data.address.postcode;
                        const address = data.display_name;

                        document.getElementById('city').value = city || '';
                        document.getElementById('state').value = state || '';
                        document.getElementById('postal-code').value = postalCode || '';
                        document.getElementById('address').value = address;
                    } else {
                        console.log('Nominatim API error');
                    }
                })
                .catch(error => console.log('Error fetching address:', error));
        }
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






    <!-- Modal -->
    <div id="myModal1" class="modal1" style="display: none;">
        <div class="modal-content1">
            <p class="mb-0">This Service Agreement (hereinafter referred to as "Agreement") has been entered from the date of quotation accepting date & valid till the supply of material or completion of the work (quantity referred from quotation page) is not completed. </p>
             <p class="mb-0">URSBID, a company registered under the Companies Act, 2013, having its registered address at Parewpur, Dharshawa, Shrawasti 271835 </p>
           
                 <div class="go-to-btn mb-4">
                                <a href="{{url('/accept-terms-condition')}}"><small>Read More</small></a>
                            </div>
                            
                           
                                    <div class="input-item input-item-textarea ltn__custom-icon mb-4" >

                                        <input type="checkbox" id="closeModal1" name="term_and_condition" required="" value="term_and_cond_acc"> I have read and accept the terms and
                                        conditions
                                  
                                                                    </div>
        </div>
    </div>


    <script>
    document.getElementById('termAndCondition1').addEventListener('change', function() {
        const modal = document.getElementById('myModal1');
        if (this.checked) {
            modal.style.display = 'block';
        }
    });

    document.getElementById('closeModal1').addEventListener('click', function() {
        const modal = document.getElementById('myModal1');
        modal.style.display = 'none';
    });
    </script>

</body>

</html>