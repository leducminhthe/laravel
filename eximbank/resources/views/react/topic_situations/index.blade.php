@extends('react.layouts.app')
@section('page_title', trans('lahandle_situations.topic_situation'))

@section('header')
    <link rel="stylesheet" href="{{ mix('css/topic_situation.css') }}">
@endsection

@section('content')
    <div id="languages"
        data-topic_situation="{{ trans('lahandle_situations.topic_situation') }}"
        data-enter_name_topic="{{ trans('lahandle_situations.enter_code_name_situations') }}"
        data-date_created="{{ trans('lahandle_situations.date_created') }}"
        data-comment="{{ trans('lahandle_situations.comment') }}"
        data-likes="{{ trans('lahandle_situations.likes') }}"
        data-code="{{ trans('lahandle_situations.code') }}"
        data-description="{{ trans('lahandle_situations.description') }}"
        data-write_comment="{{ trans('lahandle_situations.write_comment') }}"
        data-like="{{ trans('lahandle_situations.like') }}"
        data-replied="{{ trans('lahandle_situations.replied') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25 wrapped_situation_react">
                    
    </div>
@endsection
