<div class="card card-shadow mt-3 p-3">
    <div class="card-body d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <a href="#">
                <img src="{{ url(str_replace('http://127.0.0.1:8000','',$notification->data['user']['profile_pic'])) }}"
                    class="rounded-circle me-2" width="50" height="50">
            </a>
            <p>
                {{ $notification->data['user']['name'] }} {{ $notification->data['notify'] }}
                on
                your
                <a href="{{ route('posts.show',['post' => $notification->data['post']['id']]) }}"
                    class="link ms-1">{{ $notification->data['post']['title'] }}</a>
            </p>
        </div>
        <div class="mark-as-read">
            @if ($notification->read_at == null)
                <button class="btn btn-sm markasread-btn" data-id="{{ $notification->id }}">
                    Mark As Read
                </button>
            @else
                <i class="fas fa-check-double me-2"></i>
            @endif

        </div>
    </div>
</div>