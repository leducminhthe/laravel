@extends('themes.mobile.layouts.app')

@section('page_title', $category->name)

@section('header')
    <style>
        #list-salekit .item-salekit a{
            color: #4b5b6a
        }
        #list-salekit .item-cate-salekit a{
            color: #fff
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="sa4d25" id="list-salekit">
            <div class="row mt-2">
                @foreach ($category_child as $item)
                    @php
                        $color = luminance($item->bg_mobile, 0.2);

                        $child = $check_child($item->id);
                        $route = $child ? route('themes.mobile.saleskit.salekit.child', ['cate_id' => $item->id]) : route('themes.mobile.saleskit.salekit.detail', ['cate_id' => $item->id]);
                    @endphp
                    <style>
                        .item_{{ $item->id }} {
                            background: linear-gradient(30deg, {{ $item->bg_mobile }}, {{ $color }});
                            padding: 5px;
                            border-radius: 10px;
                        }
                    </style>

                    <div class="col-6 col-md-3 p-1">
                        <div class="card text-center item-cate-salekit shadow">
                            <a href="javascript:void(0);" onclick="loadSpinner('{{ $route }}', 1, 2)" class="item_{{ $item->id }}">
                                <i class="fa fa-folder fa-5x"></i> <br>
                                {{ sub_char($item->name, 5) }}
                            </a>
                        </div>
                    </div>
                @endforeach
                @foreach ($saleskit as $item)
                    @php
                        $object = $saleskit_obj($item->id);
                    @endphp
                    @if ($object)
                        <div class="col-12 col-md-3 p-1">
                            <div class="card item-salekit shadow rounded">
                                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.saleskit.view_pdf', [$item->id]) }}', 1, 2)" class="row p-2">
                                    <div class="col-auto">
                                        <i class="fa fa-file-pdf fa-3x"></i>
                                    </div>
                                    <div class="col pl-0">
                                        <h6 class="mb-0">{{ $item->name }}</h6>
                                        <span class="small">{{ get_date($item->updated_at) }}</span>
                                        @if ($item->views > 0)
                                            <span class="float-right">
                                                <i class="fa fa-check"></i>
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
