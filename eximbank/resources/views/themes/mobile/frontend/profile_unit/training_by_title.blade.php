@extends('themes.mobile.layouts.app')

@section('page_title', 'Lộ trình đào tạo')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection
@section('content')
    <div class="container mt-2">
        <div class="mt-10 mb-40 row" id="training-by-title">
            <div class="col-12 p-1">
                <div id="tree-unit" class="tree">
                    <div class="d_flex_align">
                        <img src="{{ (\App\Models\Profile::avatar($profile->user_id)) }}" alt="" class="w-5 rounded-circle" height="60px">
                        <div class="ml-2">
                            <h5 class="mb-1">{{ $profile->full_name }}</h5>
                            <h5>{{ trans('latraining.completed') }} <span class="total_percent font-weight-bold">0</span>%</h5>
                        </div>
                    </div>
                    <ul class="ul_parent">
                        @php
                            $total_percent = 0;
                        @endphp
                        @foreach($get_training_roadmap as $key => $item)
                            <li>
                                @php
                                    $total_percent += ($item->percent == 100 ? 1 : 0)
                                @endphp
                                <div class="item mb-1">
                                    <span class="font-weigth-bold">
                                        <a href="javascript:void(0)" class="">
                                            <i class=""></i> {{ ($key + 1) .'./ '. mb_strtoupper($item->name, 'UTF-8') }}
                                        </a>
                                    </span>
                                    <div class="wrraped_progress row">
                                        <div class="col-10 pr-0">
                                            <div class="progress progress2 mb-0">
                                                <div class="progress-bar" style="width: {{ $item->percent }}%" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2" style="margin-top: -10px;">
                                            {{ $item->percent }}%
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script type="text/javascript">
        var percent = {{ number_format($total_percent/(count($get_training_roadmap) > 0 ? count($get_training_roadmap) : 1)*100) }};
        $('#tree-unit .total_percent').html(percent);
    </script>
@stop
