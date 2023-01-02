<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('lfm.title-page') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('styles/file-manager/images/folder.png') }}">
    <link rel="stylesheet" href="{{ asset('css/lfm.css') }}">
</head>
<body>
<div class="container-fluid" id="wrapper">
    <div class="card bg-primary text-white p-0 rounded-0">
        <div class="card-header">
            <h5 class="card-title text-uppercase">{{ trans('lfm.title-panel') }}</h5>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-3 hidden-xs">
            <div class="text-primary" id="tree"></div>
        </div>
        <div class="col-9" id="main">
            {{-- Menu --}}
            <nav class="navbar navbar-expand-sm border" id="nav">
                <div class="navbar-header">
                    @if (session()->get('url_filemanager') != 'thread')
                        <a class="navbar-brand clickable text-primary d-none" id="to-previous">
                            <i class="fa fa-arrow-left"></i>
                            <span class="hidden-xs">{{ trans('lfm.nav-back') }}</span>
                        </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="nav-buttons">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item">
                          <a class="clickable nav-link" id="thumbnail-display">
                            <i class="fa fa-th-large"></i>
                            <span>{{ trans('lfm.nav-thumbnails') }}</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="clickable nav-link" id="list-display">
                            <i class="fa fa-list"></i>
                            <span>{{ trans('lfm.nav-list') }}</span>
                          </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ trans('lfm.nav-sort') }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="#" class="dropdown-item" id="list-sort-alphabetic">
                                        <i class="fa fa-sort-alpha-asc"></i> {{ trans('lfm.nav-sort-alphabetic') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" id="list-sort-time">
                                        <i class="fa fa-sort-amount-asc"></i> {{ trans('lfm.nav-sort-time') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="visible-xs d-none" id="current_dir" style="padding: 5px 15px;background-color: #f8f8f8;color: #5e5e5e;"></div>

            {{-- Thông báo --}}
            <div id="alerts"></div>

            {{-- Nội dung --}}
            <div id="content" class="mt-2"></div>
        </div>

        <ul id="fab">
            <li>
                <a href="javascript:void(0)"></a>
                <ul class="hide">
                    <li>
                        <a href="javascript:void(0)" id="add-folder"
                           data-mfb-label="{{ trans('lfm.nav-new') }}">
                            <i class="fa fa-folder"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" id="upload"
                           data-mfb-label="{{ trans('lfm.nav-upload') }}">
                            <i class="fa fa-upload"></i>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ trans('lfm.title-upload') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aia-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lfm.upload') }}" role='form' id='uploadForm' name='uploadForm' method='post' enctype='multipart/form-data' class="dropzone">

                    <div class="form-group" id="attachment">
                        <div class="controls text-center">
                            <div class="text-center">
                                <a href="javascript:void(0)" class="btn rounded-0" id="upload-button"><i class="fa
                                fa-cloud-upload"></i> {{ trans('lfm.message-choose') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <input type='hidden' name='working_dir' id='working_dir'>
                    <input type='hidden' name='previous_dir' id='previous_dir'>
                    <input type='hidden' name='type' id='type' value='{{ request("type") }}'>
                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn rounded-0" data-dismiss="modal">{{ trans('lfm.btn-close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFolderModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm thư mục</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tên thư mục</label>
                    <input class="form-control" autocomplete="off" type="text" id="folder-name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="b-add-folder"><i class="fa fa-plus"></i> Tạo thư mục</button>
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="lfm-loader">
    <img src="{{ asset('styles/file-manager/images/loader.svg') }}" />
</div>

<script>
    var route_prefix = "{{ url('/') }}";
    var lfm_route = "{{ url(config('lfm.url_prefix')) }}";
    var lang = JSON.parse('{!! json_encode(trans('lfm')) !!}');
    var _token = '{{ csrf_token() }}';
</script>
<script src="{{ mix('js/lfm.js') }}"></script>

<script type="text/javascript">
    $.fn.fab = function () {
        var menu = this;
        menu.addClass('mfb-component--br mfb-zoomin').attr('data-mfb-toggle', 'hover');
        var wrapper = menu.children('li');
        wrapper.addClass('mfb-component__wrap');
        var parent_button = wrapper.children('a');
        parent_button.addClass('mfb-component__button--main')
            .append($('<i>').addClass('mfb-component__main-icon--resting fa fa-plus'))
            .append($('<i>').addClass('mfb-component__main-icon--active fa fa-times'));
        var children_list = wrapper.children('ul');
        children_list.find('a').addClass('mfb-component__button--child');
        children_list.find('i').addClass('mfb-component__child-icon');
        children_list.addClass('mfb-component__list').removeClass('hide');
    };

    $('#fab').fab({
        buttons: [
            {
                icon: 'fa fa-folder',
                label: "{{ trans('lfm.nav-new') }}",
                attrs: {id: 'add-folder'}
            },
            {
                icon: 'fa fa-upload',
                label: "{{ trans('lfm.nav-upload') }}",
                attrs: {id: 'upload'}
            }
        ]
    });

    Dropzone.options.uploadForm = {
        paramName: "upload",
        uploadMultiple: false,
        parallelUploads: 5,
        clickable: '#upload-button',
        timeout: 0,
        dictDefaultMessage: 'Hoặc kéo thả tệp vào đây',
        init: function () {
            var _this = this; // For the closure
            this.on('success', function (file, response) {
                var noty = JSON.parse(file.xhr.response);
                if(noty.status == 'error') {
                    confirm(noty.message);
                }
                refreshFoldersAndItems('OK');
            });
        },
        chunking: true,
        forceChunking: true,
        chunkSize: 50000000, //cũ 1048576,
        retryChunks: true,   // retry chunks on failure
        retryChunksLimit: 3,
        acceptedFiles: "{{ implode(',', $mimetypes) }}",
        maxFilesize: parseInt('{{ $max_file_size * 1024 * 1024  }}'),
    }
</script>
</body>
</html>
