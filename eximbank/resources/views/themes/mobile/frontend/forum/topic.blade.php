@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.forum'))

@section('content')
    <div class="container forum_topic">
        <div class="row mt-1">
            <div class="col-4">
                <button class="btn mb-2" onclick="window.location.href='{{route('themes.mobile.frontend.forums.form',['id' => $forum->id])}}'">@lang('app.send_new_posts')</button>
            </div>
            <div class="col-8 pl-0">
                <form method="get" class="input-group form-search border-0">
                    <input type="text" name="q" class="form-control" placeholder="{{ data_locale('Nhập tên bài viết', 'Enter topic name') }}" value="{{ request()->get('q') }}">
                    <button type="submit" class="btn btn-link text-white position-relative text-right">
                        <i class="material-icons vm">search</i>
                    </button>
                </form>
            </div>
        </div>
        <br>
        @foreach($forum_thread as $item)
{{--            <div class="card shadow border-0 mb-1">--}}
{{--                <div class="card-body">--}}
                    <div class="row align-items-center">
                        <div class="col-3 pr-0">
                            <img src="{{ asset('themes/mobile/img/forum.png') }}" alt="" class="icons-raised avatar avatar-50 no-shadow border-0">
                        </div>
                        <div class="col-9 align-self-center pr-0 pl-0">
                            <h6 class="font-weight-normal mb-1 title">
                                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.forums.thread',['id' => $item->id]) }}')" class="forum-item-title">
                                    {{ \Str::limit($item->title) }}
                                </a>
                            </h6>
                            <p class="text-mute">
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }} .
                                <img src="{{ (\App\Models\Profile::avatar($item->updated_by ? $item->updated_by : $item->created_by)) }}" alt="" class="avatar avatar-30 border-0">
                                {{ \App\Models\Profile::fullname($item->updated_by ? $item->updated_by : $item->created_by) }}
                            </p>
                            <p class="text-mute text-secondary text-center">
                                <span class="row">
                                    <span class="col total_views">
                                        {{ $item->views }} Views
                                    </span>
                                    <span class="col border-left total_comment">
                                        {{ $forum_threat_count($item->id) }} Comment
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>
{{--                </div>--}}
{{--            </div>--}}
            <hr>
        @endforeach
        @include('themes.mobile.layouts.paginate', ['items' => $forum_thread])
    </div>
@endsection
