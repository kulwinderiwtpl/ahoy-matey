@extends('layouts.main')
@section('title', 'Setting')
@section('content')
    <main class="mb-5">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <h4 class="mt-4 font-weight-bold fw-bold">Profile Settings</h4>
                    <div class="Tribe_01 mt-4">
                        <div class="card card-shadow form_custom">
                            <div class="card-body pt-4 pb-5 px-3">
                                <form method="POST" action="{{ route('update-setting') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="control-label mb-2">Profile image</label>
                                        <div class="upload-btn-wrapper d-block style-Bck__img" id="profile-pic" @if (auth()->user()->profile_img != '')
                                            style="background: url('{{ auth()->user()->profile_pic }}')"
                                            @endif
                                            >
                                            <label for="profile-pic-input" class="btn">
                                                <svg class="svg-inline--fa fa-camera fa-w-16" aria-hidden="true"
                                                    focusable="false" data-prefix="fa" data-icon="camera" role="img"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                    data-fa-i2svg="">
                                                    <path fill="currentColor"
                                                        d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z">
                                                    </path>
                                                </svg>
                                            </label>
                                            <input type="file" name="profile_pic" id="profile-pic-input"
                                                class="d-none" accept="image/*">
                                        </div>
                                        <small class="mt-2 d-block">Recommended size of at least 400x400 px.
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="control-label mb-2">Name</label>
                                        <input class="form-control mr-sm-2 @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', auth()->user()->name) }}" type="text"
                                            placeholder="Name">

                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="control-label mb-2">Tagline</label>
                                        <input class="form-control mr-sm-2 @error('tagline') is-invalid @enderror"
                                            type="text" placeholder="Tagline" name="tagline"
                                            value="{{ old('tagline', auth()->user()->tagline) }}">
                                        @error('tagline')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group mb-3">
                                        <label class="control-label mb-2">Email</label>
                                        <input class="form-control mr-sm-2" type="text" placeholder=""
                                            value="{{ auth()->user()->email }}" disabled="">
                                    </div>
                                    <button class="btn btn-success px-4 btn-md mt-2" type="submit">Send</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $(this).on('change', '#profile-pic-input', (e) => {

                const reader = new FileReader();
                reader.onload = () => $('#profile-pic').css('background-image', `url('${reader.result}')`);;
                reader.readAsDataURL(e.target.files[0])
            })
        });
    </script>
@endpush

{{--  --}}
