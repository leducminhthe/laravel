@extends('themes.mobile.layouts.app')

@section('page_title', trans('lamenu.compete_title'))

@section('header')
    <style>
        #faq .card-body img{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div id="list_hight_score">
            <div class="row">
                <div class="col-12 p-1">
                    @if (count($emulation_badges) > 0)
                        <ul class="list-group list-group-flush border-top">
                            @foreach ($emulation_badges as $emulation_badge)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-3 p-0 d-flex align-items-center">
                                            <img src="{{ $emulation_badge->image }}" alt="" class="w-100">
                                        </div>
                                        <div class="col-9 align-self-center pr-0">
                                            <p class="mb-1">
                                                {{ $emulation_badge->name }}
                                            </p>
                                            <p class="text-mute">
                                                {{ $emulation_badge->start_time }} <i class="fa fa-arrow-right"></i> {{ $emulation_badge->end_time }} <br>
                                                {{ trans('app.rank') .': '. $emulation_badge->level }} <br>   
                                                {{ $emulation_badge->name_armorial }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
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
@endsection
