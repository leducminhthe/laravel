@extends('layouts.backend')

@section('page_title', trans('latraining.cost'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => $model->name,
                'url' => route('backend.category.training_partner')
            ],
            [
                'name' => trans('latraining.cost'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('lacategory.enter_code_name') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>

                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                    
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-align="center" data-width="3%" data-formatter="stt_formatter">#</th>
                    <th data-field="name">{{ trans('lamenu.course') }}</th>
                    <th data-field="cost" data-align='center'>{{ trans('lareport.total_cost') }}</th>
                    <th data-field="cost_detail" data-align='center' data-formatter="cost_detail_formatter">{{ trans('latraining.detail') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script>
        function stt_formatter(value, row, index) {
            return (index + 1);
        }

        function cost_detail_formatter(value, row, index) {
            return '<a href="'+ row.costDetail +'" style="cursor: pointer;"><i class="fas fa-eye"></i></a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.training_partner_cost_getdata', ['id' => $model->id]) }}',
        });
    </script>
@stop
