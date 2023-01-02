@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.forum'))

@section('content')
    <div class="container wrraped_forum">
        @foreach($forum_categories as $forum_category)
            <div class="row">
                <div class="col-12 bg-white mb-1 mt-1">
                    <h6 class="category_title"><img src="{{ asset('themes/mobile/img/comment.png') }}" alt="" class="avatar-30 no-shadow border-0"> {{ $forum_category->name }}</h6>
                </div>
            </div>

            @php
                $forums = $forum_category->topic()->orderBy('num_topic', 'DESC')->orderBy('num_comment', 'DESC')->get();
            @endphp
            @foreach($forums as $item)
                <div class="card shadow border-0 mb-1">
                    <div class="card-body p-1">
                        <div class="row align-items-center">
                            <div class="col-2 pr-0">
                                <img src="{{ image_forum_2(@$item->icon) }}" alt="" class="icons-raised avatar avatar-40 no-shadow border-0">
                            </div>
                            <div class="col-10 align-self-center">
                                <p class="font-weight-normal title_category_child">
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.forums.topic', ['id' => $item->id]) }}')" class="forum-item-title">
                                        {{ $item->name }}
                                    </a>
                                    <span class="row small text-center text-mute ">
                                        <span class="col p-0">
                                            {{ $item->getTotalViews() ? $item->getTotalViews() : 0 }} Views
                                        </span>
                                        <span class="col p-0 border-left">
                                           {{ $item->thread->count() ? $item->thread->count() : 0 }} Topics
                                        </span>
                                        <span class="col p-0 border-left">
                                            {{ $item->getTotalComment() ? $item->getTotalComment() : 0 }} Comment
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
@endsection
