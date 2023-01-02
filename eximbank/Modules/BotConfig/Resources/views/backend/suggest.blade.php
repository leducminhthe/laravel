<form action="{{route('module.botconfig.post')}}" method="post" class="form-ajax" id="form_save_suggest" enctype="multipart/form-data">
    <div class="wrap-suggest-form">
        <h3>Chat bot gợi ý</h3>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="code">{{trans('lachat.chat_question')}} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9">
                <input name="question-suggest" type="text"  class="form-control"  placeholder="{{ trans('laother.enter_question') }}" value="" >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="name">{{trans('lachat.chat_answer')}} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-9" style="background: #ced7e7; padding:12px">
                <input type="text" name="answer-suggest[]" placeholder="Câu trả lời" class="form-control" />
                <input type="text" name="link-suggest[]" class="form-control mt-2" placeholder="link url">
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-3 col-md-9">
            <button class="btn" id="add-answer-suggest">Thêm câu trả lời</button>
        </div>
    </div>
    <input type="hidden" name="suggest" value="1">
</form>
<template id="answer-suggest-template">
    <div class="form-group row">
        <div class="offset-3 col-md-9" style="background: #ced7e7; padding:12px">
            <input type="text" name="answer-suggest[]" placeholder="Câu trả lời" class="form-control" />
            <input type="text" name="link-suggest[]" class="form-control mt-2" placeholder="link url">
        </div>
    </div>
</template>
<script>
    $('#add-answer-suggest').on('click',(e)=>{
        e.preventDefault();
        let template = document.getElementById('answer-suggest-template').innerHTML;
        $('#suggest .wrap-suggest-form').append(template);
    })
</script>
