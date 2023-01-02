@if((sizeof($files) > 0) || (sizeof($directories) > 0))
  <div class="row">
    @foreach($items as $item)
      @php
        $item_path = $item->is_file ? $item->url : $item->path;
      @endphp
      <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 img-row">
        <div class="square clickable {{ $item->is_file ? '' : 'folder-item' }}" data-id="{{ $item_path }}"
          @if($item->is_file) onclick="useFile('{{ $item_path }}', '{{ $item->name }}')" @endif
        >
          @if($item->thumb)
              <img src="{{ $item->thumb }}">
              <span class="count-child">{{ $item->child }}</span>
          @else
            <i class="fa {{ $item->icon }} fa-5x"></i>
          @endif
        </div>
        <div class="caption text-center">
          <div class="btn-group title_lfm">
            <div class="row m-0 w-100">
              <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 p-1">
                <button type="button" data-id="{{ $item_path }}" title="{{ $item->name }}" class="item_name btn btn-default btn-xs{{ $item->is_file ? '' : 'folder-item'}}" 
                  @if($item->is_file && $item->thumb) onclick="useFile('{{ $item_path }}', '{{ $item->name }}')" @endif 
                >
                  {{ $item->name }}
                </button>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 m-auto">
                <a href="javascript:rename('{{ $item->id }}','{{ $item->name }}','{{ $item->type }}')" title="{{ Lang::get('lfm.menu-rename') }}">
                  <i class="fa fa-edit fa-fw"></i>
                </a>
                <a class="text-danger" href="javascript:trash('{{ $item->id }}','{{ $item->name }}','{{ $item->is_file }}','{{ {{ $item->type_item }} }}')">
                  <i class="fa fa-trash"></i>
                </a>
                <a class="mx-1" href="{{ route('lfm.download_item', ['id' => $item->id]) }}" title="Tải về">
                  <i class="fa fa-download" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@else
  <p>{{ Lang::get('lfm.message-empty') }}</p>
@endif
