@php
    $tree_child = \App\Models\Categories\Unit::select(['id','name','code'])->where('parent_code', '=', $parent_code)->get();
@endphp
<ul class="ml-3">
    @foreach($tree_child as $item)
        @php
            $count_child = \App\Models\Categories\Unit::countChild($item->code);
        @endphp
    <li>
        <div class="item">
            <i class="uil-minus uil"></i> <input type="checkbox" name="unit[]" class="check-unit" checked data-id="{{ $item->id }}" value="{{ $item->id }}">
            <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">{{ $item->name .' ('. $count_child . ') - ' . $item->profiles->count() }} <i class="fa fa-user"></i></a>
        </div>
        <div id="list{{ $item->id }}">
            @include('offline::backend.offline.tree_unit_child', ['parent_code' => $item->code])
        </div>
    </li>
    @endforeach
</ul>

