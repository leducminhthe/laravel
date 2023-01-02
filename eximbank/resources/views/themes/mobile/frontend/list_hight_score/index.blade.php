@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.top_students_high_scores'))

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
                <div class="col-xl-12 col-lg-12 col-md-12 mt-2">
                    <form method="get" action="" id="form-search" class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Tìm kiếm', 'Search') }}" value="{{ request()->get('search') }}" onchange="submit();">
                    </form>
                </div>
                <div class="col-12 p-1">
                    @foreach($user as $item)
                        <div class="card mb-2 bg-white shadow border">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto pr-0">
                                        <img src="{{ (\App\Models\Profile::avatar($item->user_id)) }}" alt="" class="avatar avatar-50 border-0">
                                    </div>
                                    <div class="col align-self-center">
                                        <p class="mb-1">
                                            {{ \App\Models\Profile::fullname($item->user_id) }}
                                        </p>
                                        <p class="text-mute">
                                            ({{ \App\Models\Profile::usercode($item->user_id) }})
                                            <span class="float-right">
                                                {{ $item->point }}
                                                <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
