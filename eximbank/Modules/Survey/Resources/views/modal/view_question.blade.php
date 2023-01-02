<div class="modal fade" id="modal_view_question" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    @if ($ques_type == 'choice')
                        @if ($multi == 0)
                            <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                            <img src="{{ asset('images/survey_template_question/setting_choice_1.png') }}" alt="" class="w-100">
                            <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                            <img src="{{ asset('images/survey_template_question/result_choice_1.png') }}" alt="" class="w-100">
                        @else
                            <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                            <img src="{{ asset('images/survey_template_question/setting_choice_multi.png') }}" alt="" class="w-100">
                            <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                            <img src="{{ asset('images/survey_template_question/result_choice_multi.png') }}" alt="" class="w-100">
                        @endif
                    @endif
                    @if ($ques_type == 'essay')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_essay.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_essay.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'text')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_enter_text.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_enter_text.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'matrix')
                        @if ($multi == 0)
                            <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                            <img src="{{ asset('images/survey_template_question/setting_matrix_1.png') }}" alt="" class="w-100">
                            <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                            <img src="{{ asset('images/survey_template_question/result_matrix_1.png') }}" alt="" class="w-100">
                        @else
                            <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                            <img src="{{ asset('images/survey_template_question/setting_matrix_multi.png') }}" alt="" class="w-100">
                            <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                            <img src="{{ asset('images/survey_template_question/result_matrix_multi.png') }}" alt="" class="w-100">
                        @endif
                    @endif
                    @if ($ques_type == 'matrix_text')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_matrix_text.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_matrix_text.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'dropdown')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_select.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_select.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'sort')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_sort.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_sort.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'percent')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_percent.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_percent.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'number')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_number.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_number.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'time')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_time.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_time.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'rank')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_rank.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_rank.png') }}" alt="" class="w-100">
                    @endif
                    @if ($ques_type == 'rank_icon')
                        <p class="p-1 font-weight-bold">{{ trans('latraining.setting') }}</p>
                        <img src="{{ asset('images/survey_template_question/setting_rank_icon.png') }}" alt="" class="w-100">
                        <p class="p-1 font-weight-bold">{{ trans('latraining.result') }}</p>
                        <img src="{{ asset('images/survey_template_question/result_rank_icon.png') }}" alt="" class="w-100">
                    @endif
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
        </div>
      </div>
    </div>
  </div>