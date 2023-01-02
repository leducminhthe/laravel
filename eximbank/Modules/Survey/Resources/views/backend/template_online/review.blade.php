@extends('layouts.backend2')

@section('page_title', trans('lasurvey.review_template'))

@section('breadcrumb')
    @php
        
        if($survey_id != 0) {
            $review = trans('laguide.watch_online');
            $breadcum= [
                [
                    'name' => trans('lamenu.survey'),
                    'url' => route('module.survey.index')
                ],
                [
                    'name' => $review,
                    'url' => ''
                ],
            ];
        } else {
            $review = trans('lasurvey.review_template');
            $breadcum= [
                [
                    'name' => trans('lamenu.survey'),
                    'url' => route('module.survey.index')
                ],
                [
                    'name' => trans('lamenu.survey_form_online'),
                    'url' => route('module.survey.template_online')
                ],
                [
                    'name' => $review,
                    'url' => ''
                ],
            ];
        }
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div class="container-fluid">
        <div class="wrapped_all">
            @foreach ($questions as $question)
                <div class="row mx-0 mb-2">
                    <div class="col-12 title mb-1">
                        <h3>{{ $question->question }}</h3>
                    </div>
                    <div class="col-12">
                        @foreach ($question->answers as $answer)
                            <div class="row wrapped_answer" onclick="showDetail({{ $answer->id }},{{ $survey_id }})">
                                <div class="{{ $survey_id != 0 ? 'col-11' : 'col-12' }}">
                                    {{ $answer->answer }}
                                </div>
                                @if ($survey_id != 0)
                                    <div class="col-1 pr-0 text-right number_user_answer_{{ $answer->id }}">
                                        <span class="count_user_answer">{{ $answer->count }}</span>
                                        <span class="ml-1"><i class="fas fa-angle-right"></i></span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="modal_user_answer_online">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title name_answer"></h4>
                </div>
                <div class="modal-body">
                    <div class="all_user"></div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('labutton.close') }}</button>
                </div> --}}
            </div>
        </div>
    </div>
@section('footer')
<script>
    var survey_id = '{{ $survey_id }}';
    var all_user = '{{ $all_user }}';
    all_user = JSON.parse(all_user);

    if (survey_id != 0) {
        var map = new Map(all_user);      

        window.Echo.channel('answer')
        .listen('SurveyRealTime', (data) => {
            console.log(data);
            if(map.has(data.user_id)) {
                var map_old = map.get(data.user_id)
                let differenceA = map_old.filter(x => !data.answer_id.includes(x));
                let differenceB = data.answer_id.filter(x => !map_old.includes(x));
                if (differenceA.length > 0) {
                    differenceA.forEach(answer => {
                        var get_number = $('.number_user_answer_'+ answer).text();
                        var count = parseInt(get_number) - 1;
                        $('.number_user_answer_'+ answer).find('.count_user_answer').html('<span>'+ count < 0 ? 0 : count +'</span>');
                    });
                }
                if (differenceB.length > 0) {
                    differenceB.forEach(answer => {
                        var get_number = $('.number_user_answer_'+ answer).text();
                        var count = parseInt(get_number) + 1;
                        $('.number_user_answer_'+ answer).find('.count_user_answer').html('<span>'+ count < 0 ? 0 : count +'</span>');
                    });
                }
                map.delete(data.user_id);
                map.set(data.user_id, data.answer_id)
            } else {
                map.set(data.user_id, data.answer_id)
                data.answer_id.forEach(answer => {
                var get_number = $('.number_user_answer_'+ answer).text();
                    var count = parseInt(get_number) + 1;
                    $('.number_user_answer_'+ answer).find('.count_user_answer').html('<span>'+ count < 0 ? 0 : count +'</span>');
                });
            }
        });
    }

    function showDetail(answer_id, survey_id) {
        $.ajax({
            type: 'POST',
            url : '{{ route('module.survey.template_online.detail_user_anser') }}',
            data : {
                answer_id: answer_id,
                survey_id: survey_id
            }
        }).done(function(data) {
            $('.name_answer').html(data.name_answer);
            var html = '';
            $.each(data.users, function (index, value) { 
                html += `<div class="wrapped_user">
                            <img src="`+ value.avatar +`" alt="" widh="30px" height="30px">
                            <span class="ml-1">`+ value.full_name +`</span>
                        </div>` 
            });
            $('.all_user').html(html);
            $('#modal_user_answer_online').modal();
            return false;
        }).fail(function(data) {
            return false;
        });
    }
</script>
@endsection
@stop
