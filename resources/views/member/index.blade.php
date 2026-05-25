@extends('layouts.main')
@section('title', titleCase($user->name))
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

    </style>
@endsection
@section('content')

    <main>
        <div class="bg-white pt-5 ">
            <div class="container">
                <div>
                    <img class="img-fluid cover-pic @if ($user->cover_img == '') d-none @endif" @if ($user->cover_img != '')
                        src="{{ $user->cover_pic }}"
                    @endif
                    >
                </div>
                <div class="user_Details d-flex justify-content-between mb-5">
                    <div class="one_user d-flex align-items-center">
                        <img src="{{ $user->profile_pic }}" alt="user_img" class="rounded-circle img-fluid w-20">
                        <h3 class="ml-3">{{ titleCase($user->name) }}</h3>
                    </div>
                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home"
                            role="tab" aria-controls="nav-home" aria-selected="true">Post</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile"
                            role="tab" aria-controls="nav-profile" aria-selected="false">Spaces</a>
                    </div>
                </nav>

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
                                                    <i class="fas fa-users me-2 text-secondary"></i> <span
                                                        class="text-secondary ">630 Members</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 ">
                                        <div class="treding_section text-center mt-5">
                                            <h3>No Posts</h3>
                                            <p class="mt-2 text-muted">{{ titleCase($user->name) }} not added any post yet
                                            </p>
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
                                                    <p class="py-2 mb-1 w-100 bg-light rounded"></p>
                                                    <p class="py-2 mb-1 w-100 bg-light rounded"></p>
                                                    <p class="py-2 mb-1 w-50  bg-light rounded"></p>
                                                    <div class="ratings mt-2 d-flex align-items-center">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 ">
                                            <div class="treding_section text-center mt-5">
                                                <h3>No Spaces</h3>
                                                <p class="mt-2 text-muted">{{ titleCase($user->name) }} isn’t a member of
                                                    any spaces yet.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>



        </div>
        </div>
    </main>
@endsection
