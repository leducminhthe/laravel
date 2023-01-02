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
    <div class="body_content_detail_offline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <a href="{{ route('frontend.all_course',['type' => 0]) }}">@lang('lamenu.course')</a>
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('frontend.all_course',['type' => 2]) }}">@lang('lamenu.offline_course')</a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $item->name }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            @php
                $status = $item->getStatusRegister();
                $text = $item->getStatusRegisterText($status);
            @endphp
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="preview_video">
                        <div class="row justify-content-center">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="preview_video">
                                    <a href="#" class="fcrse_img" data-toggle="modal" data-target="#videoModal">
                                        <img src="{{ image_offline($item->image) }}" alt="">
                                        <div class="course-overlay">
                                            @if ($item->pointSetting)
                                                @php
                                                    if ($item->pointSetting->method == 1)
                                                        $point = $item->pointSetting->point;
                                                    else{
                                                        $setting = $item->pointSetting->methodSetting->sortByDesc('point');
                                                        $point = $setting->count() > 0 ? $setting->first()->point : 0;
                                                    }
                                                @endphp
                                            <div class="badge_seller">{{ $point }} <img class="point ml-1" style="width: 20px;height: 20px" src="{{ asset('images/level/point.png') }}" alt=""></div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                <div class="_215b05">
                                    <a href="javascript:void(0)" class="_215b05">
                                        <span><i class="uil uil-windsock"></i></span>{{ $item->register->count() }} @lang('latraining.join')
                                    </a>
                                    <a href="javascript:void(0)" class="_215b05 bookmark_course" onclick="bookmarkHandle({{ $item->id }})">
                                        <span>
                                            <i class='uil uil-heart {{ $item->bookmarked ? 'check-heart' : ''}}'></i>
                                        </span>
                                        {{ $item->bookmarked ? __('laprofile.bookmarked') : __('laprofile.bookmark') }}
                                    </a>
                                    <a href="javascript:void(0)" class="ml-2 _215b05" id="share-course">
                                        <i class="far fa-share-square"></i>
                                        <span>Share</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="_215b05">
                                    <h2>{{ $item->name }}</h2>
                                    <span class="_215b05">{{ \Illuminate\Support\Str::words($item->description, 20) }}</span>
                                </div>
                                <div class="_215b05">
                                    <div class="crse_reviews mr-2">
                                        <i class="uil uil-star"></i>{{ $item->avgRatingStar() }}
                                    </div>
                                    ({{ $item->countRatingStar() }} {{ trans('laother.reviews') }})
                                </div>

                                <div class="_215b05">
                                    <div class="_215b05">
                                        <span><i class='uil uil-eye'></i></span>
                                        {{ trans('laprofile.view') }}: {{ $item->views }}
                                    </div>
                                    @if ($course_time)
                                        @php
                                            switch ($course_time_unit){
                                                case 'day': $time_unit = trans('latraining.date'); break;
                                                case 'session': $time_unit = trans('latraining.session'); break;
                                                default : $time_unit = trans('latraining.hour'); break;
                                            }
                                        @endphp
                                        <div class="_215b05">
                                            <span><i class='uil uil-clock'></i></span>
                                            {{ trans('laother.timer') }}: {{ $course_time.' '.$time_unit }}
                                        </div>
                                    @endif
                                </div>

                                <div class="_215b05">
                                    <b>@lang('latraining.time'):</b> {{ get_date($item->start_date) }} @if($item->end_date) {{ trans('latraining.to') }} {{ get_date($item->end_date) }} @endif
                                </div>

                                <div class="_215b05">
                                    <b>@lang('latraining.register_deadline'):</b> {{ get_date($item->register_deadline) }}
                                </div>

                                @if($item->getObject())
                                    <div class="_215b05">
                                        <b>{{ trans('laother.students') }}:</b> {{ $item->getObject() }}
                                    </div>
                                @endif
                                <ul class="_215b05">
                                    @if($status == 1)
                                        <div class="mt-2 item item-btn">
                                            <button class="btn btn_adcart load-modal" data-url="{{ route('frontend.ajax_modal_class', ['course_id' => $item->id]) }}">{{ $text }}</button>
                                        </div>
                                    @elseif($status == 4)
                                        <div class="mt-2">
                                            <button href="javascript:void(0)" class="btn btn_adcart" id="go-course">{{ mb_strtoupper($text) }}</button>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <button type="button" class="btn btn_adcart">{{ $text }}</button>
                                        </div>
                                    @endif

                                    @if ($go_entrance_quiz_url)
                                        <div class="mt-2">
                                            <a href="{{ $go_entrance_quiz_url }}" target="_blank" class="btn"> {{ trans('latraining.first_quiz') }}</a>
                                        </div>
                                    @endif

                                    @if ($go_quiz_url)
                                        <div class="mt-2">
                                            <a href="{{ $go_quiz_url }}" target="_blank" class="btn"> {{ trans('laother.final_exam') }}</a>
                                        </div>
                                    @endif

                                    <div id="notify-course" class="">@lang('laother.notify_go_course')</div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-12 pl-0">
                    <div class="_215b15 _byt1458">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 p-1">
                                    <div class="course_tabs">
                                        <nav>
                                            <div class="nav nav-tabs tab_crse justify-content-center" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-program-tab" data-toggle="tab" href="#nav-program" role="tab" aria-selected="true">
                                                    {{ trans('latraining.program') }}
                                                </a>
                                                <a class="nav-item nav-link" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-selected="false">
                                                    @lang('app.comment')
                                                </a>
                                                <a class="nav-item nav-link" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-selected="true">
                                                    @lang('app.description')
                                                </a>
                                                <a class="nav-item nav-link" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-selected="false">
                                                    @lang('app.content')
                                                </a>
                                                <a class="nav-item nav-link" id="nav-rating-level-tab" data-toggle="tab" href="#nav-rating-level" role="tab" aria-selected="false">
                                                    {{ trans("lamenu.kirkpatrick_model") }} ({{ $count_rating_level }})
                                                </a>
                                            </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_215b17">
                        <div class="container-fluid body_course_offline">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="course_tab_content">
                                        <div class="tab-content" id="nav-tabContent">

                                            {{--  Chương trình  --}}
                                            <div class="tab-pane fade show active" id="nav-program" role="tabpanel">
                                                @if($status == 4)
                                                    @if ($offlineActivityOnlines->count() > 0)
                                                        <div class="mt-3 mb-2">
                                                            <h4 class="mb-1">{{ trans('latraining.required_online_courses') }}</h4>
                                                            @foreach ($offlineActivityOnlines as $key => $activityOnline)
                                                                <a class="ml-2 cursor_pointer link_course_online_{{ $activityOnline->subject_id }}"
                                                                    @if ($closed_event_click != 1)
                                                                        onclick="registerActivityOnline({{ $activityOnline->course_id }},{{ $activityOnline->subject_id }})"
                                                                    @endif
                                                                >
                                                                    <span>{{ $key + 1 }}./</span>
                                                                    <span>{{ $activityOnline->nameOnlineCourse }}</span>
                                                                </a>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($offlineActivityMettings->count() > 0)
                                                        <div class="mb-2">
                                                            <h4 class="mb-1">Ms Teams</h4>
                                                            @foreach ($offlineActivityMettings as $key => $activityMetting)
                                                                @if ($activityMetting->checkLink != 0)
                                                                    <a class="ml-2" onclick="checkLinkHandle({{ $activityMetting->checkLink }})">
                                                                @else
                                                                    <a href="{{ $activityMetting->linkMetting }}" target="_blank" class="ml-2">
                                                                @endif
                                                                    <span>{{ $key + 1 }}./</span>
                                                                    <span>{{ trans('latraining.session') }} {{ $key + 1 }} ({{ $activityMetting->start_time }} - {{ $activityMetting->end_time }})</span>
                                                                </a>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($offlineActivityZooms->count() > 0)
                                                        <div class="mb-2">
                                                            <h4 class="mb-1">Zoom</h4>
                                                            @foreach ($offlineActivityZooms as $key => $activityZoom)
                                                                <a href="{{ $activityZoom->linkZoom }}" target="_blank" class="ml-2">
                                                                    <span>{{ $key + 1 }}./</span>
                                                                    <span>{{ $activityZoom->topic }} ({{ $activityZoom->start_time }} - {{ $activityZoom->end_time }})</span>
                                                                </a>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>

                                            {{--  Mô tả  --}}
                                            <div class="tab-pane fade" id="nav-about" role="tabpanel">
                                                <div class="_htg451">
                                                    {!! $item->content !!}
                                                </div>
                                            </div>

                                            {{--  Nội dung  --}}
                                            <div class="tab-pane fade" id="nav-courses" role="tabpanel">
                                                <div class="crse_content">
                                                    @php
                                                        $documents = json_decode($item->document)
                                                    @endphp
                                                    @if ( !empty($documents) )
                                                        @foreach ($documents as $key => $document)
                                                            @if($item->checkPdf( $item->id,$key) )
                                                                <div>
                                                                    <a href="{{ route('module.offline.view_pdf', ['id' => $item->id, 'key' => $key]) }}" target="_blank" class="btn btn_adcart click-view-doc mb-2" data-id="{{$item->id}}" >
                                                                        <i class="fa fa-download" aria-hidden="true"></i> {{ basename($document) }}
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <a href="{{ link_download('uploads/'.$document) }}" data-turbolinks="false" >
                                                                        <i class="fa fa-download" aria-hidden="true"></i> {{ basename($document) }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            {{--  Bình luận  --}}
                                            <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                                                <div class="rating_start_online row">
                                                    <div class="col-md-5 col-12 p-0">
                                                        @livewire('offline.comment', ['course_id' => $item->id,'avg_star' => $item->avgRatingStar()])
                                                    </div>
                                                    <div class="col-md-7 col-12">
                                                        <div class="row mx-0 wrraped_comment_offline">
                                                            <input type="hidden" name="id_comment" id="id_comment" value=''>
                                                            <div class="col-12 comment_online">
                                                                <textarea id="textarea_comment" class="textarea_emoji"></textarea>
                                                            </div>
                                                            <div class="col-12 text-right my-1">
                                                                <button class="btn cancel_edit_comment" onclick="cancelEdit({{ $item->id }})"><i class="fas fa-ban"></i> {{ trans('laother.unediting') }}</button>
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
                                            </div>

                                            {{--  Mô hình Kirkpatrick  --}}
                                            <div class="tab-pane fade" id="nav-rating-level" role="tabpanel">
                                                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-rating-level">
                                                    <thead>
                                                    <tr>
                                                        <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">{{ trans('latraining.assessments') }}</th>
                                                        <th data-field="rating_name">{{ trans('latraining.rating_name') }}</th>
                                                        <th data-field="rating_time">{{ trans('latraining.time_rating') }}</th>
                                                        <th data-field="rating_status" data-align="center">{{ trans('latraining.status') }}</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-share-key" id="modal-share-key" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Share key</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <span id="share_key"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn">{{ trans('labutton.close') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    @include('offline::modal.referer')
    <script>
        window.Rating = {
            route: '{{ route('module.offline.rating',$item->id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);

        $('#notify-course').prop('hidden', true);

        $("#go-course").on('click', function () {
            $('a[data-toggle="tab"]').removeClass('active');
            $('a[data-toggle="tab"]').attr('aria-selected',false);

            $('a[href="#nav-courses"]').attr('aria-selected',true);
            $('a[href="#nav-courses"]').addClass('active');

            $('#nav-tabContent .tab-pane').removeClass('show active');
            $('#nav-courses').addClass('show active');

            $('#go-course').prop('hidden', true);
            $('#notify-course').prop('hidden', false);
        });

        $('#share-course').on('click', function () {
            var share_key = Math.random().toString(36).substring(3);
            $.ajax({
                type: "POST",
                url: "{{ route('module.offline.detail.share_course', ['id' => $item->id, 'type' => 2]) }}",
                data:{
                    share_key: share_key,
                },
                success: function (data) {
                    $('#share_key').html(data);
                    $('#modal-share-key').modal();
                }
            });
        });

        function rating_url_formatter(value, row, index) {
            if(row.rating_level_url){
                return '<a href="'+ row.rating_level_url +'">{{ trans("latraining.evaluate") }}</a>';
            }
            return '{{ trans("latraining.evaluate") }}';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });

        function submitRegister() {
            var form =  $('#frm-course');
            form.submit();
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
            var url = "{{ route('frontend.offline.comment.course_offline', ':id') }}";
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
                cancelEdit();
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        function deleteComment(id) {
            var confirmText = "{{ trans('laforums.want_to_delete') }}";
            var url = "{{ route('frontend.offline.delete.comment.course_offline', ':id') }}";
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

        // TỰ ĐỘNG GHI DANH KHI CLICK VÀO HOẠT ĐỘNG KHÓA HỌC ONNLINE
        function registerActivityOnline(courseId, onlineId) {
            let item = $('.link_course_online_'+ onlineId);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');

            $.ajax({
                type: 'POST',
                url: "{{ route('frontend.offline.auto_register_activity_online') }}",
                dataType: 'json',
                data: {
                    'courseId': courseId,
                    'onlineId': onlineId,
                }
            }).done(function(data) {
                item.html(oldtext);
                window.open(data);
                return false;
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        //ĐÁNH DẤU
        function bookmarkHandle(id) {
            $.ajax({
                type: 'POST',
                url: "{{ route('frontend.offline.bookmark_offline') }}",
                dataType: 'json',
                data: {
                    'id': id,
                }
            }).done(function(data) {
                if(data == 1) {
                    $('.bookmark_course').html('<span><i class="uil uil-heart check-heart"></i>{{ trans("laprofile.bookmarked") }}</span>')
                } else {
                    $('.bookmark_course').html('<span><i class="uil uil-heart"></i>{{ trans("laprofile.bookmark") }}</span>')
                }
                return false;
            }).fail(function(data) {
                show_message("{{ trans('laother.data_error') }}", 'error');
                return false;
            });
        }

        // MESSAGE KTRA LINK TEAMS
        function checkLinkHandle(type) {
            if (type == 1) {
                show_message("{{ trans('latraining.time_join_teams_yet') }}", 'warning')
            } else {
                show_message("{{ trans('latraining.participation_time_out_teams') }}", 'warning')
            }
        }
    </script>
@stop
