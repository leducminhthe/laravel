@extends('layouts.app')

@section('page_title', $item->name)

@section('header')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/emojionearea/emojionearea.min.css') }}">
    <script type="text/javascript" src="{{ asset('vendor/emojionearea/emojionearea.min.js') }}"></script>
@endsection

@section('content')
<style>
    :root {
        --star-size: 22px;
        --star-color: #000;
        --star-background: #fc0;
      }

    .Stars {
        --percent: calc(var(--rating) / 5 * 100%);
        font-size: var(--star-size);
    }
    .Stars::before {
        content: '★★★★★';
        background: linear-gradient(90deg, var(--star-background) var(--percent), var(--star-color) var(--percent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
    <div class="body_content_detail_offline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title">
                            <i class="uil uil-apps"></i>
                            <a href="{{ route('frontend.home') }}">{{ trans('lamenu.home_page') }}</a>
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('frontend.all_course',['type' => 1]).'?trainingProgramId='. $item->training_program_id }}">@lang('lamenu.online_course')</a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $item->name }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            @php
                $status = $item->getStatusRegister();
                $text = status_register_text($status);
                $isRating = \Modules\Online\Entities\OnlineRating::getRating($item->id, auth()->id());

                $avgRatingStar = $item->avgRatingStar();
            @endphp
            <div class="row mt-2" style="max-width: 1350px;">
                <div class="col-12">
                    <h3 class="text-danger">{{ $item->name }}</h3>
                </div>
                <div class="col-6">
                    <img src="{{ image_online($item->image, 'detail') }}" alt="" class="" style="width: 100%;">
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-baseline">
                        <span class="float-left">
                            <i class="fa fa-user"></i> {{ $item->register->count() }}
                            <i class="fa fa-eye"></i> {{ $item->views }}
                        </span>
                        <span class="ml-2">
                            <span class="Stars" style="--rating: {{ $avgRatingStar }};"></span>
                            @if ($register)
                                <span class="load-modal cursor_pointer" data-url="{{ route('module.online.detail.ajax_rating_star', [$item->id]) }}">
                                    ({{ $item->countRatingStar() .' lượt đánh giá' }})
                                </span>
                            @else
                                <span>
                                    ({{ $item->countRatingStar() .' lượt đánh giá' }})
                                </span>
                            @endif

                        </span>
                    </div>
                    <div class="my-2">
                        <img src="/images/icon_clock.png" alt=""> <b>@lang('latraining.time'):</b>
                        {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                    </div>
                    <div class="my-2">
                        <img src="/images/hourglass.png" alt=""> <b>@lang('latraining.register_deadline'):</b> {{ get_date($item->register_deadline) }}
                    </div>
                    <div class="my-2">
                        <img src="/images/global-education.png" alt=""> <b>@lang('lasuggest_plan.form'):</b> {{ trans('backend.online') }}
                    </div>
                    <div class="my-2">
                        <img src="/images/certification.png" alt=""> <b>{{ trans('latraining.cert') }}:</b> {{ $item->has_cert ? trans('latraining.yes') : trans('latraining.no') }}
                    </div>
                    <div class="my-2">
                        <img src="/images/checklist.png" alt="">
                        <b>Đánh giá 4 cấp độ:</b>
                        @if ($online_rating_level)
                            <a href="javascript:void(0);" class="load-modal" data-url="{{ route('module.online.detail.ajax_rating_level_offline', [$item->id]) }}">
                                {{ trans('latraining.yes') }}
                            </a>
                        @else
                            {{ trans('latraining.no') }}
                        @endif
                    </div>
                    <div class="my-2">
                        <img src="/images/link.png" alt=""> <b>Khoá học tiên quyết:</b> {{ isset($subject_prerequisite_course) ? trans('latraining.yes') : trans('latraining.no')  }}
                    </div>
                    <div class="my-2">
                        <a href="javascript:void(0)" id="bookmark_course" class="bookmark_course" onclick="bookmarkHandle({{ $item->id }})">
                            <span>
                                <img src="/images/icon_heart{{ $item->bookmarked ? '_full' : '' }}.png" alt="">
                            </span>
                            {{ $item->bookmarked ? __('laprofile.bookmarked') : __('laprofile.bookmark') }}
                        </a>
                    </div>
                    <div class="my-2">
                        @if ($online_course_document)
                            <a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.online.detail.ajax_document', [$item->id]) }}">
                                Tài liệu học tập <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                    <div class="my-2">
                        @if($status == 1)
                        <form action="{{ route('module.online.register_course', [$item->id]).'?trainingProgramId='. $item->training_program_id }}" id="frm-course" method="post" class="form-ajax">
                            <button type="submit" class="btn btn_adcart">{{ $text }}</button>
                        </form>

                        @elseif($status == 4)
                            <a href="{{ route('module.online.detail_new', [$item->id]) }}" class="btn btn_adcart">Vào học</a>
                        @else
                            <button type="button" class="btn btn_adcart">{{ $text }}</button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Giới thiệu --}}
            @if ($item->content)
                <div class="row mt-2">
                    <div class="col-12">
                        <h4>Giới thiệu</h4>
                        <div class="ml-2 text-justify">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Điều kiện hoàn thành --}}
            @if ($condition)
                <div class="row mt-2">
                    <div class="col-12">
                        <h4>{{ trans('latraining.conditions') }}</h4>
                        <div class="ml-2">
                            @foreach ($online_activity as $activity)
                                <div class="">- {{ trans('latraining.complete_act') }} <b>{{ $activity->name }}</b></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Ý kiến --}}
            <div class="row mt-2" style="margin-bottom: 100px;">
                <div class="col-12">
                    <h4>Ý kiến (<span id="total_comment">{{ $comments->count() }}</span>)</h4>
                </div>
                <input type="hidden" name="id_comment" id="id_comment" value=''>
                <div class="col-12 comment_online">
                    <textarea id="textarea_comment" class="textarea_emoji"></textarea>
                </div>
                <div class="col-12 text-right my-1">
                    {{--  <button class="btn cancel_edit_comment" onclick="cancelEdit({{ $item->id }})"><i class="fas fa-ban"></i> {{ trans('laother.unediting') }}</button>  --}}
                    <button class="btn send_comment" onclick="sendComment({{ $item->id }})"><i class="fa fa-save"></i> @lang('app.send')</button>
                </div>
                <div class="col-12 all_comments">
                    @foreach($comments as $comment)
                        <div class="review_item item_{{ $comment->id }}">
                            <div class="review_usr_dt">
                                <img src="{{ image_user($comment->avatar) }}" alt="">
                                <div class="rv1458">
                                    <h4 class="tutor_name1">
                                        {{ $comment->fullname }}
                                        @if ($comment->user_id == getUserId())
                                            <div class="ml-2 rpt100">
                                                <span>
                                                    <a onclick="deleteComment({{ $comment->id }})" class="report145">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a onclick="editComment({{ $comment->id }})" class="report145">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        @endif
                                    </h4>
                                    <span class="time_145">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <p class="rvds10 content_{{ $comment->id }}">{{ ucfirst($comment->content) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        window.Rating = {
            route: '{{ route('module.online.rating',$item->id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);

        //ĐÁNH DẤU
        function bookmarkHandle(id) {
            $.ajax({
                type: 'POST',
                url: "{{ route('frontend.online.bookmark_online') }}",
                dataType: 'json',
                data: {
                    'id': id,
                }
            }).done(function(data) {
                if(data == 1) {
                    $('#bookmark_course').html('<span><img src="/images/icon_heart_full.png" alt=""> {{ trans("laprofile.bookmarked") }}</span>')
                } else {
                    $('#bookmark_course').html('<span><img src="/images/icon_heart.png" alt=""> {{ trans("laprofile.bookmark") }}</span>')
                }
                return false;
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        // BÌNH LUẬN KHÓA HỌC
        $(".textarea_emoji").emojioneArea({
            pickerPosition: "bottom",
            hidePickerOnBlur: false,
            search: false,
        });

        function sendComment(id) {
            var comment_id = $('#id_comment').val();
            console.log(comment_id);
            var content = $('#textarea_comment').val();
            var url = "{{ route('frontend.online.comment.course_online', ':id') }}";
            url = url.replace(':id',id);
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    'id': id,
                    'content': content,
                    'comment_id': comment_id
                }
            }).done(function(data) {
                if(data.status == 'error'){
                    show_message(data.message, data.status);
                    return false;
                }

                var html = '';
                if (data.status == "success" && !comment_id) {
                    html = `<div class="review_item item_`+ data.comment.id +`">
                                <div class="review_usr_dt">
                                    <img src="`+ data.profile_user.avatar +`" alt="">
                                    <div class="rv1458">
                                        <h4 class="tutor_name1">
                                            `+ ( data.profile_user.full_name ? data.profile_user.full_name : data.profile_user.name ) +`
                                            <div class="ml-2 rpt100">
                                                <span>
                                                    <a onclick="deleteComment(`+ data.comment.id +`)" class="report145">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a onclick="editComment(`+ data.comment.id +`)" class="report145">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </h4>
                                        <span class="time_145">`+ data.comment.created_at2 +`</span>
                                    </div>
                                </div>
                                <p class="rvds10 content_`+ data.comment.id +`">`+ data.comment.content +`</p>
                            </div>`;
                    $('.all_comments').prepend(html);
                } else if (data.status == "success" && comment_id) {
                    $('.content_'+ data.comment.id).text(data.comment.content);
                }

                $('#total_comment').html(data.total_comment);

                cancelEdit();
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        function deleteComment(id) {
            var confirmText = "{{ trans('laforums.want_to_delete') }}";
            var url = "{{ route('frontend.online.delete.comment.course_online', ':id') }}";
            url = url.replace(':id',id);
            if(confirm(confirmText)) {
                $.ajax({
                    type: 'POST',
                    url: url,
                }).done(function(data) {
                    $(".item_"+id).remove();
                    cancelEdit();
                    return false;
                }).fail(function(data) {
                    show_message("{{ trans('laother.data_error') }}", 'error');
                    return false;
                });
            }
        }

        function editComment(id) {
            var text = $('.content_'+id).text();
            $("#textarea_comment").data("emojioneArea").setText(text);
            $('#id_comment').val(id);
            $('.cancel_edit_comment').show();
        }

        function cancelEdit() {
            $("#textarea_comment").data("emojioneArea").setText('');
            $('#id_comment').val('');
            $('.cancel_edit_comment').hide();
        }
        // END COMMENT
    </script>
@stop
