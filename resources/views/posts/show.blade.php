@extends('layouts.main')
@section('title', 'Post')
@section('page_style')
<style>
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
</style>
@endsection
@section('content')
<main class="mb-5">
    <div class="container px-4">
        <div class="row justify-content-center">
            <div class="col-md-9 post-list">
                @include('includes.post_card',$post)
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9 post-reply-list">
                @forelse ($replies as $reply)
                @if ($loop->first)
                <h4 class="mt-4 mb-4 font-weight-bold fw-bold">Replies</h4>
                @endif
                @include('includes.reply_card', compact('reply'))
                @empty
                <div class="card card-shadow mt-3 p-3 text-center">
                    <p>No reply added</p>
                </div>
                @endforelse
            </div>
            @if ($replies->lastPage() > $replies->currentPage())
            <div class="col-md-12 text-center my-2">
                <button class="btn btn-success" data-page="1" id="load-more-replies">Load More</button>
            </div>
            @endif
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-shadow mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ auth()->user()->profile_pic }}" class="icon_bgColor">
                            <div class="text_rightSide pl-3">
                                <h6 class="mb-0">
                                    {{ auth()->user()->name }}
                                </h6>
                            </div>
                        </div>
                        <div class="py-3">
                            <input type="text" class="form-control" placeholder="Add a comment" id="reply-input">
                        </div>
                        <button class="btn btn-success float-end disabled" id="reply-btn">Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {

        $(this).on('keyup', '#reply-input', function() {
            $(this).val() != "" ? $('#reply-btn').removeClass('disabled') : $('#reply-btn').addClass('disabled');
        });

        $(this).on("click", '#reply-btn', function() {
            let reply = $('#reply-input');
            let btn = $(this);
            $.ajax({
                url: route('postreplies.store'),
                method: 'post',
                data: {
                    _token: "{{ csrf_token()}}",
                    reply: reply.val(),
                    user_id: "{{ auth()->user()->id }}",
                    post_id: "{{ $post->id }}"
                },
                beforeSend: () => $(this).addClass('disabled').append("<i class='fa fa-spin fa-spinner'></i>"),
                success: res => {
                    btn.removeClass('disabled').find('.fa-spin').remove();
                    $('.post-reply-list').append(res.html);
                    reply.val("");
                },
                error: () => {
                    btn.removeClass('disabled').find('i').remove();
                    alertify.error("something went wrong please try again !");
                }
            })
        });

        $(this).on('click', '#load-more-replies', function() {
            lastPage = Number("{{ $replies->lastPage() }}");
            let btn = $(this);
            let page = $(this).data('page');
            page += 1;
            $.ajax({
                url: "{{ route('posts.show',['post' => $post->id]) }}",
                beforeSend: () => btn.addClass('disabled').append(`<i class='fa fa-spin fa-spinner'></i>`),
                success: res => {
                    $('.post-reply-list').append(res);
                    btn.data('page', page);
                    btn.removeClass('disabled').find('.fa-spin').remove();
                    if (lastPage == page) btn.remove();
                },
            });
        })

    })
</script>
@endpush