@php
$smileClass = $post->reactions->count() > 0 ? $post->reactions->toArray()[0]['reaction'] : 'fas fa-smile';
@endphp
<div class="card card-shadow mt-3" data-id={{ $post->id }} id="post-{{ $post->id }}">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ $post->user->profile_pic }}" class="icon_bgColor">
                <div class="text_rightSide pl-3">
                    <h6 class="mb-0">
                        <a href="{{ route('member', ['id' => $post->user->id]) }}" style="text-decoration: none;
                            color: #272529;">
                            {{ $post->user->name }}
                        </a>
                    </h6>
                    <small>{{ $post->created_at->diffForHumans() }}</small>
                </div>
                @if (request()->route()->getName() == 'home')
                    <i class="fas fa-chevron-right mx-2"></i>
                    <a href="{{ route('show-spaces', ['id' => $post->space->id]) }}" style="text-decoration: none;
                    color: #000;">
                        {{ $post->space->name }}
                    </a>
                @endif
            </div>
            @if ($post->user_id == auth()->user()->id)
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle dropdown-btn border-0" type="button" id="edit-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="edit-dropdown">
                        <li>
                            <a class="dropdown-item edit-post" href="javascript:void(0);" data-id="{{ $post->id }}">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item delete-post" href="javascript:void(0);"
                                data-id="{{ $post->id }}">
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

        <div class="more-description py-4">
            <h4>{{ $post->title }}
            </h4>
            <div class="post-discription">
                {!! $post->discription !!}
            </div>
            @if ($post->file != '')
                <img src="{{ $post->file_path }}" class="img-fluid mt-2 w-100">
            @endif
        </div>
        <div class="d-flex">
            @if (request()->route()->getName() != 'posts.show')
                <a href="{{ route('posts.show', ['post' => $post->id]) }}" class="btn btn-sm me-1">
                    <i class="fas fa-reply"></i>
                    Reply
                    {{ $post->replies_count }}
                </a>
            @endif
            <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#collapseExample-{{ $post->id }}"
                data-btn="btn-{{ $post->id }}">
                <i class="{{ $smileClass }}"></i>
                React
                {{ $post->reactions_count > 0 ? $post->reactions_count : '' }}
            </button>
        </div>
        <div class="collapse" id="collapseExample-{{ $post->id }}">
            <div class="d-flex align-item-center justify-content-evenly">
                <i class="fas fa-smile reaction" data-id="{{ $post->id }}" data-reaction="fas fa-smile"></i>
                <i class="fas fa-thumbs-up reaction" data-id="{{ $post->id }}"
                    data-reaction="fas fa-thumbs-up"></i>
                <i class="far fa-heart reaction" data-id="{{ $post->id }}" data-reaction="far fa-heart"></i>
                <i class="fas fa-thumbs-down reaction" data-id="{{ $post->id }}"
                    data-reaction="fas fa-thumbs-down"></i>
            </div>
        </div>
    </div>
</div>
