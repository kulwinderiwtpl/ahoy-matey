@extends('layouts.main')
@section('title', 'Notifications')
@section('content')
    <div class="container px-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between">
                    <h4 class="mt-4 mb-4 font-weight-bold fw-bold">Notifications</h4>
                    <button class="btn markasread-btn" data-id="all">
                        <i class="fas fa-check-double me-2"></i>Mark as read All
                    </button>
                </div>


                <div class="card-main mb-5" id="notification-list">
                    @forelse ($notifications as $notification)
                        @include('user.notification_card',compact('notification'))
                    @empty

                    @endforelse
                </div>
                @if ($notifications->lastPage() > $notifications->currentPage())
                  <div class="col-md-12 text-center my-2">
                    <button class="btn btn-success" data-page="1" id="load-more-notification">Load More</button>
                   </div>
                 @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(this).on('click', '.markasread-btn', function() {
                btn = $(this);
                id = btn.data('id');
                
                $.ajax({
                    url: route('read-notifications'),
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: res => {

                        let notificationSpan = $('#notification-menu').find('#notification-count');
                        if (id == 'all')
                            $('#notification-list').find('.mark-as-read').html(
                                `<i class="fas fa-check-double me-2"></i>`);
                        else
                            $(this).parent().html(`<i class="fas fa-check-double me-2"></i>`);
                        
                        res.count > 0 ? notificationSpan.text(res.count) : notificationSpan.remove();    

                    }
                })
            });

            $(this).on('click','#load-more-notification', function(){
                let lastPage = Number("{{ $notifications->lastPage() }}");
                let btn = $(this);
                     let page = $(this).data('page');
                     page += 1;
                     $.ajax({
                            url:route('notifications',{_query:{page:page}}),
                            beforeSend: () => btn.addClass('disabled').append(`<i class='fa fa-spin fa-spinner'></i>`),
                            success: res => {
                                $('#notification-list').append(res);
                                btn.data('page',page);
                                btn.removeClass('disabled').find('.fa-spin').remove();
                                if(lastPage == page) btn.remove();
                            },
                        });
            });
        });
    </script>
@endpush
