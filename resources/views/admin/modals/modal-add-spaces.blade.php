<div class="modal fade" id="addSpacesModal" tabindex="-1" role="dialog" aria-labelledby="addSpacesModal" aria-hidden="true">
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPostModalLabel">Create a new space</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>						
			<div class="modal-body">
				<form id="add-space"> @csrf
					<input type="hidden" name="user_id" value="{{$userId->id}}">
					<input type="hidden" id="space_id" name="space_id" value="0">
					<select name="role_id" class="form-control" required="required" id="role_id"> @forelse($roleType as $role)
						<option value="{{$role->id}}">{{$role->name}}</option> @empty
						<option value="">No record found</option> @endforelse </select>					
					<input type="text" id="space_title" class="form-control" placeholder="Add Space title" name="space_title"> 
					<span class="text-danger error-name"></span>
					
					<div>
					<label class="block font-medium text-basicSurface-700" for="description">Description</label>
						<div class="mt-1">
							<div class="flex relative rounded-md shadow-sm">
								<textarea  class="form-control" rows="4" name="description" id="description" placeholder="What is this space about? (e.g. Introduce yourself and get to know other members)"></textarea>
							</div>
						</div>
					</div>
					
					<div class="row">
					<div class="col-md-10">
					<label class="block font-medium text-basicSurface-700" for="description">Make private</label>
					<p>Only members can see who's in the space and what they post.</p>
					</div>
					<div class="col-md-2">
						<div class="mt-1">
							<div class="flex relative rounded-md shadow-sm">
							<label class="switch">
								  <input id="is_private" name="is_private" type="checkbox">
								  <span class="slider round"></span>
								</label>	
							</div>
						</div>
						</div>
					</div>

					<div class="row">
					<div class="col-md-10">
					<label class="block font-medium text-basicSurface-700" for="description">Hide space</label>
					<p>Hide this space from non-space members.</p>
					</div>
					<div class="col-md-2">
						<div class="mt-1">
							<div class="flex relative rounded-md shadow-sm">
								<label class="switch">
								  <input id="is_visible" name="is_visible" type="checkbox">
								  <span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>
					</div>
					
					<br>
		
					<div class="d-flex justify-content-end">
						<button type="button" class="btn btn-outline-success" id="submit-space-btn"> Create </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>