@extends('themes.mobile.layouts.app')

@section('page_title', 'Video')

@section('content')
    @php
        $like = \Modules\DailyTraining\Entities\DailyTrainingVideo::checkLike($video->id, 1);
        $dislike = \Modules\DailyTraining\Entities\DailyTrainingVideo::checkLike($video->id, 2);
    @endphp
    <div class="container" id="detail-daily">
        <div class="row pb-3 pt-1">
            <div class="col-12">
                <video class="w-100" controls autoplay>
                    <source src="{{ $video->getLinkPlay() }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="col-auto">
                <p class="text-primary mb-0">
                    {{ $video->hashtag }}
                </p>
                <h6 class="bold">{{ $video->name }}</h6>
                <p class="text-mute">
                    <span class="m-auto">
                        {{ $video->view .' '. trans('app.view') }}
                    </span>
                    <span class="m-auto like-video text-muted">
                        <i class="material-icons vm coler-like {{ $like ? 'text-primary' : '' }}" style="font-size: 15px;">thumb_up</i>
                        <span id="like">{{ $count_like }}</span>
                    </span>
                    <span class="m-auto dislike-video text-muted">
                        <i class="material-icons vm coler-like {{ $dislike ? 'text-primary' : '' }}" style="font-size: 15px;">thumb_down</i>
                        <span id="dislike">{{ $count_dislike }}</span>
                    </span>
                </p>
                <div class="row">
                    <div class="col-auto pr-0">
                        <img src="{{ \App\Models\Profile::avatar($video->created_by) }}" alt="" class="avatar avatar-40">
                    </div>
                    <div class="col">
                        <p class="text-mute">
                            {{ \App\Models\Profile::fullname($video->created_by) }} <br>
                            {{ \Carbon\Carbon::parse($video->created_at)->diffForHumans()}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="review_right">
                    <div class="review_right_heading">
                        <h6>@lang('app.comment') ({{ $comments->count() }})</h6>
                    </div>
                </div>
                <div class="review_all120">
                    <div id="list-comment" class="mb-1">
                        @if($comments)
                            @foreach($comments as $comment)
                                @php
                                    $like_comment = \Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo::checkLikeComment($video->id, $comment->id, 1);
                                    $dislike_comment = \Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo::checkLikeComment($video->id, $comment->id, 2);
                                    $count_like_comment = \Modules\DailyTraining\Entities\DailyTrainingUserLikeCommentVideo::countLikeOrDisLike($video->id, $comment->id, 1);
                                    $count_dislike_comment = \Modules\DailyTraining\Entities\DailyTrainingUserLikeCommentVideo::countLikeOrDisLike($video->id, $comment->id, 2);
                                @endphp
                                <div class="card shadow border-0 mt-3">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <img src="{{ \App\Models\Profile::avatar($comment->user_id) }}" alt="" class="avatar avatar-40 no-shadow border-0">
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <h6 class="font-weight-normal mb-1">
                                                    {{ \App\Models\Profile::fullname($comment->user_id) }}
                                                </h6>
                                                <p class="text-mute text-secondary">
                                                    {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col align-self-center">
                                                {!! ucfirst($comment->content) !!}
                                            </div>
                                        </div>
                                        <div class="row" id="commit{{ $comment->id }}">
                                        <span class="m-auto like-comment-video text-muted" data-comment_id="{{ $comment->id }}">
                                            <i class="material-icons vm {{ $like_comment ? 'text-primary' : '' }}" style="font-size: 15px;">thumb_up</i>
                                            <span class="like-comment-{{ $comment->id }}">{{ $count_like_comment }}</span>
                                        </span>
                                            <span class="m-auto dislike-comment-video text-muted" data-comment_id="{{ $comment->id }}">
                                            <i class="material-icons vm {{ $dislike_comment ? 'text-primary' : '' }}" style="font-size: 15px;">thumb_down</i>
                                            <span class="dislike-comment-{{ $comment->id }}">{{ $count_dislike_comment }}</span>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    {{--<input type="color" class="avatar avatar-40 shadow-sm change-color" value="fff"> {{ data_locale('Chọn màu', 'Choose color') }}--}}
                    <div class="form-group">
                        <textarea class="form-control" type="text" name="content" id="content" rows="5" placeholder="{{ trans('app.content') }}"></textarea>
                    </div>
                    <button type="button" class="btn float-right" id="add-comment"><i class="fa fa-save"></i> @lang('app.send')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">

        $('.change-color').on('change', function () {
           var color = $(this).val();
            $('#content').css('color', color);
        });

        var is_like = 0;
        var is_dislike = 0;
        var type = '';
        $(".like-video").on('click', function () {
            is_like += 1;
            $('.coler-like').removeClass('text-primary');
            if (is_like % 2 == 0) {
                type = '';
                $(this).find('i').removeClass('text-primary');
            } else {
                type = 'like';
                $(this).find('i').addClass('text-primary');
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.frontend.like_video', ['id' => $video->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': type,
                }
            }).done(function (data) {
                $('#like').text(data.count_like);
                $('#dislike').text(data.count_dislike);
                return false;
            }).fail(function (data) {
                return false;
            });
        });

        $(".dislike-video").on('click', function () {
            is_dislike += 1;
            $('.coler-like').removeClass('text-primary');
            if (is_dislike % 2 == 0) {
                type = '';
                $(this).find('i').removeClass('text-primary');
            } else {
                type = 'dislike';
                $(this).find('i').addClass('text-primary');
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.frontend.like_video', ['id' => $video->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': type,
                }
            }).done(function (data) {
                $('#like').text(data.count_like);
                $('#dislike').text(data.count_dislike);
                return false;
            }).fail(function (data) {
                return false;
            });
        });

        var is_like_comment = 0;
        var is_dislike_comment = 0;
        var type_comment = '';
        $("#list-comment").on('click', '.like-comment-video', function () {
            var comment_id = $(this).data('comment_id');
            $('#commit' + comment_id).find('i').removeClass('text-primary');

            is_like_comment += 1;
            if (is_like_comment % 2 == 0) {
                type_comment = '';
                $(this).find('i').removeClass('text-primary');
            } else {
                type_comment = 'like';
                $(this).find('i').addClass('text-primary');
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.frontend.like_comment_video', ['id' => $video->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': type_comment,
                    'comment_id': comment_id,
                }
            }).done(function (data) {
                $('.like-comment-' + data.comment_id).text(data.count_like_comment);
                $('.dislike-comment-' + data.comment_id).text(data.count_dislike_comment);
                return false;
            }).fail(function (data) {
                return false;
            });
        });

        $("#list-comment").on('click', '.dislike-comment-video', function () {
            var comment_id = $(this).data('comment_id');
            $('#commit' + comment_id).find('i').removeClass('text-primary');

            is_dislike_comment += 1;
            if (is_dislike_comment % 2 == 0) {
                type_comment = '';
                $(this).find('i').removeClass('text-primary');
            } else {
                type_comment = 'dislike';
                $(this).find('i').addClass('text-primary');
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.frontend.like_comment_video', ['id' => $video->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': type_comment,
                    'comment_id': comment_id,
                }
            }).done(function (data) {
                $('.like-comment-' + data.comment_id).text(data.count_like_comment);
                $('.dislike-comment-' + data.comment_id).text(data.count_dislike_comment);
                return false;
            }).fail(function (data) {
                return false;
            });
        });

        /*$('#content').emojioneArea({
            search: false,
            pickerPosition: "bottom"
        });*/

        var item = '';
        $('#add-comment').on('click', function () {
            var color = $('.change-color').val();
            var content = '<p style="color: '+color+'">' + $('#content').val() + '</p>';

            $.ajax({
                url: "{{ route('themes.mobile.daily_training.frontend.comment_video', ['id' => $video->id]) }}",
                type: 'post',
                data: {
                    content: content,
                },
            }).done(function (data) {
                if (data.status == 'warning'){
                    show_message(data.message, data.status);
                    return false;
                }
                $("#content").val('');

                item += '<div class="card shadow border-0 mt-3">' +
                            '<div class="card-body">' +
                                '<div class="row align-items-center">' +
                                    '<div class="col-auto pr-0">' +
                                        '<img src="'+ data.img_user +'" alt="" class="avatar avatar-40 no-shadow border-0">' +
                                    '</div>' +
                                    '<div class="col-auto align-self-center">' +
                                        '<h6 class="font-weight-normal mb-1">' + data.name_user + '</h6>' +
                                        '<p class="text-mute text-secondary">' + data.time_created + '</p>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="row">' +
                                    '<div class="col align-self-center">' + data.content + '</div>' +
                                 '</div>' +
                                '<div class="row" id="commit'+ data.comment_id +'">' +
                                    '<span class="m-auto like-comment-video text-muted" data-comment_id="'+ data.comment_id +'">' +
                                        '<i class="material-icons vm" style="font-size: 15px;">thumb_up</i>' +
                                        '<span class="like-comment-'+ data.comment_id +'"></span>' +
                                    '</span>' +
                                    '<span class="m-auto dislike-comment-video text-muted" data-comment_id="'+ data.comment_id +'">' +
                                        '<i class="material-icons vm" style="font-size: 15px;">thumb_down</i>' +
                                        '<span class="dislike-comment-'+ data.comment_id +'"></span>' +
                                    '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

                $("#list-comment").append(item);

                item = '';
                return false;
            }).fail(function (data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        });
    </script>
@endsection
