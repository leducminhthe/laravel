@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.libraries'))

@section('content')
    <div class="container" id="libraries">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('themes.mobile.libraries.search') }}" id="form_search" method="GET" class="w-100">
                <select name="type" class="select2 form-control w-100"  onchange="submit();">
                    <option value="" disabled selected>Thể loại</option>
                    <option value="1">Sách giấy</option>
                    <option value="2">Sách điện tử</option>
                    <option value="3">Tài liệu</option>
                    <option value="4">Video</option>
                    <option value="5">Sách nói</option>
                </select>
                <input type="hidden" name="cate_id" class="form-control w-100 mt-2" value="0">
                <input type="text" id="search" name="search"  class="form-control w-100 mt-2" placeholder="Nhập tên sách, tác giả.." onchange="searchLibraries()">
            </form>
        </div>

        @if(count($get_news_libraries) > 0)
        <div class="row news_libraries news_item">
            <div class="col-12">
                <h5>
                    <span class="title_type">Sách mới nhất</span>
                </h5>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    @foreach ($get_news_libraries as $get_new_library)
                        @php
                            if ($get_new_library->type == 1){
                                $url = route('themes.mobile.libraries.book.detail', ['id' => $get_new_library->id]);
                            }elseif ($get_new_library->type == 2){
                                $url = route('themes.mobile.libraries.ebook.detail', ['id' => $get_new_library->id]);
                            } elseif ($get_new_library->type == 3){
                                $url = route('themes.mobile.libraries.document.detail', ['id' => $get_new_library->id]);
                            } elseif ($get_new_library->type == 4){
                                $url = route('themes.mobile.libraries.video.detail', ['id' => $get_new_library->id]);
                            } elseif ($get_new_library->type == 5){
                                $url = route('themes.mobile.libraries.audiobook.detail', ['id' => $get_new_library->id]);
                            }
                            $type = $get_new_library->type;
                            $image = $get_new_library->image;
                        @endphp
                        @if($type != 6)
                        <div class="col-4 p-1">
                            <a href="{{ $url }}">
                                <img src="{{ image_library($image) }}" alt="" height="180px" width="100%">
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if (count($get_news_book) > 0)
        <div class="row news_item">
            <div class="col-12">
                <h5><span class="title_type">Sách giấy</span></h5>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    @foreach ($get_news_book as $get_new_book)
                        <div class="col-4 p-1">
                            <a href="{{ route('themes.mobile.libraries.book.detail', ['id' => $get_new_book->id]) }}">
                                <img src="{{ image_library($get_new_book->image) }}" alt="" height="180px" width="100%">
                            </a>
                        </div>
                    @endforeach
                    @if (count($get_news_book) == 6)
                        <div class="col-12 my-2">
                            <a href="{{ route('themes.mobile.libraries.search').'?type=1&cate_id=0' }}" class="see_more">
                                <p class="text-center">{{ trans('laother.show_more') }}</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if (count($get_news_ebook) > 0)
        <div class="row news_item">
            <div class="col-12">
                <h5><span class="title_type">Sách điện tử</span></h5>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    @foreach ($get_news_ebook as $get_new_ebook)
                        <div class="col-4 p-1">
                            <a href="{{ route('themes.mobile.libraries.ebook.detail', ['id' => $get_new_ebook->id]) }}">
                                <img src="{{ image_library($get_new_ebook->image) }}" alt="" height="180px" width="100%">
                            </a>
                        </div>
                    @endforeach
                    @if (count($get_news_ebook) == 6)
                        <div class="col-12 my-2">
                            <a href="{{ route('themes.mobile.libraries.search').'?type=2&cate_id=0' }}" class="see_more">
                                <p class="text-center">{{ trans('laother.show_more') }}</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if (count($get_news_document) > 0)
        <div class="row news_item">
            <div class="col-12">
                <h5><span class="title_type">Tài liệu</span></h5>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    @foreach ($get_news_document as $get_new_document)
                        <div class="col-4 p-1">
                            <a href="{{ route('themes.mobile.libraries.document.detail', ['id' => $get_new_document->id]) }}">
                                <img src="{{ image_library($get_new_document->image) }}" alt="" height="180px" width="100%">
                            </a>
                        </div>
                    @endforeach
                    @if (count($get_news_document) == 6)
                        <div class="col-12 my-2">
                            <a href="{{ route('themes.mobile.libraries.search').'?type=3&cate_id=0' }}" class="see_more">
                                <p class="text-center">{{ trans('laother.show_more') }}</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if (count($get_news_video) > 0)
        <div class="row news_item">
            <div class="col-12">
                <h5><span class="title_type">Video</span></h5>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    @foreach ($get_news_video as $get_new_video)
                        <div class="col-4 p-1">
                            <a href="{{ route('themes.mobile.libraries.video.detail', ['id' => $get_new_video->id]) }}">
                                <img src="{{ image_library($get_new_video->image) }}" alt="" height="180px" width="100%">
                            </a>
                        </div>
                    @endforeach
                    @if (count($get_news_video) == 6)
                        <div class="col-12 my-2">
                            <a href="{{ route('themes.mobile.libraries.search').'?type=4&cate_id=0' }}" class="see_more">
                                <p class="text-center">{{ trans('laother.show_more') }}</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if (count($get_news_audiobook) > 0)
        <div class="row news_item">
            <div class="col-12">
                <h5><span class="title_type">Sách nói</span></h5>
            </div>
            <div class="col-12">
                <div class="row m-0">
                    @foreach ($get_news_audiobook as $get_new_audiobook)
                        <div class="col-4 p-1">
                            <a href="{{ route('themes.mobile.libraries.audiobook.detail', ['id' => $get_new_audiobook->id]) }}">
                                <img src="{{ image_library($get_new_audiobook->image) }}" alt="" height="180px" width="100%">
                            </a>
                        </div>
                    @endforeach
                    @if (count($get_news_audiobook) == 6)
                        <div class="col-12 my-2">
                            <a href="{{ route('themes.mobile.libraries.search').'?type=5&cate_id=0' }}" class="see_more">
                                <p class="text-center">{{ trans('laother.show_more') }}</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        function searchLibraries() {
            const elem = document.getElementById('search');
            if (elem === document.activeElement) {
                $('#form_search').submit();
            }
        }
    </script>
@endsection
