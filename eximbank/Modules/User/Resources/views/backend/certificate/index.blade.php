@extends('layouts.backend')

@section('page_title', trans('laprofile.external_certificate'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.backend.user')
            ],
            [
                'name' => $full_name,
                'url' => route('module.backend.user.edit',['id' => $user_id])
            ],
            [
                'name' => trans('laprofile.external_certificate'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @if($user_id)
        @include('user::backend.layout.menu')
    @endif
    <div role="main">
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="name_certificate">{{ trans('laprofile.certificate_name') }}</th>
                    <th data-field="name_school">{{ trans('laprofile.certificate_school') }}</th>
                    <th data-field="rank" data-align="center">{{ trans('laprofile.rank') }}</th>
                    <th data-field="time_start" data-align="center">{{ trans('laprofile.study_time') }}</th>
                    <th data-field="date_license" data-align="center">{{ trans('laprofile.date_issue') }}</th>
                    <th data-field="score" data-align="center">{{ trans('latraining.score') }}</th>
                    <th data-field="result" data-align="center">{{ trans('latraining.result') }}</th>
                    <th data-field="note" data-align="center">{{ trans('latraining.note') }}</th>
                    <th data-field="certificate" data-align="center" data-formatter="certificate_formatter">{{ trans('laprofile.attach') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-show-certificate" tabindex="-1" role="dialog" aria-labelledby="modal-import-user" aria-hidden="true">
        <div class="modal-dialog modal_my_certificate" role="document">
            <div class="modal-content">
                <div class="modal-body body_my_certificate">
    
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function certificate_formatter(value, row, index) {
            return `<div class="fun_certificate d-flex">
                        <button class="btn" id="show_`+ row.id +`" onclick="showCertificate(`+ row.id +`)"><i class="fas fa-info-circle"></i></button>
                        <button class="btn" id="download_`+ row.id +`" onclick="downloadCertificate(`+ row.id +`)"><i class="fas fa-download"></i></button>
                    </div>`;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user_certificate.getdata', ['user_id' => $user_id]) }}',
        });

        function showCertificate(id) {
            let item = $('#show_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: "POST",
                url: '{{ route('module.frontend.user.get_img_my_certificate') }}',
                dataType: 'json',
                data: {
                    'id': id,
                },
                success: function (result) {
                    item.html(oldtext);
                    if (result.status == "success") {
                        $('.body_my_certificate').html('<img class="w-100" src="'+ result.img +'" alt="">');
                        $('#modal-show-certificate').modal();
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        }

        function downloadCertificate(id) {
            let item = $('#download_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: "POST",
                url: '{{ route('module.backend.user_certificate.test', ['user_id' => $user_id]) }}',
                dataType: 'json',
                data: {
                    'id': id,
                },
                success: function (result) {
                    item.html(oldtext);
                    window.location.href = result.link_download
                    return false;
                }
            });
        }
    </script>
@endsection
