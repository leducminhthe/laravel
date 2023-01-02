{{-- <style>
    .row{
        border-bottom: 2px dotted #ddd;
    }
</style> --}}

<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">{{trans('latraining.info')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-4">{{trans('backend.employee_code')}}</label>
                    <div class="col-md-8">
                        {{ $user->code }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">{{ trans('backend.employee_name') }}</label>
                    <div class="col-md-8">
                        {{ $user->full_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">{{ trans('latraining.title') }}</label>
                    <div class="col-md-8">
                        {{ $user->title_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">{{ trans('lamenu.unit') }}</label>
                    <div class="col-md-8">
                        {{ $user->unit_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

