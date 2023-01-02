@php
    $counter = 0;
@endphp
<div class="ibox-content forum-container">
    <h2 class="st_title">
        @foreach ($breadcum as $key => $item)
            @if ($key == 0 )
                <i class="uil uil-apps"></i>
            @else
                <i class="uil uil-angle-right"></i>
            @endif
        @php
            $dropContainer = !empty($item['drop-menu'])?'relative drop-container':'';
            $arrowDrop = !empty($item['drop-menu'])?' <span class="pl-1"><i class="fas fa-caret-down"></i></span>':'';
            $dropMenu =
                '<div class="drop bg-white">
                    <ul class="list pl0">';
            if (!empty($item['drop-menu'])){
                foreach ($item['drop-menu'] as $sub){
                    $dropMenu .= '<li><a href="'.$sub['url'].'">'.$sub['name']."</a></li>";
                }
                $dropMenu.='</ul></div>';
            }else
                $dropMenu='';
        @endphp
            @if (!empty($item['url']))
                <span class="{{$dropContainer}}"><a href="{{ $item['url'] }}" title="{{ $item['name'] }}">{{ $item['name'] }}</a> {!! $arrowDrop !!} {!! $dropMenu !!}</span>
            @else
                @if ($counter == count( $breadcum ) - 1)
                    <span class="font-weight-bold {{$dropContainer}}">{{ $item['name'] }} {!! $arrowDrop !!} {!! $dropMenu !!}</span>
                @else
                    <span class="{{$dropContainer}}">{{ $item['name'] }} {!! $arrowDrop !!} {!! $dropMenu !!}</span>
                @endif
            @endif
            @php
                $counter = $counter + 1;
            @endphp
        @endforeach
    </h2>
</div>
