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
					<h1 class="m-0"></h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">&nbsp;</a></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="bg-white bg_Img pt-5 " @if ($userId->cover_img != '') style="background:url('{{ $userId->cover_pic }}')" @endif id="user-cover-pic">
			<div class="bg__Style_01 ">
				<div class="container">
					<div class="user_Details d-flex justify-content-between mb-5">
						<div class="one_user d-flex align-items-center"> <img src="{{ $userId->profile_pic }}" alt="user_img" class="rounded-circle img-fluid image__style w-20">
							<h3 class="ml-3">{{ titleCase($userId->name) }}</h3>
						</div>
					</div>
					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist"> <a class="nav-item nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Post</a> <a class="nav-item nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Spaces</a> </div>
					</nav>
				</div>
			</div>
		</div>
		<div class="container px-4">
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

					<div class="row">
						<div class="col-2">&nbsp;</div>
						<div class="mb-4 mt-3 col-4">
							<button type="button" class="btn btn-primary" name="add_post" id="add-post-btn">Add Post</button>
						</div>
					</div>

					<div class="mb-4 mt-3 post-list">


						@forelse ($posts as $post)
						<div class="row justify-content-center" id="post-{{ $post->id }}-outer">
							<div class="col-lg-8"> @include('admin.user.post_card',["post" => $post, 'userimage' => $userId->profile_pic]) </div>
						</div> @empty
						<div class="card-body pt-4 pb-5 px-5">
							<div class="row justify-content-center">
								<div class="col-lg-4">
									<div class="request1 shadow-sm bg-white">
										<div class="bg-light rounded py-5 height-cus"> </div>
										<div class="p-3">
											<h4 class="mb-2">No Feature Requests</h4>
											<p class="py-2 mb-1 w-100 bg-light rounded"></p>
											<p class="py-2 mb-1 w-100 bg-light rounded"></p>
											<p class="py-2 mb-1 w-50  bg-light rounded"></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforelse
					</div> @if ($posts->lastPage() > $posts->currentPage())

					<div class="col-md-12 text-center my-2">
						<button class="btn btn-success" data-page="1" id="load-more-post">Load More</button>
					</div> @endif
				</div>

				<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
					<div class="row justify-content-center">
						<div class="col-8">
							<div class="row">
								<div class="col-4 mb-4 mt-3">
									<button type="button" class="btn btn-primary" id="add_space" name="add_space">Add Space</button>
								</div>
							</div>
							<div id="spaces-list">
								@forelse ($spacesUsers as $user)

								<div class="card card-shadow mt-3 p-3" id="space-{{ $user->spaces->id }}">
									<div class="card-body d-flex justify-content-between">
										<p>{{ @$user->spaces->name }}</p>
										<p class="text-muted">{{ titleCase($user->role->name) }}</p>
										<div class="dropdown">
											<button class="btn dropdown-toggle dropdown-btn border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fas fa-ellipsis-h"></i> </button>

											<ul class="dropdown-menu" aria-labelledby="edit-dropdown">
												@if($userId->id == $user->spaces->user_id)
												<li>
													<a class="dropdown-item edit-spaces" href="javascript:void(0);" data-id="{{ $user->spaces->id }}">
														<i class="fas fa-edit"></i>
														Edit
													</a>
												</li>
												<li>
													<a class="dropdown-item delete-spaces" href="javascript:void(0);" data-id="{{ $user->spaces->id }}">
														<i class="fas fa-trash"></i>
														Delete
													</a>
												</li>
												@else
												<li>
											<a class="dropdown-item leave-space-btn leave-spaces" href="javascript:void(0);" data-id="{{ $user->spaces->id }}">
                                                <svg class="svg-inline--fa fa-sign-out-alt fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sign-out-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path></svg>Leave Space
											</a>
												</li>
												@endif
											</ul>


										</div>
									</div>
								</div> @empty
								<div class="card card-shadow mt-3 p-3">
									<div class="card-body d-flex justify-content-between">
										<p>No spaces are found</p>
									</div>
								</div> @endforelse
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
</div>
</section>
</div>
<!-- Modal -->
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
						<form id="edit-post"> @csrf
							<input type="hidden" id='post-id'>
							<input type="hidden" id='user_id' name="user_id" value="{{$userId->id}}">
							<input type="text" class="form-control modal-input" placeholder="Add a title" name="title" id="edit-post-titile"> <span class="text-danger"></span>
							<div id="editor-edit-post" style="height: 300px" class="mb-2"> </div> <img width="200" height="200" class="img-thumbnail d-none" id="img-preview-edit">
							<div class="d-flex justify-content-end">
								<label class="btn btn-outline-success me-1" id="btn-post-img-btn" for="post-edit-file-input"> <i class="fas fa-images"></i> </label>
								<input type="file" id="post-edit-file-input" class="d-none" accept="image/*" name="file">
								<button type="button" class="btn btn-outline-success" id="edit-post-btn"> Save </button>
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
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="fas fa-times"></i></span> </button>
			</div>
			<div class="modal-body pt-0">
				<form>
					<div class="form-group">
						<label for="recipient-name-1" class="col-form-label">Name</label>
						<input type="text" placeholder="Choose a helpful name (e.g. Knowledge Base)" class="form-control" id="recipient-name-1">
					</div>
					<div class="form-group">
						<label for="message-text" class="col-form-label">Description</label>
						<textarea cols="5" rows="6" class="form-control" placeholder="What is this collection about? (e.g. Help articles for your community)" id="message-text"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer border-0">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success">Create</button>
			</div>
		</div>
	</div>
</div>
@include('/admin/modals/modal-add-post')
@include('/admin/modals/modal-add-spaces')
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
		/*
		$(this).on('change', '#post-edit-file-input', function(e) {

		formData = new FormData();
		formData.append('post_id', $("#post-id").val());
		formData.append('profile_cover', e.target.files[0]);
		formData.append('_token', "{{ csrf_token() }}")

		let btn = $('#cover-btn');
		$.ajax({
		url:"{{route('admin.change-cover')}}",
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
		$('#user-cover-pic').css("background", url('${res.pic}'));
		alertify.success(res.message);
		},
		error: (err) => {
		btn.find("i").remove();
		btn.prop("disabled", false);
		alertify.success("Server error please try again!")
		}
		});
		});

		*/
		/*
		$(this).on('click', '.leave-space-btn', function() {
		let btn = $(this);
		let id = $(this).data('id');
		alertify.confirm(
		"Confirm",
		"Are you sure to leave this space ?",
		() => leaveSpace(id),
		() => {})
		});
		*/
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
				beforeSend: () => btn.addClass('disabled').append('<i class="fa fa-spin fa-spinner"></i>'),
				success: res => {
					$('.post-list').append(res);
					btn.data('page', page);
					btn.removeClass('disabled').find('.fa-spin').remove();
					if (lastPage == page) btn.remove();
				},
			});
		})
		const quillToollbar = [
			["bold", "italic", "underline", "blockquote"],
			[{
				list: "ordered",
			}, {
				list: "bullet",
			}, ],
			[{
				indent: "-1",
			}, {
				indent: "+1",
			}, ],
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
			}, {
				background: [],
			}, ],
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
		const quill2 = new Quill("#editor-edit-post", {
			placeholder: "Edit post here",
			modules: {
				toolbar: quillToollbar,
			},
			theme: "snow",
		});
		let modalElm = $('#addPostModal');
		/* open post model */
		$(this).on('click', '#add-post-btn', e => {
			let modal = new bootstrap.Modal(modalElm);
			modal.show();
			modalElm.on('hide.bs.modal', e => {
				$('#add-post')[0].reset();
				quill.setText('');
			});
		});
		$(this).on('click', '#submit-post-btn', function() {
			let btn = $(this);
			$('error-name').empty();
			const formData = new FormData(document.querySelector('#add-post'));
			formData.append('discription', quill.root.innerHTML);
			$.ajax({
				url: "{{route('admin.save-post')}}",
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
						$('.post-list').prepend('<div class="row justify-content-center" id="post-' + res.post_id + '-outer"><div class="col-lg-8">' + res.post + '</div></div>');
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
		// edit post
		$(this).on("click", ".edit-post", function() {
			let btn = $(this);
			let id = btn.data("id");
			const modal = new bootstrap.Modal($("#edit-post-model"));
			const formData = new FormData(document.querySelector('#add-post'));
			$.ajax({
				url: "{{route('admin.edit-post', [ 'post'=> ''])}}" + id,
				success: (res) => {
					$("#edit-post").find(`input[name="title"]`).val(res.title);
					$('#edit-post').find('#post-id').val(res.id);
					/* set content in quill editor */
					let delta = quill2.clipboard.convert(res.discription);
					quill2.setContents(delta, "silent");
					if (typeof res.file == "string") {
						$("#edit-post").find("#img-preview-edit").removeClass("d-none").attr("src", res.file_path);
					}
					modal.show();
					$("#edit-post-model").on("hide.bs.modal", (e) => {
						$("#edit-post").find("#img-preview-edit").addClass("d-none")
					});
				},
			});
		});
		/* disable button if title is blanked */
		$(this).on("keyup", '#edit-post-titile', function() {
			let val = $(this).val();
			let btn = $('#edit-post-btn');
			val.length > 0 ? btn.removeClass('disabled') : btn.addClass('disabled');
		});
		/* edit post */
		$(this).on('click', '#edit-post-btn', function() {
			let btn = $(this);
			let id = $('#post-id').val();
			var post_id = $('#post-id').val();
			const data = new FormData(document.querySelector('#edit-post'));
			data.append('title', $('#edit-post-titile').val());
			data.append('discription', $('#editor-edit-post .ql-editor').html());
			data.append('_token', "{{ csrf_token() }}")
			data.append('_method', 'put')
			data.append('user_id', $('#user_id').val())
			data.append('post_id', post_id)
			$.ajax({
				url: "{{route('admin.edit-update', [ 'post'=> ''])}}" + id,
				type: 'post',
				data: data,
				async: true,
				contentType: false,
				enctype: 'multipart/form-data',
				processData: false,
				beforeSend: () => {
					btn.append("<i class='fa fa-spin fa-spinner'></i>").prop("disabled", true);
				},
				success: res => {
					console.log(post_id);
					$('#post-' + post_id + '').replaceWith(res);
					alertify.success("Post updated successfully")
					let modal = bootstrap.Modal.getInstance($("#edit-post-model"));
					modal.hide();
					btn.prop("disabled", false).find('.fa-spin').remove();
				},
				error: err => {
					btn.prop("disabled", false).find('.fa-spin').remove();
					alertify.error('Something went wrong,please try again!')
				}
			})
		})

		/*
		---------------------------------
		===== Section Manage Spaces =====
		---------------------------------
		*/

		$(this).on('click', '.delete-spaces', function(e) {

			var result = confirm("Are you sure you realy want to delete?");
			if (!result) {
				return false;
			}

			var spaces_id = $(this).attr('data-id');
			formData = new FormData();
			formData.append('spaces_id', spaces_id);
			formData.append('_token', "{{ csrf_token() }}");
			$.ajax({
				url: "{{route('admin.delete-spaces')}}",
				type: 'post',
				data: formData,
				processData: false,
				contentType: false,
				success: res => {
					$("#space-" + spaces_id).remove();
				},
				error: (err) => {
					alertify.success("Server error please try again!")
				}
			});
		});


		/*
		--------------------------------------
		========= Manage User Spaces =========
		--------------------------------------
		*/

		/*
		--------------------------------------
		========= Add User Spaces =========
		--------------------------------------
		*/
		let modalElme = $('#addSpacesModal');
		$(this).on('click', '#add_space', e => {
			let modal = new bootstrap.Modal(modalElme);
			modal.show();
			modalElme.on('hide.bs.modal', e => {
				$('#add-space')[0].reset();
				$('#addSpacesModal').find('#space_id').val(0);
				$("#addSpacesModal").find("#addPostModalLabel").text("Create a new space");
				$('#addSpacesModal').find('#submit-space-btn').text("Create");
				quill.setText('');
			});
		});


		$(this).on('click', '#submit-space-btn', function() {
			let btn = $(this);
			$('error-name').empty();
			const formData = new FormData(document.querySelector('#add-space'));
			formData.append('description', quill.root.innerHTML);
			$.ajax({
				url: "{{route('admin.space-post')}}",
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
					if (res.success == true) {
						alertify.success(res.message);
						if (res.space_id != 0) {
							$(res.post).insertAfter('#space-' + res.post_id);
							$('#space-' + res.post_id).remove();
						} else {
							$('#spaces-list').prepend(res.post);
						}
						$("#addSpacesModal").modal('hide');
					} else {
						$('.error-name').text(res.errors.space_title[0])
					}
					btn.text("Create");
					btn.prop("disabled", false);
				},
				error: e => {
					alertify.error("Something went wrong,please try again!")
					btn.text("Create");
					btn.prop("disabled", false);
					$("#addSpacesModal").modal('hide');
				}
			})
		});

		/*
		--------------------------------------
		========= Edit User Spaces =========
		--------------------------------------
		*/

		$(this).on("click", ".edit-spaces", function() {
			let btn = $(this);
			let id = btn.data("id");

			$("#addSpacesModal").find("#addPostModalLabel").text("Edit Space");
			$("#addSpacesModal").find("#space_title").val("");
			$("#addSpacesModal").find("#description").val("");
			$('#addSpacesModal').find('#space_id').val(0);
			$('#addSpacesModal').find('#is_private').prop("checked", false);
			$('#addSpacesModal').find('#is_visible').prop("checked", false);

			const modal = new bootstrap.Modal($("#addSpacesModal"));
			const formData = new FormData(document.querySelector('#add-post'));
			$.ajax({
				url: "{{route('admin.space-show', [ 'post'=> ''])}}" + id,
				success: (res) => {
					$("#addSpacesModal").find("#space_title").val(res.post.name);
					$("#addSpacesModal").find("#description").val(res.post.about);
					$('#addSpacesModal').find('#space_id').val(res.post.id);
					if (res.post.is_private == 1) {
						$('#addSpacesModal').find('#is_private').prop("checked", true);
					}
					if (res.post.is_visible == 0) {
						$('#addSpacesModal').find('#is_visible').prop("checked", true);
					}
					$('#addSpacesModal').find('#submit-space-btn').text("Save");
					modal.show();
					$("#edit-post-model").on("hide.bs.modal", (e) => {
						$("#edit-post").find("#img-preview-edit").addClass("d-none")
					});
				},
			});
		});
		
		/*
		--------------------------------------
		========= Leave User Spaces ==========
		--------------------------------------
		*/		

		$(this).on('click', '.leave-spaces', function(e) {

			var result = confirm("Are you sure you realy want to leave?");
			if (!result) {
				return false;
			}

			var space_id = $(this).attr('data-id');
			formData = new FormData();
			formData.append('user_id', {{$userId->id}});
			formData.append('space_id', space_id);
			formData.append('_token', "{{ csrf_token() }}");

			$.ajax({
				url: "{{route('admin.leave-space')}}",
				type: 'post',
				data: formData,
				processData: false,
				contentType: false,
				success: res => {
					$("#space-" + space_id).remove();
				},
				error: (err) => {
					alertify.success("Server error please try again!")
				}
			});
		});
	})
</script> @endpush