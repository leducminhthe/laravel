<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.authorized_unit') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @foreach ($unit_manager as $key => $unit)
                    <div class="px-2">
                        {{ ($key + 1) .'./ '. $unit->name .' ('. $unit->code .')' }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

