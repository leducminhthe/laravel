@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        @endif
        @php
            $check_level = $level;
        @endphp
        <div class="row">
            <div class="col-md-3">
                @include('backend.category.unit.filter')
            </div>
            <div class="col-md-9 text-right act-btns mt-2">
                <div class="pull-right">
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                {{ trans('labutton.task') }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="width: 15rem;">
                                @if($level == 1)
                                    <a class="dropdown-item p-1" href="{{ route('backend.category.unit.tree_folder') }}">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 128 128" width="512"><path d="m90 84h34a4 4 0 0 0 4-4v-21a4 4 0 0 0 -4-4h-19v-3a4 4 0 0 0 -4-4h-11a4 4 0 0 0 -4 4v12h-60v-24h18a3.83 3.83 0 0 0 4-4v-24a3.83 3.83 0 0 0 -4-4h-23v-4a3.83 3.83 0 0 0 -4-4h-12.74a4.26 4.26 0 0 0 -4.26 4.26v31.74a3.83 3.83 0 0 0 4 4h18v70a2 2 0 0 0 2 2h62v12a4 4 0 0 0 4 4h34a4 4 0 0 0 4-4v-21a4 4 0 0 0 -4-4h-19v-3a4 4 0 0 0 -4-4h-11a4 4 0 0 0 -4 4v12h-60v-40h60v12a4 4 0 0 0 4 4zm-86-79.74a.26.26 0 0 1 .26-.26h12.74v4a3.83 3.83 0 0 0 4 4h23v24h-40zm97 91.74v3a4 4 0 0 0 4 4h19v21h-34v-28zm0-44v3a4 4 0 0 0 4 4h19v21h-34v-28z"/></svg> {{ trans('labutton.folder_tree') }}
                                    </a>
                                    @can('category-unit-create')
                                        <a class="dropdown-item p-1" href="{{ route('backend.category.unit.export', ['level' => 0]) }}">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 410.8 410.8" style="enable-background:new 0 0 410.8 410.8;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path d="M333.4,138.8h-64c-4.4,0-8,3.6-8,8c0,4.4,3.6,8,8,8h64c13.2,0,24,10.8,24,24v192c0,13.2-10.8,24-24,24h-256     c-13.2,0-24-10.8-24-24v-192c0-13.2,10.8-24,24-24h72c4.4,0,8-3.6,8-8c0-4.4-3.6-8-8-8h-72c-22,0-40,18-40,40v192     c0,22,18,40,40,40h256c22,0,40-18,40-40v-192C373.4,156.8,355.4,138.8,333.4,138.8z"/>
                                                            <path d="M205.4,246.8c-4.4,0-8,3.6-8,8v12c0,4.4,3.6,8,8,8c4.4,0,8-3.6,8-8v-12C213.4,250.4,209.8,246.8,205.4,246.8z"/>
                                                            <path d="M140.2,84.4l57.2-57.2v191.6c0,4.4,3.6,8,8,8c4.4,0,8-3.6,8-8V27.2l57.2,57.2c1.6,1.6,3.6,2.4,5.6,2.4s4-0.8,5.6-2.4     c3.2-3.2,3.2-8,0-11.2L211,2.4c-3.2-3.2-8-3.2-11.2,0L129,73.2c-3.2,3.2-3.2,8,0,11.2C132.2,87.6,137,87.6,140.2,84.4z"/>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg> {{ trans('labutton.export_full') }}
                                        </a>
                                        <a class="dropdown-item p-1" href="javascript:void(0)" id="import-plan-update" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 24 24" width="512"><path d="m13 14.2929 2.1464-2.1465c.1953-.1952.5119-.1952.7072 0 .1952.1953.1952.5119 0 .7072l-3 3c-.1953.1952-.5119.1952-.7072 0l-2.99995-3c-.19527-.1953-.19527-.5119 0-.7072.19526-.1952.51184-.1952.7071 0l2.14645 2.1465v-10.7929c0-.27614.2239-.5.5-.5s.5.22386.5.5zm5.5-8.2929c-.2761 0-.5-.22386-.5-.5s.2239-.5.5-.5h1c1.3807 0 2.5 1.11929 2.5 2.5v10c0 1.3807-1.1193 2.5-2.5 2.5h-14c-1.38071 0-2.5-1.1193-2.5-2.5v-10c0-1.38071 1.11929-2.5 2.5-2.5h1c.27614 0 .5.22386.5.5s-.22386.5-.5.5h-1c-.82843 0-1.5.67157-1.5 1.5v10c0 .8284.67157 1.5 1.5 1.5h14c.8284 0 1.5-.6716 1.5-1.5v-10c0-.82843-.6716-1.5-1.5-1.5z"/></svg> Import theo excel
                                        </a>
                                        <a class="dropdown-item p-1" href="{{ download_template('mau_import_don_vi.xlsx') }}">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m509.657 64.346-62.003-62.003c-1.5-1.5-3.535-2.343-5.657-2.343h-320.687c-4.418 0-8 3.582-8 8v181.459h-105.31c-4.418 0-8 3.582-8 8v128.32c0 4.418 3.582 8 8 8h105.31v170.221c0 4.418 3.582 8 8 8h382.69c4.418 0 8-3.582 8-8v-433.997c0-2.122-.843-4.157-2.343-5.657zm-59.66-37.033 34.689 34.689h-34.689zm-433.997 178.146h161.965c4.418 0 8-3.582 8-8v-35.909l115.091 100.069-115.091 100.069v-35.909c0-4.418-3.582-8-8-8h-161.965zm113.31 290.541v-162.221h40.655v45.466c0 3.135 1.831 5.98 4.683 7.28 1.062.483 2.192.72 3.315.72 1.894 0 3.766-.672 5.25-1.963l135.284-117.626c1.748-1.52 2.751-3.722 2.751-6.037s-1.003-4.518-2.751-6.037l-135.283-117.625c-2.366-2.057-5.714-2.542-8.566-1.243-2.853 1.3-4.683 4.146-4.683 7.28v45.466h-40.655v-173.46h304.687v54.003c0 4.418 3.582 8 8 8h54.003v417.997zm304.595-334.505c0 4.418-3.582 8-8 8h-180.005c-4.418 0-8-3.582-8-8s3.582-8 8-8h180.005c4.419 0 8 3.581 8 8zm0 133.499c0 4.418-3.582 8-8 8h-96.755c-4.418 0-8-3.582-8-8s3.582-8 8-8h96.755c4.419 0 8 3.582 8 8zm0-66.749c0 4.418-3.582 8-8 8h-96.755c-4.418 0-8-3.582-8-8s3.582-8 8-8h96.755c4.419 0 8 3.581 8 8zm0 133.499c0 4.418-3.582 8-8 8h-180.005c-4.418 0-8-3.582-8-8s3.582-8 8-8h180.005c4.419 0 8 3.582 8 8zm0 66.75c0 4.418-3.582 8-8 8h-226.5c-4.418 0-8-3.582-8-8s3.582-8 8-8h226.5c4.419 0 8 3.582 8 8zm-71.906-325.749h-162.594c-4.418 0-8-3.582-8-8s3.582-8 8-8h162.593c4.418 0 8 3.582 8 8s-3.581 8-7.999 8z"/></svg> {{ trans('labutton.import_template') }}
                                        </a>
                                        <a class="dropdown-item p-1" href="javascript:void(0)" id="import-plan" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 410.8 410.8" style="enable-background:new 0 0 410.8 410.8" xml:space="preserve"><g><g><g><path d="M333.4,138.8h-64c-4.4,0-8,3.6-8,8c0,4.4,3.6,8,8,8h64c13.2,0,24,10.8,24,24v192c0,13.2-10.8,24-24,24h-256     c-13.2,0-24-10.8-24-24v-192c0-13.2,10.8-24,24-24h72c4.4,0,8-3.6,8-8c0-4.4-3.6-8-8-8h-72c-22,0-40,18-40,40v192     c0,22,18,40,40,40h256c22,0,40-18,40-40v-192C373.4,156.8,355.4,138.8,333.4,138.8z"/><path d="M205.4,246.8c-4.4,0-8,3.6-8,8v12c0,4.4,3.6,8,8,8c4.4,0,8-3.6,8-8v-12C213.4,250.4,209.8,246.8,205.4,246.8z"/><path d="M140.2,84.4l57.2-57.2v191.6c0,4.4,3.6,8,8,8c4.4,0,8-3.6,8-8V27.2l57.2,57.2c1.6,1.6,3.6,2.4,5.6,2.4s4-0.8,5.6-2.4     c3.2-3.2,3.2-8,0-11.2L211,2.4c-3.2-3.2-8-3.2-11.2,0L129,73.2c-3.2,3.2-3.2,8,0,11.2C132.2,87.6,137,87.6,140.2,84.4z"/></g></g></g></svg>
                                            {{ trans('labutton.import') }}
                                        </a>
                                    @endcan
                                @endif
                                @can('category-unit-create')
                                    <a class="dropdown-item p-1" href="{{ route('backend.category.unit.export', ['level' => $level]) }}">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 24 24" width="512"><path d="m13 14.2929 2.1464-2.1465c.1953-.1952.5119-.1952.7072 0 .1952.1953.1952.5119 0 .7072l-3 3c-.1953.1952-.5119.1952-.7072 0l-2.99995-3c-.19527-.1953-.19527-.5119 0-.7072.19526-.1952.51184-.1952.7071 0l2.14645 2.1465v-10.7929c0-.27614.2239-.5.5-.5s.5.22386.5.5zm5.5-8.2929c-.2761 0-.5-.22386-.5-.5s.2239-.5.5-.5h1c1.3807 0 2.5 1.11929 2.5 2.5v10c0 1.3807-1.1193 2.5-2.5 2.5h-14c-1.38071 0-2.5-1.1193-2.5-2.5v-10c0-1.38071 1.11929-2.5 2.5-2.5h1c.27614 0 .5.22386.5.5s-.22386.5-.5.5h-1c-.82843 0-1.5.67157-1.5 1.5v10c0 .8284.67157 1.5 1.5 1.5h14c.8284 0 1.5-.6716 1.5-1.5v-10c0-.82843-.6716-1.5-1.5-1.5z"/></svg> {{ trans('labutton.export') }}
                                    </a>
                                @endcan
                                @can('category-unit-edit')
                                    <a class="dropdown-item p-1" href="javascript:void(0)" onclick="changeStatus(0,1)" data-status="1" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g><path d="M373.333,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S426.261,160,373.333,160z     M373.333,330.667c-41.173,0-74.667-33.493-74.667-74.667s33.493-74.667,74.667-74.667C414.507,181.333,448,214.827,448,256    S414.507,330.667,373.333,330.667z"/></g></g><g><g><path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z"/></g></g><g><g><path d="M117.333,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32c17.643,0,32-14.357,32-32v-42.667    C149.333,217.024,134.976,202.667,117.333,202.667z M128,277.333c0,5.888-4.8,10.667-10.667,10.667    c-5.867,0-10.667-4.779-10.667-10.667v-42.667c0-5.888,4.8-10.667,10.667-10.667C123.2,224,128,228.779,128,234.667V277.333z"/></g></g><g><g><path d="M224,202.667c-5.888,0-10.667,4.779-10.667,10.667v40.149l-22.443-44.928c-2.219-4.416-7.104-6.763-12.011-5.611    c-4.821,1.131-8.213,5.44-8.213,10.389v85.333c0,5.888,4.779,10.667,10.667,10.667S192,304.555,192,298.667v-40.149l22.464,44.928    c1.835,3.669,5.547,5.888,9.536,5.888c0.811,0,1.621-0.085,2.453-0.277c4.821-1.131,8.213-5.44,8.213-10.389v-85.333    C234.667,207.445,229.888,202.667,224,202.667z"/></g></g></svg>
                                        {{ trans('labutton.enable') }}
                                    </a>
                                    <a class="dropdown-item p-1" href="javascript:void(0)" onclick="changeStatus(0,0)" data-status="0" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g><path d="M138.667,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S191.595,160,138.667,160z     M138.667,330.667C97.493,330.667,64,297.173,64,256s33.493-74.667,74.667-74.667s74.667,33.493,74.667,74.667    S179.84,330.667,138.667,330.667z"/></g></g><g><g><path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z"/></g></g><g><g><path d="M288,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32s32-14.357,32-32v-42.667    C320,217.024,305.643,202.667,288,202.667z M298.667,277.333c0,5.888-4.8,10.667-10.667,10.667s-10.667-4.779-10.667-10.667    v-42.667c0-5.888,4.8-10.667,10.667-10.667s10.667,4.779,10.667,10.667V277.333z"/></g></g><g><g><path d="M384,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H384c5.888,0,10.667-4.779,10.667-10.667S389.888,202.667,384,202.667z"/></g></g><g><g><path d="M373.333,245.333H352c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S379.221,245.333,373.333,245.333z"/></g></g><g><g><path d="M448,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H448c5.888,0,10.667-4.779,10.667-10.667S453.888,202.667,448,202.667z"/></g></g><g><g><path d="M437.333,245.333H416c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S443.221,245.333,437.333,245.333z"/></g></g></svg>
                                        {{ trans('labutton.disable') }}
                                    </a>
                                @endcan
                            </div>
                          </div>
                    </div>
                    <div class="btn-group">
                        @can('category-unit-create')
                            <button type="button" class="btn btn-demo" onclick="create()">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @if($level > 0)
                            @can('category-unit-delete')
                                <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('lacategory.unit_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">
                        {{ trans('lacategory.unit') . ($total_model > 0 ? ' ('.$total_model_active.'/'.$total_model.')' : '') }}
                    </th>
                    @if($level != 0)
                        <th data-field="parent_name" data-width="20%">{{ trans('lacategory.management_unit') }}</th>
                    @endif
                    <th data-field="type_name">{{ trans('lacategory.unit_type') }}</th>
                    <th data-field="unit_manager">{{ trans('lacategory.manager') }}</th>
                    <th data-field="area_name">{{ trans('lacategory.area') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="info_formatter" data-width="5%">{{ trans('latraining.info') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('lacategory.status') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelImport" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('backend.category.unit.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelImport">{{ trans('lacategory.import_unit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="unit_id" value="{{ $level }}">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            <div class="form-group row mt-2">
                                <div class="col-md-4">
                                    <label for="">Chọn khóa chính <span class="text-danger">(*)</span></label>
                                </div>
                                <div class="col-md-8">
                                    <label class="radio-inline">
                                        <input type="radio" name="type_import" class="mr-1" value="1" checked>
                                        {{ trans('latraining.employee_code') }}
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type_import" class="mr-1" value="2">
                                        Username
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type_import" class="mr-1" value="3">
                                        Email
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            <button type="submit" class="btn">
                                {{ trans('labutton.import') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-import-update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelUpdate" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('backend.category.unit.import_update') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelUpdate">{{ trans('lacategory.import_update_unit') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="unit_id" value="{{ $level }}">
                            <input type="file" name="import_file_update" id="import_file_update" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
                <form method="post" action="{{ route('backend.category.unit.save', ['level' => $level]) }}" class="form-ajax" role="form" enctype="multipart/form-data" id="form_save" onsubmit="return false;">
                    <input type="hidden" name="level" value="{{ $level }}">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['category-unit-create', 'category-unit-edit'])
                                <button type="button" id="btn_save" onclick="saveForm(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcanany
                            <button data-dismiss="modal" aria-label="Close" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="tPanel">
                            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                                <li class="nav-item">
                                    <a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('lacategory.info') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#object" class="nav-link" data-toggle="tab">{{ trans('lacategory.management') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="base" class="tab-pane active">
                                    @include('backend.category.unit.form.info')
                                </div>

                                <div id="object" class="tab-pane">
                                    @include('backend.category.unit.form.manager')
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>

    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')" class="a-color">'+ row.name +'</a>' ;
        }
        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.unit.getdata', ['level' => $level]) }}',
            remove_url: '{{ route('backend.category.unit.remove', ['level' => $level]) }}',

        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#import-plan-update').on('click', function() {
            $('#modal-import-update').modal();
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 dòng', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('backend.category.unit.ajax_isopen_publish') }}",
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
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

        function saveForm(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.unit.save',['level' => $level]) }}",
                type: 'post',
                data: $("#form_save").serialize(),

            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#myModal2').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function edit(id){
            var level =  $("input[name=level]").val();
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.category.unit.edit',['level' => $level]) }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("input[name=email]").val(data.model.email);
                $("#note1").val(data.model.note1);
                $("#note2").val(data.model.note2);

                $("#type_unit select").val(data.model.type);
                $("#type_unit select").val(data.model.type).change();

                if (data.parent) {
                    $("#parent_id").html('<option value="'+ data.parent.id +'">'+ data.parent.code +' - '+ data.parent.name +'</option>');
                }
                if (data.area_name) {
                    $("#area_level").html('<option value="'+ data.area_name.id +'">'+ data.area_name.name +'</option>');
                }
                if (data.area) {
                    $("#area_id").html('<option value="'+ data.area.id +'">'+ data.area.name +'</option>');
                }
                $("#select_manager").html('');
                if (data.unit_managers) {
                    $.each(data.unit_managers, function (index, value) {
                        $("#select_manager").append('<option value="'+ value.user_id +'" selected>'+ value.user_code + ' - ' +  value.user_lastname + ' ' + value.user_firstname +'</option>');

                        if(value.type_manager == 1){
                            $('#type_manager_1').prop( 'checked', true );
                            $('#type_manager_2').prop( 'checked', false );
                        }else{
                            $('#type_manager_1').prop( 'checked', false );
                            $('#type_manager_2').prop( 'checked', true );
                        }
                    });
                }

                // for (var i = 1; i <= data.max_area; i++) {
                //     $("#area_id_"+i).html('');
                // }
                // $.each(data.area, function (index, value) {
                //     $("#area_id_"+index).html('<option value="'+ value.id +'">'+ value.name +'</option>');
                // });

                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
                }

                $('#myModal2').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            var max_area = '{{ $max_area }}';
            $('#form_save').trigger("reset");
            $("#type").val('').trigger('change');
            $("#parent_id").val('').trigger('change');
            $("input[name=id]").val('');
            $("#select_manager").html('');
            $('#myModal2').modal();
            $("#area_level").html('');
            $("#area_id").html('');
            $('#type_manager_1').prop( 'checked', true );
        }
    </script>

@endsection
