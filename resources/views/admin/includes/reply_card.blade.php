<div class="card card-shadow mt-3">
    <div class="card-body p-3">
        <div class="d-flex align-items-center">
            <a href="#">
                <img src="{{ $reply->user->profile_pic }}"
                    class="rounded-circle me-2" width="50" height="50">
            </a>
            {{ $reply->reply }}
        </div>   
    </div>
</div>