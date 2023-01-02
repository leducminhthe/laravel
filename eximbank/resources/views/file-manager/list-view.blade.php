@if((sizeof($files) > 0) || (sizeof($directories) > 0))
<table class="table table-condensed table-striped hidden-xs table-list-view">
    <thead>
        <th>{{ Lang::get('lfm.title-item') }}</th>
        <th class="text-center">{{ Lang::get('lfm.title-size') }}</th>
        <th class="text-center">{{ Lang::get('lfm.title-type') }}</th>
        <th class="text-center">{{ Lang::get('lfm.title-modified') }}</th>
        <th class="text-center">{{ Lang::get('lfm.title-action') }}</th>
    </thead>
    <tbody>
        @foreach($items as $item)
            @php
                $item_path = $item->is_file ? $item->url : $item->path;
            @endphp
        <tr>
            <td>
                <a class="{{ $item->is_file ? '' : 'folder-item' }} clickable" data-id="{{ $item_path }}" title="{{ $item->name }}" 
                    @if($item->is_file) onclick="useFile('{{ $item_path }}', '{{ $item->name }}')" @endif
                >
                    @if($item->thumb)
                        <img src="{{ $item->thumb }}" style="width: 15%;">
                    @else
                        <i class="fa {{ $item->icon }}"></i>
                    @endif
                    {{ $item->name }}
                </a>
            </td>
            <td class="text-center">{{ $item->size > 0 ? filesize_formatted($item->size) : "" }}</td>
            <td class="text-center">{{ $item->type }}</td>
            <td class="text-center">{{ get_date($item->time, 'H:i:s d/m/Y') }}</td>
            <td class="actions text-center d-flex">
                @if (!empty($item->unit_by))
                    <a href="javascript:rename('{{ $item->id }}','{{ $item->name }}','{{ $item->type }}')" title="{{ Lang::get('lfm.menu-rename') }}">
                        <i class="fa fa-edit fa-fw"></i>
                    </a>
                    <a href="javascript:trash('{{ $item->id }}','{{ $item->name }}','{{ $item->is_file }}','{{ $item->type_item }}')" title="{{ Lang::get('lfm.menu-delete') }}">
                        <i class="fa fa-trash fa-fw"></i>
                    </a>
                    <a class="mx-1" href="{{ route('lfm.download_item', ['id' => $item->id]) }}" title="Tải về">
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <p>{{ trans('lfm.message-empty') }}</p>
@endif
