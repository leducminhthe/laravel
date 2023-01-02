@extends('layouts.backend')

@section('page_title', trans('lasetting.languages'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.languages'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @if(isset($notifications))
        @foreach($notifications as $notification)
            @if(@$notification->data['messages'])
                @foreach($notification->data['messages'] as $message)
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                @endforeach
            @else
                <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
            @endif
            @php
                $notification->markAsRead();
            @endphp
        @endforeach
    @endif
    <div role="main">
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" value="" placeholder="{{ trans('lasetting.type_keyword') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    {{--<span><a href="javascript:void(0)" class="btn load-modal" data-url="{{ route('backend.languages.get_modal') }}"><i class="fa fa-plus-circle"></i> Tạo nhóm</a></span>--}}
                    <span>
                        @can('languages-create')
                            <a href="javascript:void(0)" class="btn" data-toggle="modal" data-target="#modal-create-new-lang">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.create_lang') }}
                            </a>
                        @endcan
                    </span>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('languages-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.synchronize', ['group_id' => $id]) }}" class="btn"><i class="fa fa-upload"></i> {{ trans('labutton.synchronized_to_file') }}</a>
                            </div>
                        @endcan

                        @can('languages-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.syncdb2file') }}" class="btn"><i class="fa fa-download"></i> {{ trans('labutton.synchronized_to_db') }}</a>
                            </div>
                        @endcan

						 @can('languages-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.export_file') }}" class="btn"> {{ trans('labutton.export') }}</a>
                            </div>
                        @endcan

                        @can('languages-create')
                            <div class="btn-group">
                                <a href="{{ route('backend.languages.export') }}" class="btn"> {{ trans('labutton.export_excel') }}</a>
                            </div>
                        @endcan

                        {{-- @can('languages-create')
                            <div class="btn-group">
                                <a href="{{ download_template('mau_import_languages.xlsx') }}" class="btn"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            </div>
                        @endcan --}}
                        @can('languages-create')
                            <div class="btn-group">
                                <a href="javascript:void(0)" class="btn" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</a>
                            </div>
                        @endcan

                        {{--@can('feedback-create')
                            <a href="{{ route('backend.languages.create', $id) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                            @if(\App\Models\Permission::isAdmin())
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                             @endif--}}
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
        @foreach($groups as $v)
            <a class="btn{{ $v["id"]==$id?' actived':'' }}" href="{{ route('backend.languages.group', $v["id"]) }}">{{$v["name"]}}</a>
        @endforeach
        </div>
        <div class="mt-2 text-danger text-bold row">
            <div class="col-md-10">
            {{ trans('laother.note_update_lang') }}
            </div>
            <div class="col-md-2 text-right" style="display: none">
                <button class="btn btn-primary" id="git_push">git push</button>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-formatter="name_formatter" data-width="360px">{{ trans('lasetting.keyword') }}</th>
                    @foreach($lang_types as $type)
                    <th data-width="200px" data-field="{{ $type->key == 'vi' ? 'content' : 'content_'.$type->key }}">{{ $type->name }}</th>
                    @endforeach
                    <th data-field="note">{{ trans('lacore.note') }}</th>
                    <th data-width="100px" data-field="group_name">{{ trans('lasetting.group') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('backend.languages.import') }}" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ trans('lasetting.import') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            <button type="submit" class="btn"><i class="fa fa-upload"></i> {{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-create-new-lang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ route('backend.languages.create_new') }}" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ trans('lasetting.add_lang') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="icon">{{ trans('lasetting.icon') }} (32x32)</label>
                                <a href="javascript:void(0)" id="select-image">{{trans('lasetting.choose_picture')}}</a>
                                <div id="image-review" ></div>
                                <input name="icon" id="image-select" type="text" class="d-none" value="">
                            </div>
                            <div class="form-group">
                                <label for="key">{{ trans('lasetting.code') }}</label>
                                <input type="text" name="key" class="form-control" placeholder="{{ trans('lasetting.key_placeholder') }}">
                            </div>
                            <div class="form-group">
                                <label for="name">{{ trans('lasetting.languages') }}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ trans('lasetting.lang_name') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            @can('languages-create')
                                <button type="submit" class="btn"><i class="fa fa-save"></i> {{ trans('labutton.save') }}</button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.pkey +' </a>';
        }
        var table = new LoadBootstrapTable({
            url: '{{ route('backend.languages.getdata', $id) }}',
            remove_url: '{{ route('backend.languages.remove') }}'
        });

        $('#model-list-import').on('click', function () {
            $('#modal-import').modal();
        });

        $('#import-user').on('click', function () {
            $('#modal-import').hide();
            $('#modal-import-user').modal();
        });

        $("#modal-create-new-lang #select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#modal-create-new-lang #image-review").html('<img src="' + path + '" class="img-responsive">');
                $("#modal-create-new-lang #image-select").val(path);
            });
        });
        $('#git_push').on('click',function (e) {
            e.preventDefault();
            let item = $(this);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.languages.git_push')}}",
                type: 'post',
                data: {}
            }).done(function(data) {
                item.html(oldtext);
                show_message(data.message, data.status);
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        })
    </script>

@endsection
