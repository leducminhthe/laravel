{{-- @extends('layouts.backend')

@section('page_title', 'Người dùng liên hệ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Người dùng liên hệ</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search-user w-100 mb-3" id="form-search">
                    <input type="text" class="form-control datepicker" name="search" value="" placeholder="-- {{ trans('laprofile.created_at') }} --">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('user-contact-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="created_at" data-width="10%">{{ trans('laprofile.created_at') }}</th>
                    <th data-field="title">{{ trans('laprofile.heading') }}</th>
                    <th data-field="content">{{ trans('laprofile.content') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.user-contact.getdata') }}',
            remove_url: '{{ route('backend.user-contact.remove') }}'
        });
    </script>
{{-- @endsection --}}
