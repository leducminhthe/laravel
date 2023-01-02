@extends('layouts.backend')

@section('page_title', trans('latraining.course_for_concentration'))

@section('header')
    {{-- <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script> --}}
    <script src="{{asset('modules/online/js/course.userpoint.js')}}"></script>
@endsection

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.online_course'),
                'url' => route('module.online.management')
            ],
            [
                'name' => trans('latraining.course_for_concentration'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 act-btns">
                <div class="pull-right">
                    @include('online::backend.online.filter')
                    <div class="wrraped_online text-right">
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                    {{ trans('labutton.task') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 12rem;">
                                    @can('online-course-status')
                                        <a class="dropdown-item p-1" onclick="changeStatus(0,1)" data-status="1" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M373.333,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S426.261,160,373.333,160z     M373.333,330.667c-41.173,0-74.667-33.493-74.667-74.667s33.493-74.667,74.667-74.667C414.507,181.333,448,214.827,448,256    S414.507,330.667,373.333,330.667z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M117.333,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32c17.643,0,32-14.357,32-32v-42.667    C149.333,217.024,134.976,202.667,117.333,202.667z M128,277.333c0,5.888-4.8,10.667-10.667,10.667    c-5.867,0-10.667-4.779-10.667-10.667v-42.667c0-5.888,4.8-10.667,10.667-10.667C123.2,224,128,228.779,128,234.667V277.333z" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M224,202.667c-5.888,0-10.667,4.779-10.667,10.667v40.149l-22.443-44.928c-2.219-4.416-7.104-6.763-12.011-5.611    c-4.821,1.131-8.213,5.44-8.213,10.389v85.333c0,5.888,4.779,10.667,10.667,10.667S192,304.555,192,298.667v-40.149l22.464,44.928    c1.835,3.669,5.547,5.888,9.536,5.888c0.811,0,1.621-0.085,2.453-0.277c4.821-1.131,8.213-5.44,8.213-10.389v-85.333    C234.667,207.445,229.888,202.667,224,202.667z" />
                                                    </g>
                                                </g>
                                            </svg> {{ trans('labutton.enable') }}
                                        </a>
                                        <a class="dropdown-item p-1" onclick="changeStatus(0,0)" data-status="0" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M138.667,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S191.595,160,138.667,160z     M138.667,330.667C97.493,330.667,64,297.173,64,256s33.493-74.667,74.667-74.667s74.667,33.493,74.667,74.667    S179.84,330.667,138.667,330.667z"/>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z"/>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M288,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32s32-14.357,32-32v-42.667    C320,217.024,305.643,202.667,288,202.667z M298.667,277.333c0,5.888-4.8,10.667-10.667,10.667s-10.667-4.779-10.667-10.667    v-42.667c0-5.888,4.8-10.667,10.667-10.667s10.667,4.779,10.667,10.667V277.333z"/>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M384,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H384c5.888,0,10.667-4.779,10.667-10.667S389.888,202.667,384,202.667z"/>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M373.333,245.333H352c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S379.221,245.333,373.333,245.333z"/>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M448,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H448c5.888,0,10.667-4.779,10.667-10.667S453.888,202.667,448,202.667z"/>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M437.333,245.333H416c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S443.221,245.333,437.333,245.333z"/>
                                                    </g>
                                                </g>
                                            </svg> {{ trans('labutton.disable') }}
                                        </a>
                                    @endcan
                                    @can('online-course-approve')
                                        <a class="dropdown-item p-1 approved" data-model="el_online_course" data-status="1" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Check_Mark" d="m64 128c-35.289 0-64-28.711-64-64s28.711-64 64-64 64 28.711 64 64-28.711 64-64 64zm0-120c-30.879 0-56 25.121-56 56s25.121 56 56 56 56-25.121 56-56-25.121-56-56-56zm-9.172 78.828 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0l-37.172 37.172-13.172-13.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l16 16c.781.781 1.805 1.172 2.828 1.172s2.047-.391 2.828-1.172z"/></svg> {{ trans('labutton.approve') }}
                                        </a>
                                        <a class="dropdown-item p-1 approved" data-model="el_online_course" data-status="0" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 16 16" width="512"><g id="_19" data-name="19"><path d="m8 16a8 8 0 1 1 8-8 8 8 0 0 1 -8 8zm0-15a7 7 0 1 0 7 7 7 7 0 0 0 -7-7z"/><path d="m8.71 8 3.14-3.15a.49.49 0 0 0 -.7-.7l-3.15 3.14-3.15-3.14a.49.49 0 0 0 -.7.7l3.14 3.15-3.14 3.15a.48.48 0 0 0 0 .7.48.48 0 0 0 .7 0l3.15-3.14 3.15 3.14a.48.48 0 0 0 .7 0 .48.48 0 0 0 0-.7z"/></g></svg> {{ trans('labutton.deny') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            @can('online-course-create')
                                <a href="{{ route('module.online.course_for_offline.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                            @endcan
                            @can('online-course-delete')
                                <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                            @endcan
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="isopen" data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('latraining.open_off') }}</th>
                    <th data-field="name" data-sortable="true" data-formatter="name_formatter" class="text-nowrap">{{ trans('latraining.course') }}</th>
                    <th data-field="subject_name">{{ trans('latraining.subject') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                    <th data-formatter="info_formatter" data-align="center" data-width="5%">{{ trans('latraining.info') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script>
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name + '<br> (' + row.code + ') </a> <br>' + row.start_date  + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
        }

        function isopen_formatter(value, row, index) {
            var status = row.isopen == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            var text_status = '';
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("latraining.deny") }}</span>'; break;
                case 1: text_status = '<span class="text-success">{{trans("latraining.approve")}}</span>'; break;
                case 2: text_status = '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>'; break;
            }

            if(row.approved_step){
                text_status += `<br> (<a href="javascript:void(0)" data-id="${row.id}" data-model="el_online_course" class="text-success font-weight-bold load-modal-approved-step">${row.approved_step}</a>)`;
            }
            return text_status;
        }


        function info_formatter(value, row, index) {
            var info = '';
            info += '<a href="javascript:void(0)" class="load-modal btn" data-url="'+row.info_url+'" title="{{ trans('latraining.info') }}"> <i class="fa fa-info-circle"></i></a>';

            return info;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.course_for_offline.getdata') }}',
            remove_url: '{{ route('module.online.course_for_offline.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.online.ajax_isopen_publish') }}";
        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans("lacore.min_one_course") }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: ajax_isopen_publish,
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                if (id == 0) {
                    show_message(data.message, data.status);
                }
                $(table.table).bootstrapTable('refresh');
                $('.btn_action_table').toggle(false);
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        }
    </script>
@stop
