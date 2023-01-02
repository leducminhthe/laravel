@extends('themes.mobile.layouts.app')

@section('page_title', 'Video')

@section('content')
    @php
        if($cate_id) {
            $daily_cate = $categories_item;
        } else {
            $daily_cate = $categories;
        }
    @endphp
    <div class="container wrraped_daily_training" id="daily_training_mobile">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('themes.mobile.daily_training.frontend') }}" id="form_search" method="GET" class="w-100">
                <select name="cate_id" class="select2 form-control w-100"  onchange="submit();">
                    <option value="" disabled selected>{{ trans('lamenu.category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"{{ $cate_id && $cate_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="row news_item">
            <div class="col-12 mb-2">
                <h5>
                    <span class="title_type">Video mới nhất ({{ $get_daily_training_video_news->count() }})</span>
                    <span class="btn float-right"><a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend.my_video') }}')" class="text-white">Video của tôi</a></span>
                </h5>
            </div>
            @foreach ($get_daily_training_video_news as $get_daily_training_video_new)
                @php
                    $count_comment = Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo::where('video_id', '=', $get_daily_training_video_new->id)->count();
                @endphp
                <div class="col-6 pb-2">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend.detail_video', ['id' => $get_daily_training_video_new->id]) }}')">
                        <img src="{{ image_daily($get_daily_training_video_new->avatar) }}" alt="" class="w-100" height="120px" style="object-fit: cover">
                    </a>
                    <span class="title_daily_video mt-1">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend.detail_video', ['id' => $get_daily_training_video_new->id]) }}')">
                        {{ $get_daily_training_video_new->name }}
                        </a>
                    </span>
                    <p class="text-mute small mb-1">
                        {{ $count_comment }} <i class="fa fa-comment"></i>
                    </p>
                    <p class="text-mute small mb-1">
                        {{ \App\Models\Profile::fullname($get_daily_training_video_new->created_by) .' - '. $get_daily_training_video_new->view .' '. trans('app.view')}}
                    </p>
                    <p class="text-mute small mb-1">
                        {{ \Carbon\Carbon::parse($get_daily_training_video_new->created_at)->diffForHumans() }}
                    </p>
                </div>
            @endforeach
        </div>
        @foreach ($daily_cate as $category)
            @php
                $query = Modules\DailyTraining\Entities\DailyTrainingVideo::query();
                $query->where('category_id',$category->id);
                $query->where('status',1);
                $query->orderByDesc('created_at');
                if (!$cate_id) {
                    $query->take(4);
                }
                $get_daily_training_videos =  $query->get();
            @endphp
            @if ($get_daily_training_videos->count() > 0)
            <div class="row news_item">
                <div class="col-12 mb-2">
                    <h5><span class="title_type">{{ $category->name .' ('. $get_daily_training_videos->count() .')' }}</span></h5>
                </div>
                @foreach ($get_daily_training_videos as $get_daily_training_video)
                    @php
                        $count_comment = Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo::where('video_id', '=', $get_daily_training_video->id)->count();
                    @endphp
                    <div class="col-6 pb-2">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend.detail_video', ['id' => $get_daily_training_video->id]) }}')">
                            <img src="{{ image_daily($get_daily_training_video->avatar) }}" alt="" class="w-100" height="120px" style="object-fit: cover">
                        </a>
                        <span class="title_daily_video mt-1">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend.detail_video', ['id' => $get_daily_training_video->id]) }}')">
                            {{ $get_daily_training_video->name }}
                            </a>
                        </span>
                        <p class="text-mute small mb-1">
                            {{ $count_comment }} <i class="fa fa-comment"></i>
                        </p>
                        <p class="text-mute small mb-1">
                            {{ \App\Models\Profile::fullname($get_daily_training_video->created_by) .' - '. $get_daily_training_video->view .' '. trans('app.view')}}
                        </p>
                        <p class="text-mute small mb-1">
                            {{ \Carbon\Carbon::parse($get_daily_training_video->created_at)->diffForHumans() }}
                        </p>
                    </div>
                @endforeach
                @if (count($get_daily_training_videos) == 4 && !$cate_id)
                    <div class="col-12 my-2">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.daily_training.frontend').'?cate_id='.$category->id }}')" class="see_more">
                            <p class="text-center">{{ trans('laother.show_more') }}</p>
                        </a>
                    </div>
                @endif
            </div>      
            @endif
        @endforeach

        <div class="modal-backdrop fade" style="display:none;"></div>

        <script>
            $('#form_search').on('click', function () {
                $('#daily_training_mobile .modal-backdrop').show();
                $('#daily_training_mobile .modal-backdrop').addClass('show');
            });

            $('#daily_training_mobile .modal-backdrop').on('click', function () {
                $('#daily_training_mobile .modal-backdrop').hide();
                $('#daily_training_mobile .modal-backdrop').removeClass('show');
            });
        </script>
    </div>
@endsection
