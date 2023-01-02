@extends('react.layouts.app')
@section('page_title', trans('laguide.guide'))

@section('content')
    <div id="languages"
        data-guide="{{ trans('laguide.guide') }}"
        data-download="{{ trans('laguide.download') }}"
        data-delete="{{ trans('labutton.delete') }}"
        data-watch_online="{{ trans('laguide.watch_online') }}"
        data-guide_post="{{ trans('laguide.guide_post') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
