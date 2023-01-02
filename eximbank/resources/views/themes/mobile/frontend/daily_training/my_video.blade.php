@extends('themes.mobile.layouts.app')

@section('page_title', 'Video')

@section('content')
    <div class="container" id="daily_training_mobile">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('themes.mobile.daily_training.frontend.my_video') }}" id="form_search" method="GET" class="w-100">
                <select name="cate_id" class="select2 form-control w-100"  onchange="submit();">
                    <option value="" disabled selected>{{ trans('lamenu.category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="row news_item">
            <div class="col-12 mb-2">
                <h5><span class="title_type">Video của tôi</span></h5>
            </div>
            @foreach ($videos as $get_daily_training_video_new)
                <div class="col-6 pb-2">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend.detail_video', ['id' => $get_daily_training_video_new->id]) }}')">
                        <img src="{{ image_file($get_daily_training_video_new->avatar) }}" alt="" class="w-100" height="120px" style="object-fit: cover">
                    </a>
                    <span class="title_daily_video">{{ $get_daily_training_video_new->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endsection
