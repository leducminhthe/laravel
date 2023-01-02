@extends('react.layouts.app')
@section('page_title', trans('lasuggest.suggest'))

@section('content')
    <div id="languages"
        data-suggest="{{ trans('lasuggest.suggest') }}"
        data-date_created="{{ trans('lasuggest.date_created') }}"
        data-answered="{{ trans('lasuggest.answered') }}"
        data-comment="{{ trans('lasuggest.comment') }}"
        data-enter_suggest="{{ trans('lasuggest.enter_suggest') }}"
        data-time="{{ trans('lasuggest.time') }}"
        data-content="{{ trans('lasuggest.content') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
