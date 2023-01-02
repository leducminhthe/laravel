<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.info') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2">{{ trans('latraining.created_at') }}</div>
                    <div class="col-10">{{ $created_at2 }}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-2">{{ trans('latraining.creator') }}</div>
                    <div class="col-10">
                        {{ $user_created->full_name .' ('. $user_created->code .')' }} <br>
                        {{ trans('latraining.title') .': '. $user_created->title_name }} <br>
                        {{ trans('lamenu.unit') .': '. $user_created->unit_name }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-2">{{ trans('latraining.editor') }}</div>
                    <div class="col-10">
                        {{ $user_updated->full_name .' ('. $user_updated->code .')' }} <br>
                        {{ trans('latraining.title') .': '. $user_updated->title_name }} <br>
                        {{ trans('lamenu.unit') .': '. $user_updated->unit_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

