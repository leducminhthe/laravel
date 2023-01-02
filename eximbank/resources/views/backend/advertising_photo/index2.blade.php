@extends('layouts.backend')

@section('page_title', trans('backend.advertising_photo'))

@php
    $tabs = Request::segment(3);
@endphp

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.advertising_photo'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="course_tabs" id="my-course">
                <nav>
                    <div class="nav nav-pills mb-4 tab_crse" id="nav-tab" role="tablist">
                        @can('advertising-photo')
                            <a class="nav-item nav-link @if ($tabs == '1')
                                active
                                @endif" id="nav-news-tab" href="{{ route('backend.advertising_photo',['type' => 1]) }}" >{{ trans('lamenu.news_list') }}
                            </a>

                            {{-- <a class="nav-item nav-link @if ($tabs == '0')
                                active
                                @endif" id="nav-news-outside-tab" href="{{ route('backend.advertising_photo',['type' => 0]) }}" >{{ trans('lamenu.news_list_outside') }}
                            </a> --}}
                        @endcan
                    </div>
                </nav>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @switch(Request::segment(3))
                            @case('1')
                                @include('backend.advertising_photo.index')
                                @break
                            @case('0')
                                @include('backend.advertising_photo.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
