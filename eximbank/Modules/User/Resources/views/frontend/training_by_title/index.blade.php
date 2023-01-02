<div class="mt-10 mb-40 row" id="training-by-title">
    <div class="col-md-4 col-12 p-1">
        <img src="{{ image_file($imageTrainingByTitle->image2) }}" alt="" width="100%">
    </div>
    <div class="col-md-8 col-12 p-1">
        <div id="tree-unit" class="tree">
            <div class="">
                <img src="{{ image_file(\App\Models\Profile::avatar()) }}" alt="" class="w-5 rounded-circle">
                <span class="h4">{{ trans('latraining.completed') }} <span class="total_percent font-weight-bold">0</span>%</span>
            </div>
            <ul class="ul_parent">
                @php
                    $old_date = '';
                    $now = date('Y-m-d');
                    $percent_training_by_title = 0;
                @endphp
                @foreach($training_by_title_category as $key => $item)
                    @php
                        if($key == 0){
                            $old_date =\Carbon\Carbon::parse($start_date)->addDays($item->num_date_category + 1);

                            $start_date_format = \Carbon\Carbon::parse($start_date)->format('d/m/Y');
                            $end_date_format = \Carbon\Carbon::parse($start_date)->addDays($item->num_date_category);
                        }else{
                            $start_date = \Carbon\Carbon::parse($old_date)->format('Y-m-d');

                            $start_date_format = \Carbon\Carbon::parse($old_date)->format('d/m/Y');
                            $end_date_format = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category);

                            $old_date = \Carbon\Carbon::parse($old_date)->addDays($item->num_date_category + 1);
                        }

                        $class_active = '';
                        if($start_date <= $now && $now <= $end_date_format){
                            $class_active = 'shadow bg-white rounded inprocess_training';

                            $percent_training_by_title = $f_percent_training_by_title($item->id);
                        }
                    @endphp
                    <li class="{{ $class_active }}" >
                        <div class="item mb-1">
                            <i class="uil uil-plus"></i>
                            <a href="javascript:void(0)" data-id="{{ $item->id }}" data-route="{{ route('module.frontend.user.training_by_title.tree_folder.get_child_level_subject', ['id' => $item->id, 'start_date' => $start_date]) }}" class="tree-item-level-subject">
                                <span class="font-weigth-bold">{{ mb_strtoupper($item->name, 'UTF-8') }}</span>
                                <span>
                                    ({{ $start_date_format .' - '. $end_date_format->format('d/m/Y')  }})
                                </span>
                                -
                                <span class="font-weight-bold">
                                    {{ $f_percent_training_by_title($item->id) }}%
                                </span>
                            </a>
                        </div>
                        <div id="list{{ $item->id }}"></div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.total_percent').html('{{ $percent_training_by_title }}');

    $(function () {
        $(document).on('click','.btnRegisterSubject',function (e) {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: '{{ trans("laprofile.note_register_course") }} ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("lacore.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    let data = {};
                    data.subject_id = $(this).data('subject_id');
                    let item = $(this);
                    let oldtext = item.html();
                    item.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
                    $.ajax({
                        type: 'PUT',
                        url: '{{ route('module.frontend.user.roadmap.register') }}',
                        dataType: 'json',
                        data
                    }).done(function(data) {
                        item.attr('disabled',false).html(oldtext);
                        show_message(data.message,data.status);
                    }).fail(function(data) {
                        item.attr('disabled',false).html(oldtext);
                        show_message('{{ trans('laother.data_error') }}','error');
                        return false;
                    });
                }
            });

        });
    });

    var openedClass = 'uil-minus uil';
    var closedClass = 'uil uil-plus';

    window.onload = (event) => {
        run();
    };

    function run() {
        var treeItem = $('.inprocess_training').find('.tree-item-level-subject');
        var id = treeItem.data('id');
        var child_url = treeItem.data('route');
        if(id && child_url) {
            $('#list'+id).load(child_url);
            var icon = treeItem.closest('.item').children('i:first');
            icon.toggleClass(openedClass + " " + closedClass);
        }
    }

    $('#tree-unit').on('click', '.tree-item-level-subject', function (e) {
        var id = $(this).data('id');
        var child_url = $(this).data('route');

        if ($(this).closest('.item').find('i:first').hasClass(openedClass)){
            $('#list'+id).find('ul').remove();
        }else{
            $('#list'+id).load(child_url);
        }

        var icon = $(this).closest('.item').children('i:first');
        icon.toggleClass(openedClass + " " + closedClass);
    });
</script>
