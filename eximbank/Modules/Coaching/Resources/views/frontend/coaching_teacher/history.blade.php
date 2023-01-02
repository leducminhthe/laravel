@extends('layouts.app')

@section('page_title', trans('latraining.history'))

@section('content')
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
                                        <a href="{{ route('module.coaching.frontend') }}">Coaching</a>
                                        <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">{{ trans('latraining.history') }}</span>
                                    </h2>
                                </div>
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

                                                if ($teacher_register->score_teacher_comment >= $teacher_register->score_training_objectives) {
                                                    $text_result = trans('latraining.passed');
                                                }elseif ($teacher_register->metor_again == 1) {
                                                    $text_result = trans('latraining.metor_again');
                                                }else {
                                                    $text_result = trans('latraining.not_achieved');
                                                }
                                            }elseif (get_date($teacher_register->start_date, 'Y-m-d') <= date('Y-m-d') && $teacher_register->plan_content) {
                                                $text_status = trans('latraining.processing');
                                            }else {
                                                $text_status = trans('latraining.unfulfilled');
                                            }
                                        @endphp
                                        <div class="{{ $key }} bg-white p-2 mb-2 border">
                                            {{ trans('latraining.student') .': '. $teacher_register->lastname .' '. $teacher_register->firstname .' ('. $teacher_register->user_code .')' }} <br>
                                            {{ trans('latraining.content_skills') .': '. $teacher_register->content }} <br>
                                            <i class="far fa-calendar-alt"></i> {{ get_date($teacher_register->start_date) }} <i class="fa fa-arrow-right"></i> {{ get_date($teacher_register->end_date) }} <br>
                                            {{ trans('latraining.status') .': '. $text_status }} <br>
                                            {{ trans('latraining.result') .': '. $text_result }} <br>
                                            <a href="{{ route('module.coaching.frontend.edit_content_skill', ['id' => $teacher_register->id, 'coaching_teacher_user_id' => $teacher_register->coaching_teacher_user_id]) }}" class="btn">
                                                {{ trans('latraining.evaluate') }}
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
@stop

