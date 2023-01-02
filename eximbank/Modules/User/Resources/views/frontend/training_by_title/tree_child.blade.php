<ul class="ul_child">
    @foreach($childs as $key => $item)
        <style>
            .percent_{{ $item->id }}{
                width: {{ $item->percent_subject . '% !important' }};
            }

            .style_item_{{ $item->id }}{
                padding: 0px 15px 5px 15px;
                border-radius: 10px;
                /* background: {{ ($item->percent_subject > 0 ? ($item->percent_subject == 100 ? '#c5e0b4' : '#f8cbad') : '') }}; */
            }
        </style>
        <li>
            <div class="item mb-2">
                <div class="row">
                    <div class="col-11 pr-0">
                        <div class="subject-item style_item_{{ $item->id }}">
                            @if($item->has_course)
                                <a href="javascript:void(0)" class="load-modal" data-url="{{ route('module.frontend.user.show_modal_roadmap', [$item->subject_id] ) }}">
                                    {{ ucfirst($item->subject_name) }} - {{ trans('laprofile.timer') }}: {{ $item->num_time }}
                                </a>
                            @else
                                <a href="javascript:void(0)" data-subject_id="{{ $item->subject_id }}" class="btnRegisterSubject">
                                    {{ ucfirst($item->subject_name) }} - {{ trans('laprofile.timer') }}: {{ $item->num_time }}
                                </a>
                            @endif
                            <div class="wrraped_progress row">
                                <div class="col-10 pr-0">
                                    <div class="progress progress2 mb-0">
                                        <div class="progress-bar percent_{{ $item->id }}" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 px-1">
                                    <span>@if($item->percent_subject > 0) {{ number_format($item->percent_subject, 2) }}% @endif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 d-flex align-items-center">
                        @if($item->percent_subject > 0)
                            @if($item->percent_subject == 100)
                                <i class="fa fa-check text-success h3"></i>
                            @else
                                <i class="fa fa-times text-danger h3"></i>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </li>
    @endforeach
</ul>

<script>
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
</script>
