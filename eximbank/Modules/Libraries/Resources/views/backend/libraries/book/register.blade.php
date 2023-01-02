@extends('layouts.backend')

@section('page_title', trans('lamenu.book_register'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.book_register'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main" id="register_book">
        <div class="row">
            <div class="col-md-2">
                @include('libraries::backend.libraries.book.filter_register')
            </div>
            <div class="col-md-10 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ route('module.libraries.book.register.export') }}">
                            <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                        </a>
                        <button class="btn approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{trans('labutton.approve')}}
                        </button>
                        <button class="btn approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans('labutton.deny')}}
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn status" data-status="2">
                            <i class="fa fa-check-circle"></i> &nbsp;{{trans('labutton.get_books')}}
                        </button>
                        <button class="btn status" data-status="3">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{trans('labutton.book_back')}}
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="index" data-align="center" data-width="2%" data-formatter="index_formatter">{{ trans('latraining.stt') }}</th>
                <th data-field="check" data-checkbox="true" data-width="2%"></th>
                <th data-field="book_name" data-width="30%">{{trans('backend.book_name')}}</th>
                <th data-field="current_number" data-width="5%" data-align="center">{{ trans('latraining.quantity') }}</th>
                <th data-field="quantity" data-width="5%" data-align="center">{{ trans('lalibrary.number_books_borrowed') }}</th>
                <th data-field="full_name" data-align="center" data-width="10%">{{trans("backend.borrower")}}</th>
                <th data-field="unit_name" data-align="center" data-width="10%">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="title_name" data-align="center" data-width="10%">{{ trans('latraining.title') }}</th>
                <th data-field="borrow_date" data-align="center" data-width="10%">{{trans("backend.date_borrow")}}</th>
                <th data-field="user_return_book" data-align="center" data-width="10%">{{trans("backend.pay_day")}}</th>
                <th data-field="pay_date" data-align="center" data-width="10%">{{ trans('lalibrary.deadline') }}</th>
                <th data-field="register_date" data-align="center" data-width="10%">{{trans("backend.date_register")}}</th>
                <th data-field="status" data-align="center" data-width="5%">{{trans('latraining.status')}}</th>
            </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.libraries.book.register.getdata') }}',
            remove_url: '{{ route('module.libraries.book.register.remove') }}'
        });

    </script>
    <script src="{{ asset('styles/module/libraries/js/register_book.js') }}"></script>
@endsection




