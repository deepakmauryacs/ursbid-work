@extends('seller.layouts.app')
@section('title', 'two')

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

                <div class="container-fluid">
                <div
                    class=" checkout wizard1 wizard7 global-shadow px-sm-50 px-20 py-sm-50 py-30 mb-30 bg-white radius-xl w-100">
                    <div class="row justify-content-center">
                        <div class="col-xl-8">
                            <div class="checkout-progress-indicator content-center">

                                @include('admin.wizz.sidebar')


                            </div><!-- checkout -->
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="card card-default card-md mb-4">
                                        <!-- <div class="card-header">
                            <h6>Add wizz     </h6>
                        </div> -->
                                        <div class="card-body py-md-25">
                                        <form method="post" action="{{ url('admin/wizz/update/'.$blog->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                Field 1 </label>
                                                <input type="hidden" name='last_id' value="{{ $blog->id }}">
                                            <input type="text"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="field1" name="field1" value="{{ $blog->field1 }}"> 
                                        </div>
                                        @if ($errors->has('field1'))
                                        <span class="text-danger">{{ $errors->first('field1') }}</span>
                                        @endif
                                    </div>
                                   
                               
                                    <div class="col-md-8">

                                    <button type="submit" class="px-4 btn-sm btn-primary">Next</button>
                                </div>


                            </form>
                                        </div>

                                    </div>
                                </div>
                                <!-- ends: .card -->
                            </div>
                        </div><!-- ends: col -->
                    </div>
                </div><!-- End: .global-shadow-->
            </div>
           


        </div>
    </div>
</div>
</div>

@endsection