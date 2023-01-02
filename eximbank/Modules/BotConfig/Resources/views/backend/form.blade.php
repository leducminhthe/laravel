<div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" id="ajax-modal-popup" role="document">
        <form action="{{route('module.botconfig.post')}}" method="post" class="form-ajax" id="form_save" onsubmit="return false;" enctype="multipart/form-data">
            <input type="hidden" name="id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel"></h5> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="btn-group act-btns">
                        @canany(['certificate-template-create', 'certificate-template-edit'])
                            <button type="button" onclick="save(event)"  class="btn save">{{ trans('labutton.save') }}</button>
                        @endcan
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
                <div class="modal-body" id="body_modal">
                    <h3>Chat bot gợi ý</h3>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="code">{{trans('lachat.chat_question')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <input name="question" type="text" id="botquestion"  class="form-control" data-role="tagsinput" value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="code">{{trans('lachat.chat_suggest')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <input name="suggest" type="checkbox"  class="form-control" value="1" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="name">{{trans('lachat.chat_answer')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="answer" id="bot-answer" class="form-control"> </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
