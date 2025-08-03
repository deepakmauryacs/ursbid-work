@extends('ursbid-admin.layouts.app')
@section('title', 'Website Settings')

@section('content')
<style>
    .code-box {
        font-family: 'Courier New', Courier, monospace;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        white-space: pre;
    }
</style>
<div class="container-fluid">

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Website Settings</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Website Settings</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="webSettingsForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Site Name -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="site_name" class="form-label fw-semibold">Site Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $setting->site_name ?? '') }}" placeholder="Enter Site Name">
                            </div>
                        </div>

                        <!-- Site Description -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="site_description" class="form-label fw-semibold">Site Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="site_description" name="site_description" rows="3" placeholder="Enter Site Description">{{ old('site_description', $setting->site_description ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Site Keywords -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="site_keywords" class="form-label fw-semibold">Site Keywords</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="site_keywords" name="site_keywords" rows="2" placeholder="Enter SEO Keywords">{{ old('site_keywords', $setting->site_keywords ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Site Logo 1 -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="site_logo_1" class="form-label fw-semibold">Site Logo 1</label>
                            </div>
                            <div class="col-md-8">
                                @if(!empty($setting->site_logo_1))
                                    <div class="mb-2">
                                       <img src="{{ asset('public/' . $setting->site_logo_1) }}" alt="Header Logo" height="80" style="border: 1px solid #ccc;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="site_logo_1" name="site_logo_1" accept="image/*">
                            </div>
                        </div>

                        <!-- Site Logo 2 -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="site_logo_2" class="form-label fw-semibold">Site Logo 2</label>
                            </div>
                            <div class="col-md-8">
                                @if(!empty($setting->site_logo_2))
                                    <div class="mb-2">
                                        <img src="{{ asset('public/' . $setting->site_logo_2) }}" alt="Site Logo 2" height="80" style="border: 1px solid #ccc;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="site_logo_2" name="site_logo_2" accept="image/*">
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="site_favicon" class="form-label fw-semibold">Site Favicon</label>
                            </div>
                            <div class="col-md-8">
                                @if(!empty($setting->site_favicon))
                                    <div class="mb-2">
                                        <img src="{{ asset('public/' . $setting->site_favicon) }}" alt="Favicon" height="32" width="32" style="border: 1px solid #ccc;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/x-icon,image/png,image/svg+xml">
                            </div>
                        </div>

                        <!-- Copyright -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="copyright_text" class="form-label fw-semibold">Copyright Text</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" id="copyright_text" name="copyright_text" rows="3" placeholder="Enter Copyright Text">{{ old('copyright_text', $setting->copyright_text ?? '') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Custom Code Header -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="custom_code_header" class="form-label fw-semibold">Custom Code Header</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control code-box" id="custom_code_header" name="custom_code_header" rows="5" placeholder="Enter custom header scripts or meta tags">{{ old('custom_code_header', $setting->custom_code_header ?? '') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Custom Code Footer -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="custom_code_footer" class="form-label fw-semibold">Custom Code Footer</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control code-box" id="custom_code_footer" name="custom_code_footer" rows="5" placeholder="Enter custom footer scripts (e.g., analytics, custom JS)">{{ old('custom_code_footer', $setting->custom_code_footer ?? '') }}</textarea>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="row mb-0">
                            <div class="col-md-12 text-end">
                                <button id="saveBtn" type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$(function() {
    $('#webSettingsForm').on('submit', function(e) {
        e.preventDefault();
        $('#saveBtn').attr('disabled', true).text('Saving...');
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('super-admin.web-settings.save') }}",
            method: "POST",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res) {
                toastr.success(res.message);
                $('#saveBtn').attr('disabled', false).text('Save Settings');
                location.reload();
            },
            error: function(xhr) {
                let err = 'Error saving settings';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                }
                toastr.error(err, 'Error');
                $('#saveBtn').attr('disabled', false).text('Save Settings');
            }
        });
    });
});
</script>
@endsection
