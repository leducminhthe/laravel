{{--  @extends('themes.mobile.layouts.app')

@section('page_title', trans('lamenu.user'))

@section('content')  --}}
    <style>
        .wrapped_rating .dropdown-toggle::after {
            display: none !important;
        }
    </style>
    <div class="container" id="rating_page">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                Tổng NV: {{ count($list_user) }}
            </div>
            <div class="col-6 text-right">
                <button class="btn bg-template" type="button" data-toggle="modal" data-target="#modalFilterProfileUnit">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        @if(count($list_user) > 0)
            @foreach($list_user as $item)
                <div class="row bg-white mb-2 mt-1 wrapped_rating">
                    <div class="col-2 text-center p-1">
                        <img class="lazy w-100 rounded-circle" src="{{ $item->img_avatar }}">
                    </div>
                    <div class="col-10 p-1 align-self-center">
                        <div class="row m-0 d_flex_align">
                            <div class="col-10 pl-0">
                                <a href="javascript:void(0)" class="load-modal" data-url="{{ route('themes.mobile.frontend.profile_unit.info_user', ['user_id' => $item->user_id]) }}">
                                    <h6 class="mb-0">{{ $item->full_name }}</h6>
                                </a>
                            </div>
                            <div class="col-2 px-1 text-right">
                                <div class="btn-group">
                                    <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.detail', ['user_id' => $item->user_id]) }}')">
                                            Thống kê
                                        </a>
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.training_by_title',['user_id' => $item->user_id]) }}')" class="dropdown-item">
                                            Lộ trình đào tạo
                                        </a>
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.training_process',['user_id' => $item->user_id]) }}')" class="dropdown-item">
                                            Quá trình học
                                        </a>
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.quiz_result', ['user_id' => $item->user_id]) }}')" class="dropdown-item">
                                            Kết quả thi
                                        </a>
                                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.my_certificate').'?user_id='. $item->user_id }}')" class="dropdown-item">
                                            Chứng chỉ
                                        </a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <p class="mb-0" style="font-size: 85%">
                            <span class="text-muted">@lang('app.code'): {{ $item->code }}</span> <br>
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile_unit.training_by_title', ['user_id' => $item->user_id]) }}')">
                                <span class="">Hoàn thành lộ trình:</span>
                            </a>
                            <span class="{{ $item->text_color }}" style="font-size: 14px;"> {{ $item->percent_roadmap }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
            @include('themes.mobile.layouts.paginate', ['items' => $list_user])
        @else
            <div class="row">
                <div class="col text-center">
                    <span class="not_found">@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
{{--  @stop  --}}

