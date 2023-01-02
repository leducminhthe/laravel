<div class="row m-0">
    <div class="col-md-9">
        <form method="post" action="{{ route('module.online.edit.save_condition_register', ['id' => $model->id]) }}" class="form-ajax" id="form-condition-register">
            {{-- KHẢO SÁT TRƯỚC GHI DANH --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Khảo sát trước ghi danh</label>
                </div>
                <div class="col-md-9">
                    <select name="survey_register" id="survey_register" class="form-control select2" data-placeholder="-- Khảo sát trước ghi danh --">
                        <option value=""></option>
                        @foreach ($survey_register as $survey)
                            <option value="{{ $survey->id }}" {{ $model->survey_register == $survey->id ? 'selected' : '' }}>{{ $survey->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- KỲ THI ĐẦU VÀO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Thi trước ghi danh</label>
                </div>
                <div class="col-md-9">
                    <select name="register_quiz_id" id="register_quiz_id" class="form-control select2" data-placeholder="-- Kỳ thi trước ghi danh --">
                        <option value=""></option>
                        <option value="{{ $register_quiz->id }}" {{ $model->register_quiz_id == $register_quiz->id ? 'selected' : '' }}>{{ $register_quiz->name }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label"></div>
                <div class="col-md-9">
                    @if($model->lock_course == 0)
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-qrcode-entrance-quiz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">QR code thi đầu vào</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    @if ($qrcode_entrance_quiz)
                        <div id="qrcode" >
                            {!! QrCode::size(300)->generate($qrcode_entrance_quiz); !!}
                            <p>{{trans('latraining.scan_code')}}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#modal_qrcode_entrance_quiz').on('click',function () {
        $("#modal-qrcode-entrance-quiz").modal();
    });
</script>
