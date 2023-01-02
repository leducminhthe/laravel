@extends('layouts.app')

@section('page_title', 'Coaching')

@section('content')
<style>
    #list_teacher_coaching .item{
        min-height: 380px;
    }
    #list_teacher_coaching .technique{
        min-height: 50px;
    }
</style>
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25 pb-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title">
                                        <a href="/">
                                            <i class="uil uil-apps"></i>
                                            <span>{{ trans('lamenu.home_page') }}</span>
                                        </a>
                                        <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">Coaching</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 _14d25">
                                <span class="h3">
                                    Coaching/Mentor ({{ $coaching_teacher->count() }} {{ trans('lareport.teacher') }})
                                </span> | 
                                <span>
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#register_teacher"> 
                                        {{ trans('latraining.register_as_trainer') }}
                                    </a>
                                </span> |
                                <span>
                                    <a href="{{ route('module.coaching.frontend.history') }}"> 
                                        {{ trans('latraining.history') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                        @if ($coaching_teacher)
                        <div class="row mt-2" id="list_teacher_coaching">
                            <div class="col-12">
                                <div class="owl-carousel owl-theme">
                                    @foreach ($coaching_teacher as $key => $teacher)
                                        @php
                                            $total_register = Modules\Coaching\Entities\CoachingTeacherRegister::where('coaching_teacher_id', $teacher->id)->count();

                                            $sum_star = Modules\Coaching\Entities\CoachingTeacherRegister::where('coaching_teacher_id', $teacher->id)->sum('score_student_comment');

                                            $num_star = round($sum_star/$total_register);
                                        @endphp
                                        <div class="item border bg-white">
                                            <img src="{{ image_file($teacher->image) }}" class="card-img-top w-100" alt="">
                                            <div class="card-body">
                                                <div class="">{{ $teacher->user->full_name }}</div>
                                                <div class="technique">
                                                    {{ trans('latraining.technique') .': '. $teacher->technique }}
                                                </div>
                                                <i class="far fa-calendar-alt"></i> {{ get_date($teacher->start_date) }} <i class="fa fa-arrow-right"></i> {{ get_date($teacher->end_date) }} <br>
                                                {{ trans('lamenu.coaching_group') .': '. $teacher->coaching_group->name }} <br>
                                                {{ trans('latraining.number_coaching') .': '. $total_register .'/'.$teacher->number_coaching }} <br>
                                                <div class="rating-box">
                                                    @for ($i = 1; $i <= 10; $i++)
                                                        <span class="rating-star {{ $i <= $num_star ? 'full-star' : 'empty-star' }}"></span>
                                                    @endfor
                                                </div>
                                                @if ($teacher->full_class == 0)
                                                    <a href="{{ route('module.coaching.frontend.create_content_skill', ['coaching_teacher_id' => $teacher->id]) }}" class="btn mt-2">
                                                        {{ trans('laother.register') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mt-2">
                            <div class="col-12 _14d25">
                                <span class="h3">{{ trans('latraining.your_coaching_plan') }}</span> | 
                                <span><a href="{{ route('module.coaching.frontend.create_content_skill') }}">{{ trans('latraining.create_content_skills') }}</a></span> 
                            </div>
                        </div>
                        @if ($coaching_teacher_registers)
                            <div class="row mt-2">
                                <div class="col-12">
                                    @foreach ($coaching_teacher_registers as $key => $teacher_register)
                                        @php
                                            $text_result = '';
                                            if ($teacher_register->score_teacher_comment){
                                                $text_status = trans('latraining.completed');
                                                $text_btn = trans('latraining.review');

                                                if ($teacher_register->score_teacher_comment >= $teacher_register->score_training_objectives) {
                                                    $text_result = trans('latraining.passed');
                                                }elseif ($teacher_register->metor_again == 1) {
                                                    $text_result = trans('latraining.metor_again');
                                                }else {
                                                    $text_result = trans('latraining.not_achieved');
                                                }
                                            }elseif (get_date($teacher_register->start_date, 'Y-m-d') <= date('Y-m-d') && $teacher_register->plan_content) {
                                                $text_status = trans('latraining.processing');
                                                $text_btn = trans('latraining.edit_plan');
                                            }else {
                                                $text_status = trans('latraining.unfulfilled');
                                                $text_btn = trans('latraining.create_plan');
                                            }
                                        @endphp
                                        <div class="{{ $key }} bg-white p-2 mb-2 border">
                                            Coacher: {{ $teacher_register->coaching_teacher->user->lastname .' '. $teacher_register->coaching_teacher->user->firstname }} <br>
                                            {{ trans('latraining.content_skills') .': '. $teacher_register->content }} <br>
                                            <i class="far fa-calendar-alt"></i> {{ get_date($teacher_register->start_date) }} <i class="fa fa-arrow-right"></i> {{ get_date($teacher_register->end_date) }} <br>
                                            {{ trans('latraining.status') .': '. $text_status }} <br>
                                            {{ trans('latraining.result') .': '. $text_result }} <br>
                                            <a href="{{ route('module.coaching.frontend.edit_content_skill', ['id' => $teacher_register->id]) }}" class="btn">
                                                {{ $text_btn }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal đăng ký thành GV --}}
    <div class="modal fade" id="register_teacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('module.coaching.frontend.register_teacher') }}" method="post" class="form-ajax">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('lamenu.teacher_register') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="image">{{ trans('latraining.picture') }} (600 x 400) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <a href="javascript:void(0)" id="select-image">{{ trans('latraining.choose_picture') }}</a>
                                <div id="image-review"></div>
                                <input name="image" id="image-select" type="hidden" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="technique">{{ trans('latraining.technique') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <textarea name="technique" type="text" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.time_coaching') }} <span class="text-danger">*</span></label> 
                            </div>
                            <div class="col-md-9">
                                <span>
                                    <input name="start_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder="{{trans('latraining.start_date')}}" autocomplete="off" value="{{ date('d/m/Y') }}">
                                </span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span>
                                    <input name="end_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder='{{trans("latraining.end_date")}}' autocomplete="off" value="{{ date('t/m/Y') }}">
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="coaching_group_id">{{ trans('lamenu.coaching_group') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select name="coaching_group_id" id="coaching_group_id" class="form-control select2" data-placeholder="{{ trans('lamenu.coaching_group') }}">
                                    <option value=""></option>
                                    @if ($coaching_group)
                                        @foreach ($coaching_group as $group)
                                            <option value="{{ $group->id }}"> {{ $group->name .' ('. $group->code .')' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="number_coaching">{{ trans('latraining.number_coaching') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input name="number_coaching" type="text" class="form-control is-number">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('laother.register') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img src="'+ path +'" class="w-50">');
                $("#image-select").val(path);
            });
        });
    </script>
@stop
@section('footer')
    <script>
        $('#list_teacher_coaching .owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            autoplayTimeout:3000,
            dots: false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:{{ $coaching_teacher->count() < 4 ? $coaching_teacher->count() : 4 }}
                }
            }
        });
    </script>
@endsection
