@extends('layouts.app')

@section('page_title', trans('laother.title_project'))

@section('header')
    <link rel="stylesheet" href="{{ myasset('css/home_after_login.css') }}">
@endsection

@section('content')
    <div class="sa4d25 home_after_login">
        <div class="md:flex md:flex-wrap">
            <div class="wow fadeIn px-4 mb-8 h-lg md:w-1/2 lg:w-1/2" style="visibility: visible; animation-name: fadeIn;">
                <a href="{{ route('frontend.all_course',['type' => 3]) }}" class=" h-full w-full rounded overflow-hidden bg-cover bg-center bg-no-repeat flex hover:shadow hover:opacity-75 transition duration-700 ease-in-out overlay overlay--half-blue item-hover" style="background-image: url({{ myasset('images/course_2.jpg') }});">
                    <div class="mt-auto p-6 z-10 relative w-full">
                        <div class="text-white item-hover-center">
                            <h3 class="mb-0">Khoá học trực tuyến</h3>
                            <span style="font-size: 27px">E-learning</span>
                        </div>
                        <span class="view_detail btn">Xem chi tiết</span>
                    </div>
                </a>
            </div>
            <div class="wow fadeIn px-4 mb-8 h-lg md:w-1/2 lg:w-1/2" style="visibility: visible; animation-name: fadeIn;">
                <a href="{{ route('library',['type' => 2]) }}" class=" h-full w-full rounded overflow-hidden bg-cover bg-center bg-no-repeat flex hover:shadow hover:opacity-75 transition duration-700 ease-in-out overlay overlay--half-blue item-hover" style="background-image: url({{ myasset('images/lib_2.jpg') }});">
                    <div class="mt-auto p-6 z-10 relative w-full">
                        <div class="text-white item-hover-center">
                            <h3 class="mb-0">Kho sách điện tử, tài liệu </h3>
                            <span style="font-size: 27px">E-book</span>
                        </div>
                        <span class="view_detail btn">Xem chi tiết</span>
                    </div>
                </a>
            </div>
            <div class="wow fadeIn px-4 mb-8 h-lg md:w-1/2 lg:w-1/3" style="visibility: visible; animation-name: fadeIn;">
                <a href="{{ route('quiz_react') }}" class=" h-full w-full rounded overflow-hidden bg-cover bg-center bg-no-repeat flex hover:shadow hover:opacity-75 transition duration-700 ease-in-out overlay overlay--half-blue item-hover" style="background-image: url({{ myasset('images/quiz.jpg') }});">
                    <div class="mt-auto p-6 z-10 relative w-full">
                        <h3 class="text-white item-hover-center">
                            Kỳ thi kiểm tra
                        </h3>
                        <span class="view_detail btn">Xem chi tiết</span>
                    </div>
                </a>
            </div>
            <div class="wow fadeIn px-4 mb-8 h-lg md:w-1/2 lg:w-1/3" style="visibility: visible; animation-name: fadeIn;">
                <a href="{{ route('guide_react', ['type' => 1]) }}" class=" h-full w-full rounded overflow-hidden bg-cover bg-center bg-no-repeat flex hover:shadow hover:opacity-75 transition duration-700 ease-in-out overlay overlay--half-blue item-hover" style="background-image: url({{ myasset('images/HdSD_1.jpg') }});">
                    <div class="mt-auto p-6 z-10 relative w-full">
                        <h3 class="text-white item-hover-center">
                            Tài liệu hướng dẫn sử dụng
                        </h3>
                        <span class="view_detail btn">Xem chi tiết</span>
                    </div>
                </a>
            </div>
            <div class="wow fadeIn px-4 mb-8 h-lg md:w-1/2 lg:w-1/3" style="visibility: visible; animation-name: fadeIn;">
                <div class="h-full w-full rounded overflow-hidden bg-cover bg-center bg-no-repeat flex hover:shadow hover:opacity-75 transition duration-700 ease-in-out overlay overlay--half-blue item-hover" style="background-image: url({{ myasset('images/mobile_app_2.jpg') }});">
                    <div class="mt-auto p-6 z-10 relative w-full">
                        <div class="text-white item-hover-center">
                            <h3 class="mb-0">Mobile App </h3>
                            <span style="font-size: 27px">(Android, IOS)</span>
                        </div>
                        <div class="view_detail_mobile_app">
                            <a href="{{ @$app_android->link }}" @if($app_android) target="_blank" @endif><span class="btn">Android</span></a>
                            <a href="{{ @$app_apple->link }}" @if($app_apple) target="_blank" @endif><span class="btn">IOS</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
