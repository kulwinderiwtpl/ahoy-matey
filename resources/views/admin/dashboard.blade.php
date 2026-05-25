@extends('admin.layouts.app')

@section('content')

<style>
	.cover-image {
		width: 100px;
		margin-top: 10px;
	}
	.fa fa-close::before {
	  content: "\e014";
	}	
		.glyphicon.glyphicon-ok {
		  content: "\e013";
		  position: relative;
		  top: 1px;
		  display: inline-block;
		  font-family: 'Glyphicons Halflings';
		  font-style: normal;
		  font-weight: 400;
		  line-height: 1;
		}
	.glyphicon-ok::before {
	  content: "\e013";
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
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-sm-6 col-md-3">
					<div class="info-box mb-3">
						<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Total Users</span>
							<span class="info-box-number">({{ count($users) }})</span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header border-transparent">
							<h3 class="card-title">Users</h3>

							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
								<button type="button" class="btn btn-tool" data-card-widget="remove">
									<i class="fas fa-times"></i>
								</button>
							</div>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table id="example" class="table table-striped" style="width:100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											{{-- <th>Phone Number</th> --}}
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($users as $user)
										<tr>
											<td><a href="javascript:void(0)" data-id="{{$user->id}}" class="view-user">{{ $user->name }}</a></td>
											<td>{{ $user->email }}</td>
											{{-- <td><span class="badge badge-success">{{ $user->phone_number }}</span></td> --}}
											<td>
												<a href="javascript:void(0)" data-id="{{$user->id}}" class="edit-user btn btn-sm btn-info float-left">Edit</a>
												<a href="javascript:void(0)" data-id="{{$user->id}}" class="delete-user btn btn-sm btn-secondary float-left" style="margin-left:3px;">Delete</a>
												<a href="{{route('admin.view-post', ['id'=>$user->id])}}" data-id="{{$user->id}}" class="view-user btn btn-sm btn-primary float-left" style="margin-left:3px;">View Posts</a>
											</td>
										</tr>
										@endforeach
									</tbody>									
								</table>
								{{-- <table class="table m-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Phone Number</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($users as $user)
										<tr>
											<td><a href="pages/examples/invoice.html">{{ $user->name }}</a></td>
											<td>{{ $user->email }}</td>
											<td><span class="badge badge-success">{{ $user->phone_number }}</span></td>
											<td>
												<a href="javascript:void(0)" data-id="{{$user->id}}" class="edit-user btn btn-sm btn-info float-left">Edit</a>
												<a href="javascript:void(0)" data-id="{{$user->id}}" class="delete-user btn btn-sm btn-secondary float-right">Delete</a>
												<a href="{{route('admin.view-post', ['id'=>$user->id])}}" data-id="{{$user->id}}" class="view-user btn btn-sm btn-primary float-right" style="margin-right:3px;">View Posts</a>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table> --}}
							</div>

						</div>
						<div class="card-footer clearfix">

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit User Info</h5>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="formId" method="post" enctype="multipart/form-data">
					@csrf
					<input type="hidden" id="id" name="id" />
					<div class="row">
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="inputEmail">Name</label>
								<input class="form-control" id="name" required="required" type="text" placeholder="Name" name="name" value="" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="inputEmail">Email address</label>
								<input class="form-control" id="inputEmail" required="required" type="email" placeholder="name@example.com" name="email" value="" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="inputEmail">Tagline</label>
								<input class="form-control" id="tagline" required="required" type="text" placeholder="Available.." name="tagline" value="" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="phone_number">Phone</label>
								<input class="form-control" id="phone_number" type="number" placeholder="4354544354" name="phone_number" value="" />

							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="cover_image">Cover Image</label>
								<input class="form-control" id="cover_image" type="file" name="cover_image" />
								<div class="display_image"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="profile_pic">Profile Image</label>
								<input class="form-control" id="profile_pic" type="file" name="profile_pic" />
								<div class="display_proimage"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating col-md-4">
								<label for="profile_pic">Change Password</label>
								<label class="switch">
									  <input id="is_private" name="new_password" type="checkbox">
									  <span class="slider round"></span>
								</label>
							</div>
						</div>	
						<div class="col-md-12" id="password-container" style="display:none;">
							<div class="container">
								<div class="row">
									<div class="col-sm-12 col-sm-offset-3">
										<input type="password" class="input-lg form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off">
										<div class="row">
										<div class="col-sm-6">
										<span id="8char" class="glyphicon fa fa-close" style="color:#FF0004;"></span> 8 Characters Long<br>
										<span id="ucase" class="glyphicon fa fa-close" style="color:#FF0004;"></span> One Uppercase Letter
										</div>
										<div class="col-sm-6">
										<span id="lcase" class="glyphicon fa fa-close" style="color:#FF0004;"></span> One Lowercase Letter<br>
										<span id="num" class="glyphicon fa fa-close" style="color:#FF0004;"></span> One Number
										</div>
										</div>
										<input type="password" class="input-lg form-control" name="password2" id="password2" placeholder="Repeat Password" autocomplete="off">
										<div class="row">
										<div class="col-sm-12">
										<span id="pwmatch" class="glyphicon fa fa-close" style="color:#FF0004;"></span> Passwords Match
										</div>
										</div>
									</div>
								</div>
							</div>
						</div>							
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
					<button type="button" class="btn-save-user btn btn-primary px-5 py-2">{{ __('Save') }}</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- view modal --}}
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="viewModalLabel">View User Info</h5>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="inputEmail">Name</label>
								<input class="form-control disabled" id="view_name" disabled="true" type="text" placeholder="Name" name="name" value="" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="inputEmail">Email address</label>
								<input class="form-control disabled" id="view_inputEmail" disabled="true" required="required" type="email" placeholder="name@example.com" name="email" value="" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="inputEmail">Tagline</label>
								<input class="form-control disabled" id="view_tagline" disabled="true" type="text" placeholder="Available.." name="tagline" value="" />
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="phone_number">Phone</label>
								<input class="form-control disabled" id="view_phone_number" type="number" disabled="true" placeholder="4354544354" name="phone_number" value="" />

							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="cover_image">Cover Image</label>
								<div class="view_display_image"></div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-floating mb-3">
								<label for="profile_pic">Profile Image</label>
								<div class="view_display_proimage"></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
	$(document).ready(function() {
		$('#example').DataTable();
		$(this).on('click', '.close', function() {
			$('#editModal').modal('hide');
			$('#viewModal').modal('hide');
		});

		// view user info

		$(this).on('click', '.view-user', function() {
			btn = $(this);
			id = btn.data('id');
			console.log(id);
			$.ajax({
				url: "{{route('admin.edit')}}",
				type: 'get',
				data: {
					_token: "{{ csrf_token() }}",
					id: id
				},
				success: res => {
					$('.view_display_image').empty();
					$('.view_display_proimage').empty();
					$('#view_id').val('');
					$('#view_id').val(res.id);
					$('#view_name').val('');
					$('#view_name').val(res.name);
					$('#view_inputEmail').val('');
					$('#view_inputEmail').val(res.email);
					$('#view_tagline').val('');
					$('#view_tagline').val(res.tagline);
					$('#view_phone_number').val('');
					$('#view_phone_number').val(res.phone_number);
					if (res.cover_pic != "") {
						var img = document.createElement('img');
						img.src = res.cover_pic;
						img.className = "cover-image";
						$('.view_display_image').append(img);
					}
					if (res.profile_pic != "") {
						var img = document.createElement('img');
						img.src = res.profile_pic;
						img.className = "cover-image";
						$('.view_display_proimage').append(img);
					}


					$('#viewModal').modal('show');


				}
			})
		});

		// edit user info
		$(this).on('click', '.edit-user', function() {
			btn = $(this);
			id = btn.data('id');
			console.log(id);
			$.ajax({
				url: "{{route('admin.edit')}}",
				type: 'get',
				data: {
					_token: "{{ csrf_token() }}",
					id: id
				},
				success: res => {
					$('.display_image').empty();
					$('.display_proimage').empty();
					$('#id').val('');
					$('#id').val(res.id);
					$('#name').val('');
					$('#name').val(res.name);
					$('#inputEmail').val('');
					$('#inputEmail').val(res.email);
					$('#tagline').val('');
					$('#tagline').val(res.tagline);
					$('#phone_number').val('');
					$('#phone_number').val(res.phone_number);
					if (res.cover_pic != "") {
						var img = document.createElement('img');
						img.src = res.cover_pic;
						img.className = "cover-image";
						$('.display_image').append(img);
					}
					if (res.profile_pic != "") {
						var img = document.createElement('img');
						img.src = res.profile_pic;
						img.className = "cover-image";
						$('.display_proimage').append(img);
					}


					$('#editModal').modal('show');


				}
			})
		});

/*
--------------------------
*/
var password_error=0;

	$("input[type=password]").keyup(function(){
		var ucase = new RegExp("[A-Z]+");
		var lcase = new RegExp("[a-z]+");
		var num = new RegExp("[0-9]+");
		
		if($("#password1").val().length >= 8){
			$("#8char").removeClass("fa fa-close");
			$("#8char").addClass("glyphicon-ok");
			$("#8char").css("color","#00A41E");
			password_error=0;
		}else{
			$("#8char").removeClass("glyphicon-ok");
			$("#8char").addClass("fa fa-close");
			$("#8char").css("color","#FF0004");
			password_error=1;
		}
		
		if(ucase.test($("#password1").val())){
			$("#ucase").removeClass("fa fa-close");
			$("#ucase").addClass("glyphicon-ok");
			$("#ucase").css("color","#00A41E");
			password_error=0;
		}else{
			$("#ucase").removeClass("glyphicon-ok");
			$("#ucase").addClass("fa fa-close");
			$("#ucase").css("color","#FF0004");
			password_error=1;
		}
		
		if(lcase.test($("#password1").val())){
			$("#lcase").removeClass("fa fa-close");
			$("#lcase").addClass("glyphicon-ok");
			$("#lcase").css("color","#00A41E");
			password_error=0;
		}else{
			$("#lcase").removeClass("glyphicon-ok");
			$("#lcase").addClass("fa fa-close");
			$("#lcase").css("color","#FF0004");
			password_error=1;
		}
		
		if(num.test($("#password1").val())){
			$("#num").removeClass("fa fa-close");
			$("#num").addClass("glyphicon-ok");
			$("#num").css("color","#00A41E");
			password_error=0;
		}else{
			$("#num").removeClass("glyphicon-ok");
			$("#num").addClass("fa fa-close");
			$("#num").css("color","#FF0004");
			password_error=1;
		}
		
		if($("#password1").val() == $("#password2").val()){
			$("#pwmatch").removeClass("fa fa-close");
			$("#pwmatch").addClass("glyphicon-ok");
			$("#pwmatch").css("color","#00A41E");
			password_error=0;
		}else{
			$("#pwmatch").removeClass("glyphicon-ok");
			$("#pwmatch").addClass("fa fa-close");
			$("#pwmatch").css("color","#FF0004");
			password_error=1;
		}
	});
	
/*
-------------------------------
*/
		$(this).on('click', '.btn-save-user', function() {
			
			id = $("#id").val();
			var save_password=0;
			if($("#is_private").prop("checked"))
			{
				if($("#password1").val()=='' || $("#password2").val()=='')
				{
					return false;
				}else if(password_error==1)
				{
					return false;
				}
				save_password=1;
			}
			
			var data = new FormData($('#formId')[0]);
			$.ajax({
				url: "{{route('admin.update')}}",
				type: 'post',
				data: data,
				contentType: false,
				processData: false,
				success: res => {
					$('#editModal').modal('hide');
					console.log(res);
					if (res.success == "true") {
						$('#formId').trigger("reset");
						swal("Updated!", "User has been updated!", "success");
					} else {
						$('#formId').trigger("reset");
						swal("Cancelled!", "Something went Wrong!", "error");
					}
					$('#example').DataTable().draw();
				}
			})
		});

		$(this).on('click', '.delete-user', function() {
			btn = $(this);
			id = btn.data('id');
			console.log(id);

			swal({
				title: 'Are you sure?',
				text: "It will permanently deleted !",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}, function() {
				$.ajax({
					url: "{{route('admin.delete')}}",
					type: 'get',
					data: {
						_token: "{{ csrf_token() }}",
						id: id
					},
					success: res => {
						if (res.success == "true") {
							swal("Deleted!", "User has been deleted!", "success");
						} else {
							swal("Cancelled!", "Something went Wrong!", "error");
						}
						$('#example').DataTable().draw();
					}
				})
			})

		});
	
	});
</script>
@endpush