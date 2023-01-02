<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">{{trans('latraining.info')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-4">{{trans('laother.total_time_learn_title')}} <br> ({{ trans('latraining.hour') }})</label>
                    <div class="col-md-8">
                        {{ $title->title_time_kpi }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">{{ trans('laother.total_time_learn_user') }} <br> ({{ trans('latraining.hour') }})</label>
                    <div class="col-md-8">
                        {{ $title->user_time_kpi }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

