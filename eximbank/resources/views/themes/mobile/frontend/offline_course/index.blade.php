@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.in_house'))

@section('content')
    <div class="container wrapped_offline_courses">
        @if(count($items) > 0)
            <div class="row">
            @foreach($items as $offline)
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                    <div class="card shadow border-0 mb-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 p-1">
                                <img src="{{ image_offline($offline->image) }}" alt="" class="w-100 image_offline">
                            </div>
                            <div class="col-12 p-1 align-self-center">
                                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.offline.detail', ['course_id' => $offline->id]) }}', 0, 1)">
                                    <div class="my-1 title">
                                        <span class="font-weight-normal h6 mb-0">{{ $offline->name }}</span>
                                        {{--  <span class="text-mute">({{ $offline->code }})</span>  --}}
                                    </div>
                                </a>
                                <div class="text-mute">
                                    <p class="mb-0">
                                        <span onclick="openModalSummary({{ $offline->id }}, 2)" class="vdt14" style="cursor: pointer"><b>{{ trans('latraining.description') }}:</b> {{ trans('latraining.brief') }}</span> |
                                        <span onclick="openModalDescription({{ $offline->id }}, 2)" style="cursor: pointer">{{ trans('latraining.detail') }}</span>
                                    </p>
                                    <b><i class="fa fa-calendar-alt" aria-hidden="true"></i></b>
                                    {{ get_date($offline->start_date) }} @if($offline->end_date) {{ ' - '. get_date($offline->end_date) }} @endif
                                    <br>
                                    <b><i class="fas fa-clock" aria-hidden="true"></i> @lang('app.register_deadline'):</b> {{ get_date($offline->register_deadline) }}
                                </div>
                                @php
                                    $status = $offline->getStatusRegister();
                                    $text = $text_status($status);
                                    $class_color = $class_status($status);
                                    $load_modal_class = route('frontend.ajax_modal_class', ['course_id' => $offline->id]);
                                @endphp
                                @if($status == 1)
                                    <div class="item item-btn">
                                        <button type="button" class="btn float-right load-modal" data-url="{{ $load_modal_class }}"> <i class=""></i> {{ $text }}</button>
                                    </div>
                                @elseif ($status == 4)
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.offline.detail.go_activity', ['course_id' => $offline->id]) }}', 0, 1)" class="btn float-right link_btn">
                                        {{ $text }}
                                    </a>
                                @else
                                    <p class="{{ 'text-'.$class_color }} text-right">{{ $text }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            @endforeach
            </div>
            @include('themes.mobile.layouts.paginate', ['items' => $items])
        @else
            <div class="row">
                <div class="col text-center">
                    <span>@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('modal')
    @include('themes.mobile.frontend.modal_course')
@endsection

@section('footer')
    <script type="text/javascript">
        $('#filterOnline').on('click', '.list-group-item', function () {
            var type = $(this).data('type');
           $('#filterOnline .list-group-item').find('.icon').remove();
           $(this).append('<span class="icon float-right"><i class="material-icons text-primary">check</i></span>');
           var url = "{{ route('themes.mobile.frontend.offline.index').'?type=' }}"+type;
           $('#search-online').attr('href', url);
        });

        //MÔ TẢ CHI TIẾT KHÓA HỌC
        function openModalDescription(id,type) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.ajax_content_course') }}",
                data:{
                    id: id,
                    type: type,
                },
                success: function (data) {
                    $('#modal-body-description').html(data)
                    $('#modal-description').modal();
                }
            });
        }

        //TÓM TẮT KHÓA HỌC
        function openModalSummary(id,type) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.ajax_summary_course') }}",
                data:{
                    id: id,
                    type: type,
                },
                success: function (data) {
                    $('#modal-body-summary').html(data.description)
                    $('#modal-summary').modal();
                }
            });
        }
    </script>
@endsection
