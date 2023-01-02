@extends('game::layouts.app')
@section('page_title', 'Game')
@section('header')
{{--    <link rel="stylesheet" href="{{ asset('css/menu_new_inside.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/news.css') }}">--}}
@endsection
@section('content')
    <div id="react" class="wrapped_game">
    </div>
@endsection
