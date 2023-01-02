@extends('react.layouts.app')
@section('page_title', trans('lanews.news'))

@section('header')
    <link rel="stylesheet" href="{{ asset('css/menu_new_inside.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">
@endsection

@section('content')
    <div id="icon_news" 
        data-icon="{{ asset('images/home_outside.png') }}" 
    >
    </div>
    <div id="languages"
        data-featured_news="{{ trans('lanews.featured_news') }}"
        data-view_more="{{ trans('lanews.view_more') }}"
        data-related_news="{{ trans('lanews.related_news') }}"
        data-most_view="{{ trans('lanews.most_view') }}"
        data-like="{{ trans('lanews.like') }}"
        data-date_submit="{{ trans('lanews.date_submit') }}"
        data-most_view_post="{{ trans('lanews.most_view_post') }}"
        data-most_liked_post="{{ trans('lanews.most_liked_post') }}"
    >
    </div>
    <div id="react" class="sa4d25 wrapped_news_react">
                    
    </div>
@endsection
