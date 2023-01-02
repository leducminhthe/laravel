<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('laprofile.info_unit_by_user', ['user' => ($user->lastname . ' ' . $user->firstname)]) }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @for($i=1;$i<=$max_unit;$i++)
                    @php
                        $level_name = \App\Models\Categories\Unit::getLevelName($i);
                    @endphp
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('lacategory.unit_level', ['i' => $i]) }}</label>
                        </div>
                        <div class="col-md-9">
                            @if(isset($unit[$i]))
                                {{ $unit[$i]->name }}
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

