@extends('admin.layouts.app') @section('content')
<style type="text/css">
    .w-20 {
        width: 60px;
        height: 60px;
    }

    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .btn {
        border: 2px solid gray;
        color: gray;
        background-color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
    }

    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .ml-3 {
        margin-left: 25px;
    }

    .cover-pic {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .image__style {
        object-fit: cover !important;
        object-position: center !important;
    }

    .icon_bgColor {
        color: #fff;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .post-discription p span {
        color: rgb(220 220 220) !important;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">&nbsp;</a></li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-12">
                    @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('error') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h5>Update Profile</h5>
                <form id="formId" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <label for="inputEmail">Name</label>
                                <input class="form-control" id="name" required="required" type="text" placeholder="Name" name="name" value="{{ auth()->user()->name }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <label for="inputEmail">Email address</label>
                                <input class="form-control" id="inputEmail" required="required" type="email" placeholder="name@example.com" name="email" value="{{ auth()->user()->email }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" name="submit_profile" class="btn btn-primary" value="Save" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <h5>Change Password</h5>
                <form id="passId" method="post" action="{{ route('admin.password') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <label for="old_pass">Old Password</label>
                                <input class="form-control" id="old_pass" required="required" type="text" placeholder="Old Password" name="old_pass" value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <label for="new_pass">New Password</label>
                                <input class="form-control" id="new_pass" required="required" type="text" placeholder="12345678" name="new_pass" value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <label for="confirm_pass">Confirm Password</label>
                                <input class="form-control" id="confirm_pass" required="required" type="text" placeholder="12345678" name="confirm_pass" value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" name="submit_profile" class="btn btn-primary" value="Save" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection @push('scripts')
<script>
    $(document).ready(function() {

        $(this).on('click', '.delete-post', function(e) {

            var result = confirm("Are you sure you realy want to delete?");
            if (!result) {
                return false;
            }

            var post_id = $(this).attr('data-id');
            formData = new FormData();
            formData.append('post_id', post_id);
            formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                url: "{{route('admin.delete-post')}}",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: res => {
                    $("#post-" + post_id + "-outer").remove();
                },
                error: (err) => {
                    alertify.success("Server error please try again!")
                }
            });
        });

    })
</script> @endpush