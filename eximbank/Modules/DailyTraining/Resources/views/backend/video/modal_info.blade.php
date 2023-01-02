<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.info') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2">{{ trans('backend.poster') }}</div>
                    <div class="col-10">{{  $created_by .' - '. $title_name }}</div>
                </div>
                <div class="row">
                    <div class="col-2">{{ trans('backend.post_time') }}</div>
                    <div class="col-10">{{  $created_time }}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-2">{{ trans('backend.reviewer') }}</div>
                    <div class="col-10">{{  $user_approve }}</div>
                </div>
                <div class="row">
                    <div class="col-2">{{ trans('backend.review_time') }}</div>
                    <div class="col-10">{{  $time_approve }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

