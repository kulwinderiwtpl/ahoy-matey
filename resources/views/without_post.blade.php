<h1 class="mt-4 display-3 font-weight-bold fw-bold">Hi {{ titleCase(auth()->user()->name) }} 👋</h1>
<p>Let’s get started with your Circle!</p>
<div class="Tribe_01">
    <div class="first_Pt mt-4">
        <h2>1. Start a new collection</h2>
        <p class="mt-2">Collections are main categories to help structure your Circle.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body p-5">
            <h3 class="mb-3 text-secondary">Your collection of spaces</h3>
            <div class="row">
                @forelse($spaceAll as $spaces)
                <div class="col-lg-4">
                    <div class="request1 shadow-sm bg-white">
                        <div class="p-3">
                            <h4 class="mb-2">{{ $spaces->name }}</h4>
                            <p class="py-2 mb-1 w-100 bg-light rounded">Description: {{ $spaces->about }}</p>
                            <p class="py-2 mb-1 w-100 bg-light rounded">Space Status: {{ $spaces->is_private ? 'Private': 'Public'}}</p>
                            <p class="py-2 mb-1 w-50  bg-light rounded"></p>
                            <div class="ratings mt-2 d-flex align-items-center">
                                <i class="fas fa-users me-2 text-secondary"></i> <span class="text-secondary">
								{{ ($spaces->members_count==0)?"0 Member":($spaces->members_count==1?"1 Member":$spaces->members_count." Members") }} </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-lg-4">
                    <div class="request1 shadow-sm bg-white">
                        <div class="bg-light rounded py-5 height-cus">

                        </div>
                        <div class="p-3">
                            <h4 class="mb-2">0 Feature Requests</h4>
                            <p class="py-2 mb-1 w-100 bg-light rounded"></p>
                            <p class="py-2 mb-1 w-100 bg-light rounded"></p>
                            <p class="py-2 mb-1 w-50  bg-light rounded"></p>
                            <div class="ratings mt-2 d-flex align-items-center">
                                <i class="fas fa-users me-2 text-secondary"></i> <span class="text-secondary ">0
                                    Members</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>