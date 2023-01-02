<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{trans('latraining.info')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-5">{{trans('backend.employee_code')}}</label>
                    <div class="col-md-6">
                        {{ $user->code }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">{{ trans('backend.employee_name') }}</label>
                    <div class="col-md-6">
                        {{ $user->lastname . ' ' . $user->firstname }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">{{ trans('latraining.title') }}</label>
                    <div class="col-md-6">
                        {{ $title ? $title->name : '' }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">{{ trans('lamenu.unit') }}</label>
                    <div class="col-md-6">
                        {{ $unit ? $unit->name : '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

