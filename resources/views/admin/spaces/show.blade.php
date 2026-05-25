<div class="card card-shadow mt-3 p-3" id="space-{{ $space->id }}">
	<div class="card-body d-flex justify-content-between">
		<p>{{ $space->name }}</p>
		<p class="text-muted">{{ titleCase($space->name) }}</p>
		<div class="dropdown">
			<button class="btn dropdown-toggle dropdown-btn border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fas fa-ellipsis-h"></i> </button>
			
			<ul class="dropdown-menu" aria-labelledby="edit-dropdown">
				<li>
					<a class="dropdown-item edit-spaces" href="javascript:void(0);" data-id="{{ $space->id }}">
						<i class="fas fa-edit"></i>
						Edit
					</a>
				</li>
				<li>
					<a class="dropdown-item delete-spaces" href="javascript:void(0);"
						data-id="{{ $space->id }}">
						<i class="fas fa-trash"></i>
						Delete
					</a>
				</li>
			</ul>

		</div>
	</div>
</div>