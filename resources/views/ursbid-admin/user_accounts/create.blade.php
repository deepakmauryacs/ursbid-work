@extends('ursbid-admin.layouts.app')
@section('title', 'Add ' . $userType)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Add {{ $userType }}</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.accounts.index', $type) }}">{{ $userType }} List</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Add {{ $userType }}</h4>
                </div>
                <form id="userForm">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback" data-field="email"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                            <div class="invalid-feedback" data-field="phone"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Joining Date</label>
                            <input type="text" name="created_at" class="form-control" placeholder="dd-mm-yyyy" required>
                            <div class="invalid-feedback" data-field="created_at"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            <div class="invalid-feedback" data-field="status"></div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#userForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        const datePattern = /^\d{2}-\d{2}-\d{4}$/;
        const joinDate = $('input[name="created_at"]').val();
        if(!datePattern.test(joinDate)){
            $('[data-field="created_at"]').text('Date must be in dd-mm-yyyy format.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.accounts.store', $type) }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
                $('#userForm')[0].reset();
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    for(let field in errors){
                        $('[data-field="'+field+'"]').text(errors[field][0]);
                    }
                }else{
                    toastr.error('Creation failed');
                }
            }
        });
    });
});
</script>
@endpush
