<div role="main">
    <div class="row">
        <div class="col-md-12">
            <form action=" " method="post" class="form-ajax">
                <div class="form-group row">
                    <div class="text-center">
                            <div id="qrcode" >
                                {!! QrCode::size(300)->generate($qrcode_quiz); !!}
                                <p>{{ trans('latraining.scan_code_take_test') }}</p>
                            </div>
                        <a href="javascript:void(0)" id="print_qrcode">{{ trans('latraining.print_qr_code') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

