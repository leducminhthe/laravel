@extends('layouts.backend')

@section('page_title', trans('lamenu.suggestion'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.suggestion'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-12">
                @include('suggest::backend.suggest.filter')
                {{-- <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-24">
                            <select name="unit" id="unit-{{ $i }}"
                                class="form-control load-unit"
                                data-placeholder="-- {{ trans('lacategory.unit_level', ['i'=> $i]) }} --"
                                data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                            </select>
                        </div>
                    @endfor
                    <div class="w-24">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="--{{ trans('lasuggest.area') }}--"></select>
                    </div>
                    <div class="w-24">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('lasuggest.title') }} --"></select>
                    </div>

                    <div class="w-24">
                        <select name="status" class="form-control select2" data-placeholder="-- {{ trans('lasuggest.status') }} --">
                            <option value=""></option>
                            <option value="0">{{ trans('lasuggest.inactivity') }}</option>
                            <option value="1">{{ trans('lasuggest.doing') }}</option>
                            <option value="2">{{ trans('lasuggest.probationary') }}</option>
                            <option value="3">{{ trans('lasuggest.pause') }}</option>
                        </select>
                    </div>

                    <div class="w-24">
                        <input type="text" name="search" class="form-control w-100" value="" placeholder="{{ trans('lasuggest.enter_suggest') }}">
                    </div>
                    <div class="w-24">
                        <input name="start_date" type="text" class="datepicker form-control w-100" placeholder="{{ trans('lasuggest.start_date') }}" autocomplete="off">
                    </div>
                    <div class="w-24">
                        <input name="end_date" type="text" class="datepicker form-control w-100" placeholder="{{ trans('lasuggest.end_date') }}" autocomplete="off">
                    </div>
                    <div class="w-24">
                        <input type="text" name="search_code_name" class="form-control w-100" placeholder="{{ trans('lasuggest.enter_code_name_user') }}">
                    </div>
                    <div class="w-24">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form> --}}
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('lasuggest.suggestion') }}</th>
                    <th data-field="profile">{{ trans('lasuggest.user') }}</th>
                    <th data-field="email">{{ trans('lasuggest.email') }}</th>
                    <th data-field="title_name" data-width="20%">{{ trans('lasuggest.title') }}</th>
                    <th data-field="unit_name">{{ trans('lasuggest.work_unit') }}</th>
                    <th data-field="created_at2" data-with="5%">{{ trans('lasuggest.created_at') }}</th>
                    <th data-align="center" data-field="checked_reply" data-formatter="checked_reply_formatter" data-with="5%">{{ trans('latraining.answered') }}</th>
                </tr>
            </thead>
        </table>
        
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return  '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function checked_reply_formatter(value, row, index) {
            if(row.checked_reply == 1) {
                return  '<input type="checkbox" checked class="cursor_pointer checkbox_'+ row.id +'" onclick="saveCheckedReply('+ row.id +')">';
            } else {
                return  '<input type="checkbox" class="cursor_pointer checkbox_'+ row.id +'" onclick="saveCheckedReply('+ row.id +')">';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.suggest.getdata') }}',
        });

        function saveCheckedReply(id) {
            var checked = $('.checkbox_' + id).is(":checked")
            console.log(checked);
            $.ajax({
                url: "{{ route('module.suggest.save_checked_reply') }}",
                type: 'post',
                data: {
                    'id': id,
                    'checked': checked
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        }
    </script>
@endsection
