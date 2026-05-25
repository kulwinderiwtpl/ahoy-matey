<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title')</title>
    <meta name="csrf" content="{{ csrf_token() }}">
    <link href="{{ asset('/assets/css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.core.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 49px;
            height: 20px
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: #fff;
            -webkit-transition: .4s;
            transition: .4s
        }

        input:checked+.slider {
            background-color: #2196f3
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196f3
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px)
        }

        .slider.round {
            border-radius: 34px
        }

        .slider.round:before {
            border-radius: 50%
        }
    </style>
    @yield('page_style')
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand justify-content-between navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3 h3" href="#2">Ahoy-Matey</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('includes.sidebar')
        </div>
        <div id="layoutSidenav_content">
            @yield('content')
        </div>
    </div>

    {{-- invites modal --}}

    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" id="model-body">
        </div>
    </div>

    {{-- search modal --}}
    <div class="modal fade" id="search-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" id="model-body">
            <div class="modal-content">
                <div class="modal-header border-0 d-flex align-items-center">
                    <div id="search-loading">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="search" class="form-control" placeholder="Search" id="search" autocomplete="off">
                </div>
                <div class="modal-body pt-0">
                    <div class="search-result"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- edit post-model --}}
    <div class="modal fade" id="edit-post-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="edit-post">
                                @csrf
                                <input type="hidden" id='post-id'>
                                <input type="text" class="form-control modal-input" placeholder="Add a title" name="title" id="edit-post-titile">
                                <span class="text-danger"></span>
                                <div id="editor-edit-post" style="height: 300px" class="mb-2">
                                </div>
                                <img width="200" height="200" class="img-thumbnail d-none" id="img-preview-edit">
                                <div class="d-flex justify-content-end">
                                    <label class="btn btn-outline-success me-1" id="btn-post-img-btn" for="post-edit-file-input">
                                        <i class="fas fa-images"></i>
                                    </label>
                                    <input type="file" id="post-edit-file-input" class="d-none" accept="image/*" name="file">
                                    <button type="button" class="btn btn-outline-success" id="edit-post-btn">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="modal-title">
                        <h4 id="exampleModalLabel">Create collection</h4>
                        <h5>A collection is a group of spaces</h5>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form id="front-add-space"> @csrf
                        <span class="text-danger error-name"></span>
                        <div class="form-group">
                            <label for="recipient-name-1" class="col-form-label">Name</label>
                            <input type="text" name="space_title" placeholder="Choose a helpful name (e.g. Knowledge Base)" class="form-control" id="recipient-name-1">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Make private (Only members can see who's in the space and what they post.)</label>
                            <label class="switch">
                                <input id="is_private" name="is_private" checked type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Hide space (Hide this space from non-space members.)</label>
                            <label class="switch">
                                <input id="is_visible" name="is_visible" checked type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Description</label>
                            <textarea cols="5" rows="6" name="description" class="form-control" placeholder="What is this collection about? (e.g. Help articles for your community)" id="message-text"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="front-spaces">Create</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content px-3">
                <div class="modal-header border-0">
                    <div class="modal-title">
                        <h4 id="exampleModalLabel">Help & Community</h4>

                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="d-flex align-items-baseline">
                        <i class="fas fa-book text-success"></i>
                        <div class="ml-2">
                            <h5>Knowledge Base</h5>
                            <p class="p-0 m-0">Learn everything there is to know about Tribe</p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-baseline">
                        <i class="fas fa-book text-success"></i>
                        <div class="ml-2">
                            <h5>Knowledge Base</h5>
                            <p class="p-0 m-0">Learn everything there is to know about Tribe</p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-baseline">
                        <i class="fas fa-book text-success"></i>
                        <div class="ml-2">
                            <h5>Knowledge Base</h5>
                            <p class="p-0 m-0">Learn everything there is to know about Tribe</p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-baseline">
                        <i class="fas fa-book text-success"></i>
                        <div class="ml-2">
                            <h5>Knowledge Base</h5>
                            <p class="p-0 m-0">Learn everything there is to know about Tribe</p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex align-items-baseline">
                        <i class="fas fa-book text-success"></i>
                        <div class="ml-2">
                            <h5>Knowledge Base</h5>
                            <p class="p-0 m-0">Learn everything there is to know about Tribe</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h4>Still can’t find what you’re looking for?</h4>
                        <p>Our team’s always available for a chat. Let us know how we can help.</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success">Chat with us</button>
                </div>
            </div>
        </div>
    </div>
    @routes
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset('/assets/js/scripts.js') }}"></script>
    <script src={{ asset('/assets/js/custom.js') }}></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    @stack('scripts')
</body>

</html>