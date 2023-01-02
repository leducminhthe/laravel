<form action="{{route('module.botconfig.post')}}" method="post" class="form-ajax" id="form_save_keyword" enctype="multipart/form-data">
        <h3>Chat bot keyword</h3>
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
                <label for="name">{{trans('lachat.chat_answer')}} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <textarea name="answer" id="bot-answer" class="form-control"> </textarea>
            </div>
        </div>
    <input type="hidden" name="suggest" value="0">
</form>
