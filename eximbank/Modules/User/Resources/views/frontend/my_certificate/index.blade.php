<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="float-right">
                    <button class="btn cursor_pointer" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    <a href="{{ route('module.frontend.user.add_my_certificate') }}" class="btn open_search">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ trans('laprofile.add_certificate') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="course">
    <div class="col-md-12">
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-formatter="name_formatter">{{ trans('laprofile.certificate_name') }}</th>
                    <th data-field="name_school">{{ trans('laprofile.certificate_school') }}</th>
                    <th data-field="rank" data-align="center">{{ trans('laprofile.rank') }}</th>
                    <th data-field="time_start" data-align="center">{{ trans('laprofile.study_time') }}</th>
                    <th data-field="date_license" data-align="center">{{ trans('laprofile.date_issue') }}</th>
                    <th data-field="score" data-align="center">{{ trans('latraining.score') }}</th>
                    <th data-field="result" data-align="center">{{ trans('latraining.result') }}</th>
                    <th data-field="note" data-align="center">{{ trans('latraining.note') }}</th>
                    <th data-field="certificate" data-formatter="certificate_formatter" data-align="center">{{ trans('laprofile.attach') }}</th>
                </tr>
            </thead>
        </table>
    </div>
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
    function name_formatter(value, row, index) {
        return '<a href="' + row.edit_url + '">' + row.name_certificate + '</a>';
    }

    function certificate_formatter(value, row, index) {
        return '<span class="cursor_pointer" onclick="showCertificate(' + row.id + ')">' + row.certificate + '</span>';
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.backend.user.data_my_certificate') }}',
        remove_url: '{{ route('module.frontend.user.remove_my_certificate') }}',
    });

    function showCertificate(id) {
        $.ajax({
            type: "POST",
            url: '{{ route('module.frontend.user.get_img_my_certificate') }}',
            dataType: 'json',
            data: {
                'id': id,
            },
            success: function (result) {
                if (result.status == "success") {
                    $('.body_my_certificate').html('<img class="w-100" src="'+ result.img +'" alt="">');
                    $('#modal-show-certificate').modal();
                }
                show_message(result.message, result.status);
                return false;
            }
        });
    }
</script>
