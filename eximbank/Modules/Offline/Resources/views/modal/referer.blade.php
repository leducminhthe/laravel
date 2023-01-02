<div class="modal fade modal-add-activity" id="modal-referer" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.add_presenter') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('module.offline.register_course', ['id' => $item->id]) }}" id="frm-course" method="post" class="form-ajax">
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 block-left">
                            <label>{{ trans('laother.enter_presenter_code') }}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="referer" id="referer" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 block-left">
                        </div>
                        <div class="col-md-7">
                            <a href="javascript:void(0)" id="referer_modal" class="load-modal" data-url="{{ route('frontend.offline.referer.show_modal',[$item->id] ) }}"><img src="{{asset('images/qr-code.svg')}}" width="30px" /> {{ trans('latraining.scan_referrer_code') }}</a>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn" id=""><i class="fa fa-plus-circle"></i> {{ trans('latraining.add_presenter') }}</button>
                <button type="button" id="btn_closed" class="btn" ><i class="fa fa-times-circle"></i> {{ trans('laother.skip') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#btn_closed').on('click',function (e) {
        $('#referer').val('');
        var form =  $('#frm-course');
        form.submit();
    })
</script>
