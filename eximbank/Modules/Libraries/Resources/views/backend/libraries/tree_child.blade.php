@php
    $tree_child = \Modules\Libraries\Entities\LibrariesCategory::select(['id','name'])->where('parent_id', '=', $parent_id)->get();
@endphp
<ul>
    @foreach($tree_child as $item)
        @php
            $count_item = \Modules\Libraries\Entities\Libraries::where('category_id', $item->id)->count();
        @endphp
        <li>
            <div class="wrapped_item">
                <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">
                    @if($item->level == 4) <i class="uil uil-plus"></i> @else <i class="uil-minus uil"></i> @endif {{ $item->name .' ('. $count_item . ')' }} 
                </a>
                <input type="hidden" class="open_item_{{ $item->id }}" value="0">
                <span class="cursor_pointer ml-1" onclick="showItem({{ $item->id }})">
                    <i class="uil uil-plus"></i>
                </span>
                <div class="list_item list_item_{{ $item->id }}">
                </div>
            </div>
            <div id="list{{ $item->id }}">
                @if($item->level < 4)
                    @include('libraries::backend.libraries.tree_child', ['parent_id' => $item->id])
                @endif
            </div>
        </li>
    @endforeach
</ul>

