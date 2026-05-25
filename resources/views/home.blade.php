@extends('layouts.main')
@section('title', 'Feed')
@section('content')
<main class="mb-5">
  <div class="container px-4 post-list">
    @forelse ($posts as $post)
    <div class="row justify-content-center">
      <div class="col-md-9">
        @if ($loop->first)
        <h4 class="mt-4 mb-4 font-weight-bold fw-bold">Feed</h4>
        @endif
        @include('includes.post_card',$post)
      </div>
    </div>
    @empty
    @include('without_post', $spaceAll)
    @endforelse
  </div>
  @if ($posts->lastPage() > $posts->currentPage())
  <div class="col-md-12 text-center my-2">
    <button class="btn btn-success" data-page="1" id="load-more-post">Load More</button>
  </div>
  @endif
</main>
@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    let lastPage = Number("{{ $posts->lastPage() }}");
    $(this).on('click', '#load-more-post', function() {
      let btn = $(this);
      let page = $(this).data('page');
      page += 1;
      $.ajax({
        url: route('home', {
          _query: {
            page: page
          }
        }),
        beforeSend: () => btn.addClass('disabled').append(`<i class='fa fa-spin fa-spinner'></i>`),
        success: res => {
          $('.post-list').append(res);
          btn.data('page', page);
          btn.removeClass('disabled').find('.fa-spin').remove();
          if (lastPage == page) btn.remove();
        },
      });
    })
  });
</script>
@endpush