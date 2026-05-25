<div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModal" aria-hidden="true">
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPostModalLabel">App New Post</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="add-post"> @csrf
					<input type="hidden" name="space_id" value="1">
					<input type="hidden" name="user_id" value="{{$userId->id}}">
					<select name="space_id" class="form-control" required="required" id="space_id"> @forelse($spacesUsers as $space)
						<option value="{{$space->spaces->id}}">{{$space->spaces->name}}</option> @empty
						<option value="">Create Space</option> @endforelse </select>
					<input type="text" class="form-control" placeholder="Add a title" name="title"> <span class="text-danger error-name"></span>
					<div id="editor" style="height: 300px" class="mb-2"> </div> <img width="200" height="200" class="img-thumbnail d-none" id="img-preview">
					<div class="d-flex justify-content-end">
						<label class="btn btn-outline-success me-1" id="btn-post-edit-img-btn" for="post-file-input"> <i class="fas fa-images"></i> </label>
						<input type="file" id="post-file-input" class="d-none" accept="image/*" name="file">
						<button type="button" class="btn btn-outline-success" id="submit-post-btn"> Post </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>