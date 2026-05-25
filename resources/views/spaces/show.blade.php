@extends('layouts.main')
@section('title', 'Space')
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

        .btn.main_Btn {
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

        .sb-nav-fixed #layoutSidenav #layoutSidenav_content {
            top: 0px;
        }

        .form-control {
            border: none;
        }

        .form-control:focus {
            border: none;
            box-shadow: none;
        }

        .reaction {
            cursor: pointer;
        }

        .w-100 {
            width: 100%;
        }

        button#space-cover-btn {
            background: #efefef;
            border-color: #efefef;
        }

    </style>
@endsection
@section('content')
    <main>
        <div class="bg-white bg_Img pt-5" @if ($space->cover_img != '') style="background:url('{{ $space->cover_pic }}')" @endif
            id="cover-pic-space">
            <div class="bg__Style_01 pt-2">
                <div class="container">
                    <div class="user_Details d-flex justify-content-between mb-5">
                        <div class="one_user d-flex">
                            <svg class="svg-inline--fa fa-comment-dots fa-w-16 fa-2x" aria-hidden="true" focusable="false"
                                data-prefix="far" data-icon="comment-dots" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M144 208c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm112 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm112 0c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zM256 32C114.6 32 0 125.1 0 240c0 47.6 19.9 91.2 52.9 126.3C38 405.7 7 439.1 6.5 439.5c-6.6 7-8.4 17.2-4.6 26S14.4 480 24 480c61.5 0 110-25.7 139.1-46.3C192 442.8 223.2 448 256 448c141.4 0 256-93.1 256-208S397.4 32 256 32zm0 368c-26.7 0-53.1-4.1-78.4-12.1l-22.7-7.2-19.5 13.8c-14.3 10.1-33.9 21.4-57.5 29 7.3-12.1 14.4-25.7 19.9-40.2l10.6-28.1-20.6-21.8C69.7 314.1 48 282.2 48 240c0-88.2 93.3-160 208-160s208 71.8 208 160-93.3 160-208 160z">
                                </path>
                            </svg>
                            <div class="right_content pl-2">
                                <h5 class="mb-1">{{ $space->name }}</h5>
                                <div class="d-flex">
                                    <small class="text_title">Space</small>
                                    <small class="text_styleDot px-2"> · </small>
                                    @if ($space->members->count() > 0)
                                        <small class="text_totalMembers">{{ $space->members->count() }} members</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="upload-btn-wrapper">
                                <button class="btn main_Btn" id="space-cover-btn">Add Cover Image</button>
                                <input type="file" name="myfile" id="space-cover-input" accept="image/*">
                            </div>
                            <a class="nav-link dropdown-toggle text-secondary p-2 rounded" id="navbarDropdown" href="#"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"><svg
                                    class="svg-inline--fa fa-cog fa-w-16" aria-hidden="true" focusable="false"
                                    data-prefix="fas" data-icon="cog" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active show" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home"
                                role="tab" aria-controls="nav-home" aria-selected="true">Discussion</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile"
                                role="tab" aria-controls="nav-profile" aria-selected="false">About</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact"
                                role="tab" aria-controls="nav-contact" aria-selected="false">Members</a>
                        </div>
                    </nav>

                </div>
            </div>
        </div>
        <div class="row justify-content-center w-100">
            <div class="col-md-8">
                <div class="container px-4">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="  mb-4 mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card-body pt-4 pb-5 px-3">
                                            <div class="card-main mb-5">
                                                <div class="card card-shadow">
                                                    <div class="card-body p-3">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="d-flex align-items-center btn"
                                                                    id="add-post-btn">
                                                                    <img src="{{ auth()->user()->profile_pic }}"
                                                                        class="icon_bgColor">
                                                                    <div class="text_rightSide pl-3">
                                                                        <h6 class="mb-0">What's on your mind</h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="post-list">
                                                    @forelse ($posts as $post)
                                                        @include('includes.post_card',$post)
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="row mt-5 justify-content-center">
                                <div class="col-md-7">
                                    <div class="card my-4 card-shadow">
                                        <div class="card-body">
                                            <h4 class="mb-2">About</h4>
                                            <p class="mb-4">{{ $space->about }}</p>
                                            <div class="d-flex align-items-baseline ">
                                                <svg class="svg-inline--fa fa-eye fa-w-18" aria-hidden="true"
                                                    focusable="false" data-prefix="fas" data-icon="eye" role="img"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                                                    data-fa-i2svg="">
                                                    <path fill="currentColor"
                                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                    </path>
                                                </svg>
                                                <div class="main_contegev ml-2">
                                                    <h5>{{ $space->is_private ? 'Private' : 'Public' }}</h5>
                                                    <p>{{ $space->is_private ? 'Only added members' : 'Anyone' }} can view
                                                        and browse this space.</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-baseline mt-3">
                                                <svg class="svg-inline--fa fa-bars fa-w-14" aria-hidden="true"
                                                    focusable="false" data-prefix="fas" data-icon="bars" role="img"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                    data-fa-i2svg="">
                                                    <path fill="currentColor"
                                                        d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z">
                                                    </path>
                                                </svg>
                                                <div class="main_contegev ml-2">
                                                    <h5>{{ $space->is_visible ? 'Visible' : 'Invisible' }}</h5>
                                                    <p>{{ $space->is_visible ? 'Only added members' : 'Anyone' }} can view
                                                        and browse this space.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="row mt-5 justify-content-center">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4>{{ $space->members->count() }} members</h4>
                                    </div>
                                    <div class="card mt-3 card-shadow">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Space Role</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($space->members as $member)
                                                        <tr>
                                                            <td class="col d-flex align-items-center">
                                                                <img src="{{ $member->user->profile_pic }}" width="50"
                                                                    height="50" class="rounded-circle me-2">
                                                                {{ titleCase($member->user->name) }}
                                                            </td>
                                                            <td class="col">
                                                                {{ titleCase($member->role->name) }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="col" colspan="2">No member added yet</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Modal -->
    <div class="modal fade" id="add-post-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ auth()->user()->profile_pic }}" width="50" height="50" class="rounded-circle me-2">
                        <div class="d-flex flex-column">
                            <span>{{ auth()->user()->name }}</span>
                            <span>Posting in {{ $space->name }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form id="add-post">
                                @csrf
                                <input type="hidden" name="space_id" value="{{ $space->id }}">
                                <input type="text" class="form-control" placeholder="Add a title" name="title">
                                <span class="text-danger error-name"></span>
                                <div id="editor" style="height: 300px" class="mb-2">
                                </div>
                                <img width="200" height="200" class="img-thumbnail d-none" id="img-preview">
                                <div class="d-flex justify-content-end">
                                    <label class="btn btn-outline-success me-1" id="btn-post-edit-img-btn"
                                        for="post-file-input">
                                        <i class="fas fa-images"></i>
                                    </label>
                                    <input type="file" id="post-file-input" class="d-none" accept="image/*" name="file">
                                    <button type="button" class="btn btn-outline-success" id="submit-post-btn">
                                        Post
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            let modalElm = $('#add-post-model');
            let spaceId = $(`input[name="space_id"]`).val();
            const quillToollbar = [
                ["bold", "italic", "underline", "blockquote"],
                [{
                        list: "ordered",
                    },
                    {
                        list: "bullet",
                    },
                ],
                [{
                        indent: "-1",
                    },
                    {
                        indent: "+1",
                    },
                ],
                [{
                    direction: "rtl",
                }, ],

                [{
                    size: ["small", false, "large", "huge"],
                }, ],
                [{
                    header: [1, 2, 3, 4, 5, 6, false],
                }, ],

                [{
                        color: [],
                    },
                    {
                        background: [],
                    },
                ],
                [{
                    font: [],
                }, ],
                [{
                    align: [],
                }, ],

                ["clean"],
            ];

            /* quill init */
            const quill = new Quill("#editor", {
                placeholder: "Add post here",
                modules: {
                    toolbar: quillToollbar,
                },
                theme: "snow",
            });


            /* open post model */
            $(this).on('click', '#add-post-btn', e => {

                let modal = new bootstrap.Modal(modalElm);
                modal.show();

                modalElm.on('hide.bs.modal', e => {
                    $('#add-post')[0].reset();
                    quill.setText('');
                });


            });

            /*img preview */
            $(this).on('change', '#post-file-input', e => {

                const reader = new FileReader();
                reader.onload = () => $('#cover-pic-space').css('background-image',
                    `url('${reader.result}')`);
                reader.readAsDataURL(e.target.files[0]);

            });

            /* add post */
            $(this).on('click', '#submit-post-btn', function() {
                let btn = $(this);
                $('error-name').empty();
                const formData = new FormData(document.querySelector('#add-post'));
                formData.append('discription', quill.root.innerHTML);

                $.ajax({
                    url: route('posts.store'),
                    type: 'post',
                    data: formData,
                    async: true,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    beforeSend: () => {
                        btn.append("<i class='fa fa-spin fa-spinner'></i>");
                        btn.prop("disabled", true);
                    },
                    success: res => {
                        if (res.success) {

                            alertify.success(res.message);
                            $('.post-list').prepend(res.post);
                            bootstrap.Modal.getOrCreateInstance(modalElm).hide();
                        } else {
                            $('.error-name').text(res.errors.title[0])
                        }
                        btn.text("Post");
                        btn.prop("disabled", false);
                    },
                    error: e => {
                        alertify.error("Something went wrong,please try again!")
                        btn.text("Post");
                        btn.prop("disabled", false);
                        bootstrap.Modal.getOrCreateInstance(modalElm).hide();
                    }
                })

            });

            /* change cover */

            $(this).on('change', '#space-cover-input', function(e) {

                formData = new FormData();

                formData.append('cover_img', e.target.files[0]);
                formData.append('_token', "{{ csrf_token() }}")

                let btn = $('#space-cover-btn');

                $.ajax({
                    url: route('spaces-cover', {
                        id: spaceId
                    }),
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
                        btn.find(".fa-spin").remove().prop("disabled", false);;
                        $('#cover-pic-space').css('background-image',
                            `url('${res.pic}')`)
                        alertify.success(res.message);
                    },
                    error: (err) => {
                        btn.find(".fa-spin").remove();
                        btn.prop("disabled", false);
                        alertify.error("Server error please try again!")
                    }
                });
            });

        });

    </script>
@endpush
