@extends('layouts.backend')

@section('page_title', trans('lamenu.survey'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.survey'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-4">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder='{{trans("lasurvey.enter_name_survey")}}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
            <div class="col-md-8 text-right act-btns">
                <div class="pull-right">
                    {{-- @can('survey-template')
                        <div class="btn-group">
                            <a href="{{ route('module.survey.template_online') }}" class="btn"><i class="fa fa-drivers-license"></i> {{ trans('lamenu.survey_form_online') }}</a>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('module.survey.template') }}" class="btn"><i class="fa fa-drivers-license"></i> {{trans('labutton.survey_form')}}</a>
                        </div>
                    @endcan --}}
                    @can('survey-create')
                        <div class="btn-group">
                            <button class="btn copy">
                                <i class="fa fa-plus-circle"></i> &nbsp;{{ trans('labutton.copy') }}
                            </button>
                        </div>
                    @endcan
                    @can('survey-status')
                        <div class="btn-group">
                            <button class="btn publish" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{trans('labutton.enable')}}
                            </button>
                            <button class="btn publish" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{trans('labutton.disable')}}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('survey-create')
                        <a href="{{ route('module.survey.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}</a>
                        @endcan
                        @can('survey-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('lasurvey.open')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('lasurvey.survey_name')}}</th>
                    <th data-field="date" data-width="15%" data-align="center" data-formatter="date_formatter">{{trans('lasurvey.time')}}</th>
                    <th data-field="count_ques" data-width="10%" data-align="center">{{trans('lasurvey.number_of_questions')}}</th>
                    <th data-field="count_survey" data-width="10%" data-align="center" data-formatter="count_survey_formatter">
                        {{trans('lasurvey.join')}} / {{trans('lasurvey.object')}}
                    </th>
                    <th data-field="report" data-width="10%" data-align="center" data-formatter="report_formatter">{{trans('lasurvey.report')}}</th>
                    <th data-field="review" data-width="5%" data-formatter="review_formatter" data-align="center">{{trans('lasurvey.review_template')}}</th>
                    <th data-formatter="get_qrcode" data-align="center" data-width="10%">Qr code</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mã khảo sát</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div id="qrcode-wrap">
                            <div id="qrcode"></div>
                            <p>Quét mã để vào khảo sát</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).on('click','.qrcode',function () {
            var qrcode = $(this).find('.qrcode_hide').html();
            $('#qrcode-wrap #qrcode').html(qrcode);
            $("#modal-qrcode").modal();
        });
        $('#print_qrcode').on("click", function () {
            $('#qrcode').printThis();
        });
        function get_qrcode(value, row, index) {
            if(row.qrcode) {
                return '<a href="javascript:void(0)" class="qrcode"><i class="fas fa-qrcode"></i><div class="qrcode_hide" style="visibility:hidden; display: none">'+row.qrcode+'</div></a>';
            } else {
                return '-';
            }
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'" class="a-color">'+ row.name+'</a>';
        }

        function count_survey_formatter(value, row, index) {
            return  row.count_survey_user + ' / ' + row.count_object;
        }

        function report_formatter(value, row, index) {
            var html = '';
            @can('survey-export-report')
                html += '<a href="'+ row.report_url +'" class="btn" title="{{ trans("backend.report_all") }}"><i class="fa fa-download"></i></a> ';
            @endcan

            @can('survey-view-report')
                html += '<a href="'+ row.report_detail_url +'" class="btn" title="{{ trans("backend.detail_report") }}"><i class="fa fa-list-ul"></i></a>';
            @endcan

            return html;
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-exclamation-triangle text-warning"></i>';
        }

        function review_formatter(value, row, index) {
            if(row.review) {
                if(row.survey_online == 1) {
                    return '<a href="'+ row.review +'" class="btn"> <i class="fas fa-info-circle"></i> </a>';
                } else {
                    return '<a href="'+ row.review +'" class="btn"> <i class="fa fa-eye"></i> </a>';
                }
            }
        }

        function date_formatter(value, row, index) {
            return row.start_date +'<br>'+ row.end_date;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.survey.getdata') }}',
            remove_url: '{{ route('module.survey.remove') }}'
        });

        $('.publish').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 khảo sát', 'error');
                return false;
            }

            $.ajax({
                url: "{{ route('module.survey.ajax_isopen_publish') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });

        // SAO CHÉP KHẢO SÁT
        $('.copy').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('{{ trans("lacore.min_one_course") }}', 'error');
                return false;
            }
            $.ajax({
                url: '{{ route("module.survey.copy") }}',
                type: 'post',
                data: {
                    ids: ids,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                $('.btn_action_table').toggle(false);
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        });
    </script>
@endsection
