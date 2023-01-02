@extends('themes.mobile.layouts.app')

@section('page_title', 'FAQ')

@section('header')
    <style>
        #faq .card-body img{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div id="faq">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mt-2 p-1">
                    <form method="get" action="" id="form-search" class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Tìm kiếm', 'Search') }}" value="{{ request()->get('search') }}" onchange="submit();">
                    </form>
                </div>
                <div class="col-12 p-1">
                    @foreach($faqs as $faq)
                        <div class="mb-1 border-bottom shadow-sm">
                            <div class="text-primary" data-toggle="collapse" data-target="#question{{ $faq->id }}" aria-expanded="true" aria-controls="question{{ $faq->id }}">
                                <div class="py-2 title_question_faq row m-0">
                                    <span class="col">{{ $faq->name }}</span>
                                    <span class="col-auto float-right"><i class="fa fa-chevron-down"></i></span>
                                </div>
                            </div>
                            <div id="question{{ $faq->id }}" class="collapse" data-parent="#faq">
                                <div class="text-justify">
                                    {!! $faq->content !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
