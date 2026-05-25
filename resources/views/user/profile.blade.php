@extends('layouts.main')
@section('title', 'Setting')
@section('page_style')
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
</style>
@endsection
@section('content')
<main>
    @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif
    <div class="bg-white bg_Img pt-5 " @if (auth()->user()->cover_img != '') style="background:url('{{ auth()->user()->cover_pic }}')" @endif
        id="user-cover-pic">
        <div class="bg__Style_01 ">
            <div class="container">
                <div class="user_Details d-flex justify-content-between mb-5">
                    <div class="one_user d-flex align-items-center">
                        <img src="{{ auth()->user()->profile_pic }}" alt="user_img" class="rounded-circle img-fluid image__style w-20">
                        <h3 class="ml-3">{{ titleCase(auth()->user()->name) }}</h3>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="upload-btn-wrapper">
                            <button class="btn" id="cover-btn">Add Cover Image</button>
                            <input type="file" name="myfile" id="cover-pic-input" accept="image/*" />
                        </div>
                        <a class="nav-link dropdown-toggle text-secondary p-2 rounded" href="{{ route('setting') }}"><i class="fas fa-cog"></i></a>
                    </div>

                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Post</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Spaces</a>
                    </div>
                </nav>

            </div>
        </div>
    </div>
    <div class="container px-4">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="mb-4 mt-3 post-list">
                    @forelse ($posts as $post)
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            @include('includes.post_card',$post)
                        </div>
                    </div>
                    @empty
                    <div class="card-body pt-4 pb-5 px-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-4">
                                <div class="request1 shadow-sm bg-white">
                                    <div class="bg-light rounded py-5 height-cus">

                                    </div>
                                    <div class="p-3">
                                        <h4 class="mb-2">Feature Requests</h4>
                                        <p class="py-2 mb-1 w-100 bg-light rounded"></p>
                                        <p class="py-2 mb-1 w-100 bg-light rounded"></p>
                                        <p class="py-2 mb-1 w-50  bg-light rounded"></p>
                                        <div class="ratings mt-2 d-flex align-items-center">
                                            <i class="fas fa-users me-2 text-secondary"></i> <span class="text-secondary ">630 Members</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 ">
                                <div class="treding_section text-center mt-5">
                                    <h3>No trending posts</h3>
                                    <p class="mt-2">This section displays the 10 most popular posts within the
                                        past month across your spaces.</p>
                                    <a href="#2" class="btn btn-secondary px-4 btn-md mt-2">Explore Spaces</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
                @if ($posts->lastPage() > $posts->currentPage())
                <div class="col-md-12 text-center my-2">
                    <button class="btn btn-success" data-page="1" id="load-more-post">Load More</button>
                </div>
                @endif
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="row justify-content-center">
                    <div class="col-8">
                        @forelse ($spacesUsers as $user)
                        <div class="card card-shadow mt-3 p-3" id="space-{{ $user->id }}">
                            <div class="card-body d-flex justify-content-between">
                                <p>{{ $user->spaces->name }}</p>
                                <p class="text-muted">{{ titleCase($user->role->name) }}</p>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle dropdown-btn border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item leave-space-btn" href="javascript:void(0);" data-id="{{ $user->id }}">
                                                <i class="fas fa-sign-out-alt"></i>
                                                Leave Space
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @empty

                        @endforelse
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

        $(this).on('change', '#cover-pic-input', function(e) {

            formData = new FormData();

            formData.append('profile_cover', e.target.files[0]);
            formData.append('_token', "{{ csrf_token() }}")
            console.log(route('change-cover'));
            let btn = $('#cover-btn');
            $.ajax({
                url: route('change-cover'),
                type: 'post',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: () => {
                    btn.append("<i class='fa fa-spin fa-spinner'></i>");
                    btn.prop("disabled", true);
                },
                success: res => {
                    btn.find("i").remove();
                    btn.prop("disabled", false);
                    $('#user-cover-pic').css("background", `url('${res.pic}')`);
                    alertify.success(res.message);
                },
                error: (err) => {
                    btn.find("i").remove();
                    btn.prop("disabled", false);
                    alertify.success("Server error please try again!")
                }
            });
        });

        /* leave space functionality */
        $(this).on('click', '.leave-space-btn', function() {
            let btn = $(this);
            let id = $(this).data('id');
            alertify.confirm(
                "Confirm",
                "Are you sure to leave this space ?",
                () => leaveSpace(id),
                () => {})
        });

        $(this).on('click', '#load-more-post', function() {
            let btn = $(this);
            let page = $(this).data('page');
            let lastPage = Number("{{ $posts->lastPage() }}");
            page += 1;
            $.ajax({
                url: route('profile', {
                    _query: {
                        page: page
                    }
                }),
                beforeSend: () => btn.addClass('disabled').append(
                    `<i class='fa fa-spin fa-spinner'></i>`),
                success: res => {
                    $('.post-list').append(res);
                    btn.data('page', page);
                    btn.removeClass('disabled').find('.fa-spin').remove();
                    if (lastPage == page) btn.remove();
                },
            });
        })
    })
</script>
@endpush