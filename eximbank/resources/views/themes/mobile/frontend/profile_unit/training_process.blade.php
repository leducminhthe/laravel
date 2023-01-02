@extends('themes.mobile.layouts.app')

@section('page_title', 'Quá trình học')

@section('content')
    <div class="container wrraped_training_process">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush" id="training-process">
                    @if(count($get_history_course) > 0)
                        @foreach($get_history_course as $item)
                            <div class="row mb-2 bg-white shadow border">
                                <div class="col-auto align-self-center">
                                    @if($item->result)
                                        <img src="{{ asset('themes/mobile/img/course_icon.png') }}" alt="" class="avatar-40">
                                    @else
                                        <img src="{{ asset('themes/mobile/img/desktop-pc.png') }}" alt="" class="avatar-40">
                                    @endif
                                </div>

                                <div class="col pl-0 info">
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ $item->url }}')">
                                        {{ $item->name }}
                                    </a>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                                    </p>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ round($item->percent, 2) }}%
                                        <span class="float-right">
                                            {{ $item->type }}
                                        </span>
                                    </p>
                                    <p class="text-mute mt-1">
                                        <span class="{{ $item->result ? 'complete_process' : 'uncomplete_process' }}">
                                            {{ $item->result ? trans('app.completed') : trans('app.uncomplete') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $get_history_course->links('themes/mobile/layouts/pagination') }}
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
