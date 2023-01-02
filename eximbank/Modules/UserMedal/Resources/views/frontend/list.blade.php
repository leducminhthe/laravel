@extends('layouts.app')

@section('page_title', trans('lamenu.usermedal_setting'))

@section('content')
    <div class="container-fluid wrraped_usermedal">
        <div class="row">
            <div class="col-md-12">
                <div class="_14d25">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ibox-content forum-container">
                                <h2 class="st_title">
                                    <a href="/">
                                        <i class="uil uil-apps"></i>
                                        <span>{{ trans('lamenu.home_page') }}</span>
                                    </a>
                                    <i class="uil uil-angle-right"></i>
                                    <span class="font-weight-bold">{{ trans('lamenu.usermedal_setting') }}</span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 bg-white">
                        <div class="col-md-12 ">
                            <div class="_14d25 mt-3">
                                <div class="row">
                                    @foreach($items as $item)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                                            <div class="row m-0">
                                                <div class="col-12">
                                                    <div class="wrraped_img">
                                                        <a href="{{ route('module.frontend.usermedal.detail',$item->id) }}">
                                                            <img src="{{ image_chuongtrinhthidua($item->photo) }}" alt="" class="w-100 image_usermedal">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mx-0 mb-4 mt-1">
                                                <div class="col-12">
                                                    <a class="crse14s link_daily_training" href="{{ route('module.frontend.usermedal.detail',$item->id) }}" style="line-height: 18px;">
                                                        <span class="daily_name_training">{{ $item->name }}</span>
                                                    </a>
                                                    <p class="text-mute small mb-1">
                                                        <i class="fa fa-calculator"></i>
                                                        {{ date('d/m/Y', $item->start_date) }}
                                                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                        {{ date('d/m/Y', $item->end_date) }}
                                                    </p>
                                                    <p class="text-mute small mb-1" style="line-height: 15px;">
                                                        {!! sub_char($item->content) !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
