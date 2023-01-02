<!doctype html>
<html lang="en" class="deeppurple-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="Maxartkiller">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ mix('themes/mobile/css/search_daily_training_mobile_header.min.css') }}" rel="stylesheet">

    <style>
        .dropdown-content {
            display: none;
        }
    </style>
</head>

<body>
<div class="wrapper homepage">
    <div class="row no-gutters">
        <div class="col">
            <form method="get" class="input-group form-search border-0">
                <div class="input-group-prepend">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend') }}')" class="btn btn-link text-dark">
                        <i class="material-icons vm">navigate_before</i>
                    </a>
                </div>
                <input type="text" name="q" class="form-control"
                       placeholder="{{ data_locale('Nhập tên video hoặc hashtag', 'Enter video name or hashtag') }}"
                       value="{{ request()->get('q') }}">
                <button type="submit" class="btn btn-link text-dark position-relative text-right">
                    <i class="material-icons vm">search</i>
                </button>
            </form>
        </div>
    </div>
    <div class="container">
        @if(request()->get('q'))
            @if(count($items) > 0)
                @foreach($items as $key => $video)
                <div class="row pb-3 pt-1">
                    <div class="col-12">
                        <video class="w-100" controls autoplay>
                            <source src="{{ $video->getLinkPlay() }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto pr-0">
                                <img src="{{ \App\Models\Profile::avatar($video->created_by) }}" alt="" class="avatar avatar-40">
                            </div>
                            <div class="col">
                                <a href="{{ route('themes.mobile.daily_training.frontend.detail_video', ['id' => $video->id]) }}">{{ $video->name }}</a>
                                <p class="text-mute small">
                                    {{ \App\Models\Profile::fullname($video->created_by) .' - '. $video->view .' '. trans('app.view') .' - '. \Carbon\Carbon::parse($video->created_at)->diffForHumans()}}
                                </p>
                            </div>
                            <div class="col-auto pl-0">
                                @if(profile()->user_id == $video->created_by)
                                    <div class="dropdown small">
                                        <i class="material-icons">more_vert</i>
                                        <div class="dropdown-content">
                                            <span class="disable-video text-danger" data-video_id="{{ $video->id }}">
                                                @lang('app.delete')
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                <div class="row">
                <div class="col-6">
                    @if($items->previousPageUrl())
                        <a href="{{ $items->previousPageUrl() }}" class="bp_left">
                            <i class="material-icons">navigate_before</i> @lang('app.previous')
                        </a>
                    @endif
                </div>
                <div class="col-6 text-right">
                    @if($items->nextPageUrl())
                        <a href="{{ $items->nextPageUrl() }}" class="bp_right">
                            @lang('app.next') <i class="material-icons">navigate_next</i>
                        </a>
                    @endif
                </div>
            </div>
            @else
                <div class="row">
                    <div class="col-12 text-center">
                        <span>@lang('app.not_found')</span>
                    </div>
                </div>
            @endif
        @endif
    </div>
    <!-- footer-->
@include('themes.mobile.layouts.footer')
<!-- footer ends-->

</div>
<!-- jquery, popper and bootstrap js -->
<script src="{{ mix('themes/mobile/js/search_daily_training_mobile_footer.min.js') }}"></script>

<script>
    $(".disable-video").on('click', function () {
        let id = $(this).data('video_id');

        $.ajax({
            type: 'POST',
            url: '{{ route('module.daily_training.frontend.disable_video') }}',
            dataType: 'json',
            data: {
                '_token': '{{ csrf_token() }}',
                'id': id
            }
        }).done(function(data) {
            window.location = '';
            return false;
        }).fail(function(data) {
            return false;
        });
    });
    var i = 1;
    $('.dropdown').on('click', function () {
        i += 1;
        if (i % 2 == 0) {
            $(this).find('.dropdown-content').css('display', 'block');
        } else {
            $(this).find('.dropdown-content').css('display', 'none');
        }
    });
</script>

</body>
</html>

