<div class="wrraped_unit_choose row m-0">
    <div class="{{ $multiple != 1 ? 'col-11' : 'col-12'}} px-1" onclick="chooseUnitHandle({{ session()->get('user_unit') }})">
        <input type="hidden" name="multiple" id="multiple_unit" value="{{ $multiple }}">
        <input type="hidden" name="unit_id" id="search_unit_id" class="unit_id" value="{{ $unit_id ? $unit_id : '' }}">
        <span class="name_unit">
            @if ($unit_name)
                <div class="get_name_unit">{{ $unit_name }}</div>
            @else
                <span class="default_title">-- {{ trans('latraining.choose_unit') }} --</span>
            @endif
        </span>
    </div>
    @if ($multiple != 1)
        <div class="col-1 px-1 delete_unit_id" onclick="deleteUnitSearch()">
            <span><i class="fas fa-times"></i></span>
        </div>
    @endif
</div>