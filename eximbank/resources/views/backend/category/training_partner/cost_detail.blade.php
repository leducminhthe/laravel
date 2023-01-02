@extends('layouts.backend')

@section('page_title', trans('latraining.detail_cost'))

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
                'url' => route('backend.training_partner_cost',['id' => $model->id])
            ],
            [
                'name' => trans('latraining.detail_cost'),
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
                <label for="">{{ trans('latraining.unit') }}:</label><span>VNĐ</span>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover" id="table-cost">
            <thead>
                <tr>
                    <th data-align="center" data-width="3%">#</th>
                    <th>{{ trans('latraining.cost') }}</th>
                    <th>{{ trans('latraining.type_cost') }}</th>
                    <th>{{ trans('latraining.amount_paid') }}</th>
                </tr>
            </thead>
            <body>
                @foreach ($training_costs as $key => $training_cost)
                    <tr>
                        <th data-align="center" data-width="3%">{{ ($key + 1) }}</th>
                        <th>{{ $training_cost->name }}</th>
                        <th>{{ $training_cost->typeCostName }}</th>
                        <th>{{ number_format($training_cost->actual_amount, 0) }}</th>
                    </tr>
                @endforeach
                <tr>
                    <th></th>
                    <th>{{ trans('latraining.total') }}</th>
                    <th></th>
                    <th id="total_plan_amount">
                        {{ number_format($totalActualAmount, 0) . ' VNĐ' }}
                    </th>
                </tr>
            </body>
        </table>
    </div>
@stop
