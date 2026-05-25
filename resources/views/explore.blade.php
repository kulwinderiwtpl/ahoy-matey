@extends('layouts.main')
@section('title', 'Explore')
@section('content')
    <main class="mb-5">
        <div class="container px-4">

            <h3 class="mt-4 font-weight-bold fw-bold">Explore</h3>
            <p>Let’s get started with your Circle!</p>
            <div class="Tribe_01 mt-5">
                <div class="card">
                    <div class="card-body pt-4 pb-5 px-5" data-bs-toggle="modal" data-bs-target="#search-modal">
                        <h4>Looking for something?</h4>
                        <input class="form-control mr-sm-2 mt-4" type="search" placeholder="Search" aria-label="Search">
                    </div>
                </div>
                <div class="card  mb-4 mt-3">
                    <div class="card-body pt-4 pb-5 px-5">
                        <h4 class="mb-2">Trending posts</h4>
                        <p class="text-secondary">Active discussions inside the community</p>
                        <div class="post-list">
                            @forelse ($posts as $post)
                                @include('includes.post_card',$post)
                            @empty
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
                                                    <svg class="svg-inline--fa fa-users fa-w-20 me-2 text-secondary"
                                                        aria-hidden="true" focusable="false" data-prefix="fas"
                                                        data-icon="users" role="img" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 640 512" data-fa-i2svg="">
                                                        <path fill="currentColor"
                                                            d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z">
                                                        </path>
                                                    </svg>
                                                    <!-- <i class="fas fa-users me-2 text-secondary"></i> Font Awesome fontawesome.com -->
                                                    <span class="text-secondary ">630 Members</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 ">
                                        <div class="treding_section text-center mt-5">
                                            <h3>No trending posts</h3>
                                            <p class="mt-2">This section displays the 10 most popular posts within the
                                                past month
                                                across your spaces.</p>
                                            <a href="#2" class="btn btn-success px-4 btn-md mt-2">Explore Spaces</a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
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

        });

    </script>
@endpush
