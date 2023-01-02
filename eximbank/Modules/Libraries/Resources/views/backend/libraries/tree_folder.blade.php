@extends('layouts.backend')

@section('page_title', trans('lacategory.folder_tree'))

@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => $name,
                'url' => $route
            ],
            [
                'name' => trans('lacategory.folder_tree'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <style>
        .list_item {
            margin-left: 14px;
            border-left: 1px solid black;
            padding-left: 20px;
        }
    </style>
    <div role="main">
        <div id="tree-unit" class="tree">
            @foreach($corporations as $item)
                @php
                    $count_item = \Modules\Libraries\Entities\Libraries::where('category_id', $item->id)->count();
                @endphp
                <div class="wrapped_item">
                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">
                        <div></div>
                        <i class="uil-minus uil"></i> 
                        {{ $item->name .' ('. $count_item . ')' }}
                    </a>
                    <input type="hidden" class="open_item_{{ $item->id }}" value="0">
                    <span class="cursor_pointer ml-1" id="span_{{ $item->id }}" onclick="showItem({{ $item->id }})">
                        <i class="uil uil-plus"></i>
                    </span>
                    <div class="list_item list_item_{{ $item->id }}">
                    </div>
                </div>
                <div id="list{{ $item->id }}">
                    @include('libraries::backend.libraries.tree_child', ['parent_id' => $item->id])
                </div>
            @endforeach
        </div>
    </div>
    <script type="text/javascript">
        var openedClass = 'uil-minus uil';
        var closedClass = 'uil uil-plus';

        $('#tree-unit').on('click', '.tree-item', function (e) {
           var id = $(this).data('id');

            if ($(this).find('i:first').hasClass(openedClass)){
                $('#list'+id).find('ul').remove();
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('module.book.unit.tree.get_child') }}",
                    dataType: 'json',
                    data: {
                        id: id
                    }
                }).done(function(data) {
                    let rhtml = '';

                    rhtml += '<ul>';
                    $.each(data.childs, function (i, item){
                        rhtml += `<li>
                                    <div class="wrapped_item">
                                        <a href="javascript:void(0)" data-id="`+ item.id +`" class="tree-item">
                                            <i class="uil uil-plus"></i>` + item.name + ` (` + data.count_item[item.id] + `)
                                        </a>
                                        <input type="hidden" class="open_item_`+ item.id +`" value="0">
                                        <span class="cursor_pointer ml-1" id="span_`+ item.id +`" onclick="showItem(`+ item.id +`)">
                                            <i class="uil uil-plus"></i>
                                        </span>
                                        <div class="list_item list_item_`+ item.id +`">
                                        </div>
                                    </div>
                                    <div id="list`+ item.id +`"></div>
                                </li>`
                    });
                    rhtml += '</ul>';

                    document.getElementById('list'+id).innerHTML = '';
                    document.getElementById('list'+id).innerHTML = rhtml;
                }).fail(function(data) {
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });
            }

            if (this == e.target) {
                var icon = $(this).children('i:first');
                icon.toggleClass(openedClass + " " + closedClass);
                $(this).children().children().toggle();
            }
        });

        function showItem(id) {
            console.log(id);
            var checkOpen = $('.open_item_'+ id).val();
            if (checkOpen == 1){
                $('.list_item_'+ id).html('');
                $('.open_item_'+ id).val(0)
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('module.book.unit.tree.get_item') }}",
                    dataType: 'json',
                    data: {
                        id: id
                    }
                }).done(function(data) {
                    let rhtml = '';
                    $.each(data, function (i, item){
                        rhtml += '<p class="mb-0">'+ item.name +'</p>';
                    });
                    $('.list_item_'+ id).html(rhtml);
                    $('.open_item_'+ id).val(1)
                }).fail(function(data) {
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });
            }
        }
    </script>
@endsection
