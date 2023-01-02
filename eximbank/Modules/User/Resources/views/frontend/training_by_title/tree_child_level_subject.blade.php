<ul class="ul_child_level_subject">
    @foreach($childs_level_subject as $key => $item)
    <li>
        <div class="item item_{{ $cate_id }}_{{$key}} mb-1">
            <i class="uil uil-plus"></i>
            <a href="javascript:void(0)" 
                data-id="{{ $item->id }}" 
                data-route="{{ route('module.frontend.user.training_by_title.tree_folder.get_child', ['id' => $item->cate_id, 'lv_subject_id' => $item->id,'start_date' => $item->start_date]) }}" class="tree-parent count-parent_{{ $cate_id }}_{{$key}} tree-item-parent{{ $cate_id }}"
            >
                <strong>{{ mb_strtoupper($item->name, 'UTF-8') }}</strong>
            </a>
        </div>
        <div class="list_parent list_parent_{{ $cate_id }}_{{ $key }}" id="list_parent_{{ $cate_id }}_list_subject_{{ $item->id }}"></div>
    </li>
    @endforeach
</ul>
<script>
    var cateId = '{{ $cate_id }}';
    var treeParent = $('.tree-item-parent' + cateId).length;
    for (let index = 0; index < treeParent; index++) {
        $('.item_'+ cateId + "_" + index).find('i:first').removeClass('uil uil-plus')
        $('.item_'+ cateId + "_" + index).find('i:first').addClass(openedClass)
        let child_url = $('.count-parent_'+  cateId + '_' + index).data('route');
        $('.list_parent_'+ cateId + '_' + index).load(child_url);
    }
    

    $('#tree-unit').on('click', '.tree-item-parent{{ $cate_id }}', function (e) {
        var id = $(this).data('id');
        var child_url = $(this).data('route');

        if ($(this).closest('.item').find('i:first').hasClass(openedClass)){
            $('#list_parent_{{ $cate_id }}_list_subject_'+id).find('ul').remove();
        }else{
            $('#list_parent_{{ $cate_id }}_list_subject_'+id).load(child_url);
        }

        var icon = $(this).closest('.item').children('i:first');
        icon.toggleClass(openedClass + " " + closedClass);
    });
</script>