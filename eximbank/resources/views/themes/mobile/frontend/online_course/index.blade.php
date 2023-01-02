@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.onl_course'))

@section('content')
    <div class="container wrapped_online_courses">
        @if(count($items) > 0)
            <div class="row">
                @foreach($items as $online)
                    @php
                        $check_promotion_course_setting = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$online->id)->exists();
                        $status = !userThird() ? $online->getStatusRegister() : 4;
                        $text = $online->getStatusRegisterText($status);
                        $class_color = $online->getBtnClassStatusRegister($status);
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                        <div class="card shadow border-0 mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 p-1">
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.online.detail', ['course_id' => $online->id]) }}', 0, 1)">
                                            <img src="{{ image_online($online->image) }}" alt="" class="w-100 image_online">
                                        </a>
                                    </div>
                                    <div class="col-12 p-1 align-self-center">
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                @if ($check_promotion_course_setting)
                                                    <span onclick="openModalBonus({{$online->id}})">
                                                        <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="20px" height="15px">
                                                        {{ data_locale('Điểm thưởng', 'Points') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.online.detail', ['course_id' => $online->id]) }}', 0, 1)">
                                            <div class="my-1 title">
                                                <span class="font-weight-normal h6 mb-0">{{ $online->name }}</span>
                                                {{--  <span class="text-mute">({{ $online->code }})</span>  --}}
                                            </div>
                                        </a>
                                        <div class="text-mute">
                                            <p class="mb-0">
                                                <span onclick="openModalSummary({{ $online->id }}, 1)" class="vdt14" style="cursor: pointer"><b>{{ trans('latraining.description') }}:</b> {{ trans('latraining.brief') }}</span> |
                                                <span onclick="openModalDescription({{ $online->id }}, 1)" style="cursor: pointer">{{ trans('latraining.detail') }}</span>
                                            </p>
                                            <b><i class="fa fa-calendar-alt" aria-hidden="true"></i> </b> {{ get_date($online->start_date) }} @if($online->end_date) {{' - '. get_date($online->end_date) }} @endif
                                            <br>
                                            <b><i class="fas fa-clock" aria-hidden="true"></i> @lang('app.register_deadline'):</b> {{ get_date($online->register_deadline) }}
                                            <br>
                                            @if ($status == 1)
                                                <form action="{{ route('module.online.register_course', ['id' => $online->id]) }}" method="post" class="form-ajax" id="form_register">
                                                    @csrf
                                                    <div class="item item-btn text-right">
                                                        <button type="submit" class="btn">{{ $text }}</button>
                                                    </div>
                                                </form>
                                            {{--  @elseif ($status == 4)
                                                <a href="{{ route('themes.mobile.frontend.online.detail.go_activity', ['course_id' => $online->id]) }}" class="btn float-right">
                                                    {{ $text }}
                                                </a>  --}}
                                            @else
                                                <p class="my-1 text-right {{ $class_color }}">{{ $text }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- MODAL ĐIỂM THƯỞNG --}}
                    <div class="modal fade modal-add-activity" id="modal-bonus-{{$online->id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">
                                        <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="20px" height="15px">
                                        Điểm thưởng
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    @php
                                        $arr_code = [
                                            'assessment_after_course' => 'Đánh giá sau khóa học',
                                            'evaluate_training_effectiveness' => 'Đánh giá hiệu quả đào tạo',
                                            'rating_star' => 'Đánh giá sao',
                                            'share_course' => 'Share khóa học'
                                        ];
                                        $complete = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($online->id, 1, 'complete');
                                        $landmarks = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($online->id, 1, 'landmarks');
                                        $rating_star = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($online->id, 1, 'rating_star');
                                    @endphp
                                    <div class="promotion-enable">
                                        <div class="form-group row">
                                            <div class="col-sm-3 control-label">
                                                <h6>{{ trans('backend.scoring_method') }}:</h6>
                                            </div>
                                            <div class="col-md-9">
                                                @if ($complete)
                                                    <div class="form-check form-check-inline">
                                                        <div class="custom-control custom-radio promotion_0_radio">
                                                            <input type="radio" class="custom-control-input point-type" id="promotion_0_{{$online->id}}" onclick="checkBoxBonus({{$online->id}})" name="method" value="0">
                                                            <label class="custom-control-label" for="promotion_0_{{$online->id}}">{{ trans('backend.complete_course') }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($landmarks)
                                                    <div class="form-check form-check-inline promotion_1_radio">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input point-type" id="promotion_1_{{$online->id}}" onclick="checkBoxBonus({{$online->id}})" name="method" value="1">
                                                            <label class="custom-control-label" for="promotion_1_{{$online->id}}">{{ trans('backend.landmarks') }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($rating_star)
                                                    <div class="form-check form-check-inline promotion_2_radio">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input point-type" id="promotion_2_{{$online->id}}" onclick="checkBoxBonus({{$online->id}})" name="method" value="2">
                                                            <label class="custom-control-label" for="promotion_2_{{$online->id}}">{{ trans('backend.other') }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($complete)
                                            <div class="promotion_0_group_{{$online->id}}">
                                                <div class="form-group row">
                                                    <div class="col-sm-3 control-label"></div>
                                                    <div class="col-md-9">
                                                        <input name="start_date" style="width: 115px" readonly type="text" class="form-control d-inline-block datepicker" placeholder="Bắt đầu" autocomplete="off" value="{{ $complete && $complete->start_date ? get_date($complete->start_date) : '' }}">
                                                        <input name="end_date" style="width: 115px" readonly type="text" class="form-control d-inline-block datepicker" placeholder="Kết thúc" autocomplete="off" value="{{ $complete && $complete->end_date ? get_date($complete->end_date) : '' }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-3 control-label"></div>
                                                    <div class="col-md-4">
                                                        <input name="point_complete" readonly type="text" class="form-control" placeholder="{{ trans('backend.bonus_points') }}" autocomplete="off" value="{{ $complete ? $complete->point : '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($landmarks)
                                            <div class="promotion_1_group_{{$online->id}}">
                                                <div class="row promotion-table">
                                                    <div class="col-md-12">
                                                        <table class="tDefault table table-hover bootstrap-table" id="table_setting_{{$online->id}}">
                                                            <thead>
                                                            <tr>
                                                                <th data-align="center" data-width="3%" data-formatter="stt_formatter_bonus">STT</th>
                                                                <th data-field="score" data-align="center">{{ trans('backend.landmarks') }}</th>
                                                                <th data-field="point" data-align="center">{{ trans('backend.bonus_points') }}</th>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="promotion_2_group_{{$online->id}}">
                                            @foreach($arr_code as $key => $code)
                                                @php
                                                    $other = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($online->id, 1, $key);
                                                @endphp
                                                @if ($other)
                                                    <div class="form-group row">
                                                        <div class="col-sm-3 control-label"></div>
                                                        <div class="col-md-4">
                                                            {{ $code }}
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input name="point[]" readonly type="text" class="form-control" placeholder="{{ trans('backend.bonus_points') }}" autocomplete="off" value="{{ $other ? $other->point : '' }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        var complete = '<?php echo $complete  ?>';
                        var landmarks = '<?php echo $landmarks  ?>';
                        var other = '<?php echo $other ?>';
                        if (landmarks !== '' && other == '' && complete == '') {
                            $(".promotion_0_group_{{$online->id}}").hide();
                            $(".promotion_1_group_{{$online->id}}").show();
                            $(".promotion_2_group_{{$online->id}}").hide();
                        } else if (landmarks == '' && other !== '' && complete == '') {
                            $(".promotion_0_group_{{$online->id}}").hide();
                            $(".promotion_1_group_{{$online->id}}").hide();
                            $(".promotion_2_group_{{$online->id}}").show();
                        } else {
                            $(".promotion_0_group_{{$online->id}}").show();
                            $(".promotion_1_group_{{$online->id}}").hide();
                            $(".promotion_2_group_{{$online->id}}").hide();
                        }
                    </script>
                @endforeach
            </div>
            @if(\App\Models\Profile::usertype() != 2)
                @include('themes.mobile.layouts.paginate', ['items' => $items])
            @endif
        @else
            <div class="row">
                <div class="col text-center">
                    <span>@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('modal')
    {{-- MODAL LINK SHARE --}}
    <div class="modal fade modal-add-activity" id="modal-share">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Share link khóa học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-share">
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn" onclick="copyShare()">Copy</button> --}}
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    @include('themes.mobile.frontend.modal_course')
@endsection

@section('footer')
    <script type="text/javascript">
        $('#filterOnline').on('click', '.list-group-item', function () {
            var type = $(this).data('type');
           $('#filterOnline .list-group-item').find('.icon').remove();
           $(this).append('<span class="icon float-right"><i class="material-icons text-primary">check</i></span>');
           var url = "{{ route('themes.mobile.frontend.online.index').'?type=' }}"+type;
           $('#search-online').attr('href', url);
        });

        // Điểm thưởng
        function stt_formatter_bonus(value, row, index) {
            return (index + 1);
        }

        function openModalBonus(id) {
            $('#modal-bonus-'+id).modal();
        }

        function checkBoxBonus(id) {
            if ($("#promotion_0_"+id).is(":checked")) {
                $(".promotion_0_group_"+id).show();
                $(".promotion_1_group_"+id).hide();
                $(".promotion_2_group_"+id).hide();
            }

            if ($("#promotion_1_"+id).is(":checked")) {
                $(".promotion_0_group_"+id).hide();
                $(".promotion_1_group_"+id).show();
                $(".promotion_2_group_"+id).hide();
                var url = "{{ route('module.promotion.get_setting', ['courseId' => ':id', 'course_type' => 1, 'code' => 'landmarks']) }}";
                url = url.replace(':id',id);
                var table_bonus = new LoadBootstrapTable({
                    locale: '{{ \App::getLocale() }}',
                    url: url,
                    table: '#table_setting_'+id
                });
            }

            if ($("#promotion_2_"+id).is(":checked")) {
                $(".promotion_0_group_"+id).hide();
                $(".promotion_1_group_"+id).hide();
                $(".promotion_2_group_"+id).show();
            }
        }

        // Share khóa học
        function shareCourse(id) {
            var share_key = Math.random().toString(36).substring(3);
            var url = "{{ route('module.online.detail.share_course', ['id' => ':id', 'type' => 1]) }}";
            url = url.replace(':id',id);
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    share_key: share_key,
                },
                success: function (data) {
                    var url_link = "{{ route('module.online.detail', ['id' => ':id']).'?share_key=' }}";
                    url_link = url_link.replace(':id',id);
                    $('#modal-body-share').html(`<b>Link share:</b> <span id="link_share">`+ url_link + data.key + `</span>`)
                    $('#modal-share').modal();
                }
            });
        }

        function copyShare() {
            var copyText = $("#link_share").get(0);

            if(window.getSelection) {
                var selection = window.getSelection();
                var range = document.createRange();
                range.selectNodeContents(copyText);
                selection.removeAllRanges();
                selection.addRange(range);
                document.execCommand("Copy");
            }
        }

        //MÔ TẢ CHI TIẾT KHÓA HỌC
        function openModalDescription(id,type) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.ajax_content_course') }}",
                data:{
                    id: id,
                    type: type,
                },
                success: function (data) {
                    $('#modal-body-description').html(data)
                    $('#modal-description').modal();
                }
            });
        }

        //TÓM TẮT KHÓA HỌC
        function openModalSummary(id,type) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.ajax_summary_course') }}",
                data:{
                    id: id,
                    type: type,
                },
                success: function (data) {
                    $('#modal-body-summary').html(data.description)
                    $('#modal-summary').modal();
                }
            });
        }
    </script>
@endsection
