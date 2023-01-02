@extends('layouts.app')

@section('page_title', 'Chi phí học viên')

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        <span class="font-weight-bold">Chi phí học viên</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-inline form-search-user mb-3" id="form-search">
                                    <input type="text" name="search" class="form-control input-search" autocomplete="off" placeholder="{{ trans('labutton.search') .' '. trans('app.course') }}" value="">
                                    <input name="start_date" type="text" class="datepicker form-control search_start_date" placeholder="{{trans('latraining.start_date')}}" autocomplete="off">
                                    <input name="end_date" type="text" class="datepicker form-control search_end_date" placeholder="{{trans('latraining.end_date')}}" autocomplete="off">
                                    <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="course">
                            <div class="col-md-12">
                                <table class="tDefault table table-hover bootstrap-table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th data-field="code">{{ trans('latraining.course_code') }}</th>
                                            <th data-field="name">{{ trans('latraining.course_name') }}</th>
                                            <th data-field="start_date" data-align="center">{{ trans('latraining.start_date') }}</th>
                                            <th data-field="end_date" data-align="center">{{ trans('latraining.end_date') }}</th>
                                            @foreach ($student_costs as $key => $student_cost)
                                                <th data-align="center" data-field="student_cost_{{ $student_cost->id }}">{{ $student_cost->name }}</th>
                                            @endforeach
                                            <th data-field="total_student_cost">Tổng</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.student_cost.getdata') }}',
        });

        function saveCost(id, course_id) {
            var regid = $('#register_id_' + id + '_' + course_id).val();
            var cost_id = $('#cost_id_' + id + '_' + course_id).val();
            var cost = $('#input_sudent_cost_' + id + '_' + course_id).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.student_cost.save') }}',
                dataType: 'json',
                data: {
                    'regid': regid,
                    'cost_id': cost_id,
                    'cost': cost,
                    'course_id': course_id,
                },
            }).done(function(data) {
                var total_cost = parseInt( data.total_student_cost, 10 );
                total_cost = total_cost.toLocaleString( "en-US" )
                $('.total_student_cost_'+course_id).html(total_cost+ " VNĐ")
                return false;
            }).fail(function(data) {

                Swal.fire(
                    '',
                    '{{ trans('laother.data_error') }}',
                    'error'
                );
                return false;
            });
        }

        $(document).ready(function () {
            var $course = $( "#course" );
            var $input = $course.find( ".input_sudent_cost" );
            $input.on( "keyup", function( event ) {
                var $this = $( this );
                // Get the value.
                var input = $this.val();
                var input = input.replace(/[\D\s\._\-]+/g, "");
                input = input ? parseInt( input, 10 ) : 0;

                $this.val( function() {
                    return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
                } );
            });
        });
    </script>
@stop
