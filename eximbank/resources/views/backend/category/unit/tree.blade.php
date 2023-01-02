@extends('layouts.backend')

@section('page_title', trans('lacategory.folder_tree'))

@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('lacategory.unit_level', ['i' => 1]),
                'url' => route('backend.category.unit', ['level' => 1])
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
    <div role="main">
        <div id="tree-unit" class="tree">
            @foreach($corporations as $item)
                @php
                    $count_child = \App\Models\Categories\Unit::countChild($item->code);
                @endphp
                <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">
                    <i class="uil-minus uil"></i> {{ $item->name .' ('. $count_child . ') - ' . $item->profiles->count() }} <i class="fa fa-user"></i>
                </a>
                <div id="list{{ $item->id }}">
                    @include('backend.category.unit.tree_child', ['parent_code' => $item->code])
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
           }else{
               $.ajax({
                   type: 'POST',
                   url: "{{ route('backend.category.unit.tree_folder.get_child') }}",
                   dataType: 'json',
                   data: {
                       id: id
                   }
               }).done(function(data) {
                   let rhtml = '';

                   rhtml += '<ul>';
                   $.each(data.childs, function (i, item){

                       rhtml += '<li>';
                       rhtml += '<a href="javascript:void(0)" data-id="'+ item.id +'" class="tree-item">';
                       rhtml += '<i class="uil uil-plus"></i>' + item.name + ' (' + data.count_child[item.id] + ')';
                       rhtml += '</a>';
                       rhtml += '<div id="list'+ item.id +'"></div>';
                       rhtml += '</li>';
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
    </script>
@endsection
