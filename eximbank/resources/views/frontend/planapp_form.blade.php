@extends('layouts.app')

@section('page_title', 'Lập đánh giá hiệu quả đào tạo')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/profile.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/prism.css') }}">
    <script language="javascript" src="{{ asset('styles/module/planapp/js/plan_app.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid" id="trainingroadmap">
        <form name="frmPlanApp" method="post" action="{{ route('frontend.plan_app.form', ['course' => $course->course_id, 'type' => $course->course_type]) }}" class="form-validate form-ajax">
            <div class="planappform">
                <div align="center"><h2>Đánh giá hiệu quả đào tạo</h2></div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td scope="row">{{ trans('backend.course') }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ trans('backend.employee_name') }}</td>
                                <td>{{ $profile->full_name }} ({{ $profile->code }})</td>
                            </tr>
                            <tr>
                                <td scope="row">{{ trans('latraining.time') }}</td>
                                <td>{{ get_date($course->start_date) . ($course->end_date ? ' - '.get_date($course->end_date) : '') }}</td>
                                <td>Năm sinh</td>
                                <td>{{ get_date($profile->dob) }}</td>
                            </tr>
                            <tr>
                                <td scope="row">{{ trans('backend.locations') }}</td>
                                <td>{{ $course->training_location_name }}</td>
                                <td>{{ trans('latraining.title') }}</td>
                                <td>{{ $profile->title_name }}</td>
                            </tr>
                            <tr>
                                <td scope="row">Biểu mẫu</td>
                                <td>{{ $course->plan_app_template_name }}</td>
                                <td>{{ trans('backend.business_unit_name') }}</td>
                                <td>{{ $profile->unit_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @foreach($plan_app_template_cate as $item)
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{!! $item->sort !!}. {!! ucfirst($item->name) !!}</h5>
                        </div>
                        <div class="col-md-4 pull-right text-right pb-1">
                            @if($enable != 'disabled')
                            <a data-cate="{{ $item->id }}" class="add_item btn {{$enable}}"><i class=" fa fa-plus"></i> Thêm tiêu chí</a>
                            @endif
                        </div>
                    </div>
                    @php
                        $plan_item = \App\Http\Controllers\Frontend\PlanAppController::getPlanAppItem($item->id);
                        $plan_item_user = \App\Http\Controllers\Frontend\PlanAppController::getPlanAppItemUser($item->id);
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm tblBinding_{{ $item->id }}" data-cate="{{ $item->id }}">
                            <thead>
                                <tr>
                                    @foreach($plan_item as $value)
                                        <th class="text-center">{!! $value->name !!}</th>
                                    @endforeach
                                    @if($enable != 'disabled')
                                    <th class="text-center">{{ trans('labutton.delete') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($plan_item_user)>0)
                                    @foreach($plan_item_user as $index => $value)
                                    <tr>
                                        <input type="hidden" value="{{ $value->id }}" name="item_id[{{$item->id}}][]">
                                        <td>
                                            <input type="text" class="form-control" name="name[{{$item->id}}][]" value="{{ $value->name }}">
                                        </td>
                                        <td>
                                            <input type="{{ $plan_item[1]->data_type == 1 ? 'text' : 'number' }}" class="form-control" name="item_1[{{$item->id}}][]" value="{{ $value->criteria_1 }}">
                                        </td>
                                        <td>
                                            <input type="{{ $plan_item[2]->data_type == 1 ? 'text' : 'number' }}" class="form-control" name="item_2[{{$item->id}}][]" value="{{ $value->criteria_2 }}">
                                        </td>
                                        <td>
                                            <input type="{{ $plan_item[3]->data_type == 1 ? 'text' : 'number' }}" class="form-control" name="item_3[{{$item->id}}][]" value="{{ $value->criteria_3 }}">
                                        </td>
                                        @if($index > 0 && $enable != 'disabled')
                                        <td class="text-center">
                                            <a href="javascript:void(0)" data-id="{{ $value->id }}"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        @foreach($plan_item as $value)
                                            @if($value->sort==1)
                                                <td>
                                                    <input type="{{ $value->data_type == 1 ? 'text' : 'number' }}" class="form-control" name="name[{{$item->id}}][]">
                                                </td>
                                            @else
                                                <td>
                                                    <input type="{{ $value->data_type == 1 ? 'text' : 'number' }}" class="form-control" name="item_{{$value->sort-1}}[{{$item->id}}][]">
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach
                <div class="text-center">
                    @if($enable != 'disabled')
                        <button type="submit" class="btn" name="btn_save" value="1">
                            <i class="fa fa-floppy-o"></i> Lưu
                        </button>
                        <button type="submit" class="btn" name="btn_save" value="2" id="send-mail-approve">
                            <i class="fa fa-paper-plane"></i> Lưu & gửi
                        </button>
                        <input type="hidden" name="user_id" value="{{ $profile->user_id }}">
                        <input type="hidden" name="course_id" value="{{ $course->course_id }}">
                        <input type="hidden" name="course_type" value="{{ $course->course_type }}">
                    @endif
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $("#send-mail-approve").on('click', function () {
            var user_id = $("input[name=user_id]").val();
            var course_id = $("input[name=course_id]").val();
            var course_type = $("input[name=course_type]").val();
            $.ajax({
                url: base_url +'/plan-app/send-mail-approve',
                type: 'post',
                data: {
                    user_id: user_id,
                    course_id: course_id,
                    course_type: course_type,
                }
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>
@stop
